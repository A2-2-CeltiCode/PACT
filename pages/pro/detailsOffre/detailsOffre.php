<?php
require_once("../offre/offer.php");
require_once("../components/Label/Label.php");
require_once("../components/Button/Button.php");

// Création de l'instance d'offre
$offer = new Offer(
    $offerType = "Restauration",
    $title = "Restaurant",
    $description = "Les Saveurs de Bretagne",
    $additionalDescription = "Le restaurant breton Les Saveurs de Bretagne offre une ambiance chaleureuse et authentique, avec ses poutres apparentes, ses pierres naturelles et ses décorations maritimes. Niché près de la côte, l'établissement propose un menu qui célèbre la cuisine traditionnelle bretonne : galettes de sarrasin, fruits de mer frais, et crêpes sucrées.",
    $images = ["../assets/images/chasseur.jpg", "../assets/images/resto.png", "../assets/images/table.jpg"],
    $pricing = 50, // Prix en euros
    $packageType = "Standard Package",
    $promotionType = "Premium",
    $city = "Paris",
    $postalCode = "75001",
    $street = "10 Rue de l'Exemple",
    $phoneContact = "+33 1 23 45 67 89",
    $website = "https://www.tiktok.com/@afexix/video/7333332260483009835?is_from_webapp=1&sender_device=pc&web_id=7426315516917122593"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Details</title>
    <link rel="stylesheet" href="offerDetails.css">
    <link rel="stylesheet" href="../style/ui.css">
</head>

<body>
    <h1>Détails de l'offre</h1>
    <div class="container">

        <div class="carousel">
            <div class="carousel-images">
                <?php foreach ($offer->getImages() as $image): ?>
                    <img src="<?= $image; ?>" alt="Offer Image" class="carousel-image">
                <?php endforeach; ?>
            </div>
            <button class="carousel-button prev">❮</button>
            <button class="carousel-button next">❯</button>
        </div>

        
        <div class="offer-info">
            <?php
            Label::render("offer-title", "", "", "" . $offer->getOfferType(), "../assets/icon/restaurant.svg");
            Label::render("", "", "", "" . $offer->getDescription()); ?>
            <div class="address">
                <?php
                Label::render("offer-infos", "", "", "" . $offer->getStreet(), "../assets/icon/location.svg");
                Label::render("offer-infos", "", "", "" . $offer->getCity());
                Label::render("offer-infos", "", "", "" . $offer->getPostalCode());
                ?>
            </div>
            <?php
            Label::render("offer-infos", "", "", "" . $offer->getPackageType(), "../assets/icon/promotion.svg");
            Label::render("offer-infos", "", "", "" . $offer->getPhoneContact(),  "../assets/icon/phone.svg");
            Label::render("offer-infos", "", "", "<a href=\"" . $offer->getWebsite() . "\" target=\"_blank\">www.lessaveursdebretagne.com</a>",  "../assets/icon/website.svg");
            Label::render("offer-infos", "", "", "" . $offer->getAdditionalDescription()); ?>
            <ul>
                <li><?php Label::render("offer-infos", "", "", "Type de promotion: " . $offer->getPromotionType()); ?></li>
                <li><?php Label::render("offer-infos", "", "", "Prix: " . $offer->getPricing() . "€"); ?></li>
            </ul>

        </div>


        <?php Button::render("", "", "Modifier", "pro", "", "", "../StoryBook/StoryBook.php") ?>

    </div>


    <script src="offerDetails.js"></script>
</body>

</html>