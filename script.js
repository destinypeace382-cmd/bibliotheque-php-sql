// Récupération des éléments HTML

const boutonsFiltres = document.querySelectorAll(".filtres button");
const livres = document.querySelectorAll(".livre");
const champRecherche = document.querySelector("#rechercheLivre");
const triLivres = document.querySelector("#triLivres");
const catalogue = document.querySelector(".catalogue");
const compteurResultats = document.querySelector("#compteurResultats");
const aucunResultat = document.querySelector("#aucunResultat");
const boutonsCoeur = document.querySelectorAll(".bouton-coeur");
const boutonFavoris = document.querySelector("#boutonFavoris");


    // Catégorie sélectionnée au départ

        let categorieActive = "tous";


    // Indique si on affiche uniquement les favoris

        let afficherFavoris = false;


    // Récupère les favoris déjà enregistrés dans le navigateur

        let favoris = JSON.parse(localStorage.getItem("favoris")) || [];


    // Fonction qui filtre les livres

        function rechercherLivres() {

    // Récupère le texte écrit dans la barre de recherche

        const texteRecherche = champRecherche.value.toLowerCase().trim();


    // Compte le nombre de livres affichés

        let nombreLivresVisibles = 0;


    // Parcourt tous les livres

        livres.forEach(function (livre) {

    // Récupère la catégorie du livre
    
        const categorieLivre = livre.dataset.categorie;


    // Récupère l'identifiant du livre
        const idLivre = livre.dataset.id;


     // Récupère le titre du livre
        const titre = livre
            .querySelector("h2")
            .textContent
            .toLowerCase();


    // Récupère le nom de l'auteur
        const auteur = livre
            .querySelector(".auteur")
            .textContent
            .toLowerCase();


    // Vérifie si le livre correspond à la catégorie sélectionnée
        const correspondCategorie =
            categorieActive === "tous" ||
            categorieLivre === categorieActive;


    // Vérifie si le livre correspond à la recherche
        const correspondRecherche =
            titre.includes(texteRecherche) ||
            auteur.includes(texteRecherche);


    // Vérifie si le livre correspond au mode favoris
        const correspondFavoris =
            afficherFavoris === false ||
            favoris.includes(idLivre);

    // Affiche le livre s'il respecte toutes les conditions
        if (
            correspondCategorie &&
            correspondRecherche &&
            correspondFavoris
        ) {

            livre.style.display = "block";

            nombreLivresVisibles++;

        } else {

            // Cache le livre
            livre.style.display = "none";

        }

    });


    // Mise à jour du compteur
        if (nombreLivresVisibles === 0) {
            compteurResultats.textContent = "0 livre trouvé";
        } else if (nombreLivresVisibles === 1) {
            compteurResultats.textContent = "1 livre trouvé";
        } else {
            compteurResultats.textContent = 
                nombreLivresVisibles + " livres trouvés";
        }


        // Affiche le message si aucun livre n'est trouvé
            if (nombreLivresVisibles === 0) {
                aucunResultat.style.display = "block";
            } else {
                aucunResultat.style.display = "none";
             }
}


    // Gestion des boutons de catégorie
        boutonsFiltres.forEach(function (bouton) {

        bouton.addEventListener("click", function () {

    // Ignore le bouton Mes favoris
        if (!bouton.dataset.categorie) {
            return;
        }


    // Récupère la catégorie sélectionnée
        categorieActive = bouton.dataset.categorie;


    // Retire la classe active des boutons de catégorie
        boutonsFiltres.forEach(function (btn) {

            if (btn.dataset.categorie) {

                btn.classList.remove("active");

            }

        });


    // Ajoute la classe active au bouton sélectionné
        bouton.classList.add("active");


    // Met à jour l'affichage des livres
        rechercherLivres();

    });

});


    // Recherche en temps réel
        champRecherche.addEventListener("input", function () {

        rechercherLivres();

});


    // Fonction qui trie les livres
        function trierLivres() {

    // Récupère le choix effectué
        const choixTri = triLivres.value;


    // Transforme la liste des livres en tableau
        const listeLivres = Array.from(livres);


    // Tri par année croissante
    if (choixTri === "annee-croissante") {

        listeLivres.sort(function (a, b) {

            const anneeA = parseInt(
                a.querySelector(".annee").textContent
            );

            const anneeB = parseInt(
                b.querySelector(".annee").textContent
            );

            return anneeA - anneeB;

        });

    }


    // Tri par année décroissante
    else if (choixTri === "annee-decroissante") {

        listeLivres.sort(function (a, b) {

            const anneeA = parseInt(
                a.querySelector(".annee").textContent
            );

            const anneeB = parseInt(
                b.querySelector(".annee").textContent
            );

            return anneeB - anneeA;

        });

    }


    // Tri par titre de A à Z
    else if (choixTri === "titre-az") {

        listeLivres.sort(function (a, b) {

            const titreA = a
                .querySelector("h2")
                .textContent
                .trim();

            const titreB = b
                .querySelector("h2")
                .textContent
                .trim();


            // Compare les titres selon l'alphabet français
            return titreA.localeCompare(titreB, "fr");

        });

    }


    // Replace les livres dans le catalogue
    listeLivres.forEach(function (livre) {

        catalogue.appendChild(livre);

    });

}


// Détecte un changement dans le menu de tri
triLivres.addEventListener("change", function () {

    trierLivres();

});


// Affiche les favoris déjà enregistrés au chargement
livres.forEach(function (livre) {

    // Récupère l'identifiant du livre
    const idLivre = livre.dataset.id;


    // Récupère le bouton cœur du livre
    const boutonCoeur = livre.querySelector(".bouton-coeur");


    // Vérifie si le livre est déjà dans les favoris
    if (favoris.includes(idLivre)) {

        boutonCoeur.textContent = "♥";

        boutonCoeur.classList.add("favori");

    }

});


// Gestion du clic sur les cœurs
boutonsCoeur.forEach(function (bouton) {

    bouton.addEventListener("click", function () {

        // Récupère la carte du livre
        const livre = bouton.closest(".livre");


        // Récupère l'identifiant du livre
        const idLivre = livre.dataset.id;


        // Vérifie si le livre est déjà favori
        if (favoris.includes(idLivre)) {

            // Retire le livre des favoris
            favoris = favoris.filter(function (id) {

                return id !== idLivre;

            });


            // Remet le cœur vide
            bouton.textContent = "♡";

            bouton.classList.remove("favori");

        } else {

            // Ajoute le livre aux favoris
            favoris.push(idLivre);


            // Remplit le cœur
            bouton.textContent = "♥";

            bouton.classList.add("favori");

        }


        // Enregistre les favoris dans le navigateur
        localStorage.setItem(
            "favoris",
            JSON.stringify(favoris)
        );


        // Si on regarde uniquement les favoris,
        // actualise immédiatement l'affichage
        if (afficherFavoris) {

            rechercherLivres();

        }

    });

});


// Gestion du bouton Mes favoris
boutonFavoris.addEventListener("click", function () {

    // Inverse le mode favoris
    afficherFavoris = !afficherFavoris;


    // Si le mode favoris est activé
    if (afficherFavoris) {

        boutonFavoris.classList.add("active");

        boutonFavoris.textContent = "♥ Mes favoris";

    } else {

        // Si le mode favoris est désactivé
        boutonFavoris.classList.remove("active");

        boutonFavoris.textContent = "♡ Mes favoris";

    }


    // Met à jour l'affichage
    rechercherLivres();

});


// Lance l'affichage initial des livres
rechercherLivres();