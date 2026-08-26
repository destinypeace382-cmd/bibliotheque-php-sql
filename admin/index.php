<?php

require_once "../config/db.php";

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
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administration - Livres</title>

</head>

<body>

    <h1>Administration des livres</h1>

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
                        <?= htmlspecialchars($livre['id']) ?>
                    </td>

                    <td>

                        <img
                            src="../<?= htmlspecialchars($livre['image']) ?>"
                            alt="<?= htmlspecialchars($livre['titre']) ?>"
                            width="80"
                        >

                    </td>

                    <td>
                        <?= htmlspecialchars($livre['titre']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($livre['auteur']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($livre['categorie']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($livre['annee']) ?>
                    </td>

                    <td>

                        <a href="livre-modifier.php?id=<?= htmlspecialchars($livre['id']) ?>">
                            Modifier
                        </a>

                        |

                        <a
                            href="livre-supprimer.php?id=<?= htmlspecialchars($livre['id']) ?>"
                            onclick="return confirm('Voulez-vous vraiment supprimer ce livre ?');"
                        >
                            Supprimer
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</body>

</html>