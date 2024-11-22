<?php
use \composants\Select\Select;
use \composants\CheckboxSelect\CheckboxSelect;
use controlleurs\Offre\Offre;
require_once $_SERVER['DOCUMENT_ROOT'] . '/controlleurs/Offre/Offre.php';
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
require_once  $_SERVER["DOCUMENT_ROOT"] . '/composants/CheckboxSelect/CheckboxSelect.php';
require_once 'fonctionTrie.php';


// Connexion à la base de données
include 'connect_params.php';
$pdo = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

// Récupération des paramètres de la requête
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'idoffre DESC';
$titre = isset($_GET['titre']) ? $_GET['titre'] : '';
$minPrix = isset($_GET['minPrix']) ? $_GET['minPrix'] : null;
$maxPrix = isset($_GET['maxPrix']) ? $_GET['maxPrix'] : null;
$nomcategories = isset($_GET['nomcategorie']) ? (array)$_GET['nomcategorie'] : ['Tout'];

// Récupération des résultats
$resultats = getOffres($pdo, $sort, $minPrix, $maxPrix, $titre, $nomcategories);

// Vérifiez si la requête est une requête AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    $offres = [];
    foreach ($resultats as $item) {
        $offre = new Offre($item['titre'], $item['nomcategorie'], "o", $item['nomimage'], "o", $item['idoffre'], $item['tempsenminutes']);
        $offres[] = (string)$offre;
    }
    echo json_encode($offres);
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
        gap: 10px; /* Espace entre les offres */
    }
    </style>
    <script src="trieGeneral.js"></script>
</head>
<body>
    <form id="searchForm" method="GET" action="">
        <!-- Formulaire de tri -->
        <input type="hidden" id="sortInput" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
        <input type="text" name="titre" placeholder="Titre" value="<?php echo htmlspecialchars($titre); ?>">
        <input type="number" name="minPrix" placeholder="Prix Min" value="">
        <input type="number" name="maxPrix" placeholder="Prix Max" value="">
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
        ?>
        <button type="submit">Rechercher</button>
    </form>

    <div>
        <!-- Boutons de tri -->
        <button type="button" onclick="document.getElementById('sortInput').value='valprix ASC'; trier('valprix ASC')">Trier par prix croissant</button>
        <button type="button" onclick="document.getElementById('sortInput').value='valprix DESC'; trier('valprix DESC')">Trier par prix décroissant</button>
        <button type="button" onclick="document.getElementById('sortInput').value='idoffre ASC'; trier('idoffre ASC')">Trier par date croissante</button>
        <button type="button" onclick="document.getElementById('sortInput').value='idoffre DESC'; trier('idoffre DESC')">Trier par date décroissante</button>
        <button type="button" onclick="window.location.href='/bordel/trieGeneral.php'">Réinitialiser</button>
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
