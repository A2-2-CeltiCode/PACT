<?php
session_start();

if (!isset($_POST['idoffre'])) header("Location: /pages/visiteur/accueil/accueil.php");;

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$contextes = ["Contexte de la visite", "Affaires", "Couple", "Famille", "Amis", "Solo"];


$stmt = $dbh->prepare("INSERT INTO pact._avis VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, DEFAULT)");

$stmt->execute([
    $_POST['idoffre'],
    $_SESSION['idCompte'],
    $_POST['contenu'],
    $_POST['note'],
    $_POST['titre'],
    $contextes[$_POST['contexte']],
    $_POST['datevisite']
]);

$idavis = $dbh->lastInsertId();

$avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idavis";
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

header("Location: /pages/membre/detailsOffre/detailsOffre.php?id={$_POST['idoffre']}");

