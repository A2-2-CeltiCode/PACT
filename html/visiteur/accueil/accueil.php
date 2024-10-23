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

    ?>
</head>

<?php

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

    $offresSql = $dbh->query(<<<STRING
SELECT titre                                                                       as nom,
       nomcategorie                                                                as type,
       ville,
       (SELECT idimage
        FROM pact.vue_image_offre
        WHERE pact.vue_image_offre.idoffre = offre.idoffre FETCH FIRST 1 ROW ONLY) as idimage,
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

foreach ($offresSql as $item) {
    $offreProches[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['duree']);
}

//$offreProches += $offreProches;
/*
for ($i = 0; $i < 16; $i++) {
    $offreProches[] = $ofr;
}*/
?>

<body>
<header>
    <div>
        <img src="/assets/icon/logo.svg" alt="Logo PACT">
        <span>PACT</span>
    </div>
    <div>
        <?php Input::render(placeholder: 'Entrez une localisation...') ?>
    </div>
    <nav>
        <!-- Liens de navigation pour la page d'accueil et les offres -->
        <a href="accueil.php">Accueil</a>
        <a href="offre.php">Offres</a>
    </nav>
    <div class="entete-langue">
        <img id="logo-langue" src="/assets/icon/logofr.svg" alt="Français">
        <label for="selecteur-langue"></label>
        <select id="selecteur-langue">
            <option value="fr">Français</option>
            <option value="en">English</option>
        </select>
    </div>
    <div>
        <a href="connexionComptePro.php" class="lien-bouton">
            <button>S'inscrire / Se Connecter</button>
        </a>
    </div>
    <script src="script-header.js"></script>
</header>
<div>
    <?php Input::render(class: "barre_recherche", placeholder: "Recherche activitées, restaurants, lieux ...",
        icon: "/assets/icon/recherche.svg") ?>
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
<footer>
    <a href="creationComptePro.php" class="lien-bouton">
        <button>DEVENIR MEMBRE</button>
    </a>
    <div class="liens">
        <a href="mentions.php">Mentions Légales</a>
        <a href="quiSommeNous.php">Qui sommes nous ?</a>
        <a href="condition.php">Conditions Générales</a>
    </div>
    <div class="icones-reseaux">
        <div><a href="#"><?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icon/facebook.svg') ?></a></div>
        <div>
            <a href="https://www.instagram.com/pactlannion/"><?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icon/twitter.svg') ?></a>
        </div>
        <div>
            <a href="https://x.com/TripEnArvorPACT"><?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icon/instagram.svg') ?></a>
        </div>
    </div>
    <p>© 2024 PACT, Inc.</p>
    <a href="#"
       class="remonte-page"><?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icon/arrow_upward.svg') ?></a>
</footer>
</body>
</html>

