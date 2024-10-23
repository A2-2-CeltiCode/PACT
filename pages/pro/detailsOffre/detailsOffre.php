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

    $stmt = $dbh->query('SELECT * FROM pact.vue_menu_restaurant', PDO::FETCH_ASSOC);
    $menu_restaurant = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_parc_attractions', PDO::FETCH_ASSOC);
    $parc_attractions = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_restaurant', PDO::FETCH_ASSOC);
    $restaurant = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_spectacle', PDO::FETCH_ASSOC);
    $spectacle = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_activite', PDO::FETCH_ASSOC);
    $tags_activite = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_parc_attractions', PDO::FETCH_ASSOC);
    $tags_parc_attractions = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_restaurant', PDO::FETCH_ASSOC);
    $tags_restaurant = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_spectacle', PDO::FETCH_ASSOC);
    $tags_spectacle = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_visite', PDO::FETCH_ASSOC);
    $tags_visite = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_visite', PDO::FETCH_ASSOC);
    $visite = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_visite_guidee', PDO::FETCH_ASSOC);
    $visite_guidee = $stmt->fetch();

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

<?php Header::render(HeaderType::Pro); ?>
<body>
    <?php Label::render("offre-title", "", "", $spectacle['titre']); ?>
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
            Label::render("offre-description", "", "", $spectacle['description'], "../../../ressources/icone/spectacle.svg");?>
            <div class="offre-infos">
                <?php
                Label::render("offre-detail", "", "", $spectacle['descriptiondetaillee']);
                $adresse = $spectacle['numrue'] . " " . $spectacle['nomrue'] . ", " . $spectacle['codepostal'] . " " . $spectacle['ville'];
            
                Label::render("offre-adresse", "", "", $adresse, "../../../ressources/icone/localisateur.svg");
                Label::render("offre-website", "", "", "<a href='" . $spectacle['siteinternet'] . "' target='_blank'>" . $spectacle['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");
                
                Label::render("info-complementaire", "", "", "Infos complémentaires:", "../../../ressources/icone/info.svg");?>
                <ul>
                <li><?php Label::render("offre-tag", "", "", "Tag: " . $tags_spectacle['nomtag']); ?></li>
                <li style="color:red">il faut faire des li dynamiques</li>
                </ul>
                
            </div>
            <!-- Prix de l'offre -->
        </div>
        <?php Label::render("offre-prix", "", "", "" . $spectacle['valprix'] . "€", "../../../ressources/icone/price.svg"); ?>

        <!-- Bouton pour modifier l'offre -->
        <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", "", "../StoryBook/StoryBook.php") ?>
    </div>
    

    <script src="detailsOffre.js"></script>
</body>

<?php Footer::render(FooterType::Pro); ?>

</html>