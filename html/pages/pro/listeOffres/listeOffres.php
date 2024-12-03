<?php
session_start();
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
/*
if (!array_key_exists('idComtpe', $_SESSION) || is_null($_SESSION['idCompte'])) {
    header("Location: /pages/pro/connexionComptePro/connexionComptePro.php");
}*/

// Importation des composants
use composants\Input\Input;
use composants\Button\Button;
use composants\Button\ButtonType;
use composants\Label\Label;
use composants\Select\Select;
use composants\CheckboxSelect\CheckboxSelect;
use composants\Checkbox\Checkbox;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/CheckboxSelect/CheckboxSelect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Checkbox/Checkbox.php";
require_once  "fonctionTrie.php";

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;port=5432;dbname=$dbname", $dbuser, $dbpass);
    $status = $_GET['status'] ?? 'enligne'; // Statut par défaut

    $idCompte = '2';

    // Requête selon le statut
    $query = $status === 'enligne' ? 'SELECT * FROM pact._offre WHERE estEnLigne = TRUE and idCompte = ' . $idCompte : 'SELECT * FROM pact._offre WHERE estEnLigne = FALSE and idCompte = ' . $idCompte;

    $stmt = $dbh->query($query);
    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($offres)) {
        $offresMessage = "Aucune offre trouvée.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs
    print "Erreur !: " . htmlspecialchars($e->getMessage()) . "<br>";
    die();
}


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
$resultats = getOffres($dbh, $sort, $minPrix, $maxPrix, $titre, $nomcategories, $ouverture, $fermeture, $localisation,$etat);

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
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Offres</title>
    <link rel="stylesheet" href="listeOffres.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<?php Header::render(HeaderType::Pro); ?>

<body>
<form id="searchForm" method="GET" action="">
    
    
<div class="titre-page">
        <h1>Mes Offres</h1>
        <a href="../creerOffre/creerOffre.php"><?php Button::render("btn-cree", "", "Créer une Offre", ButtonType::Pro,
                "", true); ?></a>
    </div>
    <div class="onglets">
        <a href="?status=enligne" class="onglet <?php echo ($status === 'enligne') ? 'actif' : ''; ?>">En ligne</a>
        <a href="?status=horsligne" class="onglet <?php echo ($status === 'horsligne') ? 'actif' : ''; ?>">Hors
            ligne</a>
    </div>
<div class="conteneur-offres">
    



    <section class="conteneur-filtres-offres">

    <tri class="tris">
        <h5>Tri</h5>
        <?php Select::render(
            'menutris',
            'filtre', 
            'filtre',
            false,
            [
                "prixCroissant" => "Tri par prix croissant",
                "prixDecroissant" => "Tri par prix décroissant",
                "dateCroissante" => "Tri par date croissante",
                "dateDecroissante" => "Tri par date décroissante",
            ],
            isset($_GET['filtre']) ? $_GET['filtre'] : 'tout'
        );
        ?>
        <hr>
        <h5>Filtres</h5>

        <?php
        $options = [
            ['Activite' => 'Activite', 'name' => 'options[]', 'value' => 'Activite', 'text' => 'Activite', 'checked' => false],
            ['Spectacle' => 'Spectacle', 'name' => 'options[]', 'value' => 'Spectacle', 'text' => 'Spectacle', 'checked' => false],
            ['Restaurant' => 'Restaurant', 'name' => 'options[]', 'value' => 'Restaurant', 'text' => 'Restaurant', 'checked' => false],
            ['Parc d\'attractions' => 'Parc d\'attractions', 'name' => 'options[]', 'value' => 'Parc d\'attractions', 'text' => 'Parc d\'attractions', 'checked' => false],
            ['Visite' => 'Visite', 'name' => 'options[]', 'value' => 'Visite', 'text' => 'Visite', 'checked' => false],
        ];
    
        foreach ($options as $option) {
            Checkbox::render(
                $class = "",
                $id = $option['id'],
                $name = $option['name'],
                $value = $option['value'],
                $text = $option['text'],
                $required = false,
                $checked = $option['checked']
            );
        }
        ?>
        <hr>
        <input type="hidden" id="sortInput" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
        <div class="input">
        <?php Input::render(name:"titre", type:"text", placeholder:'Titre*', value: htmlspecialchars($titre)); ?>
        <?php Input::render(name:"localisation", type:"text", placeholder:'localisation', value: htmlspecialchars($localisation)); ?>
        
        <?php Input::render(name:"minPrix", type:"number", placeholder:'Prix Min', value: htmlspecialchars($minPrix)); ?>
        <?php Input::render(name:"maxPrix", type:"number", placeholder:'Prix Max', value: htmlspecialchars($maxPrix)); ?>
        </div>
    </tri>

    <div class="liste-offres">
        <?php if (!empty($offresMessage)): ?>
            <p><?php echo $offresMessage; ?></p>
        <?php else: ?>
            <?php foreach ($offres as $offre): ?>
                <?php
                $idoffre = $offre['idoffre'];

                // Récupération du type d'offre
                $stmt = $dbh->prepare('SELECT nomCategorie FROM (
                        SELECT nomCategorie FROM pact._spectacle WHERE idoffre = :idoffre UNION ALL
                        SELECT nomCategorie FROM pact._restaurant WHERE idoffre = :idoffre UNION ALL
                        SELECT nomCategorie FROM pact._parcAttractions WHERE idoffre = :idoffre UNION ALL
                        SELECT nomCategorie FROM pact._activite WHERE idoffre = :idoffre UNION ALL
                        SELECT nomCategorie FROM pact._visite WHERE idoffre = :idoffre
                    ) AS categories');
                $stmt->execute([':idoffre' => $idoffre]);
                $typeOffre = $stmt->fetchColumn();

                // Normalisation du type d'offre
                $typeOffre = str_replace([" ", "'"], '_', strtolower($typeOffre));
//                if ($typeOffre === 'parc_dattractions') {
//                    $typeOffre = 'parc_attractions';
//                }

                // Récupération des informations de l'offre
                $raisonSociete = $dbh->query('SELECT cp.raisonsocialepro FROM pact._offre o JOIN pact._comptePro cp ON o.idCompte = cp.idCompte WHERE o.idoffre = ' . $idoffre)
                    ->fetch();
                $adresse = $dbh->query('SELECT ville, codepostal FROM pact._adresse WHERE idadresse = ' . $offre['idadresse'])
                    ->fetch();
                $adresseTotale = $adresse['ville'] . ', ' . $adresse['codepostal'];
                $images = $dbh->query('SELECT nomimage FROM pact._image WHERE idoffre = ' . $idoffre)->fetch();
                ?>
                <div class="carte-offre" onclick="document.getElementById('form-<?php echo $idoffre; ?>').submit();">
                    <form id="form-<?php echo $idoffre; ?>" action="../detailsOffre/detailsOffre.php" method="POST">
                        <input type="hidden" name="idOffre" value="<?php echo $idoffre; ?>">

                    </form>
                    <div class="image-offre">
                        <?php
                        // Affichage de l'image ou d'un SVG par défaut
                        if (empty($images)) {
                            echo '<div class="image-offre"><svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960" width="10em" fill="#000000">
                                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                                </svg></div>';
                        } else {
                            $path_img = "../../../ressources/" . $idoffre . "/images/" . $images['nomimage'];
                            if (file_exists($path_img)) {
                                echo '<img src="' . htmlspecialchars($path_img) . '" class="image-carte">';
                            } else {
                                echo '<div class="image-offre"><svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960" width="10em" fill="#000000">
                                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                                    </svg></div>';
                            }
                        }
                        ?>
                    </div>
                    <div class="details-offre">
                        <div class="donnees-offre">
                            <div class="titre">
                                <?php if ($typeOffre === 'parc_d_attractions') {
                                    Label::render('details-offre', '', '', 'Parc d\'attraction',
                                        "../../../ressources/icone/parc_d_attraction.svg");
                                } else {
                                    Label::render('details-offre', '', '', ucfirst(htmlspecialchars($typeOffre)),
                                        "../../../ressources/icone/$typeOffre.svg");
                                }
                                ?>
                            </div>
                            <?php Label::render('details-offre .titre', '', '', htmlspecialchars($offre['titre'])); ?>
                            <div class="infos-offre">
                                <?php Label::render('', '', '', $raisonSociete['raisonsocialepro']); ?>
                                <?php Label::render('', '', '', $adresseTotale,
                                    "../../../ressources/icone/localisateur.svg"); ?>
                            </div>
                        </div>
                        <div class="prix-offre">
                            <?php Label::render('prix', '', '', htmlspecialchars($offre['nomforfait'])); ?>
                            <?php Label::render('', '', '', 'Option : ' . htmlspecialchars($offre['nomoption'])); ?>
                        </div>
                    </div>
                    <div class="button-container">
                        <form action="../modifierOffre/modifierOffre.php" method="POST">
                            <input type="hidden" name="idOffre" value="<?php echo $idoffre;?>">
                            <?php Button::render("button-modif", "", "Modifier", ButtonType::Pro, "", true); ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </section>
</div>
<?php Footer::render(FooterType::Pro); ?>
</form>
</body>

</html>