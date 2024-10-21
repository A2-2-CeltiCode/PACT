<!DOCTYPE html>
<html lang="fr">

<!-- Inclusion du fichier PHP pour le composant d'entrée (input) -->
<?php include "composants/Input/Input.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'Offre</title>

    <!-- Liens vers les fichiers CSS pour le style général et spécifique -->
    <link rel="stylesheet" href="header_footer.css">
    <link rel="stylesheet" href="offreDetail.css">

</head>

<body>
    <!-- En-tête du site avec le logo, la barre de recherche, la navigation et la langue -->
    <header class="entete">
        <div class="entete-logo">
            <img src="../../ressources/icone/logo.svg" alt="Logo PACT">
            <span class="texte-logo">PACT</span>
        </div>
        <div class="entete-recherche">
            <!-- Composant d'entrée pour la recherche avec une localisation -->
            <?php Input::render("text", "Entrez une localisation..."); ?>
        </div>
        <nav class="entete-navigation">
            <a href="index.php">Accueil</a>
            <a href="offre.php">Offres</a>
        </nav>
        <div class="entete-langue">
            <!-- Sélecteur de langue avec icône -->
            <img src="../../ressources/icone/logofr.svg" alt="Français">
            <select name="langue" id="langue">
                <option value="fr">Français</option>
                <option value="en">English</option>
            </select>
        </div>
        <div class="entete-connexion">
            <!-- Lien vers la page de connexion avec un bouton -->
            <a href="connexionComptePro.php" class="lien-bouton"><button>S'inscrire / Se Connecter</button></a>
        </div>
    </header>

    <!-- Contenu principal avec les détails de l'offre -->
    <main>
        <div class="offre">
            <!-- Image principale de l'offre -->
            <img class="image-offre" alt="Image offre" src="../../ressources/icone/cate.jpg" />

            <div class="description">
                <h2>
                    <!-- Icône de restaurant avec texte de titre -->
                    <img src="../../ressources/icone/restaurant.svg" alt="Logo restaurant" style="vertical-align: middle; height: 40px;">
                    Saveur de Bretagne
                </h2>
                <!-- Description du restaurant -->
                <p>Le restaurant breton "Les Saveurs de Bretagne" offre une ambiance chaleureuse et authentique, avec ses poutres apparentes, ses pierres naturelles et ses décorations maritimes. Niché près de la côte, l'établissement propose un menu qui célèbre la cuisine traditionnelle bretonne : galettes de sarrasin, fruits de mer frais, et crêpes sucrées.</p>

                <h3>
                    <!-- Icône d'horloge et horaires -->
                    <img src="../../ressources/icone/horloge.svg" alt="Logo horloge" style="vertical-align: middle; height: 40px;">
                    11h-15h & 19h-23h
                </h3>

                <h3>
                    <!-- Icône de navigation et lien vers le site web -->
                    <img src="../../ressources/icone/naviguer.svg" alt="Logo site web" style="vertical-align: middle; height: 40px;">
                    <a href="https://www.lesfilsamaman.com/restaurants/rennes/?utm_source=google&utm_medium=organic&utm_campaign=mybusiness-website">Site du restaurant</a>
                </h3>

                <h3>
                    <!-- Icône de localisation et adresse -->
                    <img src="../../ressources/icone/localisateur.svg" alt="Logo position" style="vertical-align: middle; height: 40px;">
                    Lannion (22300) 3 rue bidule
                </h3>

                <!-- Liste imbriquée pour les informations supplémentaires -->
                <ul>
                    <li>Infos complémentaires
                        <ul>
                            <li>Déjeuner</li>
                            <li>Tag : Française - Crêperie</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Section droite avec note en étoiles et information de contact -->
            <div class="colonne-droite">
                <div class="note_etoiles">
                    <!-- Icônes d'étoiles pour la note -->
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../ressources/icone/etoile_mid.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../ressources/icone/etoile_vide.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <!-- (répéter pour le nombre d'étoiles pleines et vides nécessaires) -->
                </div>
                <h3>€€€</h3>

                <div class="carte">

                    <div class="dessus-carte">
                    <!-- Informations de profil : photo, nom, localisation, contact -->
                        <img alt="Photo de profil" height="50" src="https://storage.googleapis.com/a1aa/image/X1R0j5nq39oeCyPhJsMjBx3peJ0fBncTGTd4TCy7MPRk7NPnA.jpg" width="50"/>
                        <div>
                            <h2>Serge Sauvion</h2>
                            <p>
                                <img src="../../ressources/icone/localisateur.svg" alt="icone localisation" style="vertical-align: middle;">
                                Lannion
                            </p>
                        </div>
                    </div> 

                    <div class="dessous-carte">
                        <div class="email">
                            <img src="../../ressources/icone/lettre.svg" alt="icone lettre" style="vertical-align: middle;">
                            <a href="mailto:serge@gmail.com">serge@gmail.com</a>
                        </div>
                        <div class="telephone">
                            <img src="../../ressources/icone/telephone.svg" alt="icone téléphone" style="vertical-align: middle;">
                            <span>(671) 555-0110</span>
                        </div>
                        <!-- Bouton d'envoi de mail -->
                        <a class="bouton" href="mailto:serge@gmail.com">
                            <img src="../../ressources/icone/lettre.svg" alt="icone lettre" style="vertical-align: middle;">
                            Envoyer un Mail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page avec des liens importants et des icônes de réseaux sociaux -->
    <footer>
        <!-- Bouton pour devenir membre -->
        <a href="creationComptePro.php" class="lien-bouton"><button>DEVENIR MEMBRE</button></a>
        <div class="liens-importants">
            <!-- Liens vers des pages légales et informatives -->
            <a href="mentions.php">Mentions Légales</a>
            <a href="quiSommeNous.php">Qui sommes nous ?</a>
            <a href="condition.php">Conditions Générales</a>
        </div>
        <div class="icones-reseaux-sociaux">
            <!-- Icônes de réseaux sociaux : Facebook, Twitter, Instagram -->
            <a href="https://www.facebook.com/?locale=fr_FR"><img src="../../ressources/icone/facebook.svg" alt="Icon facebook" style="vertical-align: middle;"></a>
            <a href="https://x.com/TripEnArvorPACT"><img src="../../ressources/icone/twitter.svg" alt="Icon X" style="vertical-align: middle;"></a>
            <a href="https://www.instagram.com/pactlannion/"><img src="../../ressources/icone/instagram.svg" alt="Icon instagram" style="vertical-align: middle;"></a>
        </div>
        <p>© 2024 PACT, Inc.</p>
        <a href="#entete" class="remonter-page"><img src="../../ressources/icone/fleche.svg" alt="Icon fleche" style="vertical-align: middle;"></a>
    </footer>
</body>
</html>
