<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAvis = intval($_POST['idAvis']);
    $titre = htmlspecialchars($_POST['titre']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = intval($_POST['note']);
    $contexteVisite = htmlspecialchars($_POST['contexteVisite']);

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

        $stmt = $dbh->prepare("UPDATE pact._avis SET titre = :titre, commentaire = :commentaire, note = :note, contextevisite = :contexteVisite WHERE idavis = :idAvis");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':contexteVisite', $contexteVisite);
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: detailsOffre.php?id=" . $_POST['idOffre']);
            exit;
        } else {
            echo "Erreur lors de la mise à jour de l'avis.";
        }
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    }
}
?>