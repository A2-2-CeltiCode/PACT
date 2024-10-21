<!DOCTYPE html>
<html>
<head>
    <?php require "components/Input/Input.php"?>
    <?php require "components/Button/Button.php"?>
    <?php require "components/Input/Textarea.php"?>
    <?php require "components/Input/Select.php"?>
    <title>Création d'une offre</title>

    <script src="creationOffre.js"></script>

    <link rel="stylesheet" href="./creationOffre.css">
</head>
<body>
          
    <div>
        <img src="logo.png" alt="Logo">
        <h1>Créez votre Offre</h1>
    </div>
    
    
    <form method="post" action="creationOffre.php" enctype="multipart/form-data">
        <section>
            <article>
                <label>Nom de l'offre</label>
                <?php Input::render(name:"offerName", type:"text", required:"true") ?>

                <label>information de l'offre</label>
                <?php Input::render(name:"city", type:"text", required:"true", placeholder:'Ville') ?>
                <?php Input::render(name:"postalCode", type:"number", required:'true', placeholder:"Code Postale") ?>
                <?php Input::render(name:"postalAddress", type:"text", required:"true", placeholder:'Adresse Postale') ?>
                
                <label>Numéro de téléphone</label>
                <?php Input::render(name:"phoneNumber", type:"number", required:"true", placeholder:'ex : 06 10 12 01 24') ?>

                <label>Site Web</label>
                <?php Input::render(name:"website", type:"text", required:"true", placeholder:'ex : www.siteWeb.com') ?>

                <label>Description de l'offre</label>
                <?php Textarea::render(name:"offerDescription", required:"true", rows:7) ?>
            </article>
            <article>
                <label>description Detaille</label>
                <?php Textarea::render(name:"detailedDescription", required:"true", rows:7) ?>
            
                <label>Tags</label>
                <?php Input::render(name:"tags", type:"text", required:"true") ?>
                
                <label>Type de forfait</label>
                <?php 
                    Select::render(
                        name: "packageType", 
                        required: true, 
                        options: [
                            "Standard" => "Standard",
                            "Premium" => "Premium"
                        ]
                    );
                ?>

                <label>Photo</label>   
                <div class="drop-zone" id="drop-zone">Déposez une image ou cliquez ici</div>
                <input type="file" id="fileInput" accept="image/*" multiple style="display: none;" required>

                <div id="successMessage" style="display:none; color: green;">Image ajoutée avec succès !</div>

                <label>Type de promotion de l'offre</label>
                <?php 
                    Select::render(
                        name: "promotionType", 
                        required: true, 
                        options: [
                            "Aucune" => "Aucune",
                            "Relief" => "Relief",
                            "aLaUne" => "A la une"
                        ]
                    );
                ?>
            
                <label>Type d'offre</label>
                <?php 
                    Select::render(
                        name: "offerType", 
                        required: true, 
                        options: [
                            "" => "Sélectionner un type d'offre",
                            "Activite" => "Activite",
                            "Visite" => "Visite",
                            "Spectacle" => "Spectacle",
                            "ParcAttraction" => "Parc d'attraction",
                            "Restauration" => "Restauration"
                        ]
                    );
                ?>

                <!-- Section Activité -->
                <div id="Activite" class="section" style="display:none;">
                    <label>Capacité d'accueil</label>
                    <?php Input::render(name: "capacity", type: "number", required:"true") ?>
                    <label>Durée de l'activité</label>
                    <?php Input::render(name: "duration", type: "number", required:"true") ?>
                </div>

                <!-- Section Visite -->
                <div id="Visite" class="section" style="display:none;">
                    <label>Capacité d'accueil</label>
                    <?php Input::render(name: "capacity", type: "number", required:"true") ?>
                    <label>Durée de la visite</label>
                    <?php Input::render(name: "duration", type: "number", required:"true") ?>
                    <label>Visite guidée</label>
                    <input type="radio" id="oui" name="guided" value="oui" required>
                    <label for="oui">Oui</label>
                    <input type="radio" id="non" name="guided" value="non" required>
                    <label for="non">Non</label>
                </div>

                <!-- Section Spectacle -->
                <div id="Spectacle" class="section" style="display:none;">
                    <label>Capacité d'accueil</label>
                    <?php Input::render(name: "capacity", type: "number", required:"true") ?>
                    <label>Durée du spectacle</label>
                    <?php Input::render(name: "duration", type: "number", required:"true") ?>
                </div>

                <!-- Section Parc d'Attraction -->
                <div id="ParcAttraction" class="section" style="display:none;">
                    <label>Nombre d'attractions</label>
                    <?php Input::render(name: "numAttractions", type: "number", required:"true") ?>
                    <label>Âge minimum</label>
                    <?php Input::render(name: "minAge", type: "number", required:"true") ?>
                </div>

                <!-- Section Restauration (vide dans cet exemple) -->
                <div id="Restauration" class="section" style="display:none;">
                    <!-- VIDEEEE -->
                </div>
            </article>
        </section> 
        <div>
            <?php Button::render(text: "Annuler", type: ButtonType::Pro, submit: false); ?>
            <?php Button::render(text: "Valider", type: ButtonType::Pro, submit: true); ?>
        </div>
    </form>
</body>
</html>
