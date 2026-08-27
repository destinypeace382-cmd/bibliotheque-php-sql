<?php

require_once "../includes/csrf.php";
require_once "../config/db.php";

$message = recupererMessage();

/* Pagination */

$livresParPage = 5;

$page = isset($_GET["page"])
    ? (int) $_GET["page"]
    : 1;

if ($page < 1) {
    $page = 1;
}

/* Nombre total de livres */

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM livres
");

$stmt->execute();

$totalLivres = (int) $stmt->fetchColumn();

$totalPages = (int) ceil(
    $totalLivres / $livresParPage
);

if ($totalPages < 1) {
    $totalPages = 1;
}

if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $livresParPage;


/* Récupération des livres de la page */

$sql = "
    SELECT
        livres.id,
        livres.titre,
        livres.auteur,
        livres.annee,
        livres.image,
        categories.nom AS categorie
    FROM livres
    INNER JOIN categories
        ON livres.categorie_id = categories.id
    ORDER BY livres.id ASC
    LIMIT :limite OFFSET :offset
";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(
    ":limite",
    $livresParPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ":offset",
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Administration - Livres</title>

</head>

<body>

    <h1>Administration des livres</h1>


    <?php if ($message): ?>

        <p style="color: green;">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <p>

        <a href="livre-ajouter.php">
            Ajouter un livre
        </a>

    </p>


    <p>

        <a href="categories.php">
            Gérer les catégories
        </a>

    </p>


    <p>

        Total :
        <?= htmlspecialchars($totalLivres) ?>
        livre(s)

    </p>


    <table border="1" cellpadding="10">

        <thead>

            <tr>

                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Année</th>
                <th>Actions</th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($livres as $livre): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($livre["id"]) ?>
                    </td>


                    <td>

                        <img
                            src="../<?= htmlspecialchars($livre["image"]) ?>"
                            alt="<?= htmlspecialchars($livre["titre"]) ?>"
                            width="80"
                        >

                    </td>


                    <td>
                        <?= htmlspecialchars($livre["titre"]) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($livre["auteur"]) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($livre["categorie"]) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($livre["annee"]) ?>
                    </td>


                    <td>

                        <a
                            href="livre-modifier.php?id=<?= htmlspecialchars($livre["id"]) ?>"
                        >
                            Modifier
                        </a>

                        |

                        <form
                            action="livre-supprimer.php"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce livre ?');"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= htmlspecialchars($livre["id"]) ?>"
                            >


                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(genererTokenCSRF()) ?>"
                            >


                            <button type="submit">
                                Supprimer
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <br>


    <!-- Pagination -->

    <div>

        <?php if ($page > 1): ?>

            <a href="?page=<?= $page - 1 ?>">
                ← Précédent
            </a>

        <?php endif; ?>


        <?php for ($i = 1; $i <= $totalPages; $i++): ?>

            <?php if ($i === $page): ?>

                <strong>
                    <?= $i ?>
                </strong>

            <?php else: ?>

                <a href="?page=<?= $i ?>">
                    <?= $i ?>
                </a>

            <?php endif; ?>

        <?php endfor; ?>


        <?php if ($page < $totalPages): ?>

            <a href="?page=<?= $page + 1 ?>">
                Suivant →
            </a>

        <?php endif; ?>

    </div>

</body>

</html>