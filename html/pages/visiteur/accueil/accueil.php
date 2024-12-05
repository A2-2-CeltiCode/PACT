<?php
session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "membre") {
    header("location: /pages/membre/accueil/accueil.php");
} elseif (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "pro") {
    header("Location: /pages/pro/listeOffres/listeOffres.php");
}
?>
<!doctype html>
<html lang="fr-FR">
<head>
    <title>PACT</title>
    <link rel="stylesheet" href="/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml">
    <style>
        header + div {
            <?php
            if (isset($_SESSION["typeUtilisateur"]) && $_SESSION["typeUtilisateur"] == "membre") {
                echo 'background-image: url("/ressources/img/font-barre-recherce-membre.png");';
            } else {
                echo 'background-image: url("/ressources/img/font-barre-recherce.png");';
            }
            ?>
        }
        svg {
            <?=isset($_SESSION["typeUtilisateur"]) && $_SESSION["typeUtilisateur"] == "membre" ? "stroke: var(--primaire-membre)":"stroke: var(--primaire-visiteur)"?>
        }
    </style>
    <?php

    use composants\Input\Input;
    use controlleurs\Offre\Offre;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/Input/Input.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/controlleurs/Offre/Offre.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/Footer/Footer.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/Header/Header.php';
    global $driver, $server, $dbname, $dbuser, $dbpass;

    ?>
</head>

<?php

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

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

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}
$offreProches = [];
foreach ($offresSql as $item) {
    $offreProches[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree']);
}
?>

<body>
<?php isset($_SESSION["idCompte"])?Header::render(type: HeaderType::Member):Header::render(); ?>
<div>
    <?php Input::render(class: "barre_recherche", placeholder: "Recherche activitées, restaurants, lieux ...",
        icon: "/ressources/icone/recherche.svg") ?>
</div>
<main>
    <div>
        <h2>Autour de vous</h2>
        <div class="carrousel">
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
        <div>
            <button>
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button>
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </div>
    </div>
    <div>
        <h2>Les plus recommandées</h2>
        <div class="carrousel">
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
        <div>
            <button>
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button>
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </div>
    </div>
    <div>
        <h2>Les mieux noté!</h2>
        <div class="carrousel">
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
        <div>
            <button>
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button>
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </div>
    </div>
</main>
<?php isset($_SESSION["idCompte"])?Footer::render(type: FooterType::Member):Footer::render(); ?>
</body>
<script src="accueil.js"></script>
</html>

