<?php
    require "../../../connect_params.php";
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", 
            $user, $pass);
    
    function insererPrix($dbh, $prix) {
        $sql = "SELECT valprix FROM pact._prix WHERE valprix = :valprix";
        $stmt = $dbh->prepare($sql);  
        $stmt->bindValue(':valprix', $prix, PDO::PARAM_STR);
        $stmt->execute();
        
        $prix2 = $stmt->fetchAll();
    
        if (empty($prix2)) {
            $stmt = $dbh->prepare("INSERT INTO pact._prix(valprix) VALUES(:valprix)");
            $stmt->bindValue(':valprix', $prix, PDO::PARAM_STR); 
            $stmt->execute();
        }
    }

    function insererDuree($dbh, $tempsEnMinutes) {
        $sql = "SELECT tempsEnMinutes FROM pact._duree WHERE tempsEnMinutes = :tempsEnMinutes";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':tempsEnMinutes', $tempsEnMinutes, PDO::PARAM_INT);
        $stmt->execute();
        $dureeExistante = $stmt->fetchColumn();
    
        // Si la durée n'existe pas, on l'insère
        if (empty($dureeExistante)) {
            
            $stmt = $dbh->prepare(
                "INSERT INTO pact._duree(tempsEnMinutes) 
                VALUES(:tempsEnMinutes)"
            );
            $stmt->bindValue(':tempsEnMinutes', $tempsEnMinutes, PDO::PARAM_INT);
            $stmt->execute();
        }
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
    

    if ($adressePostale) {
        // Expression régulière pour capturer les chiffres au début
        preg_match('/^(\d+)\s*(.*)$/', $adressePostale, $matches);
        $numRue = $matches[1]; // La partie des chiffres
        $nomRue  = $matches[2];    // Le reste de l'adresse
    }
            
        

    $idCompte=2;
    



    $dbh = new PDO("$driver:host=$server;dbname=$dbname",$user, $pass);

    //Insertion dans la BDD



    //creation adresse
    $stmt = $dbh->prepare(
        "INSERT INTO pact._adresse(codePostal, ville, nomRue, numRue, numTel)
        VALUES(:codePostal, :ville, :nomRue, :numRue, :numTel)"
    );

    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
    $idAdresse=$dbh->lastInsertId();

    //creation offre
    $stmt = $dbh->prepare(
        "INSERT INTO pact._offre(idCompte, titre, description, descriptionDetaillee,siteInternet,nomOption,nomForfait,estEnLigne,idAdresse) 
        VALUES(:idCompte, :titre, :description, :descriptionDetaillee, :siteInternet, :nomOption, :nomForfait, :estEnLigne, :idAdresse)"        
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
    $stmt->execute();
    $idOffre=$dbh->lastInsertId();

    //création et insertion image
    foreach ($_FILES['monDropZone']['name'] as $key => $val) {
        $nomImage = $_FILES['monDropZone']['name'][$key];
        $tmp_name = $_FILES['monDropZone']['tmp_name'][$key];
        $location = '../../ressources/'.$idOffre .'/images'.'/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO pact._image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Type d'offre : Activité
    if ($typeOffre === "Activite") {
        insererPrix($dbh, $prix1);
        insererDuree($dbh, $duree1);


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
            $stmt = $dbh->prepare(
                "INSERT INTO pact._possedeActivite(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Spectacle
    if ($typeOffre === "Spectacle") {
        insererPrix($dbh, $prix3);
        insererDuree($dbh, $duree3);

        $stmt = $dbh->prepare(
            "INSERT INTO pact._spectacle(idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :capacite)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree3, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix3, PDO::PARAM_STR);
        $stmt->bindValue(':capacite', $capacite, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO pact._possedeSpectacle(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Parc d'Attraction
    if ($typeOffre === "ParcAttraction") {
        insererPrix($dbh, $prix3);

        $nomImage = $_FILES['planParc']['name'][$key];
        $tmp_name = $_FILES['planParc']['tmp_name'][$key];
        $location = '../../ressources/'.$idOffre .'/carte'.'/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO pact._image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
        $planParc = $dbh->lastInsertId();

        $stmt = $dbh->prepare(
            "INSERT INTO pact._parcAttractions(idOffre, nomCategorie, tempsEnMinutes, valPrix, planParc, nbAttractions, ageMin)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :planParc, :nbAttractions, :ageMin)"
        );
        
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':valPrix', $prix3, PDO::PARAM_STR);
        $stmt->bindValue(':planParc', $planParc, PDO::PARAM_STR);
        $stmt->bindValue(':nbAttractions', $nombreAttractions, PDO::PARAM_INT);
        $stmt->bindValue(':ageMin', $ageMinimum2, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO pact._possedeParcAttractions(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Visite
    if ($typeOffre === "Visite") {
        insererPrix($dbh, $prix2);
        insererDuree($dbh, $duree2);
        $stmt = $dbh->prepare(
            "INSERT INTO pact._visite(idOffre, nomCategorie, tempsEnMinutes, valPrix, estGuidee)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :estGuidee)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree2, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix2, PDO::PARAM_STR);
        $stmt->bindValue(':estGuidee', $guidee, PDO::PARAM_BOOL);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO pact._possedeVisite(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Restauration
    if ($typeOffre === "Restauration") {

        $nomImage = $_FILES['carteRestaurant']['name'][$key];
        $tmp_name = $_FILES['carteRestaurant']['tmp_name'][$key];
        $location = '../../ressources/'.$idOffre .'/carte'.'/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO pact._image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
        $carteRestaurant = $dbh->lastInsertId();

        $stmt = $dbh->prepare(
            "INSERT INTO pact._restaurant(idOffre, nomCategorie, gammeRestaurant, idImage)
            VALUES(:idOffre, :nomCategorie, , :gammeRestaurant, :idImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':gammeRestaurant', $gammeRestaurant, PDO::PARAM_STR);
        $stmt->bindValue(':idImage', $carteRestaurant, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO pact._possedeRestaurant(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }

        


    }
