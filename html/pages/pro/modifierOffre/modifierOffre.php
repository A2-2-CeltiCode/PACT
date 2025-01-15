<?php
session_start();
error_reporting(E_ALL ^ E_WARNING);
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$idOffre = $_POST['idOffre'] ?? 1;
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

$debutOption = null;
$stmt = $dbh->prepare(
    "SELECT debutOption FROM pact._annulationOption WHERE idOffre = :idOffre"
);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->execute();
$debutOption = $stmt->fetch(PDO::FETCH_COLUMN);
$currentDate = date("Y-m-d");

if ($offre1 != null) {
    $sql = "SELECT * FROM pact.vue_activite WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix1 = $vueOffre["valprix"];
    $duree1 = $vueOffre["tempsenminutes"];
    $ageMinimum1 = $vueOffre["agemin"];
    $prestation = $vueOffre["prestation"];
    $typeOffre = $offre1;

    $sql = "SELECT nomtag FROM pact.vue_tags_activite WHERE idOffre = $idOffre";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $tagActivite = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
if ($offre2 != null) {
    $sql = "SELECT * FROM pact.vue_spectacle WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();
    $prix2  = $vueOffre["valprix"];
    $duree2 = $vueOffre["tempsenminutes"];
    $capacite = $vueOffre["capacite"];
    $typeOffre = $offre2;

    $sql = "SELECT nomtag FROM pact.vue_tags_spectacle WHERE idOffre = $idOffre";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $tagSpectacle = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
if ($offre3 != null) {
    $sql = "SELECT * FROM pact.vue_parc_attractions WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix3 = $vueOffre["valprix"];
    $ageMinimum2 = $vueOffre["agemin"];
    $nombreAttractions = $vueOffre["nbattractions"];
    $typeOffre['nomcategorie'] = "parc";

    $sql = "SELECT nomtag FROM pact.vue_tags_parc_attractions WHERE idOffre = $idOffre";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $tagParc = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
if ($offre4 != null) {
    $sql = "SELECT * FROM pact.vue_visite WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $prix4 = $vueOffre["valprix"];
    $guidee = $vueOffre["estguidee"];
    $duree4 = $vueOffre["tempsenminutes"];
    $typeOffre = $offre4;

    $sql = "SELECT nomtag FROM pact.vue_tags_visite WHERE idOffre = $idOffre";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $tagVisite = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
if ($offre5 != null) {
    $sql = "SELECT * FROM pact.vue_restaurant WHERE idOffre = $idOffre";
    $stmt = $dbh->query($sql);
    $vueOffre = $stmt->fetch();


    $carteRestaurant = $vueOffre["nomimage"];
    $gammeRestaurant = $vueOffre["nomgamme"];
    $prix5 = $vueOffre["valprix"];
    $typeOffre = $offre5;
    $sql = "SELECT nomtag FROM pact.vue_tags_restaurant WHERE idOffre = $idOffre";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $tagRestaurant = $stmt->fetchAll(PDO::FETCH_COLUMN);
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
$options = $vueOffre["nomoption"];

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
    <script>
        function confirmCancelOption(message) {
            return confirm(message);
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const cancelOptionRadios = document.querySelectorAll('input[name="options"]');
            const debutOption = "<?php echo $debutOption; ?>";
            const currentDate = "<?php echo $currentDate; ?>";
            cancelOptionRadios.forEach(radio => {
                radio.addEventListener('change', (event) => {
                    if (event.target.value === 'true') {
                        if (debutOption >= currentDate) {
                            if (!confirmCancelOption("Êtes-vous sûr de vouloir annuler l'option ? Vous ne paierez pas l'option.")) {
                                event.target.checked = false;
                                document.getElementById('non').checked = true;
                            }
                        } else {
                            if (!confirmCancelOption("Êtes-vous sûr de vouloir annuler l'option ? Vous paierez quand même l'option.")) {
                                event.target.checked = false;
                                document.getElementById('non').checked = true;
                            } else {
                                document.getElementById('non').checked = false;
                            }
                        }
                    }
                });
            });
            displaySelectedValues(); // Afficher les valeurs sélectionnées au chargement de la page
        });
    </script>
</head>
<?php Header::render(HeaderType::Pro); ?>

<body>
    <form class="info-display" id="myForm" method="post" action="confirmationModificationOffre.php" enctype="multipart/form-data">
        
        <input type="hidden" name="typeOffre" value="<?php echo $typeOffre['nomcategorie']; ?>">
        <input type="hidden" name="typePromotion" value="<?php echo $options; ?>">
        <?php
        
        ?>
        <h1>Modifier votre Offre</h1>
        <section>

            <article>
                <h4>Information de l'offre</h4>
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

            </article>
            <article class="description">
                <div>


            </article>
            <article>
                <h4>Description de l'offre</h4>
                <div>
                    <label>Description de l'offre*</label>
                    <?php Textarea::render(name: "descriptionOffre", required: "true", rows: 2, value: $descriptionOffre) ?>
                </div>
                <div>
                    <label>Description Détaille</label>
                    <?php Textarea::render(name: "descriptionDetaillee", rows: 7, value: $descriptionDetaillee) ?>
                </div>
            </article>
            <article>
                <h4>Details de l'offre</h4>

                </div>

                <div>
                    <input type="hidden" name="ancienLigne" value="<?php echo $estEnLigne; ?>">
                    <label>Est en ligne*</label>
                    <br>
                    <div class="flex">
                        <input type="radio" id="vrai" name="estEnLigne" value="true" <?php if ($estEnLigne == "1") echo "checked"; ?>>
                        <label for="oui">Oui</label>
                        <input type="radio" id="faux" name="estEnLigne" value="false" <?php if ($estEnLigne == "0") echo "checked"; ?>>
                        <label for="non">Non</label>
                    </div>
                </div>


                <div>
                    
                    <?php
                    $stmt = $dbh->prepare(
                        "SELECT debutOption FROM pact._annulationOption WHERE idOffre = :idOffre"
                    );
                    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
                    $stmt->execute();
                    $debutOption = $stmt->fetch(PDO::FETCH_COLUMN);
                    
                    if($options!="Aucune"){
                        ?>
                        <label>Voulez vous annuler l'option <br> <?php  echo(htmlspecialchars(strtolower($options))) ?> ?</label>
                        <br>

                        <input type="radio" id="oui" name="options" value="true" >
                        <label for="oui">Oui</label>
                        <input type="radio" id="non" name="options" value="false" checked>
                        <label for="non">Non</label>
                        <?php
                    }
                    ?>
                </div>
                <br>

                



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
                                            $tagActivite,
                                            

                                        );
                                    ?>
                                <br>
                                <div class="selected-values">
                                    
                                </div>
                                
                            </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix1", type: "number", value: $prix1) ?>
                            <label>Âge minimum</label>
                            <?php Input::render(name: "ageMinimum1", type: "number", value: $ageMinimum1) ?>
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
                                $tagVisite
                            );
                        ?>
                        <br>
                        <div class="selected-values"></div>
                    </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix4", type: "number", value: $prix4) ?>
                            <label>Durée de la visite</label>
                            <?php Input::render(name: "duree4", type: "number", value: $duree4) ?>
                            <label>Visite guidée</label>
                            <input type="radio" id="oui" name="guidee" value="true" <?php if ($guidee == "1") echo "checked"; ?> onclick="toggleLangue(true)">
                            <label for="oui">Oui</label>
                            <input type="radio" id="non" name="guidee" value="false" <?php if ($guidee == "0") echo "checked"; ?> onclick="toggleLangue(false)">
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
                                    $tagSpectacle
                                    
                                );
                            ?>
                            <br>
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
                                    $tagParc
                                );
                            ?>
                            <br>
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
                                    $tagRestaurant
                                );
                                
                            ?>
                            <br>
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

                                <div>
                                <br>
                                <div class="flex">
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
                    </div>
                <?php } ?>
            </article>
        </section>
        <section class="adaptable">
        <div class="basDePage">
            <div class="reunion">
                        <div class="choix">
                            <input type="hidden" name="ancienLigne" value="<?php echo $estEnLigne; ?>">
                            <label>Est en ligne*</label>
                            <br>
                            <input type="radio" id="vrai" name="estEnLigne" value="true" <?php if ($estEnLigne == "1") echo "checked"; ?>>
                            <label for="oui">Oui</label>
                            <input type="radio" id="faux" name="estEnLigne" value="false" <?php if ($estEnLigne == "0") echo "checked"; ?>>
                            <label for="non">Non</label>
                        </div>

                        <br>

                        <div class="choix">
                            
                            <?php
                            $stmt = $dbh->prepare(
                                "SELECT debutOption FROM pact._annulationOption WHERE idOffre = :idOffre"
                            );
                            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
                            $stmt->execute();
                            $debutOption = $stmt->fetch(PDO::FETCH_COLUMN);
                            
                            if($options!="Aucune"){
                                ?>
                                <label>Annuler l'option <?php  echo(htmlspecialchars(strtolower($options))) ?> ?</label>
                                <br>

                                <input type="radio" id="oui" name="options" value="true" >
                                <label for="oui">Oui</label>
                                <input type="radio" id="non" name="options" value="false" checked>
                                <label for="non">Non</label>
                                <?php
                            }
                            ?>
                        </div>
            </div>
            <div class="btns">
                <br>
                <?php Button::render(onClick:"window.location.href = '../listeOffres/listeOffres.php';", text: "Annuler", type: "pro", submit: false, ); ?>
                <?php Button::render(text: "Valider", type:"pro", submit: true , class:"valid"); ?>
            </div>
            
            
            <input type="hidden" name="idOffre" value="<?php echo $crampte;?>">
        </div>
        <div class="droite">
                <p>*:champ obligatoire</p>
            </div>
        
        
        </section>

        
        

    </form>
    
</body>
<?php Footer::render(FooterType::Pro); ?>
<script src="modificationOffre.js"></script>
</html>