<?php

    session_start();
    require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
    //header("Location: " . $_SERVER["DOCUMENT_ROOT"] . "/pages/listeOffres/listeOffres.php");

    $dateDuJour = date("Y-m-d");
    $compteCourant = $_SESSION['idCompte'];

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $stmt = $dbh->prepare("SELECT idOffre FROM pact.vue_offres WHERE idCompte = $compteCourant");
    $stmt->execute();
    $offres = $stmt->fetchAll();

    //print_r($offres);

    foreach ($offres as $key => $offre) {
        $idOffreCourant = $offre['idoffre'];

        $stmt = $dbh->prepare("SELECT lastUpdate FROM pact.vue_facture_option WHERE idOffre = $idOffreCourant");
        $stmt->execute();
        $dateLastUpdate = $stmt->fetchAll();
        print_r($dateLastUpdate);
        
    }
    





?>

