<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

function recupAvisEnLigne(PDO $pdo) {
    $sql = "SELECT * FROM pact.avis WHERE estenligne = true";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemple d'utilisation
try {
    $pdo = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $avisEnLigne = recupAvisEnLigne($pdo);
    print_r($avisEnLigne);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
