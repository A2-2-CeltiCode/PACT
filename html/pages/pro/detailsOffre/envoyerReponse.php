<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer l'ID de l'avis et le commentaire envoyés via le formulaire
    $membre = $_POST['compte'];
    $idCompte = $_SESSION['idCompte']; // L'ID du compte de l'utilisateur connecté
    $commentaire = $_POST['texteReponse']; // Le commentaire de la réponse
    $idOffre = $_POST['idOffre'];

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $query = "SELECT idavis FROM pact._avis NATURAL JOIN pact.vue_compte_membre WHERE idOffre = $idOffre and pseudo = '$membre'";
    $idAvis = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)['idavis'];
    
    try {

        // Connexion à la base de données
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

        // Insertion de la réponse dans la base de données
        $stmt = $dbh->prepare("INSERT INTO pact._reponseavis (idAvis, idCompte, commentaire) VALUES (:idAvis, :idCompte, :commentaire)");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->bindParam(':idCompte', $idCompte);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();

        // Mise à jour de l'avis pour le marquer comme "vu"
        $stmt = $dbh->prepare("UPDATE pact._avis SET estvu = true WHERE idavis = :idAvis");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->execute();

        // Redirection vers detailsOffre.php après l'enregistrement
        header("Location: detailsOffre.php?idOffre=" . $idOffre);
        exit; // S'assurer que le script s'arrête après la redirection
    } catch (PDOException $e) {
        // En cas d'erreur, renvoyer un message d'erreur
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        // Fermer la connexion
        $dbh = null;
    }
}
?>