<?php
require_once "config/db.php";

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

    <title>La Bibliothèque</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <header>

        <div class="header-contenu">

            <div class="logo">
                <h3>
                    <span class="point">•</span>
                    La Bibliothèque
                </h3>
            </div>

            <nav>
                <a href="#">Accueil</a>
            </nav>

        </div>

    </header>


    <!-- Contenu principal -->
    <main>

        <!-- Présentation -->
        <section class="hero">

            <h1>Catalogue</h1>

            <p class="subtitle">
                Parcourez notre catalogue de lecture, romans, science-fiction,
                jeunesse et plus encore.
            </p>

        </section>


        <!-- Barre des filtres -->
        <section class="filtres">

            <button class="active" data-categorie="tous">
                Tous
            </button>

            <button data-categorie="roman">
                Roman
            </button>

            <button data-categorie="science-fiction">
                Science-fiction
            </button>

            <button data-categorie="jeunesse">
                Jeunesse
            </button>

            <button data-categorie="bande-dessinee">
                Bande dessinée
            </button>

            <button data-categorie="documentaire">
                Documentaire
            </button>

            <button data-categorie="poesie">
                Poésie
            </button>

            <button id="boutonFavoris">
                ♡ Mes favoris
            </button>

        </section>

        <section class="outils">

    <div class="recherche">
        <input
            type="search"
            id="rechercheLivre"
            placeholder="Rechercher un livre ou un auteur..."
            aria-label="Rechercher un livre ou un auteur"
        >
    </div>

    <div class="tri">

        <label for="triLivres">Trier par :</label>

        <select id="triLivres">
            <option value="">Par défaut</option>
            <option value="annee-croissante">Année croissante</option>
            <option value="annee-decroissante">Année décroissante</option>
            <option value="titre-az">Titre A → Z</option>
        </select>

    </div>

</section>

        <!-- Nombre de résultats -->
    <div class="resultats">
        <p id="compteurResultats">8 livres trouvés</p>
    </div>

        <!-- Message affiché lorsqu'aucun livre n'est trouvé -->
    <div id="aucunResultat" class="aucun-resultat">
        Aucun livre ne correspond à votre recherche.
    </div>

        <!-- Catalogue des livres -->
        <section class="catalogue">
	
	<?php foreach ($livres as $livre): ?>

    <?php
        $categorie = strtolower($livre['categorie']);

        $categorie = str_replace(
            ['é', 'è', 'ê', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù'],
            ['e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u'],
            $categorie
        );

        $categorie = str_replace(' ', '-', $categorie);
    ?>

    <article
        class="livre"
        data-id="<?= htmlspecialchars($livre['id']) ?>"
        data-categorie="<?= htmlspecialchars($categorie) ?>"
    >

        <button
            class="bouton-coeur"
            aria-label="Ajouter aux favoris"
        >
            ♡
        </button>

        <div class="couverture">

            <img
                src="<?= htmlspecialchars($livre['image']) ?>"
                alt="Couverture du livre <?= htmlspecialchars($livre['titre']) ?>"
            >

        </div>

        <div class="contenu-livre">

            <h2>
                <?= htmlspecialchars($livre['titre']) ?>
            </h2>

            <p class="auteur">
                <?= htmlspecialchars($livre['auteur']) ?>
            </p>

            <div class="infos">

                <span class="categorie">
                    <?= htmlspecialchars(strtoupper($livre['categorie'])) ?>
                </span>

                <span class="annee">
                    <?= htmlspecialchars($livre['annee']) ?>
                </span>

            </div>

        </div>

    </article>

<?php endforeach; ?>

            

        </section>

    </main>


    <!-- Pied de page -->
    <footer>

    <p>&copy; 2026 La Bibliothèque</p>

    <div class="reseaux">

        <!-- Instagram -->
        <a href="#" class="instagram" aria-label="Instagram">
            <i class="fa-brands fa-instagram"></i>
        </a>

        <!-- X -->
        <a href="#" class="twitter" aria-label="X">
            <i class="fa-brands fa-x-twitter"></i>
        </a>

        <!-- Facebook -->
        <a href="#" class="facebook" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
        </a>

    </div>

</footer>

    <!-- Fichier JavaScript -->
    <script src="script.js"></script>

</body>

</html>