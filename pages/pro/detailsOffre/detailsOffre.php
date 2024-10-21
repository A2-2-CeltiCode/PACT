<?php
require_once("../../../controlleur/offreController.php");
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");

/*

$server = '';
$driver = '';
$dbname = '';
$user   = '';
$pass	= '';
$pdo = new PDO("$driver:host=$server;dbname=$dbname",$user, $pass);



$offreId = $_GET['idOffre'] ?? 1;

try
{
}
catch (Exception $e)
{
    die($e->getMessage());
}
$offre = Offre::getOfferById($pdo, $offreId);

if (!$offre) {
    echo "Offer not found.";
    exit;
}

*/

$offre = new Offre(
    1,
    "Offre Spéciale Restaurant",
    "Une offre exclusive pour un dîner inoubliable.",
    "Profitez d'un dîner complet avec entrée, plat et dessert dans un cadre magnifique.",
    "https://restaurant-exemple.com",
    "Option Vegan",
    "Forfait Gourmet"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Details</title>
    <link rel="stylesheet" href="detailsOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<body>
    <h1>Détails de l'offre</h1>
    <div class="container">

        <div class="carousel">
            <div class="carousel-images">
                <!-- Assuming the Offer class has a method getImages() to return images -->
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        <div class="offre-info">
            <?php
            // Rendering title, description, and other offre details using Labels
            Label::render("offre-title", "", "", $offre->getTitre(), "../assets/icon/restaurant.svg");
            Label::render("", "", "", $offre->getDescription());
            ?>
            <div class="address">
                <?php
                // Render address-related info (assuming the Offer class has these methods)
                Label::render("offre-infos", "", "", $offre->getNomForfait(), "../assets/icon/location.svg");
                Label::render("offre-infos", "", "", $offre->getNomOption());
                ?>
            </div>
            <?php
            // Render other offre information like contact, package type, etc.
            Label::render("offre-infos", "", "", $offre->getNomForfait(), "../assets/icon/promotion.svg");
            Label::render("offre-infos", "", "", $offre->getSiteInternet(), "../assets/icon/website.svg");
            Label::render("offre-infos", "", "", $offre->getDescriptionDetaillee());
            ?>
            <ul>
                <li><?php Label::render("offre-infos", "", "", "Prix: " . $offre->getIdOffre() . "€"); ?></li>
            </ul>
        </div>

        <!-- Modify button -->
        <?php Button::render("", "", "Modifier", "pro", "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>

</html>