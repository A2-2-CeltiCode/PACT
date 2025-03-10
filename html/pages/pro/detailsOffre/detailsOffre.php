<?php
error_reporting(E_ALL ^ E_WARNING);

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

session_start();
$idCompte = $_SESSION['idCompte'];

// Récupération de l'identifiant de l'offre
$idOffre = $_POST['idOffre'] ?? '1';
$idOffre = $_GET['idOffre'] ?? $idOffre;

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    
    // Ajout des filtres pour trier les avis
    $sortBy = $_GET['sortBy'] ?? 'date_desc';
    $filterBy = $_GET['filterBy'] ?? 'all';

    $query = "SELECT titre, note, commentaire, pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis,poucehaut,poucebas,estvu FROM pact._avis JOIN pact.vue_compte_membre ON pact._avis.idCompte = pact.vue_compte_membre.idCompte WHERE idOffre = $idOffre";

    if ($filterBy === 'viewed') {
        $query .= " AND estvu = true";
    } elseif ($filterBy === 'not_viewed') {
        $query .= " AND estvu = false";
    }

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
    // Vérification de l'existence de l'offre
    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }

    // Vérification du nombre de réponses de l'utilisateur pour cette offre
    $stmt = $dbh->prepare("SELECT COUNT(*) as totalReponses FROM pact._reponseavis WHERE idCompte = :idCompte AND idAvis IN (SELECT idAvis FROM pact.vue_avis WHERE idOffre = :idOffre)");
    $stmt->execute([':idCompte' => $idCompte, ':idOffre' => $idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalReponses = $result ? $result['totalReponses'] : 5;
    
    $thumbsUpMap = [];
    $thumbsDownMap = [];
    
    foreach ($avis as $avi) {
        $thumbsUpMap[$avi['idavis']] = $avi['poucehaut'];
        $thumbsDownMap[$avi['idavis']] = $avi['poucebas'];
    }
    
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
    <title>Détails de l'offre</title>
    <link rel="stylesheet" href="detailsOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>
<?php Header::render(HeaderType::Pro); ?>
<button class="retour" title="fleche gauche"><a href="../listeOffres/listeOffres.php"><img
            src="../../../ressources/icone/arrow_left.svg"></a></button>

<body>
    <div id="toast" class="toast">Avis bien signalé</div>
    <div class=titre>
        <?php Label::render("titre-svg", "", "", "", "../../../ressources/icone/{$typeOffre}.svg"); ?>
        <?php Label::render("titre-offre", "", "", $offre['titre']); ?>
    </div>
    <div class="container">
        <div class="container-gauche">
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
            </div>
            <?php if ($typeOffre !== 'restaurant'){ ?>
                <?php Label::render("offre-prix", "", "", "Prix: " . $offre['valprix'] . "€"); ?>
            <?php }else{; ?>
            <?php Label::render("offre-prix", "", "", "Prix: " . $offre['nomgamme'] . "€"); ?>
            <?php }; ?>
        </div>

        <div class="offre-infos">
            <?php
            // Affichage des détails de l'offre
            Label::render("offre-description", "", "", $offre['description'], "../../../ressources/icone/".$typeOffre.".svg");
            Label::render("offre-detail", "offre-detail", "", $offre['descriptiondetaillee']);
            ?>
            <div class="address">
                <?php
                // Construction de l'adresse complète
                $adresseTotale = $adresse['codepostal'] . ' ' . $adresse['ville'] . ', ' . $adresse['rue'];
                Label::render("offre-adresse", "", "", $adresseTotale, "../../../ressources/icone/localisateur.svg");
                ?>
            </div>
            <?php Label::render("offre-option", "", "", "" . $offre['numtel'], "../../../ressources/icone/telephone.svg"); ?>
            <?php

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
                        $string = $gammeRestaurant['nomgamme'];
                        $start = strpos($string, '(') + 1;
                        $end = strpos($string, ')');

                        $gamme = substr($string, $start, $end - $start);
                        Label::render("", "", "", "Gamme Restaurant: " . $gamme, "../../../ressources/icone/gamme.svg");
                        break;
                    case 'spectacle':
                        Label::render("", "", "", "Durée: " . $minutesSpectacle['tempsenminutes'] . 'min', "../../../ressources/icone/horloge.svg");
                        Label::render("", "", "", "Capacité: " . $capacite['capacite'] . ' personnes', "../../../ressources/icone/capacite.svg");
                        break;
                    case 'parc_attractions':
                        Label::render("", "", "", "Age minimum: " . $ageMinimumParc['agemin'] . ' ans', "../../../ressources/icone/age.svg");
                        Label::render("", "", "", "Nombre d'attractions: " . $nbAttraction['nbattractions'], "../../../ressources/icone/attractions.svg");
                        break;
                    case 'activite':
                        Label::render("", "", "", "Age minimum: " . $ageMinimumActivite['agemin'] . ' ans', "../../../ressources/icone/age.svg");
                        Label::render("", "", "", "Durée: " . $minutesActivite['tempsenminutes'] . 'min', "../../../ressources/icone/horloge.svg");
                        Label::render("", "", "", "Prestation: " . $prestation['prestation'], "../../../ressources/icone/prestation.svg");
                        break;
                    case 'visite':
                        Label::render("", "", "", "Durée: " . $minutesVisite['tempsenminutes'] . 'min', "../../../ressources/icone/horloge.svg");
                        Label::render("", "", "", "Guidée: " . ($guidee['estguidee'] ? 'Oui' : 'Non'), "../../../ressources/icone/naviguer.svg");
                        break;
                    default:
                        die("Aucune offre n'a été trouvée");
                }
                ?>
            </ul>
            <div class="moyenne-notes">
                <?php Label::render("moyenne-notes", "", "", "Moyenne des notes: " . number_format($moyenneNotes, 1) . "/5", "../../../ressources/icone/note.svg"); ?>
            </div>
        </div>
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


    </div>
    <div>
        <div class="liste-avis">
            <div>
                <h1>Avis</h1>
            </div>
            <div class="filters">
                <label for="sortBy">Trier par:</label>
                <select id="sortBy">
                    <option value="date_desc" selected>Date décroissante</option>
                    <option value="date_asc">Date croissante</option>
                    <option value="note_desc">Note décroissante</option>
                    <option value="note_asc">Note croissante</option>
                </select>

                <label for="filterBy">Filtrer par:</label>
                <select id="filterBy">
                    <option value="all">Tous</option>
                    <option value="viewed">Vus</option>
                    <option value="not_viewed">Non vus</option>
                </select>
            </div>
            <div>
                <?php
                foreach ($avis as $avi) {
                    if (!isset($avi["idavis"])) {
                        continue;
                    }
                    
                    $stmt = $dbh->prepare("SELECT idreponse, commentaire, to_char(datereponse,'DD/MM/YY') as datereponse FROM pact._reponseavis WHERE idAvis = :idAvis");
                    $stmt->execute([':idAvis' => $avi['idavis']]);
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Ajouter la classe 'non-vu' si l'avis n'a pas été vu
                    $nonVuClass = $avi['estvu'] ? '' : 'non-vu';
                    ?>
                    <div class="avi <?= $nonVuClass ?>" data-idavis="<?= $avi["idavis"] ?>">
                        <?php if (!$avi['estvu']): ?>
                            <div class="non-vu">Non vu</div>
                        <?php endif; ?>
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
                                echo "<img src='/ressources/avis/{$avi["idavis"]}/$image' alt='imgAvis' width='64' height='64' onclick=\"openUp(event)\">";
                            }
                            ?>
                        </div>
                        <div>
                            <p>
                                <?= $avi["pseudo"] ?>
                            </p>
                            <p>
                                le <?= $avi["datevisite"] ?> en <?= $avi["contextevisite"] ?>
                            </p>

                        </div>
                        <div class="thumbs">
                            <button class="thumbs-up" title="like" data-idavis="<?= $avi["idavis"] ?>">👍 <?= $thumbsUpMap[$avi["idavis"]] ?? 0 ?></button>
                            <button class="thumbs-down" title="dislike" data-idavis="<?= $avi["idavis"] ?>">👎 <?= $thumbsDownMap[$avi["idavis"]] ?? 0 ?></button>
                        </div>
                        <br>
                        <div>
                            <?php Button::render("btn-signaler", "btn-signaler","bouton signaler", "Signaler", ButtonType::Pro, "", false); ?>
                            <?php if (empty($reponses) && $totalReponses < 3): ?>
                                <?php Button::render("btn-repondre", "btn-repondre","bouton repondre", "Répondre", ButtonType::Pro, "", false); ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($reponses)): ?>
                            <div class="reponses">

                                <?php foreach ($reponses as $reponse): ?>
                                    <div class="reponse">
                                    <h2>Réponse:</h2>
                                        <p class="reponse-content">
                                            <?= $reponse["commentaire"] ?>
                                        </p>
                                        <div>
                                            <p>
                                                <?= $reponse["pseudo"] ?>
                                            </p>
                                            <p>
                                                le <?= $reponse["datereponse"] ?>
                                            </p>
                                        </div>
                                        <form action="supprimerReponse.php" method="POST">
                                            <input type="hidden" name="idReponse" value="<?= $reponse['idreponse'] ?>">
                                            <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                                            <?php Button::render("btn-supprimer", "","bouton supprimer", "Supprimer", ButtonType::Pro, "", true); ?>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="popup" id="popup-repondre">
            <div class="popup-content">
                <span class="close">&times;</span>
                <form action="envoyerReponse.php" method="POST">
                    <input type="hidden" name="idAvis" id="popup-idAvis">
                    <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                    <textarea name="reponse" placeholder="Votre réponse..."></textarea>
                    <button type="submit" title="bouton envoyer">Envoyer</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        const idOffre = <?= json_encode($idOffre) ?>;
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
    <?php Footer::render(FooterType::Pro); 
    $dbh = null;
    ?>
</body>

</html>