<?php
// Connexion à la base de données
$server = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = '13phenix';

try {
    $dbh = new PDO("pgsql:host=$server;port=5433;dbname=$dbname", $user, $pass);
    // Définit explicitement le schéma 'pact'
    $dbh->exec("SET search_path TO pact;");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Récupérer l'ID de l'offre depuis l'URL ou autre paramètre
$idOffre = isset($_GET['idOffre']) ? intval($_GET['idOffre']) : 1; // Par défaut, affiche l'offre avec idOffre 1

// Requête pour récupérer les détails de l'offre
$requete_sql = '
    SELECT o.titre, o.descriptiondetaillee, o.siteinternet, o.nomoption, o.nomforfait, a.codepostal, a.ville, 
           a.numtel, c.email, a.ville AS compteville, cp.idcompte, a.codepostal AS comptecodepostal,
           cp.denominationsociale, i.nomimage
    FROM pact._offre o
    JOIN pact._comptepro cp ON o.idcompte = cp.idcompte
    JOIN pact._compte c ON cp.idcompte = c.idcompte
    JOIN pact._adresse a ON o.idadresse = a.idadresse
    LEFT JOIN pact._image i ON o.idoffre = i.idoffre
    WHERE o.idoffre = :idOffre
';

$requete_preparee = $dbh->prepare($requete_sql);
$requete_preparee->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
$requete_preparee->execute();
$offre = $requete_preparee->fetch(PDO::FETCH_ASSOC);

// Si aucune offre n'est trouvée, on affiche un message d'erreur
if (!$offre) {
    echo "Aucune offre trouvée pour cet identifiant.";
    die();
}

// Chemin par défaut si aucune image n'est trouvée
$imagePath = "../../ressources/icone/default.jpg";
if (isset($offre['nomimage']) && !empty($offre['nomimage'])) {
    $imagePath = "../../ressources/images/" . $offre['nomimage'];
}

// Étape 1 : Déterminer le type de l'offre
$typeOffre = '';
$tags = [];

// On vérifie dans quelle table spécifique l'offre se trouve
$tablesTypes = ['_spectacle', '_activite', '_parcattractions', '_visite', '_restaurant'];
foreach ($tablesTypes as $table) {
    $sqlType = "SELECT 1 FROM pact.$table WHERE idoffre = :idOffre LIMIT 1";
    $stmtType = $dbh->prepare($sqlType);
    $stmtType->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmtType->execute();
    if ($stmtType->fetch()) {
        $typeOffre = $table;
        break;
    }
}

// Étape 2 : Récupérer les tags associés selon le type d'offre
if ($typeOffre == '_spectacle') {
    $sqlTags = "SELECT nomtag FROM pact._possedespectacle WHERE idoffre = :idOffre";
} elseif ($typeOffre == '_activite') {
    $sqlTags = "SELECT nomtag FROM pact._possedeactivite WHERE idoffre = :idOffre";
} elseif ($typeOffre == '_parcattractions') {
    $sqlTags = "SELECT nomtag FROM pact._possedeparcattractions WHERE idoffre = :idOffre";
} elseif ($typeOffre == '_visite') {
    $sqlTags = "SELECT nomtag FROM pact._possedepossedevisite WHERE idoffre = :idOffre";
} elseif ($typeOffre == '_restaurant') {
    $sqlTags = "SELECT nomtag FROM pact._possederestaurant WHERE idoffre = :idOffre";
}

if (!empty($sqlTags)) {
    $stmtTags = $dbh->prepare($sqlTags);
    $stmtTags->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmtTags->execute();
    $tags = $stmtTags->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once("../../composants/Label/Label.php");
      require_once("../../composants/Button/Button.php");
      require_once("../../composants/Input/Input.php");
      require_once("../../composants/Header/Header.php");
      require_once("../../composants/Footer/Footer.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'Offre</title>

    <!-- Liens vers les fichiers CSS pour le style général et spécifique -->
    <link rel="stylesheet" href="offreDetail.css">
    <link rel="stylesheet" href="../../ui.css">
</head>

 
<body>
    <?php Header::render(HeaderType::Member); ?>   
    <main>
        <div class="offre">
            <img class="image-offre" alt="Image offre" src="<?php echo $imagePath; ?>" />
            <div class="description">
                <?php 
                    Label::render("nom-restau", "", "", $offre['titre']);
                    Label::render("", "", "", isset($offre['descriptiondetaillee']) ? $offre['descriptiondetaillee'] : "Description non disponible");
                    Label::render("bas_desc", "", "", "11h-15h & 19h-23h", "../../ressources/icone/horloge.svg"); 
                ?>
                <a href="<?php echo $offre['siteinternet']; ?>">
                    <?php Label::render("bas_desc", "", "", "Site du restaurant", "../../ressources/icone/naviguer.svg"); ?>
                </a>
                <?php 
                    Label::render("bas_desc", "", "", $offre['ville'] . ' (' . $offre['codepostal'] . ')', "../../../ressources/icone/localisateur.svg"); 
                ?>
                <ul>
                    <li>Infos complémentaires :
                        <ul>
                            <li><?php echo $offre['nomoption']; ?></li>
                            <li>Forfait : <?php echo $offre['nomforfait']; ?></li>
                        </ul>
                    </li>
                    <!-- Ajout des tags associés -->
                    <?php if (!empty($tags)): ?>
                        <li>Tags associés :
                            <ul>
                                <?php foreach ($tags as $tag): ?>
                                    <li><?php echo htmlspecialchars($tag); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="colonne-droite">
                <div class="note_etoiles">
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine">
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine">
                    <img src="../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine">
                    <img src="../../ressources/icone/etoile_mid.svg" alt="Logo étoile pleine">
                    <img src="../../ressources/icone/etoile_vide.svg" alt="Logo étoile pleine">
                </div>
                <?php Label::render("tranche_prix", "", "", "€€€"); ?>

                <div class="carte">
                    <?php 
                        if (isset($offre['denominationsociale'])) {
                            Label::render("", "", "", $offre['denominationsociale']);
                        }
                        if (isset($offre['compteville'])) {
                            Label::render("", "", "", $offre['compteville'], "../../../ressources/icone/localisateur.svg");
                        }
                    ?>
                    <div class="email">
                        <img src="../../ressources/icone/lettre.svg" alt="icone lettre">
                        <a href="mailto:<?php echo $offre['email']; ?>"><?php echo $offre['email']; ?></a>
                    </div>
                    <div class="telephone">
                        <img src="../../ressources/icone/telephone.svg" alt="icone téléphone">
                        <span><?php echo isset($offre['numtel']) ? $offre['numtel'] : '(Numéro de téléphone non disponible)'; ?></span>
                    </div>
                    <a class="bouton" href="mailto:<?php echo $offre['email']; ?>">
                        <img src="../../ressources/icone/lettre.svg" alt="icone lettre">
                        Envoyer un Mail
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php Footer::render(FooterType::Guest); ?>
</body>
</html>