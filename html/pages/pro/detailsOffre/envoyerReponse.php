<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

        // Vérifier si l'offre a encore des jetons de réponse disponibles
        $query = "SELECT nbJetonsReponse FROM pact._offre WHERE idOffre = :idOffre";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->execute();
        $nbJetonsReponse = $stmt->fetchColumn();

        if ($nbJetonsReponse <= 0) {
            // Redirection vers detailsOffre.php avec un message d'erreur
            header("Location: detailsOffre.php?idOffre=$idOffre&error=plus_de_reponses");
            exit;
        }

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

        // Mettre à jour le nombre de jetons de réponse
        $stmt = $dbh->prepare("UPDATE pact._offre SET nbJetonsReponse = nbJetonsReponse - 1 WHERE idOffre = :idOffre AND nbJetonsReponse > 0");
        $stmt->bindParam(':idOffre', $idOffre);
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