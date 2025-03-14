<?php 
session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "membre") {
    header("Location: /pages/membre/accueil/accueil.php");
    exit();
} elseif (!isset($_SESSION['idCompte'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
    exit();
}

use composants\Input\Input;
use composants\Button\Button;
use composants\InsererImage\InsererImage;
use composants\Checkbox\Checkbox;
use composants\Select\Select;
use composants\Textarea\Textarea;
use composants\CheckboxSelect\CheckboxSelect;

require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/InsererImage/InsererImage.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Checkbox/Checkbox.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Textarea/Textarea.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/CheckboxSelect/CheckboxSelect.php";

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$sql = "SELECT numsiren FROM pact._CompteProPrive WHERE idCompte = :idCompte";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':idCompte', $_SESSION['idCompte'], PDO::PARAM_INT);
$stmt->execute();
$numsiren = $stmt->fetchColumn();

$sql = "SELECT banquerib FROM pact._ComptePro WHERE idCompte = :idCompte";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':idCompte', $_SESSION['idCompte'], PDO::PARAM_INT);
$stmt->execute();
$rib = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Création d'une offre</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="creerOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
    
</head>

<body>
    <?php Header::render(HeaderType::Pro); ?>
    

        <form class="info-display" id="myForm" method="post" action="confimationCreationOffre.php"
            onsubmit="return validateForm() && validateVilleAdresseCodePostal()" enctype="multipart/form-data">
            <h1>Créez votre Offre</h1>
            <section>
                <article>
                <h4>Information de l'offre</h4>
                <br>
                    <div>
                        <label>Nom de l'offre*</label>
                        <?php Input::render(name: "nomOffre", type: "text", required: "true") ?>
                    </div>
                    <div>
                        <label>Information de l'offre</label>
                        <div>
                            <?php Input::render(name: "ville", type: "text", id: "ville", required: "true", placeholder: 'Ville*', onkeyup: "suggestVilles()") ?>
                            <div id="suggestions"></div>
                        </div>
                        <?php Input::render(name: "codePostal", id: "postcode", type: "number", required: 'true', placeholder: "Code Postal*",onkeyup:"suggestPostale") ?>
                        <div>
                            <?php Input::render(name: "adressePostale", id: "adresse", type: "text", placeholder: 'Adresse Postale', onkeyup: "suggestAdresses()") ?>
                            <div id="adresseSuggestions"></div>
                        </div>
                        <input type="hidden" id="longitude" name="longitude">
                        <input type="hidden" id="latitude" name="latitude">
                        <div id="map" style="height: 300px; width: 100%;"></div>
                        <br>
                    </div>
                    <div>
                        <label>Numéro de téléphone*</label>
                        <?php Input::render(name: "numeroTelephone", type: "number", required: "true", placeholder: 'ex : 06 10 12 01 24') ?>
                    </div>
                    <div>
                        <label>Site Web</label>
                        <?php Input::render(name: "siteWeb", type: "text", placeholder: 'ex : www.siteWeb.com') ?>
                    </div>
                    <div>
                        <label>Heure d'ouverture</label>
                        <?php Input::render(name: "ouverture", type: "time", placeholder: '', required: "true") ?>
                    </div>
                    <div></div>
                        <label>Heure de fermeture</label>
                        <?php Input::render(name: "fermeture", type: "time", placeholder: '', required: "true") ?>
                    </div>
                </article>
                <article>
                    <h4>Description de l'offre</h4>
                    <br>
                    <div>
                        <label>Description de l'offre*</label>
                        <?php Textarea::render(name: "descriptionOffre", required: "true", rows: 2) ?>
                    </div>
                    <div>
                        <label>Description Détaille</label>
                        <?php Textarea::render(name: "descriptionDetaillee", rows: 7) ?>
                    </div>
                    <div>
                        <label>Photo*</label>
                        <?php InsererImage::render("monDropZone[]", "Glissez-déposez vos images ici", 5, true, true, ['jpg', 'png'], "monDropZone[]"); ?>
                    </div>
                </article>
                <article>
                    <h4>Details de l'offre</h4>
                    <br>
                    <div>
                        <?php
                        if ($numsiren != null) {
                            if ($rib == null) {
                                ?><label>IBAN*</label><?php
                                Input::render(name: "iban", type: "text", required: false);
                            }
                            ?>
                            <label>Type de forfait*</label>
                            <?php
                            $option = null;
                            $sql = "SELECT nomforfait FROM pact._forfaitPro";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabForfait = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($tabForfait as $forfait) {
                                $option[$forfait['nomforfait']] = $forfait['nomforfait'];
                            }
                            Select::render(
                                name: "typeForfait",
                                required: false,
                                options: $option,
                            );
                        } else {
                            ?><input type="hidden" name="typeForfait" value="Gratuit"><?php
                        }
                        ?>
                    </div>
                    <div>
                        <label>Type de promotion de l'offre*</label>
                        <?php
                        $option = null;
                        $sql = "SELECT nomoption FROM pact._option";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $tabOption = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($tabOption as $type) {
                            $option[$type['nomoption']] = $type['nomoption'];
                        }
                        Select::render(
                            name: "typePromotion",
                            required: false,
                            options: $option
                        );
                        ?>
                    </div>
                    <div id="datePromotionContainer" style="display:none;">
                        <label>Date de début de la promotion*</label>
                        <?php Input::render(name: "datePromotion", type: "week") ?>
                        <label>Nombre de semaine (max 4 semaine)</label>
                        <?php Input::render(name: "durepromotion", id: "durepromotion", type: "number", min: 1, max: 4) ?>
                    </div>
                    <div>
                        <label>Type d'offre*</label>
                        <?php
                        $option = null;
                        $sql = "SELECT nomcategorie FROM pact._categorie";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $tabcategorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $option["Selectionner"] = "Selectionner";
                        foreach ($tabcategorie as $categorie) {
                            if ($categorie['nomcategorie'] !== "Parc d'attractions") {
                                $option[$categorie['nomcategorie']] = $categorie['nomcategorie'];
                            } else {
                                $option["parc"] = $categorie['nomcategorie'];
                            }
                        }
                        Select::render(
                            name: "typeOffre",
                            required: false,
                            options: $option,
                            id: "typeOffre"
                        );
                        ?>
                    </div>
                    <div id="Activite" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag = [];
                            foreach ($tabTag as $key => $Tag) {
                                $tag[$Tag['nomtag']] = $Tag['nomtag'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "tag_activite_",
                                name: "tag[]",
                                required: false,
                                options: $tag,
                                buttonText: "Tag"
                            );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                        <label>Prix*</label>
                        <?php Input::render(name: "prix1", type: "number") ?>
                        <label>Âge minimum</label>
                        <?php Input::render(name: "ageMinimum1", type: "number") ?>
                        <label>Prestation</label>
                        <?php Input::render(name: "prestation", type: "text") ?>
                        <label>Durée de l'activité</label>
                        <?php Input::render(name: "duree1", type: "number") ?>
                    </div>
                    <div id="Visite" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag = [];
                            foreach ($tabTag as $key => $Tag) {
                                $tag[$Tag['nomtag']] = $Tag['nomtag'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "tag_visite_",
                                name: "tag[]",
                                required: false,
                                options: $tag,
                                buttonText: "Tag"
                            );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                        <label>Prix*</label>
                        <?php Input::render(name: "prix2", type: "number") ?>
                        <label>Durée de la visite</label>
                        <?php Input::render(name: "duree2", type: "number") ?>
                        <label>Date de la visite</label>
                        <?php Input::render(name: "dateVisite", type: "date") ?>
                        <label>Visite guidée</label>
                        <input type="radio" id="oui" name="guidee" value="true" onclick="toggleLangue(true)">
                        <label for="oui">Oui</label>
                        <input type="radio" id="non" name="guidee" value="false" onclick="toggleLangue(false)">
                        <label for="non">Non</label>
                        <div id="langue" style="display: none;">
                            <?php
                            $sql = "SELECT nomlangage FROM pact._langage";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabLangue = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($tabLangue as $key => $tabLangue) {
                                $langue[$key] = $tabLangue['nomlangage'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "langue_",
                                name: "langue[]",
                                required: false,
                                options: $langue,
                                buttonText: "Langue"
                            );
                            ?>
                        </div>
                    </div>
                    <div id="Spectacle" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag = [];
                            foreach ($tabTag as $key => $Tag) {
                                $tag[$Tag['nomtag']] = $Tag['nomtag'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "tag_spectacle_",
                                name: "tag[]",
                                required: false,
                                options: $tag,
                                buttonText: "Tag"
                            );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                        <label>Prix*</label>
                        <?php Input::render(name: "prix3", type: "number") ?>
                        <label>Capacité d'accueil</label>
                        <?php Input::render(name: "capacite", type: "number") ?>
                        <label>Durée du spectacle</label>
                        <?php Input::render(name: "duree3", type: "number") ?>
                        <label>Date de l'evenement</label>
                        <?php Input::render(name: "dateSpectacle", type: "date") ?>
                    </div>
                    <div id="parc" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag = [];
                            foreach ($tabTag as $key => $Tag) {
                                $tag[$Tag['nomtag']] = $Tag['nomtag'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "tag_parc_",
                                name: "tag[]",
                                required: false,
                                options: $tag,
                                buttonText: "Tag"
                            );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                        <label>Prix*</label>
                        <?php Input::render(name: "prix4", type: "number") ?>
                        <label>Nombre d'attractions</label>
                        <?php Input::render(name: "nombreAttractions", type: "number"); ?>
                        <div>
                            <label>Plan du Parc</label>
                            <?php InsererImage::render("planParc[]", "Glissez-déposez vos images ici", 1, false, false, ['pdf'], "planParc[]"); ?>
                        </div>
                        <label>Âge minimum</label>
                        <?php Input::render(name: "ageMinimum2", type: "number") ?>
                    </div>
                    <div id="Restaurant" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagRestaurant";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag = [];
                            foreach ($tabTag as $key => $Tag) {
                                $tag[$Tag['nomtag']] = $Tag['nomtag'];
                            }
                            CheckboxSelect::render(
                                class: 'checkbox',
                                id: "tag_restaurant_",
                                name: "tag[]",
                                required: false,
                                options: $tag,
                                buttonText: "Tag"
                            );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                        <div>
                            <label>Carte du Restaurant</label>
                            <?php InsererImage::render("carteRestaurant[]", "Glissez-déposez vos images ici", 1, false, false, ['pdf'], "carteRestaurant[]"); ?>
                        </div>
                        <div>
                            <label>Gamme du restaurant</label>
                            <?php
                            $option = null;
                            $sql = "SELECT nomGamme FROM pact._gamme";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $tabGamme = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($tabGamme as $gamme) {
                                $option[$gamme['nomgamme']] = $gamme['nomgamme'];
                            }
                            Select::render(
                                name: "gammeRestaurant",
                                options: $option
                            );
                            ?>
                        </div>
                        <div>
                            <label>Type de repas</label>
                            <br>
                            <input type="checkbox" id="dejeuner" name="typeRepas[]" value="Dejeuner">
                            <label for="dejeuner">Dejeuner</label>
                            <input type="checkbox" id="diner" name="typeRepas[]" value="Diner">
                            <label for="diner">Diner</label>
                            <input type="checkbox" id="snack" name="typeRepas[]" value="Snack">
                            <label for="snack">Snack</label>
                        </div>
                    </div>
                </article>
            </section>
            <div class="btns">
                <?php Button::render(onClick: "window.location.href = '../listeOffres/listeOffres.php';", text: "Annuler",title:"bouton annuler" ,type: "pro", submit: false); ?>
                <?php Button::render(text: "Valider",title:"bouton valider" ,type: "pro", submit: true); ?>
            </div>
        </form>
        

    </main>
    <?php Footer::render(FooterType::Pro); ?>
</body>

<script src="creerOffre.js"></script>

</html>