<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");

require_once("../../../bdd/connect_params.php");

$idOffre = '3';
$typeOffre = 'parc_attractions';

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
        case 'parc_attractions':
            $typeOffre = 'parc_attractions';
            break;
        case 'activite':
            $typeOffre = 'activite';
            break;
        case 'visite':
            $typeOffre = 'visite';
            break;
        
        default:
            die('Aucune offre n\'a été trouvée');
            break;
    }

    // Requête pour récupérer le temsp en mine de la séance de spectacle
    $stmt = $dbh->query('SELECT prestation FROM pact.vue_activite WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
    $prestation = $stmt->fetch();




        // Requête pour récupérer le temsp en mine de la séance de spectacle
        $stmt = $dbh->query('SELECT tempsenminutes FROM pact.vue_activite WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
        $minutesActivite = $stmt->fetch();

            // Requête pour récupérer l'age minimum pour un parc d'attraction ageMinimum
            $stmt = $dbh->query('SELECT agemin FROM pact.vue_activite WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
            $ageMinimumActivite = $stmt->fetch();

        // Requête pour récupérer l'age minimum pour un parc d'attraction ageMinimum
        $stmt = $dbh->query('SELECT agemin FROM pact.vue_parc_attractions WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
        $ageMinimumParc = $stmt->fetch();

            // Requête pour récupérer le nombre d'attration dans un parc d'attraction
    $stmt = $dbh->query('SELECT nbattractions FROM pact.vue_parc_attractions WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
    $nbAttraction = $stmt->fetch();

    // Requête pour récupérer la gamme du restaurant
    $stmt = $dbh->query('SELECT nomgamme FROM pact.vue_restaurant WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
    $gammeRestaurant = $stmt->fetch();

    // Requête pour récupérer le temsp en mine de la séance de spectacle
    $stmt = $dbh->query('SELECT tempsenminutes FROM pact.vue_spectacle WHERE idoffre = '. $idOffre, PDO::FETCH_ASSOC);
    $minutesSpectacle = $stmt->fetch();
    
    
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
            ?>

<?php
            Label::render("offre-option", "", "", "Option: " . $offre['nomoption'], "../../../ressources/icone/yeux.svg");
            ?>

            <?php 
                // Tags
            if ($tags!=[]) {
                $tagsString = '';
                foreach ($tags as $tag) {
                    $tagsString .= $tag['nomtag'] . " ";
                }
                $tagsString = trim($tagsString);
            Label::render("offre-tags", "", "", $tagsString, "../../../ressources/icone/tag.svg");

            // Option et forfait
            Label::render("offre-option", "", "", "Informations complémentaires: ", "../../../ressources/icone/info.svg");
                
                switch ($typeOffre) {
                    case 'restaurant':
                        echo '<li>';
                        Label::render("", "", "", "Gamme Restaurant: " . $gammeRestaurant, "../../../ressources/icone/gamme.svg");
                        echo '</li>';
                        // afficher le menu du restaurant en file que l'on peut télécharger en pdf
                        
                        break;
                    case 'spectacle':
                        echo '<li>';
                        Label::render("", "", "", "Durée(min): " . $minutesSpectacle['tempsenminutes'], "../../../ressources/icone/timer.svg");
                        echo '</li>';
                        break;
                    case 'parc_attractions':
                        echo '<li>';
                        print_r($prestation);
                        Label::render("", "", "", "Age minimum: " . $ageMinimumParc['agemin'], "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Nombre d'attraction: " . $nbAttraction['nbattractions'], "../../../ressources/icone/timer.svg");
                        echo '</li>';
                        break;
                    case 'activite':
                        echo '<li>';
                        Label::render("", "", "", "Age minimum: " . $ageMinimumParc['agemin'], "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Durée(min): " . $minutesActivite['tempsenminutes'], "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Prestation " . $prestation[''], "../../../ressources/icone/timer.svg");
                        echo '</li>';
                        break;
                    case 'visite':
                        echo '<li>';
                        echo '</li>';
                        break;
                    
                    default:
                        die("Aucune offre n\'a été trouvée");
                        break;
                }        
                echo "</ul>";
            }
            ?>

            <!-- Prix de l'offre -->
             
            <?php
            if (strcmp($typeOffre,'restaurant')!=0) {
                Label::render("offre-prix", "", "", "Prix: " . $offre['valprix'] . "€", "../../../ressources/icone/euro.svg");
            }?>
        </div>
        <?php Label::render("offre-forfait", "", "", "Forfait: " . $offre['nomforfait'], "../../../ressources/icone/argent.svg");?>

        <!-- Bouton pour modifier l'offre -->
        <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>

<?php Footer::render(FooterType::Pro); ?>

</html>