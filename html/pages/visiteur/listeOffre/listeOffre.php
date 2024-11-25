<?php
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
require_once 'fonctionTrie.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";

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
$query = "SELECT * FROM offres WHERE 1=1";
$params = [];

$nomcategories = isset($_GET['nomcategorie']) ? explode(',', $_GET['nomcategorie']) : ['Tout'];

if (!empty($_GET['nomcategorie'])) {
    $categoriesPlaceholders = implode(',', array_fill(0, count($nomcategories), '?'));
    $query .= " AND nomcategorie IN ($categoriesPlaceholders)";
    $params = array_merge($params, $nomcategories);
}
// Récupération des résultats
$resultats = getOffres($pdo, $sort, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation,$etat);

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
    <script src="trieGeneral.js"></script>
</head>
<body>
    <form id="searchForm" method="GET" action="">
        <!-- Formulaire de tri -->
        <input type="hidden" id="sortInput" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
        <div class="input">
        <?php Input::render(name:"titre", type:"text", placeholder:'Titre*', value: htmlspecialchars($titre)); ?>
        <?php Input::render(name:"localisation", type:"text", placeholder:'localisation', value: htmlspecialchars($localisation)); ?>
        <?php Input::render(name:"minPrix", type:"number", placeholder:'Prix Min', value: htmlspecialchars($minPrix)); ?>
        <?php Input::render(name:"maxPrix", type:"number", placeholder:'Prix Max', value: htmlspecialchars($maxPrix)); ?>
        <div>
        <label for="ouverture">Heure d'ouverture</label>
        <?php Input::render(name:"ouverture", type:"time", value: htmlspecialchars($ouverture)); ?>
        </div>
        <div>
        <label for="fermeture">Heure de fermeture</label>
        <?php Input::render(name:"fermeture", type:"time", placeholder:'Heure de fermeture', value: htmlspecialchars($fermeture)); ?>
        </div>
        <?php
        $options = [
            'Spectacle' => 'Spectacle',
            'Activite' => 'Activite',
            'Restaurant' => 'Restaurant',
            'Parc d\'attractions' => 'Parc d\'attractions',
            'Visite' => 'Visite'
        ];
        CheckboxSelect::render(
            'custom-class',
            'checkbox-select-id',
            'nomcategorie',
            false,
            $options,
            $nomcategories
        );
        $options = [
            'ouvertetferme'=> 'Ouvert et fermé',
            'ouvert'=> 'Ouvert',
            'ferme'=> 'Fermé'
        ];
        Select::render(
            'custom-class',
            'select-etat', 
            'etat',
            false,
            $options,
            isset($_GET['etat']) ? $_GET['etat'] : 'tout'
        );
        
        ?>
        <?php Button::render(text: "Rechercher", submit: true); ?>
        </div>
    </form>
    <br>

    <div>
        <!-- Boutons de tri -->
        <?php Button::render(text: "Trier par prix croissant", type: "member", onClick: "document.getElementById('sortInput').value='valprix ASC'; trier('valprix ASC')"); ?>
        <?php Button::render(text: "Trier par prix décroissant", type: "member", onClick: "document.getElementById('sortInput').value='valprix DESC'; trier('valprix DESC')"); ?>
        <?php Button::render(text: "Trier par date croissante", type: "member", onClick: "document.getElementById('sortInput').value='idoffre ASC'; trier('idoffre ASC')"); ?>
        <?php Button::render(text: "Trier par date décroissante", type: "member", onClick: "document.getElementById('sortInput').value='idoffre DESC'; trier('idoffre DESC')"); ?>
        <?php Button::render(text: "Réinitialiser", type: "member", onClick: "window.location.href='/pages/visiteur/listeOffre/listeOffre.php'"); ?>
    </div>

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
