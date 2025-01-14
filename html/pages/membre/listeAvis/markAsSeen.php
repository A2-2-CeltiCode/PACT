<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

session_start();

if (!isset($_SESSION['idCompte'])) {
    http_response_code(403);
    echo "Accès refusé";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['idAvis'])) {
    $idAvis = intval($_GET['idAvis']);

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $query = "UPDATE pact._avis SET estvu = true WHERE idavis = :idAvis";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Avis marqué comme vu";
    } else {
        http_response_code(500);
        echo "Erreur lors de la mise à jour de l'avis";
    }
} else {
    http_response_code(400);
    echo "Requête invalide";
}
?>
