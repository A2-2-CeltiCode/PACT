<!DOCTYPE html>
<html>
<head>
    <?php require "components/Input/Input.php"?>
    <?php require "components/Button/Button.php"?>
    <?php require "components/Input/Textarea.php"?>
    <?php require "components/Input/Select.php"?>
    <?php require "components/InsererImage/InsererImage.php"?>
    <?php require "components/Checkbox/Checkbox.php"?> 
    <?php require "connect_params.php"?>
    <?php $dbh = new PDO("$driver:host=$server;dbname=$dbname", 
            $user, $pass);
    ?>  
    <title>Création d'une offre</title>

    <script src="creationOffre.js"></script>

    <link rel="stylesheet" href="./creationOffre.css">
</head>

<body>

    
    
    <form class="info-display" id="myForm", method="post" action="confimationCreationOffre.php" enctype="multipart/form-data">
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

                <div>
                    <?php
                    
                    $sql = "SELECT nomtag FROM pact._tag";
                    $stmt = $dbh->prepare($sql); 
                    $stmt->execute();

                    $tabTag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <label>Tag</label>
                    <div class="dropdown">
                        
                    <button onclick="toggleDropdown()">Tag</button>
                    <div class="dropdown-content" id="myDropdown">
                        <?php foreach($tabTag as $tag){ 
                                Checkbox::render(
                                    class: "checkbox",
                                    id: $tag['nomtag'],
                                    name: "tag[]",
                                    value: $tag['nomtag'],
                                    text: $tag['nomtag'],
                                    required: false,
                                    checked: false
                                );
                            }?>
                    </div>
                </div>   
                    
                </div>


                
                <div>
                    <label>Type de forfait*</label>
                    <?php 
                        Select::render(
                            name: "typeForfait", 
                            required: true, 
                            options: [
                                "Standard" => "Standard",
                                "Premium" => "Premium"
                            ]
                        );
                        ?>
                </div>
                
                <div>
                    <label>Type de promotion de l'offre*</label>
                    <?php 
                        Select::render(
                            name: "typePromotion", 
                            required: true, 
                            options: [
                                "Aucune" => "Aucune",
                                "En relief" => "En relief",
                                "A la une" => "A la une"
                            ]
                        );
                    ?>
                </div>
                
                <div>
                    <label>Photo*</label>   
                    <?php InsererImage::render("monDropZone[]", "Glissez-déposez vos images ici", 5,true,true);?>
                </div>


                <div>
                    <label>Type d'offre*</label>
                    <?php 
                        Select::render(
                            name: "typeOffre", 
                            required: true, 
                            options: [
                                "" => "Sélectionner un type d'offre",
                                "Activite" => "Activité",
                                "Visite" => "Visite",
                                "Spectacle" => "Spectacle",
                                "ParcAttraction" => "Parc d'attraction",
                                "Restauration" => "Restauration"
                            ]
                        );
                    ?>
                </div>
                <!-- Section Activité -->
                <div id="Activite" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix1", type:"number") ?>
                    <label>Âge minimum</label>
                    <?php Input::render(name: "ageMinimum1", type: "number") ?>
                    <label>Prestation </label>
                    <?php Input::render(name: "prestation", type: "text") ?>
                    <label>Durée de l'activité</label>
                    <?php Input::render(name: "duree1", type: "number") ?>
                </div>

                <!-- Section Visite -->
                <div id="Visite" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix2", type:"number") ?>
                    
                    <label>Durée de la visite</label>
                    <?php Input::render(name: "duree2", type: "number") ?>
                    
                    <label>Visite guidée</label>
                    <input type="radio" id="oui" name="guided" value="oui" onclick="toggleLangue(true)">
                    <label for="oui">Oui</label>

                    <input type="radio" id="non" name="guided" value="non" onclick="toggleLangue(false)">
                    <label for="non">Non</label>

                    <div id="langue" style="display: none;">
                        <?php
                            Checkbox::render(
                                class: "checkbox",
                                id: "francais",
                                name: "langue[]",
                                value: "Français",
                                text: "Français",
                                required: false,
                                checked: false
                            );
                            CheckBox::render(
                                class: "checkbox",
                                id: "anglais",
                                name: "langue[]",
                                value: "Anglais",
                                text: "Anglais",
                                required: false,
                                checked: false
                            );
                            CheckBox::render(
                                class: "checkbox",
                                id: "espagnol",
                                name: "langue[]",
                                value: "Espagnol",
                                text: "Espagnol",
                                required: false,
                                checked: false
                            );
                        ?>
                    </div>
                </div>

                <!-- Section Spectacle -->
                <div id="Spectacle" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix3", type:"number") ?>
                    <label>Capacité d'accueil</label>
                    <?php Input::render(name: "capacite", type: "number");?>
                    <label>Durée du spectacle</label>
                    <?php Input::render(name: "duree3", type: "number");?>
                </div>

                <!-- Section Parc d'Attraction -->
                <div id="ParcAttraction" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix4", type:"number") ?>
                    <label>Nombre d'attractions</label>
                    <?php Input::render(name: "nombreAttractions", type: "number");?>
                    <div>
                        <label>Plan du Parc</label>
                        <?php InsererImage::render("planParc", "Glissez-déposez vos images ici",1, false);?>
                    </div>
                    <label>Âge minimum</label>
                    <?php Input::render(name: "ageMinimum2", type: "number") ?>
                </div>

                <!-- Section Restauration-->
                <div id="Restauration" class="section" style="display:none;">
                    <div>
                        <label>Carte du Restaurant</label>
                        <?php InsererImage::render("carteRestaurant", "Glissez-déposez vos images ici",1, false);?>
                    </div>
                    <div>
                        <label>Gamme du restaurant</label>
                        <?php 
                            Select::render(
                                name: "gammeRestaurant", 
                                options: [
                                    "leger" => "€ (-25€)",
                                    "moyen" => "€€ (25-40€)",
                                    "fort" => "€€€ (+40€)"
                                ]
                            );
                        ?>
                    </div>
                    <div>
                        <label>Type de repas</label>
                        <br>
                        <?php
                        // Afficher les cases à cocher avec le texte correspondant
                        Checkbox::render(
                            class: "checkbox",
                            id: "petitDej1",
                            name: "repas[]",
                            value: "petit_dejeuner",
                            text: "Petit Déjeuner",
                            required: false,
                            checked: false
                        );

                        Checkbox::render(
                            class: "checkbox",
                            id: "dejeuner1",
                            name: "repas[]",
                            value: "dejeuner",
                            text: "Déjeuner",
                            required: false,
                            checked: false
                        );

                        Checkbox::render(
                            class: "checkbox",
                            id: "diner1",
                            name: "repas[]",
                            value: "diner",
                            text: "Dîner",
                            required: false,
                            checked: false
                        );
                        ?>
                    </div>


        
                    
                </div>
            </article>
        </section> 
        <div>
            <br>
            <?php Button::render(text: "Annuler", type: ButtonType::Pro, submit: false); ?>
            <?php Button::render(text: "Valider", type: ButtonType::Pro, submit: true); ?>
        </div>

    </form>
    <script src="creationOffre.js"></script>
</body>

</html>
