<?php
//require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
//session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer l'ID de l'avis et le commentaire envoyés via le formulaire
    $idAvis = $_POST['idAvis']; // Maintenant ça correspond au champ caché dans le formulaire
    $idCompte = $_SESSION['idCompte']; // L'ID du compte de l'utilisateur connecté
    $commentaire = $_POST['texteReponse']; // Le commentaire de la réponse

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
        header("Location: detailsOffre.php?idAvis=$idAvis");
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