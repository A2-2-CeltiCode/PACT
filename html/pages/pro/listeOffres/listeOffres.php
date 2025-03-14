<?php

session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "membre") {
    header("Location: /pages/membre/accueil/accueil.php");
} elseif (!isset($_SESSION['idCompte'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
}

// Importation des composants
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
use \composants\Select\Select;
use \composants\CheckboxSelect\CheckboxSelect;
use \composants\Checkbox\Checkbox;
use controlleurs\Offre\Offre;
use composants\Input\Input;
use composants\Button\Button;
use composants\Button\ButtonType;

require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
require_once  $_SERVER["DOCUMENT_ROOT"] . '/composants/CheckboxSelect/CheckboxSelect.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/composants/Checkbox/Checkbox.php';
require_once '../../../trie/fonctionTrie.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/fonctionTrie.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/barretrie.php";
require_once "../../../composants/CartePro/offre.php";
require_once "./verifFacture.php";
require_once "./verifHistorique.php";
require_once "./verifFindemois.php";

// Connexion à la base de données
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';
$idCompte = $_SESSION['idCompte'];
$status = $_GET['status'] ?? 'enligne';
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$pdo = new PDO("$driver:host=$server;port=5432;dbname=$dbname", $dbuser, $dbpass);

verifierEtCreerFacturesMensuelles($idCompte, $dbh);
verifierEtCreerHistoriqueMensuel($idCompte, $dbh);
mettreAJourJourFin($dbh, $idCompte);
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
$note = isset($_GET['note']) ? $_GET['note'] : null;
$query = "SELECT * FROM offres WHERE 1=1";

$nomcategories = isset($_GET['nomcategorie']) ? explode(',', $_GET['nomcategorie']) : ['Tout'];
$gamme = isset($_GET['option']) ? explode(',', $_GET['option']) : null;

// Récupération des résultats
$resultats = getOffres($pdo, $trie, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation,$etat,$status,$idCompte,$note,$gamme);

// Vérifiez si la requête est une requête AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');

    $offreHandler = new OffreHandler($pdo, $resultats, $offresMessage);
    $offres = [];
    foreach ($resultats as $item) {
        $offres[] = (string)$offreHandler->getOffreHtml($item);
    }


    $nombreOffres = count($resultats);

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
    <link rel="stylesheet" href="listeOffres.css">
    <link rel="stylesheet" href="../../../ui.css">
    <link rel="stylesheet" href="../../../composants/Label/Label.css">
    <script src="../../../trie/trieGeneral.js"></script>
</head>
<?php Header::render(HeaderType::Pro); ?>

<body>



    <div class="titre-page">
        <h1>Mes Offres</h1>
        <a href="../creerOffre/creerOffre.php"><?php Button::render("btn-cree", "","bouton pour crée une offre", "Créer une Offre", ButtonType::Pro, "", true); ?></a>
    </div>
    <section>
        <div class="barre trie-visible" id="styleShadow">
            <?php
    Trie::render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories,$status,$note);
    ?>
    </div>
    <div class="rangement">
    <br>



    <div class="onglets">
    
    <a href="javascript:void(0);" class="onglet <?php echo ($status === 'enligne') ? 'actif' : ''; ?>" onclick="changerStatus('enligne')">En ligne</a>
    <a href="javascript:void(0);" class="onglet <?php echo ($status === 'horsligne') ? 'actif' : ''; ?>" onclick="changerStatus('horsligne')">Hors ligne</a>
    </div>
    <p id="nombreOffres">
    <div class="rangement">
        <br>
        
        

        
        
        
        <div id="resultats" class="offres-container">
        <!-- Affichage des résultats -->
        <p id="nombreOffres">Nombre d'offres affichées : <?php echo count($resultats); ?></p>
        <?php
        $offreHandler = new OffreHandler($pdo, $resultats, $offresMessage);
        $offreHandler->displayOffres();
        ?>
    </div>
    </div>
    </section>
    <?php Footer::render(FooterType::Pro); ?>

</body>

</html>
