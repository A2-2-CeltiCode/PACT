<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idOffre = $_POST['idOffre'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $stmt = $dbh->prepare("DELETE FROM pact._offre WHERE idoffre = :idOffre");
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->execute();
        
        echo "Offre supprimée";
        header("Location: listeOffres.php");
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}
?>