<?php
// Inclusion des fichiers nécessaires pour les composants de l'interface
use \composants\Button\Button;
use \composants\Button\ButtonType;
use \composants\Label\Label;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";

// Récupération de l'identifiant de l'offre
$idOffre = isset($_POST['idOffre']) ? $_POST['idOffre'] : '1';

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    // Requête pour obtenir le type d'offre
    $stmt = $dbh->prepare('SELECT nomcategorie FROM (SELECT nomcategorie FROM pact.vue_spectacle WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_restaurant WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_parc_attractions WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_activite WHERE idoffre = :idOffre UNION ALL SELECT nomcategorie FROM pact.vue_visite WHERE idoffre = :idOffre) AS categories');
    $stmt->execute([':idOffre' => $idOffre]);
    $typeOffre = $stmt->fetchColumn();

    // Normalisation du type d'offre
    $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre)));
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }

    // Requêtes pour obtenir les informations spécifiques à l'offre
    $stmt = $dbh->query('SELECT estguidee FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $guidee = $stmt->fetch();

    // Récupération des autres informations pertinentes
    $minutesVisite = $dbh->query('SELECT tempsenminutes FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $prestation = $dbh->query('SELECT prestation FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $capacite = $dbh->query('SELECT capacite FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $minutesActivite = $dbh->query('SELECT tempsenminutes FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $ageMinimumActivite = $dbh->query('SELECT agemin FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $ageMinimumParc = $dbh->query('SELECT agemin FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $nbAttraction = $dbh->query('SELECT nbattractions FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $gammeRestaurant = $dbh->query('SELECT nomgamme FROM pact.vue_restaurant WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $minutesSpectacle = $dbh->query('SELECT tempsenminutes FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $offre = $dbh->query('SELECT * FROM pact.vue_parc_attractions' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $adresse = $dbh->query('SELECT codepostal, ville, nomrue, numrue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    
    if(strcmp($typeOffre,"Parc d'attractions") != 0){
        $tags = $dbh->query('SELECT * FROM pact.vue_tags_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();
    }
    else{
        $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();
    }
    
    
    $images = $dbh->query('SELECT _image.idImage, _image.nomImage FROM pact._image JOIN pact._offre ON _image.idOffre = _offre.idOffre WHERE _offre.idOffre = ' . $idOffre . ' AND _image.idImage NOT IN (SELECT _parcAttractions.idImage FROM pact._parcAttractions WHERE idOffre = _offre.idOffre) AND _image.idImage NOT IN (SELECT _restaurant.idImage FROM pact._restaurant WHERE idOffre = _offre.idOffre)', PDO::FETCH_ASSOC)->fetchAll();
    $carte = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._parcAttractions JOIN pact._image ON pact._parcAttractions.idImage = pact._image.idImage WHERE pact._parcAttractions.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $menu = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._restaurant JOIN pact._image ON pact._restaurant.idImage = pact._image.idImage WHERE pact._restaurant.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    // Vérification de l'existence de l'offre
    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
} finally {
    // Déconnexion de la base de données
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

                <button class="carousel-button prev desactive">❮</button>
                <button class="carousel-button next">❯</button>
                <div class="carousel-images">
                    <?php
                    // Affichage des images de l'offre
                    foreach ($images as $imageArray):
                        $path_img = "../../../ressources/" . $idOffre . "/images/" . $imageArray['nomimage'];
                        if (!file_exists($path_img)): ?>
                            <div class="carousel-image pas-images"><svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960" width="10em" fill="#000000">
                                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                                </svg></div>

                        <?php else: ?>
                            <img src="../../../ressources/<?php echo $idOffre; ?>/images/<?php echo $imageArray['nomimage']; ?>" class="carousel-image">
                        <?php endif ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($typeOffre !== 'restaurant'): ?>
                <?php Label::render("offre-prix", "", "", "Prix: " . $offre['valprix'] . "€"); ?>
            <?php endif; ?>
        </div>

        <div class="offre-infos">
            <?php
            // Affichage des détails de l'offre
            Label::render("offre-description", "", "", $offre['description'], "../../../ressources/icone/$typeOffre.svg");
            Label::render("offre-detail", "", "", $offre['descriptiondetaillee']);
            ?>
            <div class="address">
                <?php
                // Construction de l'adresse complète
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['numrue'] . ' ' . $adresse['nomrue'];
                Label::render("offre-adresse", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            <?php
            // Affichage du site internet de l'offre
            Label::render("offre-website", "", "", "<a href='" . $offre['siteinternet'] . "' target='_blank'>" . $offre['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");

            // Affichage des tags associés à l'offre
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
                // Affichage des informations spécifiques en fonction du type d'offre
                switch ($typeOffre) {
                    case 'restaurant':
                        Label::render("", "", "", "Gamme Restaurant: " . $gammeRestaurant['nomgamme'], "../../../ressources/icone/gamme.svg");
                        break;
                    case 'spectacle':
                        Label::render("", "", "", "Durée: " . $minutesSpectacle['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Capacité: " . $capacite['capacite'] . ' personnes', "../../../ressources/icone/timer.svg");
                        break;
                    case 'parc_attractions':
                        Label::render("", "", "", "Age minimum: " . $ageMinimumParc['agemin'] . ' ans', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Nombre d'attractions: " . $nbAttraction['nbattractions'], "../../../ressources/icone/timer.svg");
                        break;
                    case 'activite':
                        Label::render("", "", "", "Age minimum: " . $ageMinimumActivite['agemin'] . ' ans', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Durée: " . $minutesActivite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Prestation: " . $prestation['prestation'], "../../../ressources/icone/timer.svg");
                        break;
                    case 'visite':
                        Label::render("", "", "", "Durée: " . $minutesVisite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                        Label::render("", "", "", "Guidée: " . ($guidee['estguidee'] ? 'Oui' : 'Non'), "../../../ressources/icone/timer.svg");
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
            <?php
            // Affichage des boutons pour télécharger des documents liés à l'offre
            switch ($typeOffre) {
                case 'restaurant': ?>
                    <div class="download-button">
                        <a href="../../../ressources/<?php echo $idOffre; ?>/menu/<?php echo $menu['nomimage']; ?>" download>
                            <?php Button::render("btn", "", "Télécharger menu", ButtonType::Pro, "", false); ?>
                        </a>
                    </div>
            <?php break;
            case 'parc_attractions': ?>
                <div class="download-button">
                    <a href="../../../ressources/<?php echo $idOffre; ?>/carte/<?php echo $carte['nomimage']; ?>" download>
                        <?php Button::render("btn", "", "Télécharger carte", ButtonType::Pro, "", false); ?>
                    </a>
                </div>
        <?php break;
                case 'default':
                    break;
            }
            ?>

            <!-- Formulaire pour modifier l'offre -->
            <form action="../modifierOffre/modifierOffre.php" method="POST">
                <input type="hidden" name="idOffre" value="<?php echo $idOffre; ?>">
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