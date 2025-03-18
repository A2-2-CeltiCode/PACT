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




        $query = "SELECT a.*, o.*, c.pseudo, a.titre as avis_titre, o.titre as offre_titre FROM pact._avis a
                  JOIN pact._offre o ON a.idOffre = o.idOffre
                  JOIN pact._comptemembre c ON a.idCompte = c.idCompte
                  WHERE :idCompte = a.idCompte";



        if ($sortBy === 'date_asc') {
            $query .= " ORDER BY datevisite ASC";
        } elseif ($sortBy === 'date_desc') {
            $query .= " ORDER BY datevisite DESC";
        } elseif ($sortBy === 'note_asc') {
            $query .= " ORDER BY note ASC";
        } elseif ($sortBy === 'note_desc') {
            $query .= " ORDER BY note DESC";
        }

        $stmt = $dbh->prepare($query);
        $stmt->execute([':idCompte' => $idCompte]);
        $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    $stmt = $dbh->prepare("SELECT COUNT(*) as totalReponses FROM pact._reponseavis WHERE idCompte = :idCompte;");
    $stmt->execute([':idCompte' => $idCompte]);
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
<?php Header::render(HeaderType::Member); ?>

<button class="retour" title="bouton fleche gauche" ><a href="../listeOffres/listeOffres.php"><img
            src="../../../ressources/icone/arrow_left.svg" alt="imgAvis"></a></button>

<body>
    <!-- Toast de confirmation -->
    <div id="toast" class="toast">Avis bien signalé</div>
    <section>
        <div class="liste-avis">
            <div class="avis-header">
                <h1>Avis</h1>
            </div>
            <?php
            if ($nombreAvis > 0){
                ?>

            
            <aside class="filters">
                <label for="sortBy">Trier par:</label>
                <select id="sortBy">
                    <option value="date_desc" selected>Date décroissante</option>
                    <option value="date_asc">Date croissante</option>
                    <option value="note_desc">Note décroissante</option>
                    <option value="note_asc">Note croissante</option>
                </select>
            </aside>

            <article class="container-avis">
                <?php
                foreach ($avis as $avi) {
                    if (!isset($avi["idavis"])) {
                        continue;
                    }
                    
                    $stmt = $dbh->prepare("SELECT idreponse, commentaire, to_char(datereponse,'DD/MM/YY') as datereponse FROM pact._reponseavis WHERE idAvis = :idAvis");
                    $stmt->execute([':idAvis' => $avi['idavis']]);
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="avi" data-idavis="<?= $avi["idavis"] ?>">
                    <div class="container-head-avis">
                        <div>
                            <p class="avi-title">
                                <?= $avi["titre"] ?>
                            </p>
                            <p>
                                en <?= $avi["contextevisite"] ?>
                            </p>
                        </div>
                        
                        <div class="note" title="icone étoiles">
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

                        <div class="container-img-avis">
                            <?php
                            foreach ($imagesAvis[$avi["idavis"]] as $image) {
                                echo "<img src='/ressources/avis/{$avi["idavis"]}/$image' width='64' height='64' onclick=\"openUp(event)\">";
                                
                            }
                            ?>
                        </div>
                        <div class="container-bottom-avis">
                            <div class="container-infos-avis">
                                <p>
                                    <?php 
                                      if ($avi["idcompte"] == $idCompte) {
                                          echo $avi["pseudo"]." (vous)"; 
                                      }
                                      else {
                                          echo $avi["pseudo"];
                                    }?>
                                </p>
                                <p>
                                    le <?= $avi["datevisite"] ?>
                                </p>
                            </div>
                            <div class="thumbs">
                                <button class="thumbs-up" title="like" data-idavis="<?= $avi["idavis"] ?>">👍 <?= $thumbsUpMap[$avi["idavis"]] ?? 0 ?></button>
                                <button class="thumbs-down" title="dislike" data-idavis="<?= $avi["idavis"] ?>">👎 <?= $thumbsDownMap[$avi["idavis"]] ?? 0 ?></button>
                            </div>
                        </div>   
                        <?php if ($avi['idcompte'] == $idCompte): ?>
                            <button class="btn-supprimer" title="Supprimer un avis" data-idavis="<?= $avi["idavis"] ?>">Supprimer</button>
                        <?php endif; ?>
                        <?php if (!empty($reponses)): ?>
                            <div class="reponses">
                                <?php foreach ($reponses as $reponse): ?>
                                    <div class="reponse">
                                        <p class="avis-title">Réponse:</p>
                                        <p class="avi-content">
                                            <?= $reponse["commentaire"] ?>
                                        </p>
                                        <div class="container-info-avis">
                                            <p>
                                                <?= $reponse["pseudo"] ?>
                                            </p>
                                            <p>
                                                le <?= $reponse["datereponse"] ?>
                                            </p>
                                            
                                            <?php Button::render("btn-signaler", "btn-signaler","bouton signaler", "Signaler", ButtonType::Member, "", false); ?>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </article>
            <?php
            } else  {
                echo "<p>Aucun avis n'a été trouvé pour cette offre.</p>";
            }
            ?>
        </div>

        <div class="popup" id="popup-repondre">
            <div class="popup-content">
                <span class="close">&times;</span>
                <form action="envoyerReponse.php" method="POST">
                    <input type="hidden" name="idAvis" id="popup-idAvis">
                    <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
                    <textarea name="reponse" placeholder="Votre réponse..." required></textarea>
                    <button type="submit" title="bouton Envoyer">Envoyer</button>
                </form>
            </div>
        </div>

        <div class="popup" id="popup-creer-avis">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Créer un nouvel avis</h2>
            <span class="close">&times;</span>
        </div>
        
        <form action="creerAvis.php" method="POST" enctype="multipart/form-data" class="avis-form">
            <input type="hidden" name="idOffre" value="<?= $idOffre ?>">
            
            <!-- Titre de l'avis -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("titre", "", "", "Titre de votre avis"); ?>
                </div>
                <?php Input::render(
                    "titre",
                    "Un titre qui résume votre expérience",
                    "text",
                    "titre"
                    
                ); ?>
            </div>

            
            <!-- Commentaire détaillé -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-commentaire", "", "", "Votre avis détaillé"); ?>
                </div>
                <?php Textarea::render(
                    class: "form-control commentaire",
                    id: "commentaire",
                    name: "commentaire",
                    placeholder: "Partagez votre expérience en détail...",
                    required: true
                ); ?>
            </div>

            <!-- Évaluation par étoiles -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-note", "", "", "Votre note"); ?>
                </div>
                <div class="rating" role="radiogroup" aria-label="Note sur 5 étoiles">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" 
                               name="note" 
                               id="star<?= $i ?>" 
                               value="<?= $i ?>"
                               aria-label="<?= $i ?> étoiles"
                               required>
                        <label for="star<?= $i ?>" title="<?= $i ?> étoiles">☆</label>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Contexte de la visite -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-contexte", "", "", "Contexte de votre visite"); ?>
                </div>
                <?php
                $optionsContexte = [
                    '' => 'Sélectionnez un contexte',
                    'Affaires' => 'Voyage d\'affaires',
                    'Couple' => 'En couple',
                    'Famille' => 'En famille',
                    'Amis' => 'Entre amis',
                    'Solo' => 'Voyage solo'
                ];
                Select::render(
                    'contexteVisite',
                    '',
                    'contexteVisite',
                    true,
                    $optionsContexte,
                    'form-control'
                );
                ?>
            </div>

            <!-- Upload de photos -->
            <div class="form-group">
                <div class="form-label">
                    <?php Label::render("label-photos", "", "", "Ajoutez des photos"); ?>
                </div>
                <div class="upload-section">
                    <?php InsererImage::render(
                        "drop-zone[]",
                        "Déposez vos photos ici ou cliquez pour sélectionner",
                        5,  // Limite à 5 photos

                    ) ?>
                    <div class="upload-preview" id="imagePreview"></div>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="form-group">
                <?php Button::render(
                    "btn-envoyer",
                    "submit-avis",
                    "bouton pour publier un avis",
                    "Publier votre avis",
                    ButtonType::Member,
                    "",
                    true
                ); ?>
            </div>
        </form>
    </div>
</div>

    </section>

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
    <?php Footer::render(FooterType::Member); 
    $dbh = null;
    ?>
</body>

</html>