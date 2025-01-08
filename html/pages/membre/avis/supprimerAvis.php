<?php
session_start();

if (!isset($_POST['idoffre'])) header("Location: /pages/visiteur/accueil/accueil.php");;

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$stmt = $dbh->prepare("SELECT idavis FROM pact.vue_avis WHERE idOffre = ? AND idCompte = ?");
$stmt->execute([$_POST['idoffre'], $_SESSION['idCompte']]);
[['idavis' => $idavis]] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idavis";

if (file_exists($avis_dir)) {
    array_map("unlink", glob("$avis_dir/*.*"));
    rmdir($avis_dir);
}

$stmt = $dbh->prepare("DELETE FROM pact._representeAvis WHERE idavis = ?");
$stmt->execute([$idavis]);
$stmt = $dbh->prepare("DELETE FROM pact._avis WHERE idavis = ?");
$stmt->execute([$idavis]);

header("Location: /pages/membre/detailsOffre/detailsOffre.php?id={$_POST['idoffre']}");