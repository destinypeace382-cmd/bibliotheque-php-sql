<?php

require_once "../config/db.php";
require_once "../includes/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Méthode non autorisée.");
}

if (
    !isset($_POST["csrf_token"]) ||
    !verifierTokenCSRF($_POST["csrf_token"])
) {
    die("Erreur de sécurité : token CSRF invalide.");
}

$id = $_POST["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("Catégorie invalide.");
}


/* Vérifier que la catégorie existe */

$stmt = $pdo->prepare("
    SELECT id, nom
    FROM categories
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);

$categorie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categorie) {
    die("Catégorie introuvable.");
}


/* Vérifier si des livres utilisent la catégorie */

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM livres
    WHERE categorie_id = :categorie_id
");

$stmt->execute([
    ":categorie_id" => $id
]);

$nombreLivres = $stmt->fetchColumn();


/* Empêcher la suppression si elle est utilisée */

if ($nombreLivres > 0) {

    die(
        "Impossible de supprimer cette catégorie : "
        . $nombreLivres
        . " livre(s) utilisent encore cette catégorie."
    );
}


/* Supprimer la catégorie */

$stmt = $pdo->prepare("
    DELETE FROM categories
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);


/* Message flash */

definirMessage("Catégorie supprimée avec succès.");


/* Redirection */

header("Location: categories.php");
exit;