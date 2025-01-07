<?php
// Inclusion des fichiers nécessaires pour les composants de l'interface
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
    $offresSql = $dbh->query(<<<STRING
select distinct titre                                                                       AS nom,
       nomcategorie                                                                AS type,
       vue_offres.ville,
       nomimage as idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
STRING
    );

    $offresSql = $dbh->query(<<<STRING
select distinct titre                                                                       AS nom,
       nomcategorie                                                                AS type,
       vue_offres.ville,
       nomimage as idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
STRING
    );

    $offre = $dbh->query('SELECT * FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $typeOffre = $dbh->query('SELECT type_offre FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();

    $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre['type_offre'])));
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }

    $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();

    $images = $dbh->query('SELECT _image.idImage,_image.nomImage FROM pact._image JOIN pact._offre ON _image.idOffre = _offre.idOffre WHERE _offre.idOffre = ' . $idOffre . ' AND _image.idImage NOT IN (SELECT  _parcAttractions.carteParc FROM pact._parcAttractions WHERE idOffre = _offre.idOffre AND _parcAttractions.carteParc IS NOT NULL)AND _image.idImage NOT IN (SELECT _restaurant.menuRestaurant FROM pact._restaurant WHERE idOffre = _offre.idOffre AND _restaurant.menuRestaurant IS NOT NULL)', PDO::FETCH_ASSOC)->fetchAll();

    $entreprise = $dbh->query(
        'SELECT DISTINCT pact.vue_compte_pro.denominationsociale, pact.vue_compte_pro.raisonsocialepro, pact.vue_compte_pro.email, pact.vue_compte_pro.numtel FROM pact.vue_offres join pact.vue_compte_pro ON pact.vue_compte_pro.idcompte = pact.vue_offres.idcompte WHERE idoffre = ' . $idOffre,
        PDO::FETCH_ASSOC
    )->fetch();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
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
<?php Header::render(HeaderType::Guest); ?>
<div class=titre-pc>
        <?php Label::render("titre-offre", "", "", $offre['titre']); ?>
    </div><main>
    <div class="container">
        <div class="container-gauche">
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
                                <svg xmlns="http://www.w3.org/2000/svg" height="10em"
                                        viewBox="0 -960 960 960"
                                     width="10em" fill="#000000">
                                    <path
                                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/>
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
        </div><?php Label::render("titre-tel", "", "", $offre['titre'],"../../../ressources/icone/$typeOffre.svg"); ?>

        <div class="offre-infos">
            <?php
            // Affichage des détails de l'offre
            Label::render("offre-description", "", "", $offre['description'], "../../../ressources/icone/$typeOffre.svg");
            Label::render("offre-detail", "", "", $offre['descriptiondetaillee']);
            ?>
            <div class="address">
                <?php
                // Construction de l'adresse complète
                $adresseTotale = $offre['codepostal'] . ' ' . $offre['ville'] . ', ' . $offre['rue'];
                Label::render("offre-adresse", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg");
                ?>
            </div><?php
            // Affichage de l'horaire
                $horaires = substr($offre['heureouverture'], 0, 5) . " - " . substr($offre['heurefermeture'], 0, 5);
                Label::render("", "", "", $horaires, "../../../ressources/icone/horloge.svg");
            // Affichage du site internet de l'offre
            Label::render("offre-website", "", "", "<a href='" . $offre['siteinternet'] . "' target='_blank'>" . $offre['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg");

                // Affichage des tags associés à l'offre
                $tagsString = '';
                foreach ($tags as $tag) {
                    $tagsString .= $tag['nomtag'] . ', ';
                }
                $tagsString = rtrim($tagsString, ', ');
                if (!empty($tagsString)) {

                    Label::render("offre-tags", "", "", $tagsString, "../../../ressources/icone/tag.svg");

                }
                Label::render("offre-option", "", "", "Informations complémentaires: ", "../../../ressources/icone/info.svg");
                ?>
                <ul>
                    <?php
                    // Affichage des informations spécifiques en fonction du type d'offre
                    switch ($typeOffre) {
                        case 'restaurant':
                            $string = $gammeRestaurant['nomgamme'];
    
                            $start = strpos($string, '(') + 1;
                            $end = strpos($string, ')');
    
                            $gamme = substr($string, $start, $end - $start);
                            Label::render("", "", "", "Gamme Restaurant: " . $gamme, "../../../ressources/icone/gamme.svg");
                            break;
                        case 'spectacle':
                            Label::render("", "", "", "Durée: " . $offre['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                            Label::render("", "", "", "Capacité: " . $offre['capacite'] . ' personnes', "../../../ressources/icone/timer.svg");
                            break;
                        case 'parc_attractions':
                            Label::render("", "", "", "Age minimum: " . $offre['agemin'] . ' ans', "../../../ressources/icone/timer.svg");
                            Label::render("", "", "", "Nombre d'attractions: " . $offre['nbattractions'], "../../../ressources/icone/timer.svg");
                            break;
                        case 'activite':
                            Label::render("", "", "", "Age minimum: " . $offre['agemin'] . ' ans', "../../../ressources/icone/timer.svg");
                            Label::render("", "", "", "Durée: " . $offre['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                            Label::render("", "", "", "Prestation: " . $offre['prestation'], "../../../ressources/icone/timer.svg");
                            break;
                        case 'visite':
                            Label::render("", "", "", "Durée: " . $offre['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg");
                            Label::render("", "", "", "Guidée: " . ($offre['estguidee'] ? 'Oui' : 'Non'), "../../../ressources/icone/timer.svg");

                            break;
                        default:
                            die("Aucune offre n\'a été trouvée");
                    }
                    ?>
                </ul>
            </div>
        </div>



        <div class="offre-detail-tel">
            <p> A propos:</p>
            <?php  Label::render("description-tel", "", "", $offre['descriptiondetaillee']);  ?>
        </div>

        <aside class="contact">
                <?php
                Label::render("denomination", "", "", $entreprise['denominationsociale']);
                Label::render("denomination", "", "", $entreprise['raisonsocialepro']);
                Label::render("tel", "", "", $entreprise['numtel'], "../../../ressources/icone/telephone.svg");
                Label::render("email", "", "", $entreprise['email'], "../../../ressources/icone/mail.svg");
                Button::render("btn-mail", "", " Envoyer un mail", ButtonType::Guest, "", false, "mailto:" . $entreprise['email']);
            ?>
        </aside>
</main>
<div>
    <div class="liste-avis">
        <div>
            <h1>Avis:</h1>
            <?php Button::render(text: "Écrire un avis", type: ButtonType::Guest, onClick: "window.location.href = '/pages/membre/connexionCompteMembre/connexionCompteMembre.php?context=detailsOffre/detailsOffre.php%3Fid=$idOffre'") ?>
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
    </div></div>
    <script src="detailsOffre.js"></script>
    <?php Footer::render(FooterType::Guest); ?>

</html>