    <?php
    function insererPrix($dbh, $valPrix) {
        $stmt = $dbh->prepare(
            "INSERT INTO _prix(valPrix) 
            VALUES(:valPrix)"
        );
        $stmt->bindValue(':valPrix', $valPrix, PDO::PARAM_STR);
        $stmt->execute();
    }

    function insererDuree($dbh, $tempsEnMinutes) {
        $stmt = $dbh->prepare(
            "INSERT INTO _duree(tempsEnMinutes) 
            VALUES(:tempsEnMinutes)"
        );
        $stmt->bindValue(':tempsEnMinutes', $tempsEnMinutes, PDO::PARAM_INT);
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
    $typeOffre = $_POST['typeOffre'] ?? null;
    //type Offfre variable
    $capacite = $_POST['capacite'] ?? null;
    $duree = $_POST['duree'] ?? null;
    $guidee = $_POST['guidee'] ?? null;
    $nombreAttractions = $_POST['nombreAttractions'] ?? null;
    $ageMinimum = $_POST['ageMinimum'] ?? null;
    $idCompte=$_SESSION['idCompte'] ?? null;
    $prix = $_POST['prix'] ?? null;
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
        $nomRue = $matches[2];    // Le reste de l'adresse
    }
            
        

    print_r($_FILES['monDropZone']['name']);




    include('connect_params.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname",$user, $pass);

    //Insertion dans la BDD



    //creation adresse
    $stmt = $dbh->prepare(
        "INSERT INTO _adresse(codePostal, ville, nomRue, numRue, numTel)
        VALUES(:codePostal, :ville, :nomRue, :numRue, :numTel)"
    );

    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomRue', $nomRue, $nomRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numRue', $numRue, $numRue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':numTel', $numeroTelephone, $numeroTelephone !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();

    //creation offre
    $stmt = $dbh->prepare(
        "INSERT INTO _offre(idCompte, titre, description, descriptionDetaillee,siteInternet,nomOption,nomForfait,estEnLigne,codePostal,ville) 
        VALUES(:idCompte, :titre, :description, :descriptionDetaillee, :siteInternet, :nomOption, :nomForfait, :estEnLigne, :codePostal, :ville)"        
        );
    $stmt->bindValue(':idCompte', $idCompte, $idCompte !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':titre', $nomOffre, $nomOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':description', $descriptionOffre, $descriptionOffre !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':descriptionDetaillee', $descriptionDetaillee, $descriptionDetaillee !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':siteInternet', $siteWeb, $siteWeb !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomOption', $typePromotion, $typePromotion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':nomForfait', $typeForfait, $typeForfait !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':estEnLigne', $enLigne, $enLigne !== null ? PDO::PARAM_BOOL : PDO::PARAM_NULL);
    $stmt->bindValue(':codePostal', $codePostal, $codePostal !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':ville', $ville, $ville !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
    $idOffre=$dbh->lastInsertId();

    //création et insertion image
    foreach ($_FILES['monDropZone']['name'] as $key => $val) {
        $nomImage = $_FILES['monDropZone']['name'][$key];
        $tmp_name = $_FILES['monDropZone']['tmp_name'][$key];
        $location = 'images/' . $idOffre . '/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO _image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Type d'offre : Activité
    if ($typeOffre === "Activite") {
        insererPrix($dbh, $prix);
        $stmt = $dbh->prepare(
            "INSERT INTO _duree(tempsEnMinutes)
            VALUES(:tempsEnMinutes)"
        );
        $stmt->bindValue(':tempsEnMinutes', $duree, PDO::PARAM_INT);
        $stmt->execute();


        $stmt = $dbh->prepare(
            "INSERT INTO _activite(idOffre, nomCategorie, tempsEnMinutes, valPrix, ageMin, prestation)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :ageMin, :prestation)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':ageMin', $ageMinimum, PDO::PARAM_INT);
        $stmt->bindValue(':prestation', $prestation, PDO::PARAM_STR);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO _possedeActivite(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Spectacle
    if ($typeOffre === "Spectacle") {
        insererPrix($dbh, $prix);
        insererDuree($dbh, $duree);

        $stmt = $dbh->prepare(
            "INSERT INTO _spectacle(idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :capacite)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':capacite', $capacite, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO _possedeSpectacle(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Parc d'Attraction
    if ($typeOffre === "ParcAttraction") {
        insererPrix($dbh, $prix);
        insererDuree($dbh, $duree);

        $nomImage = $_FILES['planParc']['name'][$key];
        $tmp_name = $_FILES['planParc']['tmp_name'][$key];
        $location = 'images/' . $idOffre . '/carte'.'/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO _image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
        $planParc = $dbh->lastInsertId();

        $stmt = $dbh->prepare(
            "INSERT INTO _parcAttractions(idOffre, nomCategorie, tempsEnMinutes, valPrix, planParc, nbAttractions, ageMin)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :planParc, :nbAttractions, :ageMin)"
        );
        
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':valPrix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':planParc', $planParc, PDO::PARAM_STR);
        $stmt->bindValue(':nbAttractions', $nombreAttractions, PDO::PARAM_INT);
        $stmt->bindValue(':ageMin', $ageMinimum, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO _possedeParcAttractions(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Type d'offre : Visite
    if ($typeOffre === "Visite") {
        insererPrix($dbh, $prix);
        insererDuree($dbh, $duree);
        $stmt = $dbh->prepare(
            "INSERT INTO _visite(idOffre, nomCategorie, tempsEnMinutes, valPrix, estGuidee)
            VALUES(:idOffre, :nomCategorie, :tempsEnMinutes, :valPrix, :estGuidee, :langue)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':tempsEnMinutes', $duree, PDO::PARAM_INT);
        $stmt->bindValue(':valPrix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':estGuidee', $guidee, PDO::PARAM_BOOL);
        $stmt->bindValue(':langue', $langue, PDO::PARAM_STR);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO _possedeVisite(idOffre, nomTag) 
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
        $location = 'images/' . $idOffre . '/carte'.'/';
        
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
        
        move_uploaded_file($tmp_name, $location . $nomImage);
        
        $stmt = $dbh->prepare(
            "INSERT INTO _image(idOffre, nomImage) 
            VALUES(:idOffre, :nomImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomImage', $nomImage, PDO::PARAM_STR);
        $stmt->execute();
        $carteRestaurant = $dbh->lastInsertId();

        $stmt = $dbh->prepare(
            "INSERT INTO _restaurant(idOffre, nomCategorie, gammeRestaurant, idImage)
            VALUES(:idOffre, :nomCategorie, , :gammeRestaurant, :idImage)"
        );
        $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->bindValue(':nomCategorie', $typeOffre, PDO::PARAM_STR);
        $stmt->bindValue(':gammeRestaurant', $gammeRestaurant, PDO::PARAM_STR);
        $stmt->bindValue(':idImage', $carteRestaurant, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tag as $val) {
            $stmt = $dbh->prepare(
                "INSERT INTO _possedeRestaurant(idOffre, nomTag) 
                VALUES(:idOffre, :nomTag)"
            );
            $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
            $stmt->bindValue(':nomTag', $val, PDO::PARAM_STR);
            $stmt->execute();
        }

        


    }
