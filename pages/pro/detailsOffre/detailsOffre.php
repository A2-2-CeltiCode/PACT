<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");

require_once("../../../bdd/connect_params.php");

try {
    // Establishing the database connection
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    
    // Query to fetch offer details
    $stmt = $dbh->query('SELECT * FROM pact._offre', PDO::FETCH_ASSOC);
    $offre = $stmt->fetch();

    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }
    
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
} catch (Exception $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
} finally {
    $dbh = null; // Closing the database connection
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

<?php Header::render(); ?>

<body>
    <h1>Détails de l'offre</h1>
    <div class="container">

        <div class="carousel">
            <div class="carousel-images">
                <img src="../../../ressources/images/restaurant1.jpg" alt="Plat gourmet" class="carousel-image">
                <img src="../../../ressources/images/restaurant2.jpg" alt="Intérieur du restaurant" class="carousel-image">
                <img src="../../../ressources/images/restaurant3.jpg" alt="Chef préparant un plat" class="carousel-image">
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        <div class="offre-info">
            <?php
            Label::render("offre-title", "", "", $offre['titre'], "../../../ressources/icone/restaurant.svg");
            Label::render("offre-description", "", "", $offre['description']);
            Label::render("offre-detail", "", "", $offre['descriptionDetaillee']);
            ?>

            <div class="address">
                <?php
                $stmt_address = $dbh->query('SELECT adresse_entier FROM pact._adresse WHERE id = ' . $offre['adresse_id'], PDO::FETCH_ASSOC);
                $adresse = $stmt_address->fetch();
                Label::render("offre-infos", "", "", $adresse['adresse_entier'], "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            
            <?php
            Label::render("offre-website", "", "", "<a href='" . $offre['site_internet'] . "' target='_blank'>" . $offre['site_internet'] . "</a>", "../../../ressources/icone/naviguer.svg");
            
            $stmt_option = $dbh->query('SELECT nom_option FROM pact._option WHERE id = ' . $offre['option_id'], PDO::FETCH_ASSOC);
            $option = $stmt_option->fetch();
            Label::render("offre-option", "", "", "Option: " . $option['nom_option'], "../../../ressources/icone/info.svg");

            $stmt_forfait = $dbh->query('SELECT nom_forfait FROM pact._forfait WHERE id = ' . $offre['forfait_id'], PDO::FETCH_ASSOC);
            $forfait = $stmt_forfait->fetch();
            Label::render("offre-forfait", "", "", "Forfait: " . $forfait['nom_forfait'], "../../../ressources/icone/argent.svg");
            ?>

            <?php Label::render("offre-prix", "", "", "Prix: " . $offre['prix'] . "€", "../../../ressources/icone/price.svg"); ?>
        </div>

        <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>
<?php Footer::render(); ?>

</html>
