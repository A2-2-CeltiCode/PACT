<!doctype html>
<html lang="fr-FR">
<head>
    <title>PACT</title>
    <link rel="stylesheet" href="/style.css">
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
SELECT titre                                                                       AS nom,
       nomcategorie                                                                AS type,
       ville,
       (SELECT idimage
        FROM pact.vue_image_offre
        WHERE pact.vue_image_offre.idoffre = offre.idoffre FETCH FIRST 1 ROW ONLY) AS idimage,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
       tempsenminutes                                                              AS duree
FROM (SELECT titre, nomcategorie, ville, tempsenminutes, idoffre, idcompte
      FROM pact.vue_spectacle
      UNION
      SELECT titre, nomcategorie, ville, tempsenminutes, idoffre, idcompte
      FROM pact.vue_activite
      UNION
      SELECT titre, nomcategorie, ville, NULL AS tempsenminutes, idoffre, idcompte
      FROM pact.vue_parc_attractions
      UNION
      SELECT titre, nomcategorie, ville, tempsenminutes, idoffre, idcompte
      FROM pact.vue_visite
      UNION
      SELECT titre, nomcategorie, ville, NULL AS tempsenminutes, idoffre, idcompte
      FROM pact.vue_restaurant) as offre
         LEFT JOIN pact.vue_compte_pro_prive ppv ON offre.idcompte = ppv.idcompte
         LEFT JOIN pact.vue_compte_pro_public ppu ON offre.idcompte = ppu.idcompte
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
        $item['duree']);
}
?>

<body>
<?php Header::render(); ?>
<div>
    <?php Input::render(class: "barre_recherche", placeholder: "Recherche activitées, restaurants, lieux ...",
        icon: "/ressources/icone/recherche.svg") ?>
</div>
<main>
    <div>
        <h2>Autour de vous</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
    <div>
        <h2>Les plus recommandées</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
    <div>
        <h2>Les mieux noté!</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
</main>
<?php Footer::render(); ?>
</body>
</html>

