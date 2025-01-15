<?php session_start() ?>
<!-- Démarrage de la session pour gérer les données utilisateur -->

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
            <!-- Changement de couleur des icônes en fonction du type d'utilisateur -->
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
    $offresUnesSql = $dbh->query(<<<STRING
    SELECT DISTINCT vue_offres.titre AS nom,
        nomcategorie AS type,
        vue_offres.ville,
        nomimage AS idimage,
        idoffre,
        COALESCE(ppv.denominationsociale, ppu.denominationsociale) AS nomProprio,
        tempsenminutes AS duree,
        nomoption,
        AVG(note) AS note,
        heureouverture AS ouverture,
        heurefermeture AS fermeture,
        nomgamme,
        valprix
    FROM pact.vue_offres
    LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
    LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
    JOIN pact.vue_avis USING (idOffre)
    WHERE nomoption = 'A la une'
    GROUP BY nom, type, vue_offres.ville, idimage, idOffre, nomProprio, duree, nomoption, ouverture, fermeture, nomgamme, valprix
    STRING);

    // Requête pour récupérer les offres les mieux notées
    $offresNoteSql = $dbh->query(<<<STRING
    SELECT DISTINCT vue_offres.titre AS nom,
        nomcategorie AS type,
        vue_offres.ville,
        nomimage AS idimage,
        idoffre,
        COALESCE(ppv.denominationsociale, ppu.denominationsociale) AS nomProprio,
        tempsenminutes AS duree,
        nomoption,
        AVG(note) AS note,
        heureouverture AS ouverture,
        heurefermeture AS fermeture,
        nomgamme,
        valprix
    FROM pact.vue_offres
    LEFT JOIN pact.vue_compte_pro_prive ppv ON vue_offres.idcompte = ppv.idcompte
    LEFT JOIN pact.vue_compte_pro_public ppu ON vue_offres.idcompte = ppu.idcompte
    JOIN pact.vue_avis USING (idOffre)
    GROUP BY nom, type, vue_offres.ville, idimage, idOffre, nomProprio, duree, nomoption, ouverture, fermeture, nomgamme, valprix
    ORDER BY 9 DESC
    STRING);

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Traitement des données récupérées pour les sections "À la une" et "Les mieux notées"
$offreUnes = [];
foreach ($offresUnesSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offreUnes[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'], $item['valprix'], $item['nomgamme']);
}
$offresNote = [];
foreach ($offresNoteSql as $item) {
    $item['nomgamme'] = $item['nomgamme'] ?? 'test';
    $item['valprix'] = $item['valprix'] ?? 'test';
    $offresNote[] = new Offre($item['nom'], $item['type'], $item['ville'], $item['idimage'], $item['nomproprio'],
        $item['idoffre'], $item['duree'], $item['note'], $item['nomoption'], $item['ouverture'], $item['fermeture'], $item['valprix'], $item['nomgamme']);
}
?>

<body>
    <?php
    // Affichage de l'en-tête dynamique en fonction de l'utilisateur
    isset($_SESSION["idCompte"]) ? Header::render(type: HeaderType::Member) : Header::render();
    ?>
    <div>
        <form action="/pages/visiteur/listeOffres/listeOffres.php" method="get">
            <?php Input::render(name:"titre", class:"barre_recherche", placeholder:"Recherche activitées, restaurants, lieux ...", icon:"/ressources/icone/recherche.svg") ?>
        </form>
    </div>
    <main>
        <!-- Section "À la une" -->
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
                <button><span class="material-symbols-outlined">arrow_back_ios_new</span></button>
                <button><span class="material-symbols-outlined">arrow_forward_ios</span></button>
            </div>
        </div>
        <!-- Section "Les mieux notées" -->
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
                <button><span class="material-symbols-outlined">arrow_back_ios_new</span></button>
                <button><span class="material-symbols-outlined">arrow_forward_ios</span></button>
            </div>
        </div>
    </main>
    <?php
    isset($_SESSION["idCompte"]) ? Footer::render(type: FooterType::Member) : Footer::render();
    ?>
</body>
<script src="accueil.js"></script>
</html>
