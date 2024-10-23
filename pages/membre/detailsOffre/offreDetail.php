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

// Requête pour récupérer les détails de l'offre, y compris la description détaillée et l'image associée
$requete_sql = '
    SELECT o.titre, o.descriptiondetaillee, o.siteinternet, o.nomoption, o.nomforfait, o.codepostal, o.ville, 
           c.email, c.ville AS compteVille, c.idcompte, c.codepostal AS compteCodePostal,
           cp.denominationsociale, i.nomimage
    FROM pact._offre o
    JOIN pact._compte c ON o.idcompte = c.idcompte
    LEFT JOIN pact._comptepro cp ON c.idcompte = cp.idcompte
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
$imagePath = "../../../ressources/icone/default.jpg";
if (isset($offre['nomimage']) && !empty($offre['nomimage'])) {
    // Chemin de l'image de l'offre (modifiez le chemin si nécessaire pour correspondre à votre structure de fichiers)
    $imagePath = "../../../ressources/images/" . $offre['nomimage'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- Inclusion du fichier PHP pour le composant d'entrée (input) -->
<?php require_once("../../../composants/Label/Label.php");
      require_once("../../../composants/Button/Button.php");
      require_once("../../../composants/Input/Input.php");
      require_once("../../../composants/Header/Header.php");
      require_once("../../../composants/Footer/Footer.php");
      ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'Offre</title>

    <!-- Liens vers les fichiers CSS pour le style général et spécifique -->
    <link rel="stylesheet" href="offreDetail.css">
    <link rel="stylesheet" href="../../../ui.css">

</head>

<?php Header::render(HeaderType::Member); ?>    
<body>
    <!-- Contenu principal avec les détails de l'offre -->
    <main>
        <div class="offre">
            <!-- Image principale de l'offre -->
            <img class="image-offre" alt="Image offre" src="<?php echo $imagePath; ?>" />

            <div class="description">

                <!-- Affichage dynamique des informations de l'offre -->
                <?php 
                    Label::render("nom-restau", "", "", $offre['titre'], );
                    // Affichage de la description détaillée
                    Label::render("", "", "", $offre['descriptiondetaillee']);
                    Label::render("bas_desc", "", "", "11h-15h & 19h-23h", "../../../ressources/icone/horloge.svg"); 
                ?>

                <a href="<?php echo $offre['siteinternet']; ?>">
                    <?php Label::render("bas_desc", "", "", "Site de l'offre", "../../../ressources/icone/naviguer.svg"); ?>
                </a>
                <br>
                <?php 
                    Label::render("bas_desc", "", "", $offre['ville'] . ' (' . $offre['codepostal'] . ')', "../../../ressources/icone/localisateur.svg"); 
                ?>

                <!-- Liste imbriquée pour les informations supplémentaires -->
                <ul>
                    <li>Infos complémentaires :
                        <ul>
                            <li><?php echo $offre['nomoption']; ?></li>
                            <li>Forfait : <?php echo $offre['nomforfait']; ?></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Section droite avec note en étoiles et information de contact -->
            <div class="colonne-droite">
                <div class="note_etoiles">
                    <!-- Icônes d'étoiles pour la note -->
                    <img src="../../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../../ressources/icone/etoile_pleine.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../../ressources/icone/etoile_mid.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                    <img src="../../../ressources/icone/etoile_vide.svg" alt="Logo étoile pleine" style="vertical-align: middle;">
                </div>
                <?php Label::render("tranche_prix", "", "", "€€€"); ?>

                <div class="carte">
                    <div class="dessus-carte">
                        <!-- Informations de profil : photo, nom, localisation, contact -->
                        <img alt="Photo de profil" height="50" src="https://storage.googleapis.com/a1aa/image/X1R0j5nq39oeCyPhJsMjBx3peJ0fBncTGTd4TCy7MPRk7NPnA.jpg" width="50"/>
                        <div>
                            <!-- Affichage de la dénomination sociale à la place du nom -->
                            <?php 
                                if (isset($offre['denominationsociale'])) {
                                    Label::render("partie_haute", "", "", $offre['denominationsociale']);
                                }
                                // Afficher la ville du compte (table _compte) en dessous de la dénomination sociale
                                if (isset($offre['compteVille'])) {
                                    Label::render("partie_haute", "", "", $offre['compteVille'], "../../../ressources/icone/localisateur.svg");
                                }
                            ?>
                        </div>
                    </div> 

                    <div class="dessous-carte">
                        <div class="email">
                            <img src="../../../ressources/icone/lettre.svg" alt="icone lettre" style="vertical-align: middle;">
                            <a href="mailto:<?php echo $offre['email']; ?>"><?php echo $offre['email']; ?></a>
                        </div>
                        <div class="telephone">
                            <img src="../../../ressources/icone/telephone.svg" alt="icone téléphone" style="vertical-align: middle;">
                            <span>(Numéro de téléphone non disponible)</span>
                        </div>
                        <!-- Bouton d'envoi de mail -->
                        <a class="bouton" href="mailto:<?php echo $offre['email']; ?>">
                            <img src="../../../ressources/icone/lettre.svg" alt="icone lettre" style="vertical-align: middle;">
                            Envoyer un Mail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php Footer::render(FooterType::Guest); ?>
</body>
</html>