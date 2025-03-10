<?php 
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


$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$sql = "SELECT coordonneesX,coordonneesY   FROM pact.vue_offres";
$sth = $dbh->prepare($sql);
$sth->execute();
$offre = $sth->fetchAll();

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
Trie::render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Carte Leaflet</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
      #map {
        height: 600px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="test.js"></script>
  </body>
</html>
