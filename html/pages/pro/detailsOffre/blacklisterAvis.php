<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idAvis = $_POST['idAvis'];
    $idOffre = $_POST['idOffre'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $stmt = $dbh->prepare("UPDATE pact._avis SET estblacklist = true WHERE idavis = :idAvis");
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->execute();

        /*$stmt = $dbh->prepare("UPDATE pact._offre SET nbJetons = nbJetons - 1 WHERE idoffre = :idOffre");
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->execute();*/

        $stmt = $dbh->prepare("INSERT INTO pact._avis_blacklist (idOffre, idAvis) VALUES (:idOffre, :idAvis)");
        $stmt->bindParam(':idOffre', $idOffre);
        $stmt->bindParam(':idAvis', $idAvis);
        $stmt->execute();

        // Restaurer un jeton de réponse
        $stmt = $dbh->prepare("SELECT * FROM pact.vue_reponse WHERE idavis = :idAvis");
        $stmt->bindParam(':idAvis', $idAvis);
        $repons = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($repons != false){
            $stmt = $dbh->prepare("UPDATE pact._offre SET nbJetonsReponse = nbJetonsReponse + 1 WHERE idOffre = :idOffre");
            $stmt->bindParam(':idOffre', $idOffre);
            $stmt->execute();
        }else{

        }

        
        echo "Avis blacklisté";
        header("Location: detailsOffre.php?idOffre=" . $idOffre);
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}
?>