<?php
session_start();

// Si aucun identifiant d'offre n'est fourni dans la requête POST, redirige l'utilisateur vers la page d'accueil
if (!isset($_POST['idoffre'])) header("Location: /pages/visiteur/accueil/accueil.php");

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

// Création d'une connexion à la base de données
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

// Déclaration d'un tableau pour mapper les contextes de visite (utilisé plus tard)
$contextes = ["Contexte de la visite", "Affaires", "Couple", "Famille", "Amis", "Solo"];


$stmt = $dbh->prepare("INSERT INTO pact._avis VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, DEFAULT)");

// Exécution de la requête avec les paramètres récupérés du formulaire HTML
$stmt->execute([
    $_POST['idoffre'],             
    $_SESSION['idCompte'],          
    $_POST['contenu'],        
    $_POST['note'],             
    $_POST['titre'],              
    $contextes[$_POST['contexte']], 
    $_POST['datevisite']            
]);

// Récupère l'identifiant du dernier avis inséré 
$idavis = $dbh->lastInsertId();

// Détermine le chemin du répertoire où les images de l'avis seront stockées
$avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idavis";

// Crée le répertoire s'il n'existe pas encore
if (!file_exists($avis_dir)) {
    mkdir($avis_dir);  
}

foreach ($_FILES["dropzone"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["dropzone"]["tmp_name"][$key];
        $name = basename($_FILES["dropzone"]["name"][$key]);
        move_uploaded_file($tmp_name, "$avis_dir/$name");
        $stmt = $dbh->prepare("INSERT INTO pact._imageavis VALUES (?, ?)");
        $stmt->execute([$idavis, $name]);
    }
}

// Redirection vers la page des détails de l'offre une fois le traitement terminé
header("Location: /pages/membre/detailsOffre/detailsOffre.php?id={$_POST['idoffre']}");
