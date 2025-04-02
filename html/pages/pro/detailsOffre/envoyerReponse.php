<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $membre = $_POST['compte'];
    $idCompte = $_SESSION['idCompte'];
    $commentaire = $_POST['texteReponse'];
    $idOffre = $_POST['idOffre'];

    try {
        // Connexion à la base de données
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer l'ID de l'avis lié à l'offre et au membre
        $query = "SELECT idavis FROM pact._avis NATURAL JOIN pact.vue_compte_membre WHERE idOffre = :idOffre AND pseudo = :membre";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->bindParam(':membre', $membre);
        $stmt->execute();
        $idAvis = $stmt->fetch(PDO::FETCH_ASSOC)['idavis'];

        // Insérer la réponse dans la base de données
        $stmt = $dbh->prepare("INSERT INTO pact._reponseavis (idAvis, idCompte, commentaire) VALUES (:idAvis, :idCompte, :commentaire)");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->bindParam(':idCompte', $idCompte);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();

        // Marquer l'avis comme vu
        $stmt = $dbh->prepare("UPDATE pact._avis SET estvu = true WHERE idavis = :idAvis");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->execute();

        // Redirection après l'enregistrement
        header("Location: detailsOffre.php?idOffre=" . $idOffre);
        exit;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        // Fermer la connexion
        $dbh = null;
    }
}
?>