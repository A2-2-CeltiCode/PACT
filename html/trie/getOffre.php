<?php

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
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/fonctionTrie.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/barreTrieMembre.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';

$pdo = new PDO("$driver:host=$server;port=5432;dbname=$dbname", $dbuser, $dbpass);

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
$points = getOffres($pdo, $trie, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation, $etat, null, null, $note, $option);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $offres = [];
    foreach ($points as $item) {
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
    echo json_encode(['offres' => $offres, 'nombreOffres' => $nombreOffres,'points'=>$points]);

    exit;
}

echo json_encode($points);

?>