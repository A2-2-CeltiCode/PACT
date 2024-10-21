<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");

require_once("../../../controlleur/Offre/Offre.php");
require_once("../../../controlleur/Compte/Compte.php");
require_once("../../../controlleur/Compte/ComptePro.php");
require_once("../../../controlleur/Option/Option.php");
require_once("../../../controlleur/Adresse/Adresse.php");
require_once("../../../controlleur/Forfait/Forfait.php");

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

?>

<?php

$adresse = new Adresse(
    '75001',
    'Paris',
    'Rue de la Paix',
    '12',
    '0123456789'
);

$compte = new Compte(
    1,
    'user123',
    'password123',
    'user@example.com',
    $adresse
);

$comptePro = new ComptePro(
    1,
    'Restaurant XYZ',
    'Restaurant XYZ Corp.',
    'FR76 1234 5678 9012 3456 7890 123',
    $compte
);

$option = new Option('All Inclusive');
$forfait = new Forfait('Forfait Standard');

$offre = new Offre(
    1,
    $comptePro,
    'Special Offer',
    'A great special offer for all customers.',
    'This detailed description explains all the amazing features of the offer...',
    'http://restaurant.com',
    $option,
    $forfait
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

<?php  Header::render();?>

<body>
    <h1>Détails de l'offre</h1>
    <div class="container">

        <div class="carousel">
            <div class="carousel-images">
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        <div class="offre-info">
            <?php
            Label::render("offre-title", "", "", $offre->getTitre(), "../assets/icon/restaurant.svg");
            Label::render("offre-description", "", "", $offre->getDescription(), "../assets/icon/info.svg");
            Label::render("offre-detail", "", "", $offre->getDescription(), "../assets/icon/details.svg");
            ?>

            <div class="address">
                <?php
                Label::render("offre-infos", "", "", "Adresse", "../assets/icon/location.svg");
                Label::render("offre-infos", "", "", $offre->getComptePro()->getCompte()->getAdresse()->getAdresseEntier());
                ?>
            </div>

            <div class="additional-info">
                <?php
                Label::render("offre-option", "", "","Option: " . $offre->getOption()->getNomOption(), "../assets/icon/option.svg");
                Label::render("offre-forfait", "", "", "Forfait: " . $offre->getForfait()->getNomForfait(), "../assets/icon/forfait.svg");
                Label::render("offre-website", "", "", "Site Web: ". $offre->getSiteInternet(), "../assets/icon/website.svg");
                ?>
            </div>

            <ul>
                <li><?php Label::render("offre-prix", "", "","Prix: ". "100" . "€", "../assets/icon/price.svg"); ?></li>
            </ul>
        </div>

        <?php Button::render("btn", "", "Modifier", "pro", "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>
<?php  Footer::render();?>

</html>