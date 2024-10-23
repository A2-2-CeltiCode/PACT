<!DOCTYPE html>
<html lang="fr">

<!-- Inclusion du fichier PHP pour le composant d'entrée (input) -->
<?php require_once "../../composants/Input/Input.php";
      require_once "../../composants/Label/Label.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'Offre</title>

    <!-- Liens vers les fichiers CSS pour le style général et spécifique -->
    <link rel="stylesheet" href="header_footer.css">
    <link rel="stylesheet" href="offreDetail.css">

</head>

<body>
    <?php Header::render(HeaderType::Member); ?>
    <!-- Contenu principal avec les détails de l'offre -->
    <main>
        <div class="offre">
            <!-- Image principale de l'offre -->
            <img class="image-offre" alt="Image offre" src="../../ressources/icone/cate.jpg" />

            <div class="description">

                <?php Label::render("nom-restau", "", "", "Saveur de Bretagne", "../../ressources/icone/restaurant.svg");
                      Label::render("", "", "", 'Le restaurant breton "Les Saveurs de Bretagne" offre une ambiance chaleureuse et authentique, avec ses poutres apparentes, ses pierres naturelles et ses décorations maritimes. Niché près de la côte, l établissement propose un menu qui célèbre la cuisine traditionnelle bretonne : galettes de sarrasin, fruits de mer frais, et crêpes sucrées.');
                      Label::render("bas_desc", "", "", "11h-15h & 19h-23h", "../../ressources/icone/horloge.svg"); ?>

                 <a href="https://www.lesfilsamaman.com/restaurants/rennes/?utm_source=google&utm_medium=organic&utm_campaign=mybusiness-website">
                    <?php Label::render("bas_desc", "", "", "Site du restaurant", "../../ressources/icone/naviguer.svg");?>
                </a>
                <?php 
                    Label::render("bas_desc", "", "", "Lannion (22300) 3 rue bidule", "../../ressources/icone/localisateur.svg"); 
                ?>

                <!-- Liste imbriquée pour les informations supplémentaires -->
                <ul>
                    <li>Infos complémentaires :
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
                <?php Label::render("tranche_prix", "", "", "€€€");
                ?>

                <div class="carte">
                    <div class="dessus-carte">
                    <!-- Informations de profil : photo, nom, localisation, contact -->
                        <img alt="Photo de profil" height="50" src="https://storage.googleapis.com/a1aa/image/X1R0j5nq39oeCyPhJsMjBx3peJ0fBncTGTd4TCy7MPRk7NPnA.jpg" width="50"/>
                        <div>
                                            
                            <?php Label::render("partie_haute", "", "", "Serge Sauvion");
                             Label::render("partie_haute", "", "", "Lannion", "../../ressources/icone/localisateur.svg"); ?>
                            
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

    <?php Footer::render(FooterType::Guest); ?>
</body>
</html>
