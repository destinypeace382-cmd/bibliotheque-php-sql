<?php

require_once "../config/db.php";

$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");

    if ($nom === "") {
        $erreurs[] = "Le nom de la catégorie est obligatoire.";
    }

    if (empty($erreurs)) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM categories
            WHERE nom = :nom
        ");

        $stmt->execute([
            ":nom" => $nom
        ]);

        if ($stmt->fetch()) {
            $erreurs[] = "Cette catégorie existe déjà.";
        }
    }

    if (empty($erreurs)) {

        $stmt = $pdo->prepare("
            INSERT INTO categories (nom)
            VALUES (:nom)
        ");

        $stmt->execute([
            ":nom" => $nom
        ]);

        header("Location: categories.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajouter une catégorie</title>

</head>

<body>

    <h1>Ajouter une catégorie</h1>

    <p>
        <a href="categories.php">
            ← Retour aux catégories
        </a>
    </p>

    <?php if (!empty($erreurs)): ?>

        <div>

            <?php foreach ($erreurs as $erreur): ?>

                <p>
                    <?= htmlspecialchars($erreur) ?>
                </p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <form method="POST">

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
            Ajouter la catégorie
        </button>

    </form>

</body>

</html>