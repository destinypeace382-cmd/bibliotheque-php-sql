<?php

require_once "../config/db.php";
require_once "../includes/csrf.php";

$csrf_token = genererTokenCSRF();

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("Catégorie invalide.");
}

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

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST["csrf_token"]) ||
        !verifierTokenCSRF($_POST["csrf_token"])
    ) {
        die("Erreur de sécurité : token CSRF invalide.");
    }

    $nom = trim($_POST["nom"] ?? "");

    if ($nom === "") {

        $erreur = "Le nom de la catégorie est obligatoire.";

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM categories
            WHERE nom = :nom
            AND id != :id
        ");

        $stmt->execute([
            ":nom" => $nom,
            ":id" => $id
        ]);

        if ($stmt->fetch()) {

            $erreur = "Cette catégorie existe déjà.";

        } else {

            $stmt = $pdo->prepare("
                UPDATE categories
                SET nom = :nom
                WHERE id = :id
            ");

            $stmt->execute([
                ":nom" => $nom,
                ":id" => $id
            ]);

            definirMessage("Catégorie modifiée avec succès.");

            header("Location: categories.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une catégorie</title>
</head>

<body>

<h1>Modifier une catégorie</h1>

<?php if ($erreur): ?>

    <p style="color:red;">
        <?= htmlspecialchars($erreur) ?>
    </p>

<?php endif; ?>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrf_token) ?>"
    >

    <label for="nom">
        Nom de la catégorie :
    </label>

    <input
        type="text"
        id="nom"
        name="nom"
        value="<?= htmlspecialchars($categorie["nom"]) ?>"
        required
    >

    <button type="submit">
        Enregistrer
    </button>

</form>

<p>
    <a href="categories.php">
        Retour aux catégories
    </a>
</p>

</body>
</html>