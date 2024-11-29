<?php
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
use \composants\Select\Select;
use \composants\CheckboxSelect\CheckboxSelect;
use \composants\Checkbox\Checkbox;
use controlleurs\Offre\Offre;
use composants\Input\Input;
use composants\Button\Button;
require_once $_SERVER['DOCUMENT_ROOT'] . '/controlleurs/Offre/Offre.php';
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
require_once  $_SERVER["DOCUMENT_ROOT"] . '/composants/CheckboxSelect/CheckboxSelect.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/composants/Checkbox/Checkbox.php';
require_once '../../../trie/fonctionTrie.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/fonctionTrie.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/barretrie.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";


// Connexion à la base de données
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';
$pdo = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

// Récupération des paramètres de la requête
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'idoffre DESC';
$titre = isset($_GET['titre']) ? $_GET['titre'] : '';
$localisation = isset($_GET['localisation']) ? $_GET['localisation'] : '';
$minPrix = isset($_GET['minPrix']) ? $_GET['minPrix'] : null;
$maxPrix = isset($_GET['maxPrix']) ? $_GET['maxPrix'] : null;
$etat= isset($_GET['etat']) ? $_GET['etat'] : 'ouvertetferme';
$ouverture = isset($_GET['ouverture']) ? $_GET['ouverture'] : null;
$fermeture = isset($_GET['fermeture']) ? $_GET['fermeture'] : null;
$trie = isset($_GET['trie']) ? $_GET['trie'] : 'idoffre DESC';
$query = "SELECT * FROM offres WHERE 1=1";
$params = [];

$nomcategories = isset($_GET['nomcategorie']) ? explode(',', $_GET['nomcategorie']) : ['Tout'];

if (!empty($_GET['nomcategorie'])) {
    $categoriesPlaceholders = implode(',', array_fill(0, count($nomcategories), '?'));
    $query .= " AND nomcategorie IN ($categoriesPlaceholders)";
    $params = array_merge($params, $nomcategories);
}
// Récupération des résultats
$resultats = getOffres($pdo, $trie, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation,$etat);
// Vérifiez si la requête est une requête AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    $offres = [];
    foreach ($resultats as $item) {
        $offre = new Offre($item['titre'], $item['nomcategorie'], "o", $item['nomimage'], "o", $item['idoffre'], $item['tempsenminutes']);
        $offres[] = (string)$offre;
    }
    $nombreOffres = count($offres);
    echo json_encode(['offres' => $offres, 'nombreOffres' => $nombreOffres]);
    exit; // Stoppe l'exécution pour AJAX
}

// Si ce n'est pas une requête AJAX, inclure le HTML complet
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche d'Offres</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="listeOffres.css">
    <style>
    .offres-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px; 
    }
    .offre {
        margin: 10px auto;
    }
    .button-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .button-container button {
        height: 40px;
        display: flex;
        align-items: center;
    }
    .input {
        display: flex;
        gap: 10px;
    }
    </style>
    <script src="../../../trie/trieGeneral.js"></script>
</head>
<body>
    <?php
    Trie::render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories);
    ?>
    <br>

   

    <div id="nombreOffres">
        <p>Nombre d'offres affichées : <?php echo count($resultats); ?></p>
    </div>
    <div id="resultats" class="offres-container">
        <!-- Affichage des résultats -->
        <?php
        foreach ($resultats as $item) {
            $offre = new Offre($item['titre'], $item['nomcategorie'], "o", $item['nomimage'], "o", $item['idoffre'], $item['tempsenminutes']);
            echo $offre;
        }
        ?>
    </div>
</body>
</html>
