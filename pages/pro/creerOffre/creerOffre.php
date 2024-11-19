<?php session_start(); ?>
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

        require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/InsererImage/InsererImage.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Checkbox/Checkbox.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Header/Header.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Textarea/Textarea.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Footer/Footer.php";
        require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $idCompte = $_SESSION['idCompte'];

        $sql = "SELECT numsiren FROM pact._CompteProPrive WHERE idCompte = :idCompte";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $numsiren = $stmt->fetchColumn();
        ?> 
        <title>Création d'une offre</title>

        <script src="creerOffre.js"></script>

        <link rel="stylesheet" href="creerOffre.css">
        <link rel="stylesheet" href="../../../ui.css">
    </head>

    <body>
        <?php Header::render(HeaderType::Pro); ?>
        
        
        <form class="info-display" id="myForm" method="post" action="confimationCreationOffre.php" enctype="multipart/form-data">
            <h1>Créez votre Offre</h1>
            <section>
                
                <article>
                    <div>
                        <label>Nom de l'offre*</label>
                        <?php Input::render(name:"nomOffre", type:"text", required:"true") ?>
                    </div>

                    <div>
                        <label>Information de l'offre</label>
                        <?php Input::render(name:"ville", type:"text", required:"true", placeholder:'Ville*') ?>
                        <?php Input::render(name:"codePostal", type:"number", required:'true', placeholder:"Code Postal*") ?>
                        <?php Input::render(name:"adressePostale", id:"adressePostale", type:"text", placeholder:'Adresse Postale') ?>    
                    

                    </div>
                    
                    <div>
                        <label>Numéro de téléphone*</label>
                        <?php Input::render(name:"numeroTelephone", type:"number", required:"true", placeholder:'ex : 06 10 12 01 24') ?>
                    </div>

                    <div>
                        <label>Site Web</label>
                        <?php Input::render(name:"siteWeb", type:"text", placeholder:'ex : www.siteWeb.com') ?>
                    </div>

                    <div>
                                        
                    <div>
                        <label>Description de l'offre*</label>
                        <?php Textarea::render(name:"descriptionOffre", required:"true", rows:2) ?>
                    </div>
                </article>
                <article>
                    <div>
                        <label>Description Détaille</label>
                        <?php Textarea::render(name:"descriptionDetaillee", rows:7) ?>
                    </div>

                    
                        
                    </div>


                    
                    <div>
                        <label>Type de forfait*</label>

                        

                        <?php
                            if($numsiren==true){
                                
                            }

                            $option=null;
                            $sql = "SELECT nomforfait FROM pact._forfait";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();

                            $tabForfait = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                            foreach ($tabForfait as $forfait) {
                                $option[$forfait['nomforfait']] = $forfait['nomforfait'];
                            }
                        ?>
                        <?php 
                            Select::render(
                                name: "typeForfait", 
                                required: true, 
                                options: $option
                            );
                            
                            ?>
                    </div>
                    
                    <div>
                        <label>Type de promotion de l'offre*</label>
                        <?php
                            $option=null;
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
                    
                    <div>
                        <label>Photo*</label>   
                        <?php InsererImage::render("monDropZone[]", "Glissez-déposez vos images ici", 5,true,true,['jpg', 'png']);?>
                    </div>


                    <div>
                        <label>Type d'offre*</label>
                        <?php
                            $option=null;
                            $sql = "SELECT nomcategorie FROM pact._categorie";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();

                            $tabcategorie = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                            $option["Selectionner"]="Selectionner";
                            foreach ($tabcategorie as $categorie) {
                                if ($categorie['nomcategorie']!=="Parc d'attractions") {
                                    $option[$categorie['nomcategorie']] = $categorie['nomcategorie'];
                                }else{
                                    $option["parc"]=$categorie['nomcategorie'];
                                }
                                
                            }
                            Select::render(
                                name: "typeOffre", 
                                required: true, 
                                options: $option
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
                            ?>
                            <div class="dropdown">
                            <?php Button::render(onClick: "toggleDropdown('dropdownActivite')", text: "Tag", type: "pro", submit: false, class: "tag"); ?>
                            <div class="dropdown-content" id="dropdownActivite">
                                    <?php foreach($tabTag as $index => $tag) { 
                                        Checkbox::render(
                                            class: "checkbox",
                                            id: "tag_activite_" . $index . "_" . $tag['nomtag'], // ID unique
                                            name: "tag[]",
                                            value: $tag['nomtag'],
                                            text: $tag['nomtag'],
                                            required: false,
                                            checked: false
                                        );
                                    }?>
                                </div>
                                
                            </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix1", type: "number") ?>
                            <label>Âge minimum</label>
                            <?php Input::render(name: "ageMinimum1", type: "number") ?>
                            <label>Prestation</label>
                            <?php Input::render(name: "prestation", type: "text") ?>
                            <label>Durée de l'activité</label>
                            <?php Input::render(name: "duree1", type: "number") ?>
                        </div>
                    </div> <!-- End of Activite section -->

                    <!-- Section Visite -->
                    <div id="Visite" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="dropdown">
                                
                                <?php Button::render(onClick: "toggleDropdown('dropdownVisite')", text: "Tag", type: "pro", submit: false, class: "tag"); ?>                               
                                <div class="dropdown-content" id="dropdownVisite">
                                    <?php foreach($tabTag as $index => $tag) { 
                                        Checkbox::render(
                                            class: "checkbox",
                                            id: "tag_visite_" . $index . "_" . $tag['nomtag'], // ID unique
                                            name: "tag[]",
                                            value: $tag['nomtag'],
                                            text: $tag['nomtag'],
                                            required: false,
                                            checked: false
                                        );
                                    }?>
                                </div>
                                
                            </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix2", type: "number") ?>
                            <label>Durée de la visite</label>
                            <?php Input::render(name: "duree2", type: "number") ?>
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
                                foreach($tabLangue as $langue){
                                    CheckBox::render(
                                        class: "checkbox",
                                        id: $langue['nomlangage'],
                                        name: "langue[]",
                                        value: $langue['nomlangage'],
                                        text: $langue['nomlangage'],
                                        required: false,
                                        checked: false
                                    );
                                }
                                ?>
                            </div>
                        </div>
                    </div> <!-- End of Visite section -->

                    <!-- Section Spectacle -->
                    <div id="Spectacle" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="dropdown">
                            <?php Button::render(onClick: "toggleDropdown('dropdownSpectacle')", text: "Tag", type: "pro", submit: false, class: "tag"); ?>                               
                                <div class="dropdown-content" id="dropdownSpectacle">
                                    <?php foreach($tabTag as $index => $tag) { 
                                        Checkbox::render(
                                            class: "checkbox",
                                            id: "tag_spectacle_" . $index . "_" . $tag['nomtag'], // ID unique
                                            name: "tag[]",
                                            value: $tag['nomtag'],
                                            text: $tag['nomtag'],
                                            required: false,
                                            checked: false
                                        );
                                    }?>
                                </div>
                                
                            </div>
                            <br><br>
                            <label>Prix*</label>
                            <?php Input::render(name: "prix3", type: "number") ?>
                            <label>Capacité d'accueil</label>
                            <?php Input::render(name: "capacite", type: "number")?>
                            <label>Durée du spectacle</label>
                            <?php Input::render(name: "duree3", type: "number")?>
                        </div>
                    </div> <!-- End of Spectacle section -->

                    <!-- Section Parc d'Attraction -->
                    <div id="parc" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagAutre";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="dropdown">
                            <?php Button::render(onClick: "toggleDropdown('dropdownParc')", text: "Tag", type: "pro", submit: false, class: "tag"); ?>                               

                                <div class="dropdown-content" id="dropdownParc">
                                    <?php foreach($tabTag as $index => $tag) { 
                                        Checkbox::render(
                                            class: "checkbox",
                                            id: "tag_parc_" . $index . "_" . $tag['nomtag'], // ID unique
                                            name: "tag[]",
                                            value: $tag['nomtag'],
                                            text: $tag['nomtag'],
                                            required: false,
                                            checked: false
                                        );
                                    }?>
                                </div>
                            </div>
                            <br><br>
                            
                            <label>Prix*</label>
                            <?php Input::render(name: "prix4", type: "number") ?>
                            <label>Nombre d'attractions</label>
                            <?php Input::render(name: "nombreAttractions", type: "number");?>
                            <div>
                                <label>Plan du Parc</label>
                                <?php InsererImage::render("planParc", "Glissez-déposez vos images ici", 1, false,false,['pdf']);?>
                            </div>
                            <label>Âge minimum</label>
                            <?php Input::render(name: "ageMinimum2", type: "number") ?>
                        </div>
                    </div> <!-- End of Parc d'Attraction section -->

                    <!-- Section Restauration -->
                    <div id="Restaurant" class="section" style="display:none;">
                        <div>
                            <?php
                            $sql = "SELECT nomtag FROM pact._tagRestaurant";
                            $stmt = $dbh->prepare($sql); 
                            $stmt->execute();
                            $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="dropdown">
                            <?php Button::render(onClick: "toggleDropdown('dropdownRestaurant')", text: "Tag",type: "pro", submit: false, class: "tag"); ?>                               

                                <div class="dropdown-content" id="dropdownRestaurant">
                                    <?php foreach($tabTag as $index => $tag) { 
                                        Checkbox::render(
                                            class: "checkbox",
                                            id: "tag_restaurant_" . $index . "_" . $tag['nomtag'], // ID unique
                                            name: "tag[]",
                                            value: $tag['nomtag'],
                                            text: $tag['nomtag'],
                                            required: false,
                                            checked: false
                                        );
                                    }?>
                                </div>
                            </div>
                            <br>
                            <div>
                                <label>Carte du Restaurant</label>
                                <?php InsererImage::render("carteRestaurant", "Glissez-déposez vos images ici", 1, false,false,['pdf']);?>
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
                                <input type="checkbox" id="dejeuner" name="typeRepas[]" value="Dejeuner">
                                <label for="dejeuner">Dejeuner</label>
                                <input type="checkbox" id="diner" name="typeRepas[]" value="Diner">
                                <label for="diner">Diner</label>
                                <input type="checkbox" id="snack" name="typeRepas[]" value="Snack">
                                <label for="snack">Snack</label>
                            </div>
                        </div>
                    </div> <!-- End of Restaurant section -->



                </article>
            </section> 
            <div>
                <br>
                <?php Button::render(onClick:"window.location.href = '../listeOffres/listeOffres.php';", text: "Annuler", type: "pro", submit: false, ); ?>
                <?php Button::render(text: "Valider", type: "pro", submit: true); ?>
            </div>

        </form>
        <?php Footer::render(FooterType::Pro); ?>
    </body>
    
    <script src="creerOffre.js"></script>
    </html>