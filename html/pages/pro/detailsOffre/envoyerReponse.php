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
        header("Location: detailsOffre.php?idOffre=" . $idOffre); // Utilisation de l'identifiant de l'offre
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}

//header("Location: detailsOffre.php?idOffre=" . $_POST['idOffre']);
?>
