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

// Récupération de l'identifiant de l'offre et connexion a la bdd
$idCompteCourant = $_SESSION['idCompte'];
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $avis = $dbh->query("select pact.vue_avis.idOffre, pact._offre.idCompte, pact.vue_compte_pro.denominationsociale,pact._offre.titre, pact.vue_avis.titre as nomavis, note, commentaire, pseudo, to_char(datevisite,'DD/MM/YY') as datevisite, contextevisite, idavis
                         from pact.vue_avis join pact.vue_compte_membre ON pact.vue_avis.idCompte = pact.vue_compte_membre.idCompte
                                            join pact._offre ON pact._offre.idOffre = pact.vue_avis.idOffre 
                                            join pact.vue_compte_pro ON pact.vue_compte_pro.idCompte = pact._offre.idCompte
                         where pact.vue_compte_membre.idCompte = $idCompteCourant")->fetchAll(PDO::FETCH_ASSOC);
    $imagesAvis = [];
    foreach ($avis as $avi) {
        $img = [];
        foreach ($dbh->query("select nomimage from pact.vue_image_avis WHERE idavis = {$avi['idavis']}")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $img[] = $item["nomimage"];
        }
        $imagesAvis[$avi['idavis']] = $img;
    }
    
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
    <div class="liste-avis">
        <div>
            <h1>Avis que vous avez posté :</h1>
        </div>
        <div>
            <!--affichage des avis du membre -->
            <?php foreach ($avis as $avi) { ?>
                <div class="avi">
                        <p class="avi-title">
                            <?php echo $avi["titre"] . " de " . $avi["denominationsociale"] ?>
                        </p>    
                    <div>    
                        <p class="avi-title">
                            <?= $avi["nomavis"] ?>
                        </p>
                        <div class="note">
                            <!--affichage des étoile donnée-->
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
                        <!--information sur la visite -->
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
</main>

    <script src="listeAvis.js"></script>
<?php Footer::render(FooterType::Member); ?>
</body>

</html>
