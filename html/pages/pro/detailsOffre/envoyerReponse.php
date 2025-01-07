<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idAvis = $_POST['idAvis'];
    $reponse = $_POST['reponse'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $stmt = $dbh->prepare("INSERT INTO pact.reponses (idAvis, reponse) VALUES (:idAvis, :reponse)");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->bindParam(':reponse', $reponse);
        $stmt->execute();
        header("Location: detailsOffre.php?idOffre=" . $_POST['idOffre']);
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
} else {
    header("Location: detailsOffre.php");
}

header("Location: detailsOffre.php?idOffre=" . $_POST['idOffre']);
?>
