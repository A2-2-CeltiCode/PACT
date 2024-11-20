<!DOCTYPE html>
<html lang="fr">
<?php
session_start();

// Importation des composants
use composants\Button\Button;
use composants\Button\ButtonType;
use composants\Label\Label;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $status = $_GET['status'] ?? 'enligne'; // Statut par défaut

    $idCompte = $_SESSION['idCompte'];

} catch (PDOException $e) {
    // Gestion des erreurs
    print "Erreur !: " . htmlspecialchars($e->getMessage()) . "<br>";
    die();
}
 ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Offres</title>
    <link rel="stylesheet" href="consulterComptePro.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<body>

    <?php Header::render(HeaderType::Pro); ?>
    
    <main>

    </main>

    <?php Footer::render(FooterType::Pro); ?>
</body>
</html>