<?php
// Récupération des données du formulaire
$nomOffre = $_POST['nomOffre'] ?? null;
$ville = $_POST['ville'] ?? null;
$codePostal = $_POST['codePostal'] ?? null;
$adressePostale = $_POST['adressePostale'] ?? null;
$numeroTelephone = $_POST['numeroTelephone'] ?? null;
$siteWeb = $_POST['siteWeb'] ?? null;
$descriptionOffre = $_POST['descriptionOffre'] ?? null;
$descriptionDetaillee = $_POST['descriptionDetaillee'] ?? null;
$etiquettes = $_POST['etiquettes'] ?? null;
$typeForfait = $_POST['typeForfait'] ?? null;
$typePromotion = $_POST['typePromotion'] ?? null;
$typeOffre = $_POST['typeOffre'] ?? null;
//type Offfre variable
$capacite = $_POST['capacite'] ?? null;
$duree = $_POST['duree'] ?? null;
$guidee = $_POST['guidee'] ?? null;
$nombreAttractions = $_POST['nombreAttractions'] ?? null;
$ageMinimum = $_POST['ageMinimum'] ?? null;
$idCompte=$_SESSION['idCompte'] ?? null;
$adressePostale = $_POST['adressePostale'] ?? null;
$prix = $_POST['prix'] ?? null;
$prestation = $_POST['prestation'] ?? null; 
$gammeRestaurant = $_POST['gammeRestaurant'] ?? null;
$enLigne=true;

if ($adressePostale) {
    // Expression régulière pour capturer les chiffres au début
    preg_match('/^(\d+)\s*(.*)$/', $adressePostale, $matches);
    $numRue = $matches[1]; // La partie des chiffres
    $nomRue = $matches[2];    // Le reste de l'adresse
}
        
    

print_r($_FILES['monDropZone']['name']);




include('connect_params.php');
$dbh = new PDO("$driver:host=$server;dbname=$dbname",$user, $pass);

//Insertion dans la BDD

//création et insertion image
foreach($_FILES['monDropZone']['name'] as $key => $val){
    $nomImage = $_FILES['monDropZone']['name'][$key];
    $tmp_name = $_FILES['monDropZone']['tmp_name'][$key];
    $location = 'images/'.$idOffre.'/';
    if (!file_exists($location)) {
        mkdir($location, 0777, true);
    }
    
    move_uploaded_file($tmp_name, $location.$nomImage);
    $stmt = $dbh->prepare(
        "INSERT INTO _image(idOffre, nomImage) 
        VALUES('$idOffre', '$nomImage')"        
    );
    $stmt->execute();
}



//Insertion Activité
if($typeOffre === "Activite"){
    $stmt = $dbh->prepare(
        "INSERT INTO vue_activite (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
        nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, ageMin, prestation, estEnLigne)
        VALUES (:idCompte, :nomOption, :nomForfait, :titre, :description, :descriptionDetaillee, :siteInternet,
        :nomCategorie, :codePostal, :ville, :nomRue, :numRue, :numTel, :valPrix, :tempsEnMinutes, :ageMin, :prestation, :estEnLigne)"
    );

    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomCategorie', $typeOffre, $typeOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':valPrix', $prix, $prix !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':tempsEnMinutes', $duree, $duree !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':ageMin', $ageMinimum, $ageMinimum !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':prestation', $prestation, $prestation !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);

    $stmt->execute();
}

//Insertion Visite
if($typeOffre === "Visite"){
    $stmt = $dbh->prepare(
        "INSERT INTO vue_visite (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
        nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, estGuidee, estEnLigne)
        VALUES (:idCompte, :nomOption, :nomForfait, :titre, :description, :descriptionDetaillee, :siteInternet,
        :nomCategorie, :codePostal, :ville, :nomRue, :numRue, :numTel, :valPrix, :tempsEnMinutes, :estGuidee, :estEnLigne)"
    );


    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomCategorie', $typeOffre, $typeOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':valPrix', $prix, $prix !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':tempsEnMinutes', $duree, $duree !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':estGuidee', $guidee, $guidee !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);

    $stmt->execute();
}

//Insertion Spectacle
if ($typeOffre === "Spectacle") {
    $stmt = $dbh->prepare(
        "INSERT INTO vue_spectacle (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
        nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, capacite, estEnLigne)
        VALUES (:idCompte, :nomOption, :nomForfait, :titre, :description, :descriptionDetaillee, :siteInternet,
        :nomCategorie, :codePostal, :ville, :nomRue, :numRue, :numTel, :valPrix, :tempsEnMinutes, :capacite, :estEnLigne)"
    );

    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomCategorie', $typeOffre, $typeOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':valPrix', $prix, $prix !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':tempsEnMinutes', $duree, $duree !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':capacite', $capacite, $capacite !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);

    $stmt->execute();
}

//Insertion ParcAttraction
if ($typeOffre === "ParcAttraction") {
    $stmt = $dbh->prepare(
        "INSERT INTO vue_parc_attractions (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
        nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, ageMin, nbAttractions, estEnLigne)
        VALUES (:idCompte, :nomOption, :nomForfait, :titre, :description, :descriptionDetaillee, :siteInternet,
        :nomCategorie, :codePostal, :ville, :nomRue, :numRue, :numTel, :valPrix, :tempsEnMinutes, :ageMin, :nbAttractions, :estEnLigne)"
    );

    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomCategorie', $typeOffre, $typeOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':valPrix', $prix, $prix !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ageMin', $ageMinimum, $ageMinimum !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nbAttractions', $nombreAttractions, $nombreAttractions !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);

    $stmt->execute();
}

//Insertion Restauration
if ($typeOffre === "Restauration") {
    $stmt = $dbh->prepare(
        "INSERT INTO vue_restaurant (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
        nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, carteRestaurant, gammeRestaurant, estEnLigne)
        VALUES (:idCompte, :nomOption, :nomForfait, :titre, :description, :descriptionDetaillee, :siteInternet,
        :nomCategorie, :codePostal, :ville, :nomRue, :numRue, :numTel, :valPrix, :carteRestaurant, :gammeRestaurant, :estEnLigne)"
    );

    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomCategorie', $typeOffre, $typeOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':valPrix', $prix, $prix !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':gammeRestaurant', $gammeRestaurant, $gammeRestaurant !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);

    $stmt->execute();
}


?>
