<?php
session_start();
$idCompte = $_SESSION['idCompte'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$idAvis = $_GET['idAvis'];

// Vérifier si l'avis appartient à l'utilisateur connecté
$stmt = $dbh->prepare("SELECT idcompte FROM pact._avis WHERE idavis = :idavis");
$stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
$stmt->execute();
$avis = $stmt->fetch(PDO::FETCH_ASSOC);

if ($avis && $avis['idcompte'] == $idCompte) {
    // Supprimer les images associées à l'avis

    $stmt = $dbh->prepare("SELECT nomimage FROM pact._image WHERE idimage IN (SELECT idimage FROM pact._representeavis WHERE idavis = :idavis)");
    $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($images as $image) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/ressources/avis/' . $idAvis . '/' . $image['nomimage'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $dbh->prepare("DELETE FROM pact._avis_blacklist WHERE idavis = :idavis");
    $stmt->bindParam(':idavis', $idAvis);
    $stmt->execute();

    // Supprimer les réponses associées à l'avis
    $stmt = $dbh->prepare("DELETE FROM pact._reponseavis WHERE idavis = :idavis");
    $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();
    
    // Supprimer l'avis
    $stmt = $dbh->prepare("DELETE FROM pact._representeavis WHERE idavis = :idavis");
    $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();
    
    // Supprimer les images associées à l'avis
    $stmt = $dbh->prepare("DELETE FROM pact._image WHERE idimage IN (SELECT idimage FROM pact._representeavis WHERE idavis = :idavis)");
    $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $dbh->prepare("DELETE FROM pact._avis WHERE idavis = :idavis");
    $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à supprimer cet avis.']);
}
?>
