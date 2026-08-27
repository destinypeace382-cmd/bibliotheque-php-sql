<?php

require_once "../config/db.php";
require_once "../includes/csrf.php";

$csrf_token = genererTokenCSRF();

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
        ");

        $stmt->execute([
            ":nom" => $nom
        ]);


        if ($stmt->fetch()) {

            $erreur = "Cette catégorie existe déjà.";

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO categories (nom)
                VALUES (:nom)
            ");

            $stmt->execute([
                ":nom" => $nom
            ]);


            /* Message flash */

            definirMessage("Catégorie ajoutée avec succès.");


            /* Redirection */

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ajouter une catégorie</title>

</head>

<body>

    <h1>Ajouter une catégorie</h1>


    <p>

        <a href="categories.php">
            ← Retour aux catégories
        </a>

    </p>


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


        <div>

            <label for="nom">
                Nom de la catégorie :
            </label>

            <input
                type="text"
                id="nom"
                name="nom"
                value="<?= htmlspecialchars($_POST["nom"] ?? "") ?>"
                required
            >

        </div>


        <br>


        <button type="submit">
            Ajouter
        </button>

    </form>

</body>

</html>