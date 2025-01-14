<?php
session_start();

// Redirige l'utilisateur vers la page d'accueil si 'idoffre' n'est pas défini
if (!isset($_POST['idoffre'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

// Création d'une connexion à la base de données 
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

// Preparation et Exécution de la requête avec l'identifiant de l'offre et l'identifiant du compte utilisateu
$stmt = $dbh->prepare("SELECT idavis FROM pact.vue_avis WHERE idOffre = ? AND idCompte = ?");
$stmt->execute([$_POST['idoffre'], $_SESSION['idCompte']]);
[['idavis' => $idavis]] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Détermine le chemin du répertoire où les fichiers associés à l'avis sont stockés
$avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idavis";

if (file_exists($avis_dir)) {
    array_map("unlink", glob("$avis_dir/*.*"));
    rmdir($avis_dir);
}


// Préparation et Exécution d'une requête pour supprimer les enregistrements d'images associés à l'avis
$stmt = $dbh->prepare("DELETE FROM pact._imageavis WHERE idavis = ?");
$stmt->execute([$idavis]);

// Préparation et Exécution d'une requête pour supprimer l'avis lui-même
$stmt = $dbh->prepare("DELETE FROM pact._avis WHERE idavis = ?");
$stmt->execute([$idavis]);

// Redirection vers la page des détails de l'offre après suppression de l'avis
header("Location: /pages/membre/detailsOffre/detailsOffre.php?id={$_POST['idoffre']}");