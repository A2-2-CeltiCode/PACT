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




require_once("../../../bdd/connect_params.php");
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}


/*
$adresse = new Adresse(
    '75001',
    'Paris',
    'Rue de la Paix',
    '12',
    '0123456789'
);

$compte = new Compte(
    1,
    'utilisateur123',
    'motdepasse123',
    'utilisateur@example.com',
    $adresse
);

$comptePro = new ComptePro(
    1,
    'Restaurant Le Gourmet',
    'Le Gourmet SARL',
    'FR76 1234 5678 9012 3456 7890 123',
    $compte
);

$option = new Option('Tout Inclus');
$forfait = new Forfait('Forfait Standard');

$offre = new Offre(
    1,
    $comptePro,
    'Offre Spéciale',
    'Une offre spéciale pour tous nos clients.',
    'Cette description détaillée explique toutes les fonctionnalités exceptionnelles de l\'offre spécial de kind of compte pro votre client il est bien en effet il ne serait pas le meilleur',
    'http://restaurantlegourmet.com',
    $option,
    $forfait,
    true,
    $adresse->getCodePostal(),
    $adresse->getVille()
);

*/
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
            Label::render("offre-title", "", "", $offre->getTitre(), "../../../ressources/icone/restaurant.svg");
            Label::render("offre-description", "", "", $offre->getDescription());
            Label::render("offre-detail", "", "", $offre->getDescriptionDetaillee());
            ?>

            <div class="address">
                <?php
                Label::render("offre-infos", "", "", $offre->getComptePro()->getCompte()->getAdresse()->getAdresseEntier(), "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            <?php
            Label::render("offre-website", "", "", "<a href='" . $offre->getSiteInternet() . "' target='_blank'>" . $offre->getSiteInternet() . "</a>", "../../../ressources/icone/naviguer.svg");
            Label::render("offre-option", "", "", "Option: " . $offre->getNomOption()->getNomOption(), "../../../ressources/icone/info.svg");
            Label::render("offre-forfait", "", "", "Forfait: " . $offre->getNomForfait()->getNomForfait(), "../../../ressources/icone/argent.svg");
            ?>
            <?php Label::render("offre-prix", "", "", "Prix: " . "100" . "€", "../../../ressources/icone/price.svg"); ?>
        </div>

        <?php Button::render("btn", "", "Modifier", "pro", "", "", "../StoryBook/StoryBook.php") ?>
    </div>

    <script src="detailsOffre.js"></script>
</body>
<?php Footer::render(); ?>

</html>