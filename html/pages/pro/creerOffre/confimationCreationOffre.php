<?php
    session_start();

    header("Location: ../listeOffres/listeOffres.php");

    require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    function getMondayFromWeek($weekInput) {
        
    
        // Séparer l'année et le numéro de la semaine à partir de l'input
        list($year, $week) = sscanf($weekInput, "%d-W%d");
    
        // Calculer le lundi de la semaine donnée
        $date = new DateTime();
        $date->setISODate($year, $week); // Définit la date au lundi de la semaine
        return $date->format('Y-m-d'); // Retourne le lundi au format YYYY-MM-DD
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
    $typeOffre = $_POST['typeOffre'] ?? null;
    
    //type Offfre variable
    $capacite = $_POST['capacite'] ?? null;
    $guidee = $_POST['guidee'] ?? null;
    $nombreAttractions = $_POST['nombreAttractions'] ?? null;
    
    $idCompte=$_SESSION['idCompte'] ?? null;
    $prix1 = $_POST['prix1'] ?? null;
    $prix2 = $_POST['prix2'] ?? null;
    $prix3 = $_POST['prix3'] ?? null;
    $prix4 = $_POST['prix4'] ?? null;

    $duree1 = $_POST['duree1'];
    $duree2 = $_POST['duree2'];
    $duree3 = $_POST['duree3'];

    $ageMinimum1 = $_POST['ageMinimum1'] ?? null;
    $ageMinimum2 = $_POST['ageMinimum2'] ?? null;
        
    $prestation = $_POST['prestation'] ?? null; 
    $gammeRestaurant = $_POST['gammeRestaurant'] ?? null;
    $enLigne=true;
    $tag=$_POST['tag'] ?? null;
    $carteRestaurant = $_POST['carteRestaurant'] ?? null;
    $planParc = $_POST['planParc'] ?? null;
    $langue = $_POST['langue'] ?? null;
    $iban = $_POST['iban'] ?? null;
    $ouverture = $_POST['ouverture'] ?? null;
    $fermeture = $_POST['fermeture'] ?? null;
    $dateSpectacle = $_POST['dateSpectacle'] ?? null;
    $dateVisite = $_POST['dateVisite'] ?? null;
    $datePromotion = $_POST['datePromotion'] ?? null;
    $durepromotion = $_POST['durepromotion'] ?? null;
    
    
    $datePromotion = getMondayFromWeek($datePromotion);


    $dbh = new PDO("$driver:host=$server;dbname=$dbname",$dbuser, $dbpass);

    //Insertion dans la BDD

    if($iban !== null){
        $stmt = $dbh->prepare(
            "UPDATE pact._comptepro set banquerib= :iban WHERE idCompte = :idCompte"   
        );
        $stmt->bindValue(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->bindValue(':iban', $iban, PDO::PARAM_STR);
        $stmt->execute();
    }


    //creation adresse
    $stmt = $dbh->prepare(
        "INSERT INTO pact._adresse(codePostal, ville, rue, numTel)
        VALUES(:codePostal, :ville, :rue, :numTel)"
    );

    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':rue', $adressePostale, PDO::PARAM_STR);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
    $idAdresse=$dbh->lastInsertId();

    //creation offre
    $stmt = $dbh->prepare(
        "INSERT INTO pact._offre(idCompte, titre, description, descriptionDetaillee,siteInternet,nomOption,nomForfait,estEnLigne,idAdresse,heureouverture,heurefermeture) 
        VALUES(:idCompte, :titre, :description, :descriptionDetaillee, :siteInternet, :nomOption, :nomForfait, :estEnLigne, :idAdresse, :heureouverture, :heurefermeture)"        
        );
    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);
    $stmt->bindValue(':idAdresse', $idAdresse, $idAdresse !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':heureouverture', $ouverture, $ouverture !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':heurefermeture', $fermeture, $fermeture !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
    $idOffre=$dbh->lastInsertId();

    //création et insertion image
        foreach ($_FILES['monDropZone']['name'] as $key => $val) {
            $nomImage = $_FILES['monDropZone']['name'][$key];
            $tmp_name = $_FILES['monDropZone']['tmp_name'][$key];
            $location = $_SERVER["DOCUMENT_ROOT"] . "/ressources/" . $idOffre . '/images' . '/';
        
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


        $stmt = $dbh->prepare(
            "INSERT INTO pact._activite(idOffre, nomCategorie, tempsEnMinutes, valPrix, ageMin, prestation)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :ageMin, :prestation)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree1, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix1, PDO::PARAM_STR);
        $stmt->bindValue(':ageMin', $ageMinimum1, PDO::PARAM_INT);
        $stmt->bindValue(':prestation', $prestation, PDO::PARAM_STR);
        $stmt->execute();

        
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

        $stmt = $dbh->prepare(
            "INSERT INTO pact._spectacle(idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite,dateevenement)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :capacite,:dateevenement)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree3, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix3, PDO::PARAM_STR);
        $stmt->bindValue(':capacite', $capacite, PDO::PARAM_INT);
        $stmt->bindValue(':dateevenement', $dateSpectacle, PDO::PARAM_STR);
        $stmt->execute();

 

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

        foreach ($_FILES['planParc']['name'] as $key => $val) {
            $nomImage = $_FILES['planParc']['name'][$key];
            $tmp_name = $_FILES['planParc']['tmp_name'][$key];
            $location = $_SERVER["DOCUMENT_ROOT"] . "/ressources/" . $idOffre . '/carte' . '/';
            
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
            echo("image insérée");
            $planParc = $dbh->lastInsertId();
        }
        

        $stmt = $dbh->prepare(
            "INSERT INTO pact._parcAttractions(idOffre, nomCategorie, valPrix, carteparc, nbAttractions, ageMin)
            VALUES(:idOffre, :nomCategorie, :valPrix, :carteparc, :nbAttractions, :ageMin)"
        );
        
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', "Parc d'attractions", PDO::PARAM_STR);
        $stmt->bindValue(':valPrix', $prix4, PDO::PARAM_STR);
        $stmt->bindValue(':carteparc', $planParc, PDO::PARAM_INT);
        $stmt->bindValue(':nbAttractions', $nombreAttractions, PDO::PARAM_INT);
        $stmt->bindValue(':ageMin', $ageMinimum2, PDO::PARAM_INT);
        $stmt->execute();

        
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
        $stmt = $dbh->prepare(
            "INSERT INTO pact._visite(idOffre, nomCategorie, tempsEnMinutes, valPrix, estGuidee, dateevenement)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :estGuidee, :datevisite)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree2, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix2, PDO::PARAM_STR);
        $stmt->bindValue(':estGuidee', $guidee, PDO::PARAM_BOOL);
        $stmt->bindValue(':datevisite', $dateVisite, PDO::PARAM_STR);
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
    if ($typeOffre === "Restaurant") {

        foreach ($_FILES['carteRestaurant']['name'] as $key => $val) {
            $nomImage = $_FILES['carteRestaurant']['name'][$key];
            $tmp_name = $_FILES['carteRestaurant']['tmp_name'][$key];
            $location = $_SERVER["DOCUMENT_ROOT"] . "/ressources/" . $idOffre . '/carte' . '/';
        
            $extension = pathinfo($nomImage, PATHINFO_EXTENSION);
        
            $nouveauNomImage = uniqid() . '.' . $extension;
        
            if (!file_exists($location)) {
                mkdir($location, 0777, true);
            }
        
            if (move_uploaded_file($tmp_name, $location . $nouveauNomImage)) {
                $stmt = $dbh->prepare(
                    "INSERT INTO pact._image(idOffre, nomImage) 
                    VALUES(:idOffre, :nomImage)"
                );
                $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
                $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
                $stmt->execute();
                echo("image insérée");
                $carteRestaurant = $dbh->lastInsertId();
                echo($carteRestaurant);
            } else {
                echo("Failed to move uploaded file.");
            }
        }
        

        $stmt = $dbh->prepare(
            "INSERT INTO pact._restaurant(idOffre, nomCategorie, nomgamme, menurestaurant)
            VALUES(:idOffre, :nomCategorie , :nomgamme, :idImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':nomgamme', $gammeRestaurant, PDO::PARAM_STR);
        $stmt->bindValue(':idImage', $carteRestaurant, PDO::PARAM_INT);
        $stmt->execute();

        

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




$datePrestaServices = date('Y-m-01'); // Premier jour du mois en cours
$dateEcheance = date('Y-m-20', strtotime('+1 month')); // 20 du mois suivant

// Insertion dans la table _facture
$stmt = $dbh->prepare(
    "INSERT INTO pact._facture(idOffre, datePrestaServices, dateEcheance)
    VALUES(:idOffre, :datePrestaServices, :dateEcheance)"
);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->bindValue(':datePrestaServices', $datePrestaServices, PDO::PARAM_STR);
$stmt->bindValue(':dateEcheance', $dateEcheance, PDO::PARAM_STR);
$stmt->execute();
$idFacture = $dbh->lastInsertId();

if($typePromotion!="Aucune"){
// Insertion dans la table _annulationOption
$stmt = $dbh->prepare(
    "INSERT INTO pact._annulationOption(nbSemaines, debutOption, idOffre, nomOption, estAnnulee)
    VALUES(:nbSemaines, :debutOption, :idOffre, :nomOption, :estAnnulee)"
);
$stmt->bindValue(':nbSemaines', $durepromotion, PDO::PARAM_INT);
$stmt->bindValue(':debutOption', $datePromotion, PDO::PARAM_STR);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->bindValue(':nomOption', $typePromotion, PDO::PARAM_STR);
$stmt->bindValue(':estAnnulee', false, PDO::PARAM_BOOL);
$stmt->execute();
}
// Insertion dans la table _historiqueEnLigne
$stmt = $dbh->prepare(
    "INSERT INTO pact._historiqueenligne(idOffre, jourdebut)
    VALUES( :idOffre, :jourdebut)"
);
$stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->bindValue(':jourdebut', date("Y-m-d"), PDO::PARAM_INT);
$stmt->execute();

    echo("gg bro");