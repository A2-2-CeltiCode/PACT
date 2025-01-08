<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idReponse = $_POST['idReponse'];
    $idOffre = $_POST['idOffre'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $stmt = $dbh->prepare("DELETE FROM pact._reponseavis WHERE idreponse = :idReponse");
        $stmt->bindParam(':idReponse', $idReponse);
        $stmt->execute();
        echo "Reponse supprimée";
        header("Location: detailsOffre.php?idOffre=" . $idOffre);
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}
?>
