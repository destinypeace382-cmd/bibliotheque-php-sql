<?php

require_once "../config/db.php";
require_once "../includes/csrf.php";

$csrf_token = genererTokenCSRF();

$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* Vérification CSRF */

    if (
        !isset($_POST["csrf_token"]) ||
        !verifierTokenCSRF($_POST["csrf_token"])
    ) {
        die("Erreur de sécurité : token CSRF invalide.");
    }


    /* Récupération des données */

    $titre = trim($_POST["titre"] ?? "");
    $auteur = trim($_POST["auteur"] ?? "");
    $categorie_id = $_POST["categorie_id"] ?? "";
    $annee = $_POST["annee"] ?? "";


    /* Validation */

    if ($titre === "") {
        $erreurs[] = "Le titre est obligatoire.";
    }

    if ($auteur === "") {
        $erreurs[] = "L'auteur est obligatoire.";
    }

    if (
        $categorie_id === "" ||
        !filter_var($categorie_id, FILTER_VALIDATE_INT)
    ) {
        $erreurs[] = "La catégorie est obligatoire.";
    }

    if (
        $annee === "" ||
        !filter_var($annee, FILTER_VALIDATE_INT)
    ) {
        $erreurs[] = "L'année doit être un nombre.";
    }


    /* Vérification de l'image */

    if (
        !isset($_FILES["image"]) ||
        $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE
    ) {

        $erreurs[] = "L'image est obligatoire.";

    }


    if (empty($erreurs)) {

        $typesAutorises = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (
            !in_array(
                $_FILES["image"]["type"],
                $typesAutorises,
                true
            )
        ) {
            $erreurs[] = "Le fichier doit être une image JPG, PNG ou WEBP.";
        }

        if (
            $_FILES["image"]["size"] > 5 * 1024 * 1024
        ) {
            $erreurs[] = "L'image ne doit pas dépasser 5 Mo.";
        }
    }


    /* Ajout du livre */

    if (empty($erreurs)) {

        $extension = strtolower(
            pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            )
        );

        $nomImage = uniqid("livre_", true) . "." . $extension;

        $cheminUpload = "../uploads/" . $nomImage;


        if (
            !move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                $cheminUpload
            )
        ) {

            $erreurs[] = "Impossible d'envoyer l'image.";

        } else {

            $cheminBDD = "uploads/" . $nomImage;

            $sql = "
                INSERT INTO livres
                (
                    titre,
                    auteur,
                    categorie_id,
                    annee,
                    image
                )
                VALUES
                (
                    :titre,
                    :auteur,
                    :categorie_id,
                    :annee,
                    :image
                )
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":titre" => $titre,
                ":auteur" => $auteur,
                ":categorie_id" => $categorie_id,
                ":annee" => $annee,
                ":image" => $cheminBDD
            ]);


            /* Message flash */

            definirMessage("Livre ajouté avec succès.");


            /* Redirection */

            header("Location: index.php");
            exit;
        }
    }
}


/* Récupération des catégories */

$stmt = $pdo->prepare("
    SELECT id, nom
    FROM categories
    ORDER BY nom ASC
");

$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ajouter un livre</title>

</head>

<body>

    <h1>Ajouter un livre</h1>

    <p>

        <a href="index.php">
            ← Retour aux livres
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


    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrf_token) ?>"
        >


        <div>

            <label for="titre">
                Titre :
            </label>

            <input
                type="text"
                id="titre"
                name="titre"
                value="<?= htmlspecialchars($_POST["titre"] ?? "") ?>"
                required
            >

        </div>


        <br>


        <div>

            <label for="auteur">
                Auteur :
            </label>

            <input
                type="text"
                id="auteur"
                name="auteur"
                value="<?= htmlspecialchars($_POST["auteur"] ?? "") ?>"
                required
            >

        </div>


        <br>


        <div>

            <label for="categorie_id">
                Catégorie :
            </label>

            <select
                id="categorie_id"
                name="categorie_id"
                required
            >

                <option value="">
                    -- Choisir une catégorie --
                </option>


                <?php foreach ($categories as $categorie): ?>

                    <option
                        value="<?= htmlspecialchars($categorie["id"]) ?>"
                        <?= (
                            ($_POST["categorie_id"] ?? "")
                            == $categorie["id"]
                        ) ? "selected" : "" ?>
                    >

                        <?= htmlspecialchars($categorie["nom"]) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <br>


        <div>

            <label for="annee">
                Année :
            </label>

            <input
                type="number"
                id="annee"
                name="annee"
                value="<?= htmlspecialchars($_POST["annee"] ?? "") ?>"
                required
            >

        </div>


        <br>


        <div>

            <label for="image">
                Image :
            </label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/webp"
                required
            >

        </div>


        <br>


        <button type="submit">
            Ajouter le livre
        </button>

    </form>

</body>

</html>