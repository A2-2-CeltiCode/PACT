<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idAvis = $_POST['idAvis'];
    $idCompte = $_SESSION['idCompte'];
    $commentaire = $_POST['reponse'];
    $idOffre = $_POST['idOffre']; // Ajout de l'identifiant de l'offre

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

        $stmt = $dbh->prepare("INSERT INTO pact._reponseavis (idAvis, idCompte, commentaire) VALUES (:idAvis, :idCompte, :commentaire)");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->bindParam(':idCompte', $idCompte);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();

        $stmt = $dbh->prepare("UPDATE pact._avis SET estvu = true WHERE idavis = :idAvis");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->execute();

        echo json_encode(['success' => true]);
        header("Location: listeAvis.php?idOffre=" . $idOffre);
        exit(); // Ajout de exit() pour s'assurer que le script s'arrête après la redirection
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $dbh = null;
    }
}
?>
