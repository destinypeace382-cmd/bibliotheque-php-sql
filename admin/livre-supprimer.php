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
    die("Livre invalide.");
}


/* Récupération du livre */

$stmt = $pdo->prepare("
    SELECT image
    FROM livres
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);

$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    die("Livre introuvable.");
}


/* Suppression du livre */

$stmt = $pdo->prepare("
    DELETE FROM livres
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);


/* Suppression de l'image */

if (!empty($livre["image"])) {

    $cheminImage = "../" . $livre["image"];

    if (file_exists($cheminImage)) {
        unlink($cheminImage);
    }
}


/* Message flash */

definirMessage("Livre supprimé avec succès.");


/* Redirection */

header("Location: index.php");
exit;