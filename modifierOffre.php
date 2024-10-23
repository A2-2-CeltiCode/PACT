<?php
$idOffre=$_POST["idOffre"];






if($offers["typeOffre"]=== "Activite"){
    $sql = "SELECT * FROM vue_activite WHERE idOffre = $idOffre";
    $stmt = $pdo->query($sql);
    $vueOffre = $stmt->fetchAll();
    $prix = $vueOffre["valPrix"];
    $duree = $vueOffre["tempsEnMinutes"];
    $ageMinimum = $vueOffre["ageMin"];
    $prestation = $vueOffre["prestation"];

}else if($offers["typeOffre"]==="Spectacle"){
    $sql = "SELECT * FROM vue_spectacle WHERE idOffre = $idOffre";
    $stmt = $pdo->query($sql);
    $vueOffre = $stmt->fetchAll();
    $prix = $vueOffre["valPrix"];
    $duree = $vueOffre["tempsEnMinutes"];
    $capacite = $vueOffre["capacite"];

}else if($offers["typeOffre"]==="ParcAttraction"){
    $sql = "SELECT * FROM vue_parc_attractions WHERE idOffre = $idOffre";
    $stmt = $pdo->query($sql);
    $vueOffre = $stmt->fetchAll();
    $prix = $vueOffre["valPrix"];
    $ageMinimum = $vueOffre["ageMin"];
    $nombreAttractions = $vueOffre["nbAttractions"]; 
    $planParc = $vueOffre["idImage"];


}else if($offers["typeOffre"]==="Visite"){
    $sql = "SELECT * FROM vue_visite WHERE idOffre = $idOffre";
    $stmt = $pdo->query($sql);
    $vueOffre = $stmt->fetchAll();
    $prix = $vueOffre["valPrix"];
    $guidee = $vueOffre["estGuidee"];
    $duree = $vueOffre["tempsEnMinutes"];


}else if($offers["typeOffre"]==="Restauration"){
    $sql = "SELECT * FROM vue_restaurant WHERE idOffre = $idOffre";
    $stmt = $pdo->query($sql);
    $vueOffre = $stmt->fetchAll();
    $carteRestaurant=$vueOffre["nomImage"];
    $gammeRestaurant = $vueOffre["nomGamme"];


}
$nomOffre = $vueOffre["titre"];
$ville = $vueOffre["ville"];
$codePostal = $vueOffre["codePostal"];
$nomRue=$vueOffre["nomRue"];
$numRue=$vueOffre["numRue"];
$numeroTelephone = $vueOffre["numTel"];
$siteWeb = $vueOffre["siteInternet"];
$descriptionOffre = $vueOffre["description"];
$descriptionDetaillee = $vueOffre["descriptionDetaillee"];
$typeForfait = $vueOffre["nomForfait"];
$typePromotion = $vueOffre["nomOption"];

?>

<!DOCTYPE html>
<html>
<head>
    <?php require "components/Input/Input.php"?>
    <?php require "components/Button/Button.php"?>
    <?php require "components/Input/Textarea.php"?>
    <?php require "components/Input/Select.php"?>
    <?php require "components/InsererImage/InsererImage.php"?>
    <?php require "components/Checkbox/Checkbox.php"?>
    <title>Création d'une offre</title>

    <script src="creationOffre.js"></script>

    <link rel="stylesheet" href="./creationOffre.css">
</head>
<header class="entete">
    <div class="entete-logo">
        <!-- Logo de l'entreprise avec texte -->
        <img src="./assets/icon/logo.svg" alt="Logo PACT">
        <span class="texte-logo">PACT</span>
    </div>
    <div class="entete-recherche">
        <!-- Composant d'entrée pour la recherche -->
        <?php Input::render(type: 'text', placeholder: 'Entrez une localisation...') ?>
    </div>
    <nav class="entete-navigation">
        <!-- Liens de navigation pour la page d'accueil et les offres -->
        <a href="index.php">Accueil</a>
        <a href="offre.php">Offres</a>
    </nav>
    <div class="entete-langue">
        <!-- Sélecteur de langue avec icône pour changer de langue -->
        <img src="assets/icon/logofr.svg" alt="Français">
        <select name="langue" id="langue">
            <option value="fr">Français</option>
            <option value="en">English</option>
        </select>
    </div>
    <div class="entete-connexion">
        <!-- Lien vers la page de connexion et d'inscription avec un bouton -->
        <a href="connexionComptePro.php" class="lien-bouton"><button>S'inscrire / Se Connecter</button></a>
    </div>
</header>
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
                                "Relief" => "Relief",
                                "aLaUne" => "À la une"
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
                    <?php Input::render(name:"prix", type:"number") ?>
                    <label>Âge minimum</label>
                    <?php Input::render(name: "ageMinimum", type: "number") ?>
                    <label>Prestation </label>
                    <?php Input::render(name: "prestation", type: "text") ?>
                    <label>Durée de l'activité</label>
                    <?php Input::render(name: "duree", type: "number") ?>
                </div>

                <!-- Section Visite -->
                <div id="Visite" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix", type:"number") ?>
                    <label>Durée de la visite</label>
                    <?php Input::render(name: "duree", type: "number") ?>
                    <label>Visite guidée</label>
                    <input type="radio" id="oui" name="guided" value="oui">
                    <label for="oui">Oui</label>
                    <input type="radio" id="non" name="guided" value="non">
                    <label for="non">Non</label>
                </div>

                <!-- Section Spectacle -->
                <div id="Spectacle" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix", type:"number") ?>
                    <label>Capacité d'accueil</label>
                    <?php Input::render(name: "capacite", type: "number");?>
                    <label>Durée du spectacle</label>
                    <?php Input::render(name: "duree", type: "number");?>
                </div>

                <!-- Section Parc d'Attraction -->
                <div id="ParcAttraction" class="section" style="display:none;">
                    <label>Prix*</label>
                    <?php Input::render(name:"prix", type:"number") ?>
                    <label>Nombre d'attractions</label>
                    <?php Input::render(name: "nombreAttractions", type: "number");?>
                    <div>
                        <label>Plan du Parc</label>
                        <?php InsererImage::render("planParc", "Glissez-déposez vos images ici",1, false);?>
                    </div>
                    <label>Âge minimum</label>
                    <?php Input::render(name: "ageMinimum", type: "number") ?>
                </div>

                <!-- Section Restauration-->
                <div id="Restauration" class="section" style="display:none;">
                    <div>
                        <label>Prix*</label>
                        <?php Input::render(name:"prix", type:"number") ?>
                    </div>
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
<footer>
        <!-- Bouton pour devenir membre -->
        <a href="creationComptePro.php" class="lien-bouton"><button>DEVENIR MEMBRE</button></a>
        <div class="liens-importants">
            <!-- Liens vers des pages légales et informatives -->
            <a href="mentions.php">Mentions Légales</a>
            <a href="quiSommeNous.php">Qui sommes nous ?</a>
            <a href="condition.php">Conditions Générales</a>
        </div>
        <div class="icones-reseaux-sociaux">
            <!-- Icônes de réseau<div id="repasContainer">
    <label>Repas*</label>
    <div class="repas-input">
        <?php Input::render(name:"repas[]", type:"text", placeholder:'Nom du repas') ?>
        <button type="button" onclick="removeRepas(this)">Supprimer</button>
    </div>
</div>
<button type="button" onclick="addRepas()">Ajouter un repas</button>x sociaux : Facebook, Twitter, Instagram -->
            <a href="#"><img src="assets/icon/facebook.svg" alt="Icon facebook" style="vertical-align: middle;"></a>
            <a href="https://x.com/TripEnArvorPACT"><img src="assets/icon/twitter.svg" alt="Icon X" style="vertical-align: middle;"></a>
            <a href="https://www.instagram.com/pactlannion/"><img src="assets/icon/instagram.svg" alt="Icon instagram" style="vertical-align: middle;"></i></a>
        </div>
        <!-- Texte de copyright -->
        <p>© 2024 PACT, Inc.</p>
        <!-- Lien pour remonter en haut de la page avec une icône -->
        <a href="#entete" class="remonter-page"><i class="fas fa-arrow-up"></i></a>
    </footer>
</html>
