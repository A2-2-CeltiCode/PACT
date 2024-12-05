
<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/pages/pro/facture/creationFacture.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/pages/pro/listeFacture/calculJoursEnLigne.php";
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';

$idFacture = isset($_GET['idfacture']) ? $_GET['idfacture'] : null;
$idOffre = isset($_GET['idoffre']) ? $_GET['idoffre'] : null;
$mois = isset($_GET['mois']) ? $_GET['mois'] : null;

echo($_SESSION['idCompte']);
echo("aaa");
if ($idFacture && $idOffre) {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    
    $nbJours=calculerJoursEnLigne($dbh, $idOffre, $mois);
    
    $facture = new Facture($idOffre, 1, $_SESSION['idCompte'], $idFacture,$nbJours, $dbh);
    echo $facture->render();
} else {
    echo "Facture non trouvée.";
}
?>