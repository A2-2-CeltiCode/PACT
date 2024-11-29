<?php session_start() ?>
<!doctype html>
<html lang="fr-FR">
<head>
    <title>PACT</title>
    <link rel="stylesheet" href="/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml">
    <style>
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

    $offresProchesSql = $dbh->query("select distinct vue_offres.titre AS nom, nomcategorie AS type, vue_offres.ville, nomimage as idimage, idoffre, COALESCE(ppv.denominationsociale, ppu.denominationsociale) AS nomProprio, tempsenminutes AS duree, avg(note) AS note from pact.vue_offres LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte JOIN pact.vue_avis USING (idOffre) GROUP BY nom, type, vue_offres.ville, idimage, idOffre, nomProprio, duree, nomoption");

    $offresUnesSql = $dbh->query(<<<STRING
select distinct vue_offres.titre                                                                       AS nom,
       nomcategorie                                    AS type,
       vue_offres.ville,
       nomimage as idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree,
       nomoption,
       AVG(note) AS note
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
JOIN pact.vue_avis USING (idOffre)
WHERE nomoption = 'A la une'
GROUP BY nom,
   type,
   vue_offres.ville,
   idimage,
   idOffre,
   nomProprio,
   duree,
   nomoption
STRING
    );

    $offresNoteSql = $dbh->query(<<<STRING
select
   distinct pact.vue_offres.titre AS nom,
   nomcategorie AS type,
   vue_offres.ville,
   nomimage as idimage,
   idoffre,
   COALESCE(ppv.denominationsociale,ppu.denominationsociale) AS nomProprio,
   tempsenminutes AS duree,
   nomoption,
   AVG(note) AS note
from pact.vue_offres
   LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
   LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
   JOIN pact.vue_avis USING (idOffre)
GROUP BY nom,
   type,
   vue_offres.ville,
   idimage,
   idOffre,
   nomProprio,
   duree,
   nomoption
ORDER BY 9 DESC
STRING
    );

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}
/*$offreProches = [];
foreach ($offresProchesSql as $item) {
    $offreProches[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption']);
}*/
$offreUnes = [];
foreach ($offresUnesSql as $item) {
    $offreUnes[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption']);
}
$offresNote = [];
foreach ($offresNoteSql as $item) {
    $offresNote[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption']);
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
        <h2>À la une!</h2>
        <div class="carrousel">
            <?php
            foreach ($offreUnes as $item) {
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
        <h2>Les mieux notées</h2>
        <div class="carrousel mixed">
            <?php
            foreach ($offresNote as $item) {
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
<!--    <div>
        <h2>Les mieux noté!</h2>
        <div class="carrousel">
            <?php
/*            foreach ($offreProches as $item) {
                echo $item;
            }
            */?>
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
    </div>-->
</main>
<?php isset($_SESSION["idCompte"])?Footer::render(type: FooterType::Member):Footer::render(); ?>
</body>
<script src="accueil.js"></script>
</html>

