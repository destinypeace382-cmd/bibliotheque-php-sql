<?php

require_once "../config/db.php";

$stmt = $pdo->prepare("
    SELECT
        categories.id,
        categories.nom,
        COUNT(livres.id) AS nombre_livres
    FROM categories
    LEFT JOIN livres
        ON livres.categorie_id = categories.id
    GROUP BY categories.id, categories.nom
    ORDER BY categories.id ASC
");

$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des catégories</title>

</head>

<body>

    <h1>Gestion des catégories</h1>

    <p>
        <a href="index.php">
            ← Retour aux livres
        </a>
    </p>

    <p>
        <a href="categorie-ajouter.php">
            Ajouter une catégorie
        </a>
    </p>

    <table border="1" cellpadding="10">

        <thead>

            <tr>

                <th>ID</th>
                <th>Nom</th>
                <th>Nombre de livres</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($categories as $categorie): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($categorie["id"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($categorie["nom"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($categorie["nombre_livres"]) ?>
                    </td>

                    <td>

                        <a
                            href="categorie-modifier.php?id=<?= htmlspecialchars($categorie["id"]) ?>"
                        >
                            Modifier
                        </a>

                        |

                        <a
                            href="categorie-supprimer.php?id=<?= htmlspecialchars($categorie["id"]) ?>"
                            onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');"
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