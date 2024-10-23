<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");

require_once("../../../bdd/connect_params.php");


try {
    // Établir la connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    
    // Requête pour récupérer les détails de l'offre
    $stmt = $dbh->query('SELECT * FROM pact.vue_activite', PDO::FETCH_ASSOC);
    $activite = $stmt->fetch();
    
    $stmt = $dbh->query('SELECT * FROM pact.vue_image_offre', PDO::FETCH_ASSOC);
    $image_offre = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_spectacle', PDO::FETCH_ASSOC);
    $spectacle = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_spectacle', PDO::FETCH_ASSOC);
    $spectacle = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_spectacle', PDO::FETCH_ASSOC);
    $spectacle = $stmt->fetch();

    // Vérification si les données sont bien récupérées
    if (!$spectacle) {
        throw new Exception("Aucune offre trouvée");
    }
    
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
} catch (Exception $e) {
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

<?php Header::render(HeaderType::Member); ?>
<body>
    <h1>Détails de l'offre</h1>
    <div class="container">

        <!-- Carousel d'images -->
        <div class="carousel">
            <div class="carousel-images">
                <img src="../../../ressources/images/restaurant1.jpg" alt="Plat gourmet" class="carousel-image">
                <img src="../../../ressources/images/restaurant2.jpg" alt="Intérieur du restaurant" class="carousel-image">
                <img src="../../../ressources/images/restaurant3.jpg" alt="Chef préparant un plat" class="carousel-image">
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        <!-- Informations de l'offre -->
        <div class="offre-info">
            <?php
            // Affichage du titre de l'offre
            Label::render("offre-title", "", "", $spectacle['titre'], "../../../ressources/icone/restaurant.svg");
            
            // Description courte et détaillée
            Label::render("offre-description", "", "", $spectacle['description']);
            Label::render("offre-detail", "", "", $spectacle['descriptiondetaillee']);
            ?>

            <!-- Adresse complète -->
            <div class="address">
                <?php
                $adresse = $spectacle['numrue'] . " " . $spectacle['nomrue'] . ", " . $spectacle['codepostal'] . " " . $spectacle['ville'];
                Label::render("offre-infos", "", "", $adresse, "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            
            <!-- Site Internet -->
            <?php
            Label::render("offre-website", "", "", "<a href='" . $spectacle['siteinternet'] . "' target='_blank'>" . $spectacle['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");
            
            // Option et forfait
            Label::render("offre-option", "", "", "Option: " . $spectacle['nomoption'], "../../../ressources/icone/info.svg");
            Label::render("offre-forfait", "", "", "Forfait: " . $spectacle['nomforfait'], "../../../ressources/icone/argent.svg");
            ?>

            <!-- Prix de l'offre -->
            <?php Label::render("offre-prix", "", "", "Prix: " . $spectacle['valprix'] . "€", "../../../ressources/icone/price.svg"); ?>
        </div>

        <!-- Bouton pour modifier l'offre -->
        <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>

<?php Footer::render(FooterType::Guest); ?>

</html>