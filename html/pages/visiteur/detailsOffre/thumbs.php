<?php
session_start();
$idCompte = $_SESSION['idCompte'];

if (!isset($idCompte)) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour voter.']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$idAvis = $_GET['idAvis'];
$type = $_GET['type'];

// Vérifier si l'utilisateur a déjà voté
$stmt = $dbh->prepare("SELECT * FROM pact._avis_votes WHERE idAvis = :idAvis AND idCompte = :idCompte");
$stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
$stmt->execute();
$vote = $stmt->fetch(PDO::FETCH_ASSOC);

if ($vote) {
    // Si l'utilisateur a déjà voté, vérifier si c'est le même type de vote
    if ($vote['type'] === $type) {
        // Si c'est le même type de vote, supprimer le vote
        $stmt = $dbh->prepare("DELETE FROM pact._avis_votes WHERE idAvis = :idAvis AND idCompte = :idCompte");
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();

        if ($type === 'up') {
            $stmt = $dbh->prepare("UPDATE pact._avis SET pouceHaut = pouceHaut - 1 WHERE idAvis = :idAvis");
        } else {
            $stmt = $dbh->prepare("UPDATE pact._avis SET pouceBas = pouceBas - 1 WHERE idAvis = :idAvis");
        }
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Si c'est un type de vote différent, mettre à jour le vote
        $stmt = $dbh->prepare("UPDATE pact._avis_votes SET type = :type WHERE idAvis = :idAvis AND idCompte = :idCompte");
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();

        if ($type === 'up') {
            $stmt = $dbh->prepare("UPDATE pact._avis SET pouceHaut = pouceHaut + 1, pouceBas = pouceBas - 1 WHERE idAvis = :idAvis");
        } else {
            $stmt = $dbh->prepare("UPDATE pact._avis SET pouceBas = pouceBas + 1, pouceHaut = pouceHaut - 1 WHERE idAvis = :idAvis");
        }
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
        $stmt->execute();
    }
} else {
    // Si l'utilisateur n'a pas encore voté, ajouter le vote
    $stmt = $dbh->prepare("INSERT INTO pact._avis_votes (idAvis, idCompte, type) VALUES (:idAvis, :idCompte, :type)");
    $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->execute();

    if ($type === 'up') {
        $stmt = $dbh->prepare("UPDATE pact._avis SET pouceHaut = pouceHaut + 1 WHERE idAvis = :idAvis");
    } else {
        $stmt = $dbh->prepare("UPDATE pact._avis SET pouceBas = pouceBas + 1 WHERE idAvis = :idAvis");
    }
    $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
    $stmt->execute();
}

$thumbsUp = $dbh->query("SELECT poucehaut FROM pact._avis WHERE idAvis = $idAvis")->fetch(PDO::FETCH_ASSOC)['poucehaut'];
$thumbsDown = $dbh->query("SELECT poucebas FROM pact._avis WHERE idAvis = $idAvis")->fetch(PDO::FETCH_ASSOC)['poucebas'];

echo json_encode(['success' => true, 'thumbs_up' => $thumbsUp, 'thumbs_down' => $thumbsDown]);
?>
