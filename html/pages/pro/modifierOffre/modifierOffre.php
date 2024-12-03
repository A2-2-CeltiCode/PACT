<?php
session_start();
error_reporting(E_ALL ^ E_WARNING);

require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$idOffre = isset($_POST['idOffre']) ? $_POST['idOffre'] : '1';
$crampte = isset($_POST['idOffre']) ? $_POST['idOffre'] : '1';
$sql = "SELECT nomcategorie FROM pact._spectacle WHERE idOffre = $idOffre";
$stmt = $dbh->query($sql);
$offre2 = $stmt->fetch();

$sql = "SELECT nomcategorie FROM pact._activite WHERE idOffre = $idOffre";
$stmt = $dbh->query($sql);
$offre1 = $stmt->fetch();

$sql = "SELECT nomcategorie FROM pact._parcAttractions WHERE idOffre = $idOffre";
$stmt = $dbh->query($sql);
$offre3 = $stmt->fetch();

$sql = "SELECT nomcategorie FROM pact._visite WHERE idOffre = $idOffre";
$stmt = $dbh->query($sql);
$offre4 = $stmt->fetch();

$sql = "SELECT nomcategorie FROM pact._restaurant WHERE idOffre = $idOffre";
$stmt = $dbh->query($sql);
$offre5 = $stmt->fetch();

$prix1 = null;
$prix2 = null;
$prix3 = null;
$prix4 = null;
$prix5 = null;
$duree1 = null;
$duree2 = null;
$duree3 = null;
$duree4 = null;
$duree5 = null;
$ageMinimum1 = null;
$ageMinimum2 = null;
$capacite = null;
$prestation = null;
$nombreAttractions = null;
$guidee = null;
$carteRestaurant = null;
$gammeRestaurant = null;
$typeOffre = null;
$estEnLigne=null;

$sql = "SELECT numsiren FROM pact._CompteProPrive WHERE idCompte = :idCompte";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':idCompte', $_SESSION['idCompte'], PDO::PARAM_INT);
$stmt->execute();
$numsiren = $stmt->fetchColumn();

if ($offre1 != null) {
    $sql = "SELECT * FROM pact.vue_activite WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix1 = $vueOffre["valprix"];
    $duree1 = $vueOffre["tempsenminutes"];
    $ageMinimum1 = $vueOffre["agemin"];
    $prestation = $vueOffre["prestation"];
    $typeOffre = $offre1;
}
if ($offre2 != null) {
    $sql = "SELECT * FROM pact.vue_spectacle WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();
    $prix2 = $vueOffre["valprix"];
    $duree2 = $vueOffre["tempsenminutes"];
    $capacite = $vueOffre["capacite"];
    $typeOffre = $offre2;
}
if ($offre3 != null) {
    $sql = "SELECT * FROM pact.vue_parc_attractions WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix3 = $vueOffre["valprix"];
    $ageMinimum2 = $vueOffre["agemin"];
    $nombreAttractions = $vueOffre["nbattractions"];
    $typeOffre = "parc";
}
if ($offre4 != null) {
    $sql = "SELECT * FROM pact.vue_visite WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix4 = $vueOffre["valprix"];
    $guidee = $vueOffre["estguidee"];
    $duree4 = $vueOffre["tempsenminutes"];
    $typeOffre = $offre4;
}
if ($offre5 != null) {
    $sql = "SELECT * FROM pact.vue_restaurant WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $carteRestaurant = $vueOffre["nomimage"];
    $gammeRestaurant = $vueOffre["nomgamme"];
    $prix5 = $vueOffre["valprix"];
    $typeOffre = $offre5;
}


$nomOffre = $vueOffre["titre"];
$ville = $vueOffre["ville"];
$codePostal = $vueOffre["codepostal"];
$rue = $vueOffre["rue"];
$numeroTelephone = $vueOffre["numtel"];
$siteWeb = $vueOffre["siteinternet"];
$descriptionOffre = $vueOffre["description"];
$descriptionDetaillee = $vueOffre["descriptiondetaillee"];
$typeForfait = $vueOffre["nomforfait"];
$typePromotion = $vueOffre["nomoption"];
$estEnLigne = $vueOffre["estenligne"];

?>
<!DOCTYPE html>
<html>

<head>
    <?php
    use composants\Input\Input;
    use composants\Button\Button;
    use \composants\InsererImage\InsererImage;
    use \composants\Checkbox\Checkbox;
    use \composants\Select\Select;
    use \composants\Textarea\Textarea;
    use \composants\CheckboxSelect\CheckboxSelect;
    require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/InsererImage/InsererImage.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Checkbox/Checkbox.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Header/Header.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Textarea/Textarea.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Footer/Footer.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/CheckboxSelect/CheckboxSelect.php";
    
    ?>
    <title>Modification d'une offre</title>

    <script src="modificationOffre.js"></script>
    <link rel="stylesheet" href="../../../ui.css">
    <link rel="stylesheet" href="modificationOffre.css">
</head>
<?php Header::render(HeaderType::Pro); ?>

<body>
    <form class="info-display" id="myForm" method="post" action="confirmationModificationOffre.php" enctype="multipart/form-data">
        
        <?php
        
        ?>
        <h1>Modifier votre Offre</h1>
        <section>

            <article>
                <div>
                    <label>Nom de l'offre*</label>
                    <?php Input::render(name: "nomOffre", type: "text", required: "true", value: $nomOffre) ?>
                </div>

                <div>
                    <label>Information de l'offre</label>
                    <?php Input::render(name: "ville", type: "text", required: "true", placeholder: 'Ville*', value: $ville) ?>
                    <?php Input::render(name: "codePostal", type: "number", required: 'true', placeholder: "Code Postal*", value: $codePostal) ?>
                    <?php Input::render(name: "adressePostale", id: "adressePostale", type: "text", placeholder: 'Adresse Postale', value: $rue) ?>


                </div>

                <div>
                    <label>Numéro de téléphone*</label>
                    <?php Input::render(name: "numeroTelephone", type: "string", required: "true", placeholder: 'ex : 06 10 12 01 24', value: $numeroTelephone) ?>
                </div>

                <div>
                    <label>Site Web</label>
                    <?php Input::render(name: "siteWeb", type: "text", placeholder: 'ex : www.siteWeb.com', value: $siteWeb) ?>
                </div>

                <div>

                    <div>
                        <label>Description de l'offre*</label>
                        <?php Textarea::render(name: "descriptionOffre", required: "true", rows: 2, value: $descriptionOffre) ?>
                    </div>
            </article>
            <article>
                <div>
                    <label>Description Détaille</label>
                    <?php Textarea::render(name: "descriptionDetaillee", rows: 7, value: $descriptionDetaillee) ?>
                </div>



                </div>

                <div>
                    <label>Est en ligne*</label>
                    <input type="radio" id="oui" name="estEnLigne" value="true" <?php if ($estEnLigne == "1") echo "checked"; ?>>
                    <label for="oui">Oui</label>
                    <input type="radio" id="non" name="estEnLigne" value="false" <?php if ($estEnLigne == "0") echo "checked"; ?>>
                    <label for="non">Non</label>
                </div>

                <div>
                
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
                        required: true,
                        options: $option
                    );
                    ?>
                </div>

                



                <?php if ($offre1 != null) { ?>
                    <div id="Activite" class="section">
                        <div>
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag=[];
                            foreach($tabTag as $key => $Tag){
                                $tag[$Tag['nomtag']]=$Tag['nomtag'];
                                
                            }
                            
                            ?>
                            
                                    <?php 
                                        CheckboxSelect::render(
                                            'checkbox',
                                            "tag_activite_",
                                            "tag[]",
                                            false,
                                            $tag,

                                        );
                                    ?>
                                <div class="selected-values"></div>
                                
                            </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix1", type: "number", value: $prix1) ?>
                            <label>Âge minimum</label>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($offre4 != null) { ?>
                    
                    <div id="Visite" class="section">
                        <div>
                        <div>
                        <?php
                        $sql = "SELECT nomtag FROM pact._tagAutre";
                        $stmt = $dbh->prepare($sql); 
                        $stmt->execute();
                        $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $tag=[];
                        foreach($tabTag as $key => $Tag){
                            $tag[$Tag['nomtag']]=$Tag['nomtag'];
                            
                        }
                        

                        ?>
                        <?php 
                            
                            CheckboxSelect::render(
                                'checkbox',
                                "tag_visite_",
                                "tag[]",
                                false,
                                $tag,
                            );
                        ?>
                        <div class="selected-values"></div>
                    </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix4", type: "number", value: $prix4) ?>
                            <label>Durée de la visite</label>
                            <?php Input::render(name: "duree4", type: "number", value: $duree4) ?>
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
                                foreach($tabLangue as $key => $tabLangue){
                                    $langue[$key]=$tabLangue['nomlangage'];
                                }
                                ?>
                                <?php 
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
                    </div> 
                <?php } else if ($offre2 != null) { ?>
                   
                    <div id="Spectacle" class="section">
                        <div>
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag=[];
                            foreach($tabTag as $key => $Tag){
                                $tag[$Tag['nomtag']]=$Tag['nomtag'];
                                
                            }
                            ?>
                            <?php 
                                CheckboxSelect::render(
                                    'checkbox',
                                    "tag_spectacle_",
                                    "tag[]",
                                    false,
                                    $tag,
                                );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix2", type: "number", value: $prix2) ?>
                            <label>Capacité d'accueil</label>
                            <?php Input::render(name: "capacite", type: "number", value: $capacite) ?>
                            <label>Durée du spectacle</label>
                            <?php Input::render(name: "duree2", type: "number", value: $duree2) ?>
                        </div>
                    </div> 
                <?php } else if ($offre3 != null) { ?>
                    <div id="parc" class="section">
                        <div>
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag=[];
                            foreach($tabTag as $key => $Tag){
                                $tag[$Tag['nomtag']]=$Tag['nomtag'];
                                
                            }
                            ?>
                            <?php 
                                CheckboxSelect::render(
                                    'checkbox',
                                    "tag_parc_",
                                    "tag[]",
                                    false,
                                    $tag,
                                );
                            ?>
                            <div class="selected-values"></div>
                        </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix3", type: "number", value: $prix3) ?>
                            <label>Nombre d'attractions</label>
                            <?php Input::render(name: "nombreAttractions", type: "number", value: $nombreAttractions); ?>
                            <div>
                                <label>Plan du Parc</label>
                                <?php InsererImage::render("planParc", "Glissez-déposez vos images ici", 1, false,false,['pdf']); ?>
                            </div>
                            <label>Âge minimum</label>
                            <?php Input::render(name: "ageMinimum2", type: "number", value: $ageMinimum2) ?>
                        </div>
                    </div> 
                <?php } else if ($offre5 != null) { ?>
                    <div id="Restaurant" class="section">
                        <div>
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagRestaurant";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tag=[];
                            foreach($tabTag as $key => $Tag){
                                $tag[$Tag['nomtag']]=$Tag['nomtag'];
                                
                            }
                            ?>
                            <?php 
                                CheckboxSelect::render(
                                    'checkbox',
                                    "tag_restaurant_",
                                    "tag[]",
                                    false,
                                    $tag,
                                );
                                
                            ?>
                            <div class="selected-values"></div>
                        </div>
                            <br>
                            <div>
                                <label>Carte du Restaurant</label>
                                <?php InsererImage::render("carteRestaurant", "Glissez-déposez vos images ici", 1, false,false,['pdf']); ?>
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
                                <br><br>
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
                        </div>
                    </div>
                <?php } ?>
            </article>
        </section>
        <div class="btns">
            <br>
            <?php Button::render(onClick:"window.location.href = '../listeOffres/listeOffres.php';", text: "Annuler", type: "pro", submit: false, ); ?>
            <?php Button::render(text: "Valider", type:"pro", submit: true , class:"valid"); ?>
        </div>
        <input type="hidden" name="idOffre" value="<?php echo $crampte;?>">


    </form>
    
</body>
<?php Footer::render(FooterType::Pro); ?>
<script src="modificationOffre.js"></script>
</html>