<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idReponse = $_POST['idReponse'];
    $idOffre = $_POST['idOffre'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

        // Supprimer la réponse
        $stmt = $dbh->prepare("DELETE FROM pact._reponseavis WHERE idreponse = :idReponse");
        $stmt->bindParam(':idReponse', $idReponse);
        $stmt->execute();

        // Restaurer un jeton de réponse
        $stmt = $dbh->prepare("UPDATE pact._offre SET nbJetonsReponse = nbJetonsReponse + 1 WHERE idOffre = :idOffre AND nbJetonsReponse < 0");
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->execute();

        // Rediriger avec un message de succès
        header("Location: detailsOffre.php?idOffre=" . $idOffre . "&message=reponse_supprimee");
        exit;
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}
?>