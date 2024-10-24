<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");

require_once("../../../bdd/connect_params.php");

$idOffre = '1';
$typeOffre = 'spectacle';

try {
    // Établir la connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    switch ($typeOffre) {
        case 'restaurant':
            $typeOffre = 'restaurant';
            break;
        case 'spectacle':
            $typeOffre = 'spectacle';
            break;
        case 'parc-attractions':
            $typeOffre = 'parc_attractions';
            break;
        case 'activite':
            $typeOffre = 'activite';
            break;
        case 'visite':
            $typeOffre = 'visite';
            break;
        
        default:
            die('Aucune offre n\'a été donnée');
            break;
    }
    
    
    // Requête pour récupérer les détails de l'offre
    $stmt = $dbh->query('SELECT * FROM pact.vue_'. $typeOffre . ' WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
    $offre = $stmt->fetch();
    
    // Requête pour récupérer l'adresse de l'offre
    $stmt = $dbh->query('SELECT codepostal, ville, nomrue, numrue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre ='. $idOffre, PDO::FETCH_ASSOC);
    $adresse = $stmt->fetch();

      // Requête pour récupérer les tags de l'offre
      $stmt = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
      $tags = $stmt->fetchAll();



    $stmt = $dbh->query('SELECT nomimage FROM pact._image WHERE  idoffre ='. $idOffre, PDO::FETCH_ASSOC);
    $images = $stmt->fetch();

    // Vérification si les données sont bien récupérées
    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }
    
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
} finally {
    $dbh = null;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre</title>
    <link rel="stylesheet" href="detailsOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<?php Header::render(HeaderType::Pro); ?>
<body>
    <?php Label::render("titre-offre", "", "", $offre['titre']); ?> 
    <div class="container">
        <!-- Carousel d'images -->
        <div class="carousel">
            <div class="carousel-images">
                <?php
                foreach ($images as $image) {
                    echo '<img src="../../../ressources/'.$idOffre.'/images'.'/'.$image .'" class="carousel-image">';
                }
                ?>
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        <!-- Informations de l'offre -->
        <div class="offre-info">
            <?php
            // Affichage du titre de l'offre
            
            
            // Description courte et détaillée
            Label::render("offre-description", "", "", $offre['description'], "../../../ressources/icone/$typeOffre.svg");
            Label::render("offre-detail", "", "", $offre['descriptiondetaillee']);
            ?>

            <!-- Adresse complète -->
            <div class="address">
                <?php
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['numrue'] . ' ' . $adresse['nomrue'];
                Label::render("offre-infos", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            
            <!-- Site Internet -->
            <?php
            Label::render("offre-website", "", "", "<a href='" . $offre['siteinternet'] . "' target='_blank'>" . $offre['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");
            
            // Option et forfait
            Label::render("offre-forfait", "", "", "Forfait: " . $offre['nomforfait'], "../../../ressources/icone/argent.svg");
            Label::render("offre-option", "", "", "Information complémentaires: ", "../../../ressources/icone/info.svg");
            ?>

            <?php 
            // Tags
            if ($tags!=[] && strcmp($typeOffre,'restaurant')!=0) {
                echo "<ul> <li> Tags: ";
                foreach ($tags as $tag) {
                    echo ($tag['nomtag']) . " ";
                }
                echo "  </li> </ul>";
            }
            elseif (strcmp($typeOffre,'restaurant')==0) {
                
            }

            ?>

            <!-- Prix de l'offre -->
            <?php Label::render("offre-prix", "", "", "Prix: " . $offre['valprix'] . "€", "../../../ressources/icone/price.svg"); ?>
        </div>

        <!-- Bouton pour modifier l'offre -->
        <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>

<?php Footer::render(FooterType::Pro); ?>

</html>