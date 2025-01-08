<?php
// Inclusion des fichiers nécessaires pour les composants de l'interface
use \composants\Button\Button;
use \composants\Button\ButtonType;
use \composants\Label\Label;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";

session_start();
$idCompte = $_SESSION['idCompte'];

// Récupération de l'identifiant de l'offre
$idOffre = $_POST['idOffre'] ?? '1';
$idOffre = $_GET['idOffre'] ?? $idOffre;

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    
    // Ajout des filtres pour trier les avis
    $sortBy = $_GET['sortBy'] ?? 'date_desc';
    $filterBy = $_GET['filterBy'] ?? 'all';

    $query = "SELECT titre, note, commentaire, pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis, estvu FROM pact.vue_avis JOIN pact.vue_compte_membre ON pact.vue_avis.idCompte = pact.vue_compte_membre.idCompte WHERE idOffre = $idOffre";

    if ($filterBy === 'viewed') {
        $query .= " AND estvu = true";
    } elseif ($filterBy === 'not_viewed') {
        $query .= " AND estvu = false";
    }

    if ($sortBy === 'date_asc') {
        $query .= " ORDER BY datevisite ASC";
    } elseif ($sortBy === 'date_desc') {
        $query .= " ORDER BY datevisite DESC";
    } elseif ($sortBy === 'note_asc') {
        $query .= " ORDER BY note ASC";
    } elseif ($sortBy === 'note_desc') {
        $query .= " ORDER BY note DESC";
    }

    $avis = $dbh->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcul de la moyenne des notes
    $totalNotes = 0;
    $nombreAvis = count($avis);
    foreach ($avis as $avi) {
        $totalNotes += $avi['note'];
    }
    $moyenneNotes = $nombreAvis > 0 ? $totalNotes / $nombreAvis : 0;

    $imagesAvis = [];
    foreach ($avis as $avi) {
        $img = [];
        foreach ($dbh->query("select nomimage from pact.vue_image_avis WHERE idavis = {$avi['idavis']}")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $img[] = $item["nomimage"];
        }
        $imagesAvis[$avi['idavis']] = $img;
    }

    $offre = $dbh->query('SELECT * FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $typeOffre = $dbh->query('SELECT type_offre FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();

    $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre['type_offre'])));
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }

    // Requêtes pour obtenir les informations spécifiques à l'offre
    $stmt = $dbh->query('SELECT estguidee FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC);
    $guidee = $stmt->fetch();

    // Récupération des autres informations pertinentes
    $minutesVisite = $dbh->query('SELECT tempsenminutes FROM pact.vue_visite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $prestation = $dbh->query('SELECT prestation FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $capacite = $dbh->query('SELECT capacite FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $minutesActivite = $dbh->query('SELECT tempsenminutes FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $ageMinimumActivite = $dbh->query('SELECT agemin FROM pact.vue_activite WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $ageMinimumParc = $dbh->query('SELECT agemin FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $nbAttraction = $dbh->query('SELECT nbattractions FROM pact.vue_parc_attractions WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $gammeRestaurant = $dbh->query('SELECT nomgamme FROM pact.vue_restaurant WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $minutesSpectacle = $dbh->query('SELECT tempsenminutes FROM pact.vue_spectacle WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $adresse = $dbh->query('SELECT codepostal, ville, rue FROM pact._offre NATURAL JOIN pact._adresse WHERE idoffre =' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();
    $images = $dbh->query('SELECT idImage, nomImage FROM pact._image WHERE idOffre = ' . "'$idOffre'", PDO::FETCH_ASSOC)->fetchAll();
    $carte = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._parcAttractions JOIN pact._image ON pact._parcAttractions.carteParc = pact._image.idImage WHERE pact._parcAttractions.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $menu = $dbh->query('SELECT pact._image.idImage, pact._image.nomImage FROM pact._restaurant JOIN pact._image ON pact._restaurant.menuRestaurant = pact._image.idImage WHERE pact._restaurant.idOffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $horaires = substr($offre['heureouverture'], 0, 5) . " - " . substr($offre['heurefermeture'], 0, 5);
    // Vérification de l'existence de l'offre
    if (!$offre) {
        throw new Exception("Aucune offre trouvée");
    }

    // Vérification du nombre de réponses de l'utilisateur pour cette offre
    $stmt = $dbh->prepare("SELECT COUNT(*) as totalReponses FROM pact._reponseavis WHERE idCompte = :idCompte AND idAvis IN (SELECT idAvis FROM pact.vue_avis WHERE idOffre = :idOffre)");
    $stmt->execute([':idCompte' => $idCompte, ':idOffre' => $idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalReponses = $result ? $result['totalReponses'] : 5;
    
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Déconnexion de la base de données
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre</title>
    <link rel="stylesheet" href="detailsOffre.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>
<?php Header::render(HeaderType::Pro); ?>
<button class="retour"><a href="../listeOffres/listeOffres.php"><img
            src="../../../ressources/icone/arrow_left.svg"></a></button>

<body>
    
    <div>
        <div class="filters">
            <label for="sortBy">Trier par:</label>
            <select id="sortBy">
                <option value="date_desc" selected>Date décroissante</option>
                <option value="date_asc">Date croissante</option>
                <option value="note_desc">Note décroissante</option>
                <option value="note_asc">Note croissante</option>
            </select>

            <label for="filterBy">Filtrer par:</label>
            <select id="filterBy">
                <option value="all">Tous</option>
                <option value="viewed">Vus</option>
                <option value="not_viewed">Non vus</option>
            </select>
        </div>
        <div class="liste-avis">
            <div>
                <h1>Avis:</h1>
            </div>
            <div>
                <?php
                foreach ($avis as $avi) {
                    if (!isset($avi["idavis"])) {
                        continue;
                    }
                    
                    $stmt = $dbh->prepare("SELECT idreponse, commentaire, to_char(datereponse,'DD/MM/YY') as datereponse FROM pact._reponseavis WHERE idAvis = :idAvis");
                    $stmt->execute([':idAvis' => $avi['idavis']]);
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="avi <?= !$avi['estvu'] ? 'non-vu' : '' ?> <?= empty($reponses) ? 'sans-reponse' : '' ?>" data-idavis="<?= $avi["idavis"] ?>">
                        <div>
                            <p class="avi-title">
                                <?= $avi["titre"] ?>
                            </p>
                            <div class="note">
                                <?php
                                for ($i = 0; $i < floor($avi["note"]); $i++) {
                                    echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_pleine.svg");
                                }
                                if (fmod($avi["note"], 1) != 0) {
                                    echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_mid.svg");
                                }
                                for ($i = 0; $i <= 4 - $avi["note"]; $i++) {
                                    echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_vide.svg");
                                }
                                ?>
                            </div>
                        </div>
                        <p class="avi-content">
                            <?= $avi["commentaire"] ?>
                        </p>
                        <div>
                            <?php
                            foreach ($imagesAvis[$avi["idavis"]] as $image) {
                                echo "<img src='/ressources/avis/{$avi["idavis"]}/$image' width='64' height='64' onclick=\"openUp(event)\">";
                            }
                            ?>
                        </div>
                        <div>
                            <p>
                                <?= $avi["pseudo"] ?>
                            </p>
                            <p>
                                le <?= $avi["datevisite"] ?>
                            </p>
                            <p>
                                en <?= $avi["contextevisite"] ?>
                            </p>
                        </div>
                        <div>
                            <?php Button::render("btn", "", "Signaler", ButtonType::Pro, "", false); ?>
                            <?php if (empty($reponses) && $totalReponses < 3): ?>
                                <?php Button::render("btn-repondre", "btn-repondre", "Répondre", ButtonType::Pro, "", false); ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($reponses)): ?>
                            <div class="reponses">
                                <?php foreach ($reponses as $reponse): ?>
                                    <div class="reponse">
                                        <p class="reponse-content">
                                            <?= $reponse["commentaire"] ?>
                                        </p>
                                        <div>
                                            <p>
                                                <?= $reponse["pseudo"] ?>
                                            </p>
                                            <p>
                                                le <?= $reponse["datereponse"] ?>
                                            </p>
                                        </div>
                                        <form action="supprimerReponse.php" method="POST">
                                            <input type="hidden" name="idReponse" value="<?= $reponse['idreponse'] ?>">
                                            <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                                            <?php Button::render("btn-supprimer", "", "Supprimer", ButtonType::Pro, "", true); ?>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="popup" id="popup-repondre">
            <div class="popup-content">
                <span class="close">&times;</span>
                <form action="envoyerReponse.php" method="POST">
                    <input type="hidden" name="idAvis" id="popup-idAvis">
                    <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                    <textarea name="reponse" placeholder="Votre réponse..." required></textarea>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        const idOffre = <?= json_encode($idOffre) ?>;
    </script>
    <script src="detailsOffre.js"></script>
    <?php Footer::render(FooterType::Pro); 
    $dbh = null;
    ?>
</body>

</html>