<?php session_start() ?>
<!-- Démarrage de la session pour gérer les données utilisateur -->

<!doctype html>
<html lang="fr-FR">
<head>
    <title>Accueil - PACT</title>
    <link rel="stylesheet" href="/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="logo PACT">

    <style>
        header + navbar {
            <?php
            // Dynamisation du style en fonction du type d'utilisateur
            if (isset($_SESSION["typeUtilisateur"]) && $_SESSION["typeUtilisateur"] == "membre") {
                echo 'background-image: url("/ressources/img/font-barre-recherce-membre.png");';
            } else {
                echo 'background-image: url("/ressources/img/font-barre-recherce.png");';
            }
            ?>
        }
        svg {
            <?= isset($_SESSION["typeUtilisateur"]) && $_SESSION["typeUtilisateur"] == "membre" 
                ? "stroke: var(--primaire-membre)" 
                : "stroke: var(--primaire-visiteur)" ?>
            
        }
    </style>

    <?php
    // Chargement des classes et fichiers nécessaires
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
// Connexion à la base de données
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
    COALESCE(AVG(note), 0) AS note,
    heureouverture AS ouverture,
    heurefermeture AS fermeture,
    nomgamme,
    valprix
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
      LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
LEFT JOIN pact.vue_avis USING (idOffre)
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
    COALESCE(AVG(note), 0) AS note,
    heureouverture AS ouverture,
    heurefermeture AS fermeture,
    nomgamme,
    valprix
from pact.vue_offres
LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
      LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
LEFT JOIN pact.vue_avis USING (idOffre)
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
    SELECT
       DISTINCT pact.vue_offres.titre AS nom,
       nomcategorie AS type,
       vue_offres.ville,
       nomimage AS idimage,
       idoffre,
       COALESCE(ppv.denominationsociale, ppu.denominationsociale) AS nomProprio,
       tempsenminutes AS duree,
       nomoption,
       COALESCE(AVG(note), 0) AS note,
       heureouverture AS ouverture,
       heurefermeture AS fermeture,
       nomgamme,
       valprix
    FROM pact.vue_offres
       LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
       LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
       LEFT JOIN pact.vue_avis USING (idOffre)
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
    HAVING COALESCE(AVG(note), 0) >= 0
    ORDER BY note DESC
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
   COALESCE(AVG(note), 0) AS note,
   heureouverture AS ouverture,
   heurefermeture AS fermeture,
   nomgamme,
   valprix
from pact.vue_offres
   LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
   LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
   LEFT JOIN pact.vue_avis USING (idOffre)
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
LIMIT 100
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



$svgNote = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/note.svg");
$svgProprio = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/user.svg");
$svgClock = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/clock.svg");
$svgPin = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/mapPin.svg");
$svgArgent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/argent.svg");
?>
<body>
    <?php
    // Affichage de l'en-tête dynamique en fonction de l'utilisateur
    isset($_SESSION["idCompte"]) ? Header::render(type: HeaderType::Member) : Header::render();
    ?>
    <navbar>
        <form action="/pages/membre/listeOffres/listeOffres.php" method="get">
            <?php Input::render(name:"titre", class:"barre_recherche", placeholder:"Recherche activitées, restaurants, lieux ...", icon:"/ressources/icone/recherche.svg",title:"icone de recherche") ?>
        </form>
    </navbar>
    <div class="carousel">
    
    <button class="carousel-button prev desactive" title="flèche arrière">❮</button>
    <button class="carousel-button next desactive" title="flèche avant">❯</button>
    <div class="carousel-images">
        <?php
        
        $offresUnesSql->execute();
        $resultats = $offresUnesSql->fetchAll(PDO::FETCH_ASSOC);
        // Affichage des images de l'offre
        foreach ($resultats as $offre):
            if($offre["type"]=="Parc d'attractions"){
                $svgIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/parc_attractions.svg");
            }else{
                $svgIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/" . strtolower($offre['type']) . ".svg");
            }
            ?>
            <div class="carousel-item" onclick="window.location.href='/pages/membre/detailsOffre/detailsOffre.php?id=<?php echo $offre['idoffre']; ?>'">
                <img src="../../../ressources/<?php echo $offre["idoffre"]; ?>/images/<?php echo $offre['idimage']; ?>"
                class="carousel-image" alt="imgOffre">
                <div class="carousel-text-overlay" >
                    <div class="notes"">
                    <div>
                        <?php echo($svgIcon) ?>
                        <p class="blanc"><?php echo($offre["nom"]) ?></p>
                    </div>
                    <div>
                        <p class="blanc">

                            <?php
                            $note = $offre["note"];
                            $fullStars = floor($note);
                            $halfStar = ($note - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo str_replace('<svg', '<svg class="svgetoile"', file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_pleine.svg"));
                            }
                            if ($halfStar) {
                                echo str_replace('<svg', '<svg class="svgetoile"', file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_mid.svg"));
                            }
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo str_replace('<svg', '<svg class="svgetoile"', file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/etoile_vide.svg"));
                            }
                            ?>
                            


                        </p>
                        <?php echo number_format($note, 1); ?>
                    </div>
                    </div>
                    <div>
                    <?php echo($svgPin) ?>
                    <p class="blanc"><?php echo($offre["ville"]) ?></p>
                    </div>
                    <div>

                    </div>
                    <?php
                    if($offre["duree"] != null){
                        echo "<div>";
                        echo($svgClock);
                        echo "<p class='blanc'>" . $offre['duree'] . " minutes</p>";
                        echo"</div>";
                    }
                    ?>
                    
                    <div>
                    <?php
                    echo($svgClock);
                    ?>
                    <p class="blanc"> <?php echo date("H:i", strtotime($offre["ouverture"]))."-".date("H:i", strtotime($offre["fermeture"])) ?></p>
                    </div>
                    <div>
                    <?php 
                    if($offre["valprix"] === null){
                        echo str_replace('<svg', '<svg fill="var(--primaire-membre)"', $svgArgent);
                        echo "<p class='blanc'>".$offre["nomgamme"]."</p>";
                    }else{
                        echo str_replace('<svg', '<svg fill="var(--primaire-membre)"', $svgArgent);
                        echo "<p class='blanc'>".$offre["valprix"]." €</p>";
                    }
                    ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-dots"></div>
</div>
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
            <h2>Offres consultées récemment</h2>
            <div class="carrousel">
                <?php
                foreach ($tmp as $item) {
                    echo $item;
                }
                ?>
            </div>
            <div>
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
            </div>
            </section>
    <?php
    }
    ?>
 

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
            <!-- Section "Les mieux notées" -->
            <h2>Les mieux notées</h2>
            <div class="carrousel mixed">
                <?php
                foreach ($offresNote as $item) {
                    echo $item;
                }
                ?>
            </div>
            <nav>
                <button><span class="material-symbols-outlined">arrow_back_ios_new</span></button>
                <button><span class="material-symbols-outlined">arrow_forward_ios</span></button>
            </nav>
        </section>
    </main>
    <?php
    isset($_SESSION["idCompte"]) ? Footer::render(type: FooterType::Member) : Footer::render();
    ?>
</body>
<script src="accueil.js"></script>
</html>
