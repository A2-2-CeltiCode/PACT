<?php require_once "components/Input/Input.php"?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">

    <title>Création d'une offre</title>
</head>
<body>
    
    
    <img source="logo.png" alt="Logo">
    <h1>Créez votre Offre</h1>
    <form action="creationOffre.php" method="get" enctype="multipart/form-data">
        <label>Type d'offre</label><br>
        <select name="typeOffre" placeholder="" required>
        <option value="Activite"
        <?php if($_GET["typeOffre"]=="Activite"){ echo 'selected'; } ?>
        >Activite</option>

        <option value="Visite"
        <?php if($_GET["typeOffre"]=="Visite"){ echo 'selected'; } ?>
        >Visite</option>

        <option value="Spectacle"
        <?php if($_GET["typeOffre"]=="Spectacle"){ echo 'selected'; } ?>
        >Spectacle</option>

        <option value="ParcAttraction"
        <?php if($_GET["typeOffre"]=="ParcAttraction"){ echo 'selected'; } ?>
        >Parc d'attraction</option>

        <option value="Restauration"
        <?php if($_GET["typeOffre"]=="Restauration"){ echo 'selected'; } ?>
        >Restauration</option>
        </select><br>
        <?php Input::render(name:"nomOfrre")  ?>
        <button type="submit">Valider</button><br><br>
    </form>
    
    <?php
    if($_GET["typeOffre"]!=null){
    ?>
    <form method="post" action="creationOffre.php" enctype="multipart/form-data">
        <label>Type de forfait</label><br>
        <select name="typeForfait" required>
            <option value="Standard">Standard</option>
            <option value="Premium">Premium</option>
        </select>
        <br><br>

        <label>Nom de l'offre</label><br>
        <input type="text" name="nomOffre" autocomplete="off" required><br><br>

        <label>information de l'offre</label><br>
        <input type="text" name="ville" placeholder="Ville" required><br>
        <input type="number" name="codePostal" placeholder="code Postal" required><br>
        <input type="text" name="adressePostale" placeholder="Rue" required><br>
        
        <label>Numéro de téléphone</label><br>
        <input type="tel" name="numtel" placeholder="ex: 06 01 02 03 04" required><br>

        <label>Site Web</label><br>
        <input type="text" name="siteWeb" placeholder="ex: https://monsite.com" required><br>

        <label>Description de l'offre</label><br>
        <input type="text" name="descriptionOffre" required><br><br>

        <label>description Detaille</label><br>
        <input type="text" name="descriptionDetaille" required><br><br>

        <label>Tags</label><br>
        <input type="text" name="tags" required><br><br>

        <label>Photo</label><br>
        <input type="file" name="photo[]" multiple="true" required><br><br>
        <img src="" alt="">
        

        <label>Type de promotion de l'offre</label><br>
        <select name="typePromotion" required>
            <option value="Aucune">Aucune</option>
            <option value="Relief">Rekief</option>
            <option value="aLaUne">A la une</option>
        </select><br><br>


        <?php if($_GET["typeOffre"]=="Activite"){?>
            <label>Capacité d'accueil</label><br>
            <input type="number" name="capacite" required><br><br>

            <labe>Durée de l'activité</label><br>
            <input type="number" name="duree" required><br><br>            


        <?php }else if($_GET["typeOffre"]=="Visite"){?>
            <label>Capacité d'accueil</label><br>
            <input type="number" name="capacite" required><br><br>

            <label>Durée de la visite</label><br>
            <input type="number" name="duree" required><br><br>

            <label>Visite guidée</label><br>
            <input type="radio" id="true" name="guide" value="true">
            <label for="oui">Oui</label><br>
            <input type="radio" id="false" name="guide" value="false">
            <label for="non">Non</abel><br>
            

        <?php }else if($_GET["typeOffre"]=="Spectacle"){?>
            <label>Capacité d'accueil</label><br>
            <input type="number" name="capacite" required><br><br>

            <labe>Durée du spectacle</label><br>
            <input type="number" name="duree" required><br><br>
            

        <?php }else if($_GET["typeOffre"]=="ParcAttraction"){?>
            
            <labe>Nombre d'attraction</label><br>
            <input type="number" name="duree" required><br><br>

            <labe>Age minimum</label><br>
            <input type="number" name="duree" required><br><br>
        <?php }else if($_GET["typeOffre"]=="Restauration"){?>
            
        <?php } ?>
        <button type="submit">Valider</button>
        <a ><button href="http://localhost:8888/creationOffre.php" >Annuler</button><a>

    </form>

    <?php } ?>
    
</body>
</html>

