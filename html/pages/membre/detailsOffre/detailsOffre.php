<?php
error_reporting(E_ALL ^ E_WARNING);

// Inclusion des fichiers nécessaires pour les composants de l'interface
use \composants\Button\Button;
use \composants\Button\ButtonType;
use \composants\Input\Input;
use \composants\InsererImage\InsererImage;
use \composants\Label\Label;
use \composants\Select\Select;
use \composants\Textarea\Textarea;
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/InsererImage/InsererImage.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Textarea/Textarea.php";

session_start();
$idCompte = $_SESSION['idCompte'];

// Récupération de l'identifiant de l'offre
$idOffre = $_GET['id'];
$offresRecentesTxt = $_COOKIE["offresRecentes"] ?? serialize([]);
$offresRecentesArray = unserialize($offresRecentesTxt);

if (!array_key_exists($idOffre, $offresRecentesArray)) {
    $offresRecentesArray[$idOffre] = time();
    setcookie("offresRecentes", serialize(array_unique($offresRecentesArray)), time() + 60 * 60 * 24 * 15, "/");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getAvis') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';
    $idAvis = intval($_POST['idAvis']);
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $stmt = $dbh->prepare("SELECT titre, commentaire, note, contextevisite FROM pact._avis WHERE idavis = :idAvis");
    $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();
    $avis = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($avis);
    exit;
}

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    
    // Ajout des filtres pour trier les avis
    $sortBy = $_GET['sortBy']?? 'date_desc';
    $filterBy = $_GET['filterBy']?? 'all';

    $query = "SELECT titre, note, commentaire, CASE WHEN pseudo IS NULL THEN '<em><i>Utilisateur Supprimé</i></em>' ELSE pseudo END AS pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis,poucehaut,poucebas, pact.vue_compte_membre.idcompte, pact._avis.estblacklist FROM pact._avis JOIN pact.vue_compte_membre ON pact._avis.idCompte = pact.vue_compte_membre.idCompte WHERE idOffre = $idOffre";


    if ($sortBy === 'date_asc') {
        $query .= " ORDER BY datevisite ASC";
    } elseif ($sortBy === 'date_desc') {
        $query .= " ORDER BY datevisite DESC";
    } elseif ($sortBy === 'note_asc') {
        $query .= " ORDER BY note ASC";
    } elseif ($sortBy === 'note_desc') {
        $query .= " ORDER BY note DESC";
    }

    $avis = $dbh->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcul de la moyenne des notes
    $totalNotes = 0;
    $nombreAvis = count($avis);
    foreach ($avis as $avi) {
        $totalNotes += $avi['note'];
    }
    $moyenneNotes = $nombreAvis > 0 ? $totalNotes / $nombreAvis : 0;

    $imagesAvis = [];
    foreach ($avis as $avi) {
        $img = [];
        foreach ($dbh->query("select nomimage from pact.vue_image_avis WHERE idavis = {$avi['idavis']}")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $img[] = $item["nomimage"];
        }
        $imagesAvis[$avi['idavis']] = $img;
    }
    $offre = $dbh->query('SELECT * FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $typeOffre = $dbh->query('SELECT type_offre FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();

    $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre['type_offre'])));
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }

    // Requêtes pour obtenir les informations spécifiques à l'offre
    $stmt = $dbh->query('SELECT estguidee FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $guidee = $stmt->fetch();

    if($guidee['estguidee'] == true){
        $langueGuidee = $dbh->query('SELECT nomlangage FROM pact.vue_visite_guidee WHERE idoffre = ' .$idOffre, PDO::FETCH_ASSOC)->fetchAll();
    }

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
    $adresse = $dbh->query('SELECT codepostal, ville, rue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();
    $images = $dbh->query('SELECT pact._representeoffre.idImage, pact._image.nomImage FROM pact._representeoffre JOIN pact._image ON pact._representeoffre.idImage = pact._image.idImage WHERE pact._representeoffre.idOffre = ' . "'$idOffre'", PDO::FETCH_ASSOC)->fetchAll();    $carte = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._parcAttractions JOIN pact._image ON pact._parcAttractions.carteParc = pact._image.idImage WHERE pact._parcAttractions.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $menu = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._restaurant JOIN pact._image ON pact._restaurant.menuRestaurant = pact._image.idImage WHERE pact._restaurant.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $horaires = substr($offre['heureouverture'], 0, 5) . " - " . substr($offre['heurefermeture'], 0, 5);
    $typeOption = $dbh->query('SELECT nomoption FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    // Vérification de l'existence de l'offre
    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }

    // Vérification du nombre de réponses de l'utilisateur pour cette offre
    $stmt = $dbh->prepare("SELECT COUNT(*) as totalReponses FROM pact._reponseavis WHERE idCompte = :idCompte AND idAvis IN (SELECT idAvis FROM pact._avis WHERE idOffre = :idOffre)");
    $stmt->execute([':idCompte' => $idCompte, ':idOffre' => $idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalReponses = $result ? $result['totalReponses'] : 5;
    
    // Ajout des compteurs de pouces
    $thumbsUpMap = [];
    $thumbsDownMap = [];
    
    foreach ($avis as $avi) {
        $thumbsUpMap[$avi['idavis']] = $avi['poucehaut'];
        $thumbsDownMap[$avi['idavis']] = $avi['poucebas'];
    }

    // Vérification si l'utilisateur a déjà publié un avis sur cette offre
    $stmt = $dbh->prepare("SELECT COUNT(*) as totalAvis FROM pact._avis WHERE idCompte = :idCompte AND idOffre = :idOffre");
    $stmt->execute([':idCompte' => $idCompte, ':idOffre' => $idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $dejaPublieAvis = $result['totalavis'] > 0;
    

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Déconnexion de la base de données
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre - PACT</title>
    <link rel="stylesheet" href="detailsOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="logo PACT">
</head>
<?php Header::render(HeaderType::Member);?>
<button class="retour" title="Revenir à la page des offres"><a href="../listeOffres/listeOffres.php"><img
            src="../../../ressources/icone/arrow_left.svg"></a></button>

<body>
    <input type="text" id="idOffre" value="<?= $idOffre ?>" hidden>
    <?php $typeOption["nomoption"] = str_replace(" ","",$typeOption["nomoption"])?>
    <div id="toast" class="toast">Avis bien signalé</div>
    <div class="titre-page">
        <?php Label::render(class:"titre-svg",id: "", for:"",text: "", icon:"../../../ressources/icone/{$typeOffre}.svg",title:"icon type Offre"); ?>
        <?php Label::render("titre-offre", "", "", $offre['titre']); ?>
        <img class="etoile-une-svg" id="<?php echo $typeOption['nomoption']; ?>" src="../../../ressources/img/relief.png">
    </div>
    <section class="container">
        <article class="container-gauche">
            <div class="carousel">

                <button class="carousel-button prev desactive" title="bouton carousel précédent">❮</button>
                <button class="carousel-button next desactive" title="bouton carousel suivant">❯</button>
                <div class="carousel-images">
                    <?php
                    // Affichage des images de l'offre
                    foreach ($images as $imageArray):
                        $path_img = "../../../ressources/" . $idOffre . "/images/" . $imageArray['nomimage'];
                        if (!file_exists($path_img)): ?>
                            <div class="carousel-image pas-images"><svg xmlns="http://www.w3.org/2000/svg" height="10em"
                            viewBox="0 -960 960 960" width="10em" fill="#000000">
                            <path
                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                        </svg></div>
                        
                        <?php else: ?>
                            
                            <img src="../../../ressources/<?php echo $idOffre; ?>/images/<?php echo $imageArray['nomimage']; ?>"
                            class="carousel-image" alt="imgOffre">
                            <?php endif ?>
                            <?php endforeach; ?>
                </div>
                <div class="carousel-dots"></div>
            </div>

            <div class="offre-prix">
                <?php Label::render("horaire", "", "", $horaires, "../../../ressources/icone/horloge.svg","icon pour horaire"); ?>
                <?php if ($typeOffre !== 'restaurant'){ ?>
                    <?php Label::render("", "", "", "Prix: " . $offre['valprix'] . "€"); ?>
                <?php }else{; ?>
                <?php Label::render("", "", "", "Prix: " . $offre['nomgamme']); ?>
                <?php }; ?>
                <div class="note-moyenne">
                    <?php Label::render("moyenne-notes", "", "", " " . number_format($moyenneNotes, 1)); ?>
                    <div class="note-m">
                        <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_pleine.svg");?>
                    </div>
                </div>
            </div>
            </article>
            <article class="offre-infos <?php echo $typeOption["nomoption"]?>">

            <?php
            // Affichage des détails de l'offre
            Label::render("offre-description", "", "", $offre['description'], "../../../ressources/icone/".$typeOffre.".svg","icone de type de l'offre");
            Label::render("offre-detail", "offre-detail", "", $offre['descriptiondetaillee']);
            ?>
            <div class="address">
                <?php
                // Construction de l'adresse complète
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['rue'];
                Label::render("offre-adresse", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg","icon adresse offre");
                ?>
            </div>
            <?php Label::render("offre-option", "", "", "" . $offre['numtel'], "../../../ressources/icone/telephone.svg","icon num tel"); ?>
            <?php

            // Affichage du site internet de l'offre
            Label::render("offre-website", "", "", "<a href='" . $offre['siteinternet'] . "' target='_blank'>" . $offre['siteinternet'] . "</a>", "../../../ressources/icone/naviguer.svg","icon site internet");

            // Affichage des tags associés à l'offre
            $tagsString = '';
            foreach ($tags as $tag) {
                $tagsString .= $tag['nomtag'] . ', ';
            }
            $tagsString = rtrim($tagsString, ', ');
            if (!empty($tagsString)) {
                ?><br><?php
                Label::render("offre-tags", "", "", $tagsString, "../../../ressources/icone/tag.svg","icone des tags de l'offre");
                ?><br><?php
            }
            Label::render("offre-option", "", "", "Informations complémentaires: ", "../../../ressources/icone/info.svg","icone pour les info complémentaires");
            ?>
            <ul class="offre-details">
                <?php
                // Affichage des informations spécifiques en fonction du type d'offre
                switch ($typeOffre) {
                    case 'restaurant':
                        $string = $gammeRestaurant['nomgamme'];
                        $start = strpos($string, '(') + 1;
                        $end = strpos($string, ')');

                        $gamme = substr($string, $start, $end - $start);
                        Label::render("margin", "", "", "Gamme Restaurant: " . $gamme, "../../../ressources/icone/gamme.svg","icon game restaurant");
                        break;
                    case 'spectacle':
                        Label::render("margin", "", "", "Durée: " . $minutesSpectacle['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg","icone durée spectacle");
                        Label::render("margin", "", "", "Capacité: " . $capacite['capacite'] . ' personnes', "../../../ressources/icone/timer.svg","icone capacité de la salle pour spectacle");
                        break;
                    case 'parc_attractions':
                        Label::render("margin", "", "", "Age minimum: " . $ageMinimumParc['agemin'] . ' ans', "../../../ressources/icone/timer.svg","icone age mini pour parc");
                        Label::render("margin", "", "", "Nombre d'attractions: " . $nbAttraction['nbattractions'], "../../../ressources/icone/timer.svg","icone nombre attractions parc");
                        break;
                    case 'activite':
                        Label::render("margin", "", "", "Age minimum: " . $ageMinimumActivite['agemin'] . ' ans', "../../../ressources/icone/timer.svg","icone age mini Activité");
                        Label::render("margin", "", "", "Durée: " . $minutesActivite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg","icone durée activité");
                        Label::render("margin", "", "", "Prestation: " . $prestation['prestation'], "../../../ressources/icone/timer.svg","icone prestation Activité");
                        break;
                    case 'visite':
                        Label::render("margin", "", "", "Durée: " . $minutesVisite['tempsenminutes'] . 'min', "../../../ressources/icone/timer.svg","icone durée visite");
                        Label::render("margin", "", "", "Guidée: " . ($guidee['estguidee'] ? 'Oui' : 'Non'), "../../../ressources/icone/timer.svg","icone si viste guidée ou non");
                        echo "<br>";
                        if($guidee['estguidee'] == 'Oui'){
                            Label::render("margin", "", "", "Langue : ", "");
                            for ($i=0; $i < count($langueGuidee)  ; $i++) { 
                                if( $i < count($langueGuidee)-1){
                                    Label::render("","","",$langueGuidee[$i]["nomlangage"].",");
                                }else{
                                    Label::render("","","",$langueGuidee[$i]["nomlangage"]);
                                }                     
                            }
                        }
                        break;
                    default:
                        die("Aucune offre n\'a été trouvée");
                }
                ?>
            </ul>
            
           
        </article>
        </section>

        
        <div class="offre-package-modification">
            

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


    <section>
        <div class="liste-avis">
            <div class="avis-header">
                <h1>Avis</h1>
                <?php Button::render(id: "aviscreate",class:"btn-creer-avis" ,text: "Créer un avis",title: "bouton pour créer un avis", type:"Member") ?>
            </div>
            <?php
            if ($nombreAvis > 0){
                ?>

            
            <aside class="filters">
                <label for="sortBy">Trier par:</label>
                <select id="sortBy">
                    <option value="date_desc" selected>Date décroissante</option>
                    <option value="date_asc">Date croissante</option>
                    <option value="note_desc">Note décroissante</option>
                    <option value="note_asc">Note croissante</option>
                </select>
            </aside>

            <article class="container-avis">
                <?php
                foreach ($avis as $avi) {
                    if (!isset($avi["idavis"])) {
                        continue;
                    }
                    if ($avi["estblacklist"] && $avi['idcompte'] == $idCompte || !$avi["estblacklist"]){
  
                    $stmt = $dbh->prepare("SELECT idreponse, commentaire, denominationsociale, to_char(datereponse,'DD/MM/YY') as datereponse FROM pact.vue_reponse WHERE idAvis = :idAvis");
                    $stmt->execute([':idAvis' => $avi['idavis']]);  
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="avi" data-idavis="<?= $avi["idavis"] ?>">
                    <div class="container-head-avis">
                        <div>
                            <p class="avi-title">
                                <?= $avi["titre"] ?>
                            </p>
                            <p>
                                en <?= $avi["contextevisite"] ?>
                            </p>
                        </div>
                        
                        <div class="note" title="icone étoiles">
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

                        <div class="container-img-avis">
                            <?php
                            foreach ($imagesAvis[$avi["idavis"]] as $image) {
                                echo "<img src='/ressources/avis/{$avi["idavis"]}/$image' width='64' height='64'>";
                                
                            }
                            ?>
                        </div>
                        <div class="container-bottom-avis">
                            <div class="container-infos-avis">
                                <p>
                                    <?php 
                                      if ($avi["idcompte"] == $idCompte) {
                                          echo $avi["pseudo"]." (vous)"; 
                                      }
                                      else {
                                          echo $avi["pseudo"];
                                    }?>
                                </p>
                                <p>
                                    le <?= $avi["datevisite"] ?>
                                </p>
                            </div>
                            <div class="thumbs">
                                <button class="thumbs-up" title="like" data-idavis="<?= $avi["idavis"] ?>">👍 <?= $thumbsUpMap[$avi["idavis"]] ?? 0 ?></button>
                                <button class="thumbs-down" title="dislike" data-idavis="<?= $avi["idavis"] ?>">👎 <?= $thumbsDownMap[$avi["idavis"]] ?? 0 ?></button>
                            </div>
                        </div>   
                        <div class="option-user">
                        <?php if($avi['idcompte'] == $idCompte){
                            if($avi["estblacklist"]){ ?>
                                <h3><em>Blacklisté</em></h3>
                            <?php } else{ ?>
                                <button class="btn-modifier" title="Modifier un avis" data-idavis="<?= $avi["idavis"] ?>">Modifier</button>
                            <?php } ?>
                            <button class="btn-supprimer" title="Supprimer un avis" data-idavis="<?= $avi["idavis"] ?>">Supprimer</button>
                        <?php } else{
                                Button::render("btn-signaler", "btn-signaler","bouton signaler", "Signaler", ButtonType::Member, "", false);
                            } ?>                     
                        </div>
                        <?php if (!empty($reponses)): ?>
                            <div class="reponses">
                                <?php foreach ($reponses as $reponse): ?>
                                    <div class="reponse">
                                        <p class="avis-title">Réponse:</p>
                                        <p class="avi-content">
                                            <?= $reponse["commentaire"] ?>
                                        </p>
                                        <div class="container-bottom-avis">
                                            <div class="container-infos-avis">
                                            <p>
                                                <?= $reponse["denominationsociale"] ?>
                                            </p>
                                            <p>
                                                le <?= $reponse["datereponse"] ?>
                                            </p>
                                            <?php if($avi["estblacklist"] == false){
                                                Button::render("btn-signaler", "btn-signaler","bouton signaler", "Signaler", ButtonType::Member, "", false);
                                            } ?>
                                            </div>
                                </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }
                ?>
            </article>
            <?php
            } else  {
                echo "<p>Aucun avis n'a été trouvé pour cette offre.</p>";
            }
            ?>
        </div>

        <div class="popup" id="popup-repondre">
            <div class="popup-content">
                <span class="close">&times;</span>
                <form action="envoyerReponse.php" method="POST">
                    <input type="hidden" name="idAvis" id="popup-idAvis">
                    <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                    <textarea name="reponse" placeholder="Votre réponse..." required></textarea>
                    <button type="submit" title="bouton Envoyer">Envoyer</button>
                </form>
            </div>
        </div>

        <div class="popup" id="popup-creer-avis">
    <div class="popup-content">
        <div class="popup-header">
            <h2 id="popup-title">Créer un nouvel avis</h2>
            <span class="close">&times;</span>
        </div>
        
        <form action="creerAvis.php" method="POST" enctype="multipart/form-data" class="avis-form">
            <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
            <input type="hidden" name="idAvis" id="idAvis" value="<?= $idAvis ?>">
            
            <!-- Titre de l'avis -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("titre", "", "", "Titre de votre avis"); ?>
                </div>
                <?php Input::render(
                    "titre",
                    "Un titre qui résume votre expérience",
                    "text",
                    "titre"
                    
                ); ?>
            </div>

            
            <!-- Commentaire détaillé -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-commentaire", "", "", "Votre avis détaillé"); ?>
                </div>
                <?php Textarea::render(
                    class: "form-control commentaire",
                    id: "commentaire",
                    name: "commentaire",
                    placeholder: "Partagez votre expérience en détail...",
                    required: true
                ); ?>
            </div>

            <!-- Évaluation par étoiles -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-note", "", "", "Votre note"); ?>
                </div>
                <div class="rating" role="radiogroup" aria-label="Note sur 5 étoiles">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" 
                               name="note" 
                               id="star<?= $i ?>" 
                               value="<?= $i ?>"
                               aria-label="<?= $i ?> étoiles"
                               required>
                        <label for="star<?= $i ?>" title="<?= $i ?> étoiles">☆</label>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Contexte de la visite -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-contexte", "", "", "Contexte de votre visite"); ?>
                </div>
                <?php
                $optionsContexte = [
                    '' => 'Sélectionnez un contexte',
                    'Affaires' => 'Voyage d\'affaires',
                    'Couple' => 'En couple',
                    'Famille' => 'En famille',
                    'Amis' => 'Entre amis',
                    'Solo' => 'Voyage solo'
                ];
                Select::render(
                    'contexteVisite',
                    '',
                    'contexteVisite',
                    true,
                    $optionsContexte,
                    'form-control'
                );
                ?>
            </div>

            <!-- Upload de photos -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-photos", "", "", "Ajoutez des photos"); ?>
                </div>
                <div class="upload-section">
                    <?php InsererImage::render(
                        "drop-zone[]",
                        "Déposez vos photos ici ou cliquez pour sélectionner",
                        5,  // Limite à 5 photos

                    ) ?>
                    <div class="upload-preview" id="imagePreview"></div>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="form-group">
                <?php Button::render(
                    "btn-envoyer",
                    "submit-avis",
                    "bouton pour publier un avis",
                    "Publier votre avis",
                    ButtonType::Member,
                    "",
                    true
                ); ?>
            </div>
        </form>
    </div>
</div>

    </section>

    <!-- Popup pour afficher l'image en grand -->
    <div class="image-popup" id="image-popup">
        <span class="close">&times;</span>
        <img class="image-popup-content" id="image-popup-content">
    </div>

    <div class="popup" id="popup-deja-avis">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>Vous avez déjà posté un avis sur cette offre.</p>
        </div>
    </div>

    <script>
        const dejaPublieAvis = <?= json_encode($dejaPublieAvis) ?>;
    </script>
     <script src="detailsOffre.js"></script>
     <script>
        document.addEventListener("DOMContentLoaded", function () {
            const avisElements = document.querySelectorAll(".avi.non-vu");

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const idAvis = entry.target.dataset.idavis;
                        fetch(`markAsSeen.php?idAvis=${idAvis}`, {
                            method: 'POST'
                        }).then(response => {

                        });
                    }
                });
            });

            avisElements.forEach(avi => {
                observer.observe(avi);
            });

            // Script pour afficher l'image en grand
            const imagePopup = document.getElementById("image-popup");
            const imagePopupContent = document.getElementById("image-popup-content");
            const closeImagePopup = document.querySelector(".image-popup .close");

            document.querySelectorAll(".avi img").forEach(img => {
                img.addEventListener("click", function () {
                    imagePopupContent.src = this.src;
                    imagePopup.style.display = "block";
                });
            });

            closeImagePopup.addEventListener("click", function () {
                imagePopup.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === imagePopup) {
                    imagePopup.style.display = "none";
                }
            });
        });
    </script>
    <?php Footer::render(FooterType::Member); 
    $dbh = null;
    ?>
</body>

</html>
