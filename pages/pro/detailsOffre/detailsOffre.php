<?php
require_once("../../../composants/Label/Label.php");
require_once("../../../composants/Button/Button.php");
require_once("../../../composants/Input/Input.php");
require_once("../../../composants/Header/Header.php");
require_once("../../../composants/Footer/Footer.php");
require_once("../../../bdd/connect_params.php");

$idOffre = isset($_POST['idOffre']) ? $_POST['idOffre'] : '1';

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

    $stmt = $dbh->prepare('SELECT nomcategorie FROM (SELECT nomcategorie FROM pact.vue_spectacle WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_restaurant WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_parc_attractions WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_activite WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_visite WHERE idoffre = :idOffre) AS categories');
    $stmt->execute([':idOffre' => $idOffre]);
    $typeOffre = $stmt->fetchColumn();
    $typeOffre = str_replace(' ', '_', $typeOffre);
    $typeOffre = str_replace("'", '', $typeOffre);
    $typeOffre = strtolower($typeOffre);
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }  
    

    // Requêtes pour obtenir les informations nécessaires
    $stmt = $dbh->query('SELECT estguidee FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $guidee = $stmt->fetch();

    $stmt = $dbh->query('SELECT tempsenminutes FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $minutesVisite = $stmt->fetch();

    $stmt = $dbh->query('SELECT prestation FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $prestation = $stmt->fetch();

    $stmt = $dbh->query('SELECT capacite FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $capacite = $stmt->fetch();

    $stmt = $dbh->query('SELECT tempsenminutes FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $minutesActivite = $stmt->fetch();

    $stmt = $dbh->query('SELECT agemin FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $ageMinimumActivite = $stmt->fetch();

    $stmt = $dbh->query('SELECT agemin FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $ageMinimumParc = $stmt->fetch();

    $stmt = $dbh->query('SELECT nbattractions FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $nbAttraction = $stmt->fetch();

    $stmt = $dbh->query('SELECT nomgamme FROM pact.vue_restaurant WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $gammeRestaurant = $stmt->fetch();

    $stmt = $dbh->query('SELECT tempsenminutes FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $minutesSpectacle = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $offre = $stmt->fetch();

    $stmt = $dbh->query('SELECT codepostal, ville, nomrue, numrue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC);
    $adresse = $stmt->fetch();

    $stmt = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $tags = $stmt->fetchAll();

    $stmt = $dbh->query('SELECT nomimage FROM pact._image WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC);
    $images = $stmt->fetchAll();


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
    <div class=titre>
        <?php Label::render("titre-offre", "", "", $offre['titre']); ?>
    </div>
    <div class="container">
        <div>
            <div class="carousel">
                <div class="carousel-images">
                    <?php 
                        foreach ($images as $imageArray): ?>
                            <img src="../../../ressources/<?php echo $idOffre; ?>/images/<?php echo $imageArray['nomimage']; ?>"
                                 class="carousel-image">
                        <?php endforeach; ?>
                </div>
                <button class="carousel-button prev">❮</button>
                <button class="carousel-button next">❯</button>
            </div>
            <?php if ($typeOffre !== 'restaurant'): ?>
                <?php Label::render("offre-prix", "", "", "Prix: " . $offre['valprix'] . "€"); ?>
            <?php endif; ?>
        </div>

        <div class="offre-infos">
            <?php
            Label::render("offre-description", "", "",$offre['description'], "../../../ressources/icone/$typeOffre.svg");
            Label::render("offre-detail", "", "", $offre['descriptiondetaillee']);
            ?>
            <div class="address">
                <?php
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['numrue'] . ' ' . $adresse['nomrue'];
                Label::render("offre-adresse", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            <?php
            Label::render("offre-website", "", "", "<a href='" . $offre['siteinternet'] . "' target='_blank'>" . $offre['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");

            $tagsString = '';
            foreach ($tags as $tag) {
                $tagsString .= $tag['nomtag'] . ', ';
            }
            $tagsString = rtrim($tagsString, ', ');
            if (!empty($tagsString)) {
                ?><br><?php
                Label::render("offre-tags", "", "", $tagsString, "../../../ressources/icone/tag.svg");
                ?><br><?php
            }
            Label::render("offre-option", "", "", "Informations complémentaires: ", "../../../ressources/icone/info.svg");
            ?>
            <ul>
                <?php
                switch ($typeOffre) {
                    case 'restaurant':
                        ?>
                        <li><?php
                        Label::render("", "", "", "Gamme Restaurant: " . $gammeRestaurant['nomgamme'], "../../../ressources/icone/gamme.svg");
                        ?></li><?php
                        break;
                    case 'spectacle':
                        ?>
                        <li><?php
                        Label::render("", "", "", "Durée: " . $minutesSpectacle['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Capacité: " . $capacite['capacite'], "../../../ressources/icone/timer.svg");
                        ?></li><?php
                        break;
                    case 'parc_attractions':
                        ?>
                        <li><?php
                        Label::render("", "", "", "Age minimum: " . $ageMinimumParc['agemin'], "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Nombre d'attractions: " . $nbAttraction['nbattractions'], "../../../ressources/icone/timer.svg");
                        ?></li><?php
                        break;
                    case 'activite':
                        ?>
                        <li><?php
                        Label::render("", "", "", "Age minimum: " . $ageMinimumActivite['agemin'], "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Durée: " . $minutesActivite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Prestation: " . $prestation['prestation'], "../../../ressources/icone/timer.svg");
                        ?></li><?php
                        break;
                    case 'visite':
                        ?>
                        <li><?php
                        Label::render("", "", "", "Durée: " . $minutesVisite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Guidée: " . ($guidee['estguidee'] ? 'Oui' : 'Non'), "../../../ressources/icone/timer.svg");
                        ?></li><?php
                        break;
                    default:
                        die("Aucune offre n\'a été trouvée");
                }
                ?>
            </ul>
        </div>

        <div class="offre-package-modification">
            <div class="forfait-info">
                <?php Label::render("offre-forfait", "", "", "Forfait: " . $offre['nomforfait'], "../../../ressources/icone/argent.svg"); ?>
            </div>
            
            <form action="../modifierOffre/modifierOffre.html" method="POST">
                <input type="hidden" name="idOffre" value=<?php echo $idOffre;?>>
                <div class="modifier-button">
                    <?php Button::render("btn", "", "Modifier", ButtonType::Pro, "", true); ?>
                </div>
            </form>
        </div>

    </div>

    <script src="detailsOffre.js"></script>
</body>
<?php Footer::render(FooterType::Pro); ?>

</html>
