<?php
// Inclusion des fichiers nécessaires pour les composants de l'interface
session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "pro") {
    header("Location: /pages/pro/listeOffres/listeOffres.php");
} elseif (!isset($_SESSION['idCompte'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
}
use \composants\Button\Button;
use \composants\Button\ButtonType;
use composants\Input\Input;
use composants\InsererImage\InsererImage;
use \composants\Label\Label;
use composants\Select\Select;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/InsererImage/InsererImage.php";

// Récupération de l'identifiant de l'offre
$idOffre = $_GET['id'] ?? '1';
try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $avis = $dbh->query("select titre, note, commentaire, pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis  from pact.vue_avis join pact.vue_compte_membre ON pact.vue_avis.idCompte = pact.vue_compte_membre.idCompte where idOffre = $idOffre")->fetchAll(PDO::FETCH_ASSOC);
    $imagesAvis = [];
    foreach ($avis as $avi) {
        $img = [];
        foreach ($dbh->query("select nomimage from pact.vue_image_avis WHERE idavis = {$avi['idavis']}")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $img[] = $item["nomimage"];
        }
        $imagesAvis[$avi['idavis']] = $img;
    }
    $peutEcrireAvis = $dbh->query("SELECT count(*) FROM pact.vue_avis WHERE idCompte = 1 AND idOffre = 3")->fetchAll(PDO::FETCH_ASSOC)[0]['count'] == 0;


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
    $offre = $dbh->query('SELECT * FROM pact.vue_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $adresse = $dbh->query('SELECT codepostal, ville, rue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();
    $images = $dbh->query('SELECT _image.idImage, _image.nomImage FROM pact._image JOIN pact._offre ON _image.idOffre = _offre.idOffre WHERE _offre.idOffre = ' . $idOffre . ' AND _image.idImage NOT IN (SELECT _parcAttractions.carteParc FROM pact._parcAttractions WHERE idOffre = _offre.idOffre) AND _image.idImage NOT IN (SELECT _restaurant.menuRestaurant FROM pact._restaurant WHERE idOffre = _offre.idOffre)', PDO::FETCH_ASSOC)->fetchAll();
    $carte = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._parcAttractions JOIN pact._image ON pact._parcAttractions.carteParc = pact._image.idImage WHERE pact._parcAttractions.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $menu = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._restaurant JOIN pact._image ON pact._restaurant.menuRestaurant = pact._image.idImage WHERE pact._restaurant.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
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
<body>
<?php Header::render(HeaderType::Member); ?>
<main>
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
                            <div class="carousel-image pas-images">
                                <svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960"
                                     width="10em" fill="#000000">
                                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/>
                                </svg>
                            </div>

                        <?php else: ?>
                            <img src="../../../ressources/<?php echo $idOffre; ?>/images/<?php echo $imageArray['nomimage']; ?>"
                                 class="carousel-image">
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
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['rue'];
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
                <?php Label::render("offre-option", "", "", "Option: " . $offre['nomoption'], "../../../ressources/icone/oeil.svg"); ?>
            </div>

            <?php
            /*
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
            */ ?>


        </div>

        <div class="popup-overlay" id="popupOverlay">

            <div class="popup" id="popup">

                <span class="close" id="closePopup">&times;</span>

                <div class="popup-content">
                    <?php
                    if ($peutEcrireAvis) {
                    ?>
                    <p>Mettez un avis!</p>
                    <form method="post" enctype="multipart/form-data" action="/pages/membre/avis/creerAvis.php" onsubmit='return submitForm()'>
                        <div>
                            <?php  Input::render(id: "titre", name: "titre", required: true, maxLength: 50, placeholder: "Titre") ?>
                            <div>
                                <?php Select::render(id: "contexte", name: "contexte", required: true, options: ["Contexte de la visite", "Affaires", "Couple", "Famille", "Amis", "Solo"], selected: "Contexte de la visite") ?>
                            </div>
                            <input name="note" required="required" id="note" type="number" min="0" max="5" step="0.5" placeholder="Note" pattern="\d((\.|,)\d)?">

                        </div>
                        <label for="datevisite">date de la visite :
                            <?php Input::render(id: "datevisite", type: "date", name: "datevisite", required: true, placeholder: "date de la visite") ?>
                        </label>
                        <input type="hidden" name="idoffre" value="<?=$idOffre?>">
                        <textarea name="contenu" id="contenu" maxlength="255" required="required" spellcheck="true" placeholder="Explication de votre note et précision suplémentaire" rows="4" cols="75"></textarea>
                        <div>
                        <?php InsererImage::render(id: "dropzone[]", message: "Déposez une image ou cliquez ici (facultatif)", acceptedExtensions: ['png', 'jpg', 'jpeg', 'HEIC']); ?>
                        <?php Button::render(class: "bg-membre", text: "Valider", submit: true) ?>
                        </div>
                    </form>
                    <?php
                    } else {
                        ?>
                        <div style="display: flex; flex-direction: column; justify-content: center; height: 100%">
                            <p>Vous ne pouvez mettre qu'un seul avis par offre, veuillez supprimmer votre avis pour en
                                mettre un autre (Attention, il n'y a pas de retour en arrière!)</p>
                            <form style="height: auto" method="post" action="/pages/membre/avis/supprimerAvis.php">
                                <input type="hidden" name="idoffre" value="<?=$idOffre?>">
                                <?php Button::render(class: "bg-membre", id: 'btn-suppr', text: file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/delete.svg") . "Supprimer", submit: true) ?>
                            </form>
                        </div>
                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>
    </div>
    <div class="liste-avis">
        <div>
            <h1>Avis:</h1>
            <?php Button::render(text: "Écrire un avis", type: ButtonType::Member, onClick: "popupavis()") ?>
        </div>
        <div>
            <?php
            foreach ($avis as $avi) {
                ?>
                <div class="avi">
                    <div>
                        <p class="avi-title">
                            <?= $avi["titre"] ?>
                        </p>
                        <div class="note">
                            <?php
                            for ($i = 0; $i < floor($avi["note"]); $i++) {
                                echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_pleine.svg");
                            }
                            if (fmod($avi["note"], 1) != 0) {
                                echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_mid.svg");
                            }
                            for ($i = 0; $i <= 4 - $avi["note"]; $i++) {
                                echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_vide.svg");
                            }
                            ?>
                        </div>
                    </div>
                    <p class="avi-content">
                        <?= $avi["commentaire"] ?>
                    </p>
                    <div>
                        <?php
                        foreach ($imagesAvis[$avi["idavis"]] as $image) {
                            echo "<img src='/ressources/avis/{$avi["idavis"]}/$image' width='64' height='64' onclick=\"openUp(event)\">";
                        }
                        ?>
                    </div>
                    <div>
                        <p>
                            <?=$avi["pseudo"]?>
                        </p>
                        <p>
                            le <?=$avi["datevisite"]?>
                        </p>
                        <p>
                            en <?=$avi["contextevisite"]?>
                        </p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="" id="modal-image" />
        </div>
    </div>
    <script src="detailsOffre.js"></script>
</main>
<?php Footer::render(FooterType::Member); ?>
</body>

</html>