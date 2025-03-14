<?php
error_reporting(E_ALL ^ E_WARNING);

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

$idAvisPrioritaire = $_POST['idAvis'] ?? $_GET['idOffre'] ?? null;
try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    
    // Ajout des filtres pour trier les avis
    $sortBy = $_GET['sortBy'] ?? 'date_desc';
    $filterBy = $_GET['filterBy'] ?? 'all';

    // Récupérer tous les idOffre du compte
    $queryOffres = "SELECT idOffre FROM pact._offre WHERE idCompte = :idCompte";
    $stmtOffres = $dbh->prepare($queryOffres);
    $stmtOffres->execute([':idCompte' => $idCompte]);
    $offres = $stmtOffres->fetchAll(PDO::FETCH_COLUMN);

    if (empty($offres)) {
        $avis = [];
    } else {
        $offresPlaceholders = implode(',', array_fill(0, count($offres), '?'));

        $query = "SELECT a.*, o.*, c.pseudo, a.titre as avis_titre, o.titre as offre_titre FROM pact._avis a
                  JOIN pact._offre o ON a.idOffre = o.idOffre
                  JOIN pact._comptemembre c ON a.idCompte = c.idCompte
                  WHERE o.idOffre IN ($offresPlaceholders)";

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
        } elseif ($sortBy === 'non_vu') {
            $query .= " ORDER BY estvu ASC, datevisite DESC";
        }

        $stmt = $dbh->prepare($query);
        $stmt->execute($offres);
        $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    if ($idAvisPrioritaire) {
        usort($avis, function($a, $b) use ($idAvisPrioritaire) {
            if ($a['idavis'] == $idAvisPrioritaire) return -1;
            if ($b['idavis'] == $idAvisPrioritaire) return 1;
            return 0;
        });
    }

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

    $thumbsUpMap = [];
    $thumbsDownMap = [];

    foreach ($avis as $avi) {
        $thumbsUpMap[$avi['idavis']] = $avi['poucehaut'];
        $thumbsDownMap[$avi['idavis']] = $avi['poucebas'];
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
<button class="retour" title="bouton fleche gauche"><a href="../listeOffres/listeOffres.php"><img
            src="../../../ressources/icone/arrow_left.svg"></a></button>

<body>
    <!-- Toast de confirmation -->
    <div id="toast" class="toast">Avis bien signalé</div>
    <div>
    <div>
        <div class="liste-avis">
            <div>
                <h1>Avis</h1>
            </div>
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
            <div>
                <?php
                foreach ($avis as $avi) {
                    if (!isset($avi["idavis"])) {
                        continue;
                    }
                    
                    $stmt = $dbh->prepare("SELECT idreponse, commentaire, to_char(datereponse,'DD/MM/YY') as datereponse FROM pact._reponseavis WHERE idAvis = :idAvis");
                    $stmt->execute([':idAvis' => $avi['idavis']]);
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Ajouter la classe 'non-vu' si l'avis n'a pas été vu
                    $nonVuClass = $avi['estvu'] ? '' : 'non-vu';
                    ?>
                    <div class="avi <?= $nonVuClass ?>" data-idavis="<?= $avi["idavis"] ?>">
                        <?php if (!$avi['estvu']): ?>
                            <div class="non-vu">Non vu</div>
                        <?php endif; ?>
                        <div>
                            <p class="avi-title">
                            
                            <a href="../detailsOffre/detailsOffre.php?idOffre=<?= $avi['idoffre'] ?>"><?= $avi["titre"] ?></a>
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
                                le <?= $avi["datevisite"] ?>  en <?= $avi["contextevisite"] ?>
                            </p>

                        </div>
                        <div class="thumbs">
                            <button class="thumbs-up" title="like" data-idavis="<?= $avi["idavis"] ?>">👍 <?= $thumbsUpMap[$avi["idavis"]] ?? 0 ?></button>
                            <button class="thumbs-down" title="dislike" data-idavis="<?= $avi["idavis"] ?>">👎 <?= $thumbsDownMap[$avi["idavis"]] ?? 0 ?></button>
                        </div>
                        <div>
                            <?php Button::render("btn-signaler", "btn-signaler","bouton signaler", "Signaler", ButtonType::Pro, "", false); ?>
                            <?php if (empty($reponses) && $totalReponses < 3): ?>
                                <?php Button::render("btn-repondre", "btn-repondre","bouton de reponse","Répondre", ButtonType::Pro, "", false); ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($reponses)): ?>
                            <div class="reponses">
                                <?php foreach ($reponses as $reponse): ?>
                                    <div class="reponse">
                                    <h2>Réponse:</h2>
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
                                            <?php Button::render("btn-supprimer", "","bouton supprimer", "Supprimer", ButtonType::Pro, "", true); ?>
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
                    <button type="submit" title="bouton envoyer">Envoyer</button>
                </form>
            </div>
        </div>

        <!-- Popup pour afficher l'image en grand -->
        <div class="image-popup" id="image-popup">
            <span class="close">&times;</span>
            <img class="image-popup-content" id="image-popup-content">
        </div>

    </div>

    <script>
        const idOffre = <?= json_encode($idOffre) ?>;
    </script>
    <script src="detailsOffre.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const avisElements = document.querySelectorAll(".avi.non-vu");

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const idAvis = entry.target.dataset.idavis;
                        fetch(`markAsSeen.php?idAvis=${idAvis}`, {
                            method: 'POST'
                        }).then(response => {

                        });
                    }
                });
            });

            avisElements.forEach(avi => {
                observer.observe(avi);
            });

            // Script pour afficher l'image en grand
            const imagePopup = document.getElementById("image-popup");
            const imagePopupContent = document.getElementById("image-popup-content");
            const closeImagePopup = document.querySelector(".image-popup .close");

            document.querySelectorAll(".avi img").forEach(img => {
                img.addEventListener("click", function () {
                    imagePopupContent.src = this.src;
                    imagePopup.style.display = "block";
                });
            });

            closeImagePopup.addEventListener("click", function () {
                imagePopup.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === imagePopup) {
                    imagePopup.style.display = "none";
                }
            });
        });
    </script>
    <?php Footer::render(FooterType::Pro); 
    $dbh = null;
    ?>
</body>

</html>