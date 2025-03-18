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
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="logo PACT">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        header + navbar {
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
//    $offresProchesSql = $dbh->query("select distinct vue_offres.titre AS nom, nomcategorie AS type, vue_offres.ville, nomimage as idimage, idoffre, COALESCE(ppv.denominationsociale, ppu.denominationsociale) AS nomProprio, tempsenminutes AS duree, avg(note) AS note from pact.vue_offres LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte JOIN pact.vue_avis USING (idOffre) GROUP BY nom, type, vue_offres.ville, idimage, idOffre, nomProprio, duree, nomoption");

    $offresUnesSql = $dbh->query(<<<STRING
select distinct vue_offres.titre                                                                       AS nom,
    nomcategorie                                    AS type,
    vue_offres.ville,
    nomimage as idimage,
    idoffre,
    COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
    tempsenminutes                                                              AS duree,
    nomoption,
    AVG(note) AS note,
    heureouverture AS ouverture,
    heurefermeture AS fermeture,
    nomgamme,
    valprix
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
   nomoption,
   ouverture,
   fermeture,
   nomgamme,
   valprix
STRING
    );

    $offresNouveautésSql = $dbh->query(<<<STRING
select distinct vue_offres.titre                                                                       AS nom,
    nomcategorie                                    AS type,
    vue_offres.ville,
    nomimage as idimage,
    idoffre,
    COALESCE(ppv.denominationsociale, ppu.denominationsociale)                  AS nomProprio,
    tempsenminutes                                                              AS duree,
    nomoption,
    AVG(note) AS note,
    heureouverture AS ouverture,
    heurefermeture AS fermeture,
    nomgamme,
    valprix
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
   nomoption,
   ouverture,
   fermeture,
   nomgamme,
   valprix
ORDER BY idOffre DESC
LIMIT 10

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
   AVG(note) AS note,
   heureouverture AS ouverture,
   heurefermeture AS fermeture,
   nomgamme,
   valprix
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
   nomoption,
   ouverture,
   fermeture,
   nomgamme,
   valprix
ORDER BY 9 DESC
STRING
    );

    if (isset($_COOKIE["offresRecentes"])) {
        $ofr = unserialize($_COOKIE["offresRecentes"]);
        $l = implode(', ', array_keys($ofr));

        $offresRecentesSql = $dbh->query(<<<STRING
select
   distinct pact.vue_offres.titre AS nom,
   nomcategorie AS type,
   vue_offres.ville,
   nomimage as idimage,
   idoffre,
   COALESCE(ppv.denominationsociale,ppu.denominationsociale) AS nomProprio,
   tempsenminutes AS duree,
   nomoption,
   AVG(note) AS note,
   heureouverture AS ouverture,
   heurefermeture AS fermeture,
   nomgamme,
   valprix
from pact.vue_offres
   LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
   LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
   JOIN pact.vue_avis USING (idOffre)
WHERE idoffre IN ($l)
GROUP BY nom,
   type,
   vue_offres.ville,
   idimage,
   idOffre,
   nomProprio,
   duree,
   nomoption,
   ouverture,
   fermeture,
   nomgamme,
   valprix
LIMIT 10
STRING
        );
    } else {
        $offresRecentesSql = [];
    }

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

$offreUnes = [];
foreach ($offresUnesSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offreUnes[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'],$item['valprix'],$item['nomgamme']);
}
$offresNouveautés = [];
foreach ($offresNouveautésSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offresNouveautés[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'],$item['valprix'],$item['nomgamme']);
}
$offresNote = [];
foreach ($offresNoteSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offresNote[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'],$item['valprix'],$item['nomgamme']);
}
$offresRecentes = [];
foreach ($offresRecentesSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offresRecentes[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'],$item['valprix'],$item['nomgamme']);
}
?>
<body>
<?php isset($_SESSION["idCompte"])?Header::render(type: HeaderType::Member):Header::render(); ?>
<div class="carousel">
    
    <button class="carousel-button prev desactive" title="flèche arrière">❮</button>
    <button class="carousel-button next desactive" title="flèche avant">❯</button>
    <div class="carousel-images">
        <?php
        
        $offresUnesSql->execute();
        $resultats = $offresUnesSql->fetchAll(PDO::FETCH_ASSOC);
        // Affichage des images de l'offre
        foreach ($resultats as $offre):
            ?>
            <div class="carousel-item">
                <img src="../../../ressources/<?php echo $offre["idoffre"]; ?>/images/<?php echo $offre['idimage']; ?>"
                class="carousel-image" alt="imgOffre">
                <div class="carousel-text-overlay">
                    <p class="blanc"><?php echo($offre["nom"]) ?></p>
                    <p class="blanc">Type: <?php echo($offre["type"]) ?></p>
                    <p class="blanc">Ville: <?php echo($offre["ville"]) ?></p>
                    <p class="blanc">Propriétaire: <?php echo($offre["nomProprio"]) ?></p>
                    <p class="blanc">Durée: <?php echo($offre["duree"]) ?> minutes</p>
                    <p class="blanc">Note: <?php echo($offre["note"]) ?>/5</p>
                    <p class="blanc">Ouverture: <?php echo($offre["ouverture"]) ?></p>
                    <p class="blanc">Fermeture: <?php echo($offre["fermeture"]) ?></p>
                    <p class="blanc">Gamme: <?php echo($offre["nomgamme"]) ?></p>
                    <p class="blanc">Prix: <?php echo($offre["valprix"]) ?> €</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-dots"></div>
</div>
<navbar>
<form action="/pages/visiteur/listeOffres/listeOffres.php" method="get">
    <?php Input::render(name:"titre", class: "barre_recherche", placeholder: "Recherche activitées, restaurants, lieux ...",
        icon: "/ressources/icone/recherche.svg", title: "icone barre de recherche") ?>
</form>
</navbar>
<main>

    <?php

    

    if (count($offresRecentes) > 0) {
        arsort($ofr);
        $tmp = [];
        foreach (array_keys($ofr) as $value) {
            foreach ($offresRecentes as $offre) {
                if ($offre->getId() == $value) {
                    $tmp[] = $offre;
                }
            }
        }
        ?>
            <section>

        <!-- affichage des offres a la une -->
            <h2>Offres consultées récement</h2>
            <div class="carrousel">
                <?php
                foreach ($tmp as $item) {
                    echo $item;
                }
                ?>
            </div>
            <nav>
                <button title="fleche arrière">
                    <span class="material-symbols-outlined">
                        arrow_back_ios_new
                    </span>
                </button>
                <button title="fleche avant">
                    <span class="material-symbols-outlined">
                        arrow_forward_ios
                    </span>
                </button>
            </nav>
        </section>
    <?php
    }
    ?>

    <section>
    <!-- affichage des offres a la une -->
        <h2>À la une!</h2>
        <div class="carrousel">
            <?php
            foreach ($offreUnes as $item) {
                echo $item;
            }
            ?>
        </div>
        <nav>
            <button title="fleche arrière">
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button title="fleche avant">
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </nav>
    </section>
    <section>
        <!-- affichage des nouvelles offres -->
        <h2>Nouveautés</h2>
        <div class="carrousel">
            <?php
            foreach ($offresNouveautés as $item) {
                echo $item;
            }
            ?>
        </div>
        <nav>
            <button title="fleche arrière">
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button title="fleche avant">
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </nav>
    </section>
    <section>
        <!-- affichage des offres les mieux notées -->
        <h2>Les mieux notées</h2>
        <div class="carrousel mixed">
            <?php
            foreach ($offresNote as $item) {
                echo $item;
            }
            ?>
        </div>
        <nav>
            <button title="fleche arrière">
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </button>
            <button title="fleche avant">
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </button>
        </nav>
    </section>
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

<div class="loader-wrapper">
    <span class="loader"><span class="loader-inner"></span></span>
</div>

<?php isset($_SESSION["idCompte"])?Footer::render(type: FooterType::Member):Footer::render(); ?>

<script>
    $(window).on("load",function(){
        $(".loader-wrapper").fadeOut("slow");
    });
</script>
</body>

<script src="accueil.js"></script>

</html>

