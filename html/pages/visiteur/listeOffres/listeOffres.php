<?php
session_start();
use composants\Button\ButtonType;
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
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/barreTrieMembre.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/carte/carte.php';

// Connexion à la base de données
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';


$pdo = new  PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
// Récupération des paramètres de la requête
$sort = $_GET['sort'] ?? 'idoffre DESC';
$titre = $_GET['titre'] ?? '';
$localisation = $_GET['localisation'] ?? '';
$minPrix = $_GET['minPrix'] ?? null;
$maxPrix = $_GET['maxPrix'] ?? null;
$etat = $_GET['etat'] ?? 'ouvertetferme';
$ouverture = $_GET['ouverture'] ?? null;
$fermeture = $_GET['fermeture'] ?? null;
$trie = $_GET['trie'] ?? 'idoffre DESC';
$nomcategories = isset($_GET['nomcategorie']) ? explode(',', $_GET['nomcategorie']) : ['Tout'];
$option = isset($_GET['option']) ? explode(',', $_GET['option']) : [];
$note = $_GET['note'] ?? null;

// Récupération des résultats
$resultats = getOffres($pdo, $trie, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation, $etat, null, null, $note, $option);

// Vérifiez si la requête est une requête AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $offres = [];
    foreach ($resultats as $item) {
        $sql = 'SELECT denominationsociale FROM pact._comptepro WHERE idcompte = :idcompte';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idcompte', $item['idcompte'], PDO::PARAM_INT);
        $stmt->execute();
        $proDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item['moynotes'] == null) {
            $item['moynotes'] = 0;
        }
        $item['nomgamme'] = $item['nomgamme'] ?? 'test';
        $item['valprix'] = $item['valprix'] ?? 'test';
        $offre = new Offre($item['titre'], $item['nomcategorie'], "o", $item['nomimage'], "o", $item['idoffre'], $item['tempsenminutes'], $item['moynotes'], $item['nomoption'], $item['heureouverture'], $item['heurefermeture'], $item['valprix'], $item['nomgamme']);
        $offres[] = (string)$offre;
    }
    $nombreOffres = count($offres);
    echo json_encode(['offres' => $offres, 'nombreOffres' => $nombreOffres]);
    exit;
}

// Si ce n'est pas une requête AJAX, inclure le HTML complet
?>
<?php Header::render(HeaderType::Guest); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche d'Offres - PACT</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="listeOffre.css">
    <link rel="stylesheet" href="listeOffre.js">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="logo PACT">
</head>
<body>
<div id="pageOverlay" class="page-overlay"></div>

<input type="hidden" name="status" value="null" />
<!-- Nouveau bouton pour afficher/masquer barretrie -->

<div id="barretrie">
<?php
Trie::render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories);
$renderer = new Carte();
    $renderer->render();
?>
</div>
<br>

<div id="nombreOffres">
    <p>Nombre d'offres affichées : <?php echo count($resultats); ?></p>
</div>
<div id="resultats" class="offres-container">

    <!-- Affichage des résultats -->
    <?php
    
    foreach ($resultats as $item) {
        $sql = 'SELECT denominationsociale FROM pact._comptepro WHERE idcompte = :idcompte';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idcompte', $item['idcompte'], PDO::PARAM_INT);
        $stmt->execute();
        $proDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item['moynotes'] == null) {
            $item['moynotes'] = 0;
        }
        $item['nomgamme'] = $item['nomgamme'] ?? 'test';
        $item['valprix'] = $item['valprix'] ?? 'test';

        $offre = new Offre($item['titre'], $item['nomcategorie'], $item['ville'], $item['nomimage'], $proDetails['denominationsociale'], $item['idoffre'], $item['tempsenminutes'], $item['moynotes'], $item['nomoption'], $item['heureouverture'], $item['heurefermeture'], $item['valprix'], $item['nomgamme']);
        echo $offre;
    }
    ?>
</div>

<script src="listeOffre.js"></script>
</body>
<?php Footer::render(FooterType::Guest); ?>
</html>