<?php

require_once "../config/db.php";

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("Livre invalide.");
}


/* Vérifier que le livre existe */

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


/* Supprimer le livre */

$stmt = $pdo->prepare("
    DELETE FROM livres
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);


/* Supprimer l'image du dossier uploads */

if (!empty($livre["image"])) {

    $cheminImage = "../" . $livre["image"];

    if (file_exists($cheminImage)) {
        unlink($cheminImage);
    }
}


/* Retour à la liste */

header("Location: index.php");
exit;