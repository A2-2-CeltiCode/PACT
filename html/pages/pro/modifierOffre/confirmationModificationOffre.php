<?php
//header("Location: ../listeOffres/listeOffres.php");
session_start();
error_reporting(E_ALL ^ E_WARNING);

require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

function insererPrix($dbh, $prix) {

}

function insererDuree($dbh, $tempsEnMinutes) {
}

function deleteOldTags($dbh, $idOffre, $typeOffre) {
    $tableName = '';
    switch ($typeOffre) {
        case "Activite":
            $tableName = "pact._possedeActivite";
            break;
        case "Spectacle":
            $tableName = "pact._possedeSpectacle";
            break;
        case "parc":
            $tableName = "pact._possedeParcAttractions";
            break;
        case "Visite":
            $tableName = "pact._possedeVisite";
            break;
        case "Restauration":
            $tableName = "pact._possedeRestaurant";
            break;
    }

    if ($tableName) {
        $stmt = $dbh->prepare("DELETE FROM $tableName WHERE idOffre = :idOffre");
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function deleteOldImages($dbh, $idOffre, $typeOffre) {
    
    $tableName = '';
    switch ($typeOffre) {
        case "Activite":
            $tableName = "pact._Activite";
            break;
        case "Spectacle":
            $tableName = "pact._Spectacle";
            break;
        case "parc":
            $tableName = "pact._ParcAttractions";
            $stmt = $dbh->prepare("SELECT carteparc FROM $tableName WHERE idOffre = :idOffre");
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->execute();
            $idImageTrop = $stmt->fetch(PDO::FETCH_COLUMN);
            break;
        case "Visite":
            $tableName = "pact._Visite";
            break;
        case "Restauration":
            $tableName = "pact._Restaurant";
            $stmt = $dbh->prepare("SELECT idimage FROM $tableName WHERE idOffre = :idOffre");
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->execute();
            $idImageTrop = $stmt->fetch(PDO::FETCH_COLUMN);
            break;
    }
    
    

    $stmt = $dbh->prepare("DELETE FROM pact._representeOffre WHERE idOffre = :idOffre AND idimage != :idImage");
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':idImage', $idImageTrop, PDO::PARAM_INT);
    $stmt->execute();
}


// Récupération des données du formulaire

$nomOffre = $_POST['nomOffre'] ?? null;
$ville = $_POST['ville'] ?? null;
$codePostal = $_POST['codePostal'] ?? null;
$adressePostale = $_POST['adressePostale'] ?? null;
$numeroTelephone = $_POST['numeroTelephone'] ?? null;
$siteWeb = $_POST['siteWeb'] ?? null;
$descriptionOffre = $_POST['descriptionOffre'] ?? null;
$descriptionDetaillee = $_POST['descriptionDetaillee'] ?? null;
$typeForfait = $_POST['typeForfait'] ?? null;
$typePromotion = $_POST['typePromotion'] ?? null;
$typeOffre = $_POST['typeOffre'] ?? 1;
$capacite = $_POST['capacite'] ?? null;
$guidee = $_POST['guidee'] ?? null;
$nombreAttractions = $_POST['nombreAttractions'] ?? null;
    
$prix1 = $_POST['prix1'] ?? null;
$prix2 = $_POST['prix2'] ?? null;
$prix3 = $_POST['prix3'] ?? null;
$prix4 = $_POST['prix4'] ?? null;

$duree1 = $_POST['duree1'] ?? null;
$duree2 = $_POST['duree2'] ?? null;
$duree3 = $_POST['duree3'] ?? null;
$duree4 = $_POST['duree4'] ?? null;

$ageMinimum1 = $_POST['ageMinimum1'] ?? null;
$ageMinimum2 = $_POST['ageMinimum2'] ?? null;
    
$prestation = $_POST['prestation'] ?? null; 
$gammeRestaurant = $_POST['gammeRestaurant'] ?? null;
$tag=$_POST['tag'] ?? null;
$carteRestaurant = $_POST['carteRestaurant'] ?? null;
$planParc = $_POST['planParc'] ?? null;
$langue = $_POST['langue'] ?? null;
$enLigne = $_POST['estEnLigne'] ?? null;
$options = $_POST['options'] ?? null;
$ancienLigne= $_POST['ancienLigne'] ?? null;

if($ancienLigne==="1"){
    $ancienLigne = "true";
}else{
    $ancienLigne = "false";
}

if($options==="true"){
    $typePromotion = "Aucune";
}
print_r($typeOffre);
$idOffre = $_POST['idOffre'];
// Insertion dans la BDD
$sql = "SELECT idadresse FROM pact._offre WHERE idOffre = :idOffre";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->execute();
$idAdresse = $stmt->fetch(PDO::FETCH_ASSOC);


// Creation adresse
$stmt = $dbh->prepare(
    "UPDATE pact._adresse SET codePostal = :codePostal, ville = :ville, rue = :rue, numTel = :numTel 
    WHERE idAdresse = :idAdresse"
);
$stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':rue', $adressePostale, $adressePostale !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':idAdresse', $idAdresse["idadresse"], PDO::PARAM_INT);
$stmt->execute();

// Creation offre
echo $enLigne;
$stmt = $dbh->prepare(
    "UPDATE pact._offre SET titre = :titre, description = :description, descriptionDetaillee = :descriptionDetaillee, 
    siteInternet = :siteInternet, nomOption = :nomOption, estEnLigne = :estEnLigne 
    WHERE idOffre = :idOffre" // Assuming you have an idOffre
);
$stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT); // Assuming $idOffre is defined
$stmt->execute();

// Création et insertion image
deleteOldImages($dbh, $idOffre,$typeOffre);

foreach ($_FILES['monDropZone']['name'] as $key => $val) {
    $nomImage = $_FILES['monDropZone']['name'][$key];
    $tmp_name = $_FILES['monDropZone']['tmp_name'][$key];
    $location = '../../ressources/' . $idOffre . '/images' . '/';

    $extension = pathinfo($nomImage, PATHINFO_EXTENSION);

    $nouveauNomImage = uniqid() . '.' . $extension;

    if (!file_exists($location)) {
        mkdir($location, 0777, true);
    }

    move_uploaded_file($tmp_name, $location . $nouveauNomImage);
    $stmt = $dbh->prepare(
        "INSERT INTO pact._image(idOffre, nomImage) 
        VALUES(:idOffre, :nomImage)"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
    $stmt->execute();
}

// Type d'offre : Activité
if ($typeOffre === "Activite") {
    insererPrix($dbh, $prix1);

    $stmt = $dbh->prepare(
        "UPDATE pact._activite SET 
            nomCategorie = :nomCategorie, 
            valPrix = :valPrix, 
            ageMin = :ageMin 
        WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
    $stmt->bindValue(':valPrix', $prix1, PDO::PARAM_STR);
    $stmt->bindValue(':ageMin', $ageMinimum1, PDO::PARAM_INT);
    $stmt->execute();

    
    deleteOldTags($dbh, $idOffre, $typeOffre);
    foreach ($tag as $val) {
        foreach($val as $lebon){
        $stmt = $dbh->prepare(
            "INSERT INTO pact._possedeActivite(idOffre, nomTag) 
            VALUES(:idOffre, :nomTag)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomTag', $lebon, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
}

// Type d'offre : Spectacle
if ($typeOffre === "Spectacle") {
    insererPrix($dbh, $prix2);
    insererDuree($dbh, $duree2);

    $stmt = $dbh->prepare(
        "UPDATE pact._spectacle SET 
            nomCategorie = :nomCategorie, 
            tempsEnMinutes = :tempsEnMinutes, 
            valPrix = :valPrix, 
            capacite = :capacite 
        WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
    $stmt->bindValue(':tempsEnMinutes', $duree2, PDO::PARAM_INT);
    $stmt->bindValue(':valPrix', $prix2, PDO::PARAM_STR);
    $stmt->bindValue(':capacite', $capacite, PDO::PARAM_INT);
    $stmt->execute();

    
    deleteOldTags($dbh, $idOffre, $typeOffre);
    foreach ($tag as $val) {
        foreach($val as $lebon){
        $stmt = $dbh->prepare(
            "INSERT INTO pact._possedeSpectacle(idOffre, nomTag) 
            VALUES(:idOffre, :nomTag)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomTag', $lebon, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
}

// Type d'offre : Parc d'Attraction
if ($typeOffre === "parc") {
    insererPrix($dbh, $prix3);

    foreach ($_FILES['planParc']['name'] as $key => $val) {
        $nomImage = $_FILES['planParc']['name'][$key];
        $tmp_name = $_FILES['planParc']['tmp_name'][$key];
        $location = '../../ressources/' . $idOffre . '/carte' . '/';
        $extension = pathinfo($nomImage, PATHINFO_EXTENSION);
        $nouveauNomImage = uniqid() . '.pdf';

        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }

        move_uploaded_file($tmp_name, $location . $nouveauNomImage);
            $stmt = $dbh->prepare(
            "INSERT INTO pact._image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
        $stmt->execute();
    
        $carte = $dbh->lastInsertId();
    }

    $stmt = $dbh->prepare(
        "UPDATE pact._parcAttractions SET 
            nomCategorie = :nomCategorie, 
            valPrix = :valPrix, 
            carteparc = :idImage, 
            nbAttractions = :nbAttractions, 
            ageMin = :ageMin 
        WHERE idOffre = :idOffre"
    );

    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomCategorie', "Parc d'attractions", PDO::PARAM_STR);
    $stmt->bindValue(':valPrix', $prix3, PDO::PARAM_STR);
    $stmt->bindValue(':idImage', $carte, PDO::PARAM_STR);
    $stmt->bindValue(':nbAttractions', $nombreAttractions, PDO::PARAM_INT);
    $stmt->bindValue(':ageMin', $ageMinimum2, PDO::PARAM_INT);
    $stmt->execute();

    deleteOldTags($dbh, $idOffre, $typeOffre);

    foreach ($tag as $val) {
        foreach($val as $lebon){
        $stmt = $dbh->prepare(
            "INSERT INTO pact._possedeParcAttractions(idOffre, nomTag) 
            VALUES(:idOffre, :nomTag)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomTag', $lebon, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
}
// Type d'offre : Visite
if ($typeOffre === "Visite") {
    print_r($tag);
    insererPrix($dbh, $prix4);
    insererDuree($dbh, $duree4);
    $stmt = $dbh->prepare(
        "UPDATE pact._visite SET 
            nomCategorie = :nomCategorie, 
            tempsEnMinutes = :tempsEnMinutes, 
            valPrix = :valPrix, 
            estGuidee = :estGuidee 
        WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
    $stmt->bindValue(':tempsEnMinutes', $duree4, PDO::PARAM_INT);
    $stmt->bindValue(':valPrix', $prix4, PDO::PARAM_STR);
    $stmt->bindValue(':estGuidee', $guidee, PDO::PARAM_BOOL);
    $stmt->execute();

        $stmt = $dbh->prepare("DELETE FROM pact._possedeVisite WHERE idOffre = :idOffre");
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    
    foreach ($tag as $val) {
        foreach($val as $lebon){
        $stmt = $dbh->prepare(
            "INSERT INTO pact._possedeVisite(idOffre, nomTag) 
            VALUES(:idOffre, :nomTag)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomTag', $lebon, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
}

// Type d'offre : Restauration
if ($typeOffre === "Restauration") {
    foreach ($_FILES['carteRestaurant']['name'] as $key => $val) {
        $nomImage = $_FILES['carteRestaurant']['name'][$key];
        $tmp_name = $_FILES['carteRestaurant']['tmp_name'][$key];
        $location = '../../ressources/' . $idOffre . '/carte' . '/';
    
        $extension = pathinfo($nomImage, PATHINFO_EXTENSION);
    
        $nouveauNomImage = uniqid() . '.' . $extension;
    
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
    
        move_uploaded_file($tmp_name, $location . $nouveauNomImage);
    
        $stmt = $dbh->prepare(
            "INSERT INTO pact._image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
        $stmt->execute();
    
        $carteRestaurant = $dbh->lastInsertId();
    }
    
    $stmt = $dbh->prepare(
        "UPDATE pact._restaurant SET 
            nomCategorie = :nomCategorie, 
            gammeRestaurant = :gammeRestaurant, 
            idImage = :idImage 
        WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
    $stmt->bindValue(':gammeRestaurant', $gammeRestaurant, PDO::PARAM_STR);
    $stmt->bindValue(':idImage', $carteRestaurant, PDO::PARAM_INT);
    $stmt->execute();

    deleteOldTags($dbh, $idOffre, $typeOffre);

    foreach ($tag as $val) {
        foreach($val as $lebon){
        $stmt = $dbh->prepare(
            "INSERT INTO pact._possedeRestaurant(idOffre, nomTag) 
            VALUES(:idOffre, :nomTag)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomTag', $lebon, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
}
if ($options == true) {
    $stmt = $dbh->prepare(
        "SELECT debutOption FROM pact._annulationOption WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    $debutOption = $stmt->fetch(PDO::FETCH_COLUMN);
}


if ($options == true && $debutOption>= date("Y-m-d")) {
    $stmt = $dbh->prepare(
        "UPDATE pact._annulationOption
        SET estAnnulee = :estAnnulee
        WHERE idOffre = :idOffre"
    );
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':estAnnulee', true, PDO::PARAM_BOOL);
    $stmt->execute();
}
if($ancienLigne!=$enLigne){
    if($enLigne=="false") {
        $stmt = $dbh->prepare(
            "UPDATE pact._historiqueenligne
            SET jourFin = :jourFin
            WHERE idOffre = :idoffre AND jourDebut = (
                SELECT MAX(jourDebut) FROM pact._historiqueenligne WHERE idoffre = :idoffre
            )"
        );
        $stmt->bindValue(':idoffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':jourFin', date("Y-m-d"), PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $stmt = $dbh->prepare("
        SELECT jourfin FROM pact._historiqueenligne WHERE idoffre = :idoffre ORDER BY jourfin DESC LIMIT 1");
        $stmt->bindValue(':idoffre', $idOffre, PDO::PARAM_INT);
        $stmt->execute();
        $jourFin = $stmt->fetch(PDO::FETCH_COLUMN);
        
        if($jourFin !== date("Y-m-d")) {
            $stmt = $dbh->prepare(
                "INSERT INTO pact._historiqueenligne(idOffre, jourDebut)
                VALUES(:idOffre, :jourDebut)"
                );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':jourDebut', date('Y-m-d'), PDO::PARAM_STR);
            $stmt->execute();
        }else{
            $stmt = $dbh->prepare(
                "UPDATE pact._historiqueenligne
                SET jourFin = NULL
                WHERE idOffre = :idoffre AND jourDebut = (
                    SELECT MAX(jourDebut) FROM pact._historiqueenligne WHERE idoffre = :idoffre
                )"
            );
            $stmt->bindValue(':idoffre', $idOffre, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}