<?php

require_once "../config/db.php";

$erreurs = [];

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("Catégorie invalide.");
}


/* Récupération de la catégorie */

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


/* Traitement du formulaire */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");

    if ($nom === "") {
        $erreurs[] = "Le nom de la catégorie est obligatoire.";
    }


    /* Vérifier qu'une autre catégorie n'utilise pas déjà ce nom */

    if (empty($erreurs)) {

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
            $erreurs[] = "Cette catégorie existe déjà.";
        }
    }


    /* Modification */

    if (empty($erreurs)) {

        $stmt = $pdo->prepare("
            UPDATE categories
            SET nom = :nom
            WHERE id = :id
        ");

        $stmt->execute([
            ":nom" => $nom,
            ":id" => $id
        ]);

        header("Location: categories.php");
        exit;
    }

    $categorie["nom"] = $nom;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier une catégorie</title>

</head>

<body>

    <h1>Modifier une catégorie</h1>

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
                value="<?= htmlspecialchars($categorie["nom"]) ?>"
                required
            >

        </div>

        <br>

        <button type="submit">
            Enregistrer les modifications
        </button>

    </form>

</body>

</html>