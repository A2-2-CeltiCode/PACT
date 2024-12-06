<?php
// Inclusion des fichiers nécessaires pour les composants de l'interface
session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "pro") {
    header("Location: /pages/pro/listeOffres/listeOffres.php");
} elseif (!isset($_SESSION['idCompte'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
}
use \composants\Button\Button;
use \composants\Button\ButtonType;
use composants\Input\Input;
use composants\InsererImage\InsererImage;
use \composants\Label\Label;
use composants\Select\Select;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/InsererImage/InsererImage.php";

// Récupération de l'identifiant de l'offre
$idOffre = $_GET['id'] ?? '1';
$idCompteCourant = $_SESSION['idCompte'];
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $avis = $dbh->query("select titre, note, commentaire, pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis  from pact.vue_avis join pact.vue_compte_membre ON pact.vue_avis.idCompte = pact.vue_compte_membre.idCompte where pact.vue_compte_membre.idCompte = $idCompteCourant")->fetchAll(PDO::FETCH_ASSOC);
    $imagesAvis = [];
    foreach ($avis as $avi) {
        $img = [];
        foreach ($dbh->query("select nomimage from pact.vue_image_avis WHERE idavis = {$avi['idavis']}")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $img[] = $item["nomimage"];
        }
        $imagesAvis[$avi['idavis']] = $img;
    }
    $peutEcrireAvis = $dbh->query("SELECT count(*) FROM pact.vue_avis WHERE idCompte = {$_SESSION['idCompte']} AND idOffre = $idOffre")->fetchAll(PDO::FETCH_ASSOC)[0]['count'] == 0;

    $offresSql = $dbh->query(<<<STRING
select distinct titre                                                                       AS nom,
       nomcategorie                                                                AS type,
       vue_offres.ville,
       nomimage as idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
STRING
    );

    $offresSql = $dbh->query(<<<STRING
select distinct titre                                                                       AS nom,
       nomcategorie                                                                AS type,
       vue_offres.ville,
       nomimage as idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
STRING
    );

    $offre = $dbh->query('SELECT * FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $typeOffre = $dbh->query('SELECT type_offre FROM pact.vue_offres WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();

    $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre['type_offre'])));
    if ($typeOffre === 'parc_dattractions') {
        $typeOffre = 'parc_attractions';
    }

    $tags = $dbh->query('SELECT * FROM pact.vue_tags_' . $typeOffre . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();

    $images = $dbh->query('SELECT _image.idImage,_image.nomImage FROM pact._image JOIN pact._offre ON _image.idOffre = _offre.idOffre WHERE _offre.idOffre = ' . $idOffre . ' AND _image.idImage NOT IN (SELECT  _parcAttractions.carteParc FROM pact._parcAttractions WHERE idOffre = _offre.idOffre AND _parcAttractions.carteParc IS NOT NULL)AND _image.idImage NOT IN (SELECT _restaurant.menuRestaurant FROM pact._restaurant WHERE idOffre = _offre.idOffre AND _restaurant.menuRestaurant IS NOT NULL)', PDO::FETCH_ASSOC)->fetchAll();

    $entreprise = $dbh->query(
        'SELECT DISTINCT pact.vue_compte_pro.denominationsociale, pact.vue_compte_pro.raisonsocialepro, pact.vue_compte_pro.email, pact.vue_compte_pro.numtel FROM pact.vue_offres join pact.vue_compte_pro ON pact.vue_compte_pro.idcompte = pact.vue_offres.idcompte WHERE idoffre = ' . $idOffre,
        PDO::FETCH_ASSOC
    )->fetch();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre</title>
    <link rel="stylesheet" href="listeAvis.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>
<?php Header::render(HeaderType::Member); ?>
<main>
<div>
    <div class="liste-avis">
        <div>
            <h1>Avis que vous avez posté :</h1>
        </div>
        <div>
            <?php
            foreach ($avis as $avi) {
                ?>
                <div class="avi">
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
                            <?=$avi["pseudo"]?>
                        </p>
                        <p>
                            le <?=$avi["datevisite"]?>
                        </p>
                        <p>
                            en <?=$avi["contextevisite"]?>
                        </p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="" id="modal-image" />
        </div>
    </div>
</div>  
</main>

    <script src="listeAvis.js"></script>
<?php Footer::render(FooterType::Member); ?>
</body>

</html>
