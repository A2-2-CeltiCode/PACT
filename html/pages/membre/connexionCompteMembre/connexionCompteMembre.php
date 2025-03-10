<?php
error_reporting(E_ALL ^ E_WARNING);

// Démarre la session pour gérer l'authentification
session_start();
if (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "membre") {
    header("location: /pages/membre/accueil/accueil.php");
} elseif (isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "pro") {
    header("Location: /pages/pro/listeOffres/listeOffres.php");
}
use \composants\Input\Input;
use \composants\Button\Button;

require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

// Connexion à la base de données
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $dbh->exec("SET search_path TO pact;");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant_utilisateur = $_POST['username'];
    $mot_de_passe_utilisateur = hash("sha256",$_POST['password']);
    $requete_sql = 'SELECT * FROM pact.vue_compte_membre WHERE email = :identifiant';

    $requete_preparee = $dbh->prepare($requete_sql);
    $requete_preparee->bindParam(':identifiant', $identifiant_utilisateur);
    $requete_preparee->execute();

    // Vérifie si un compte correspondant a été trouvé
    if ($compte = $requete_preparee->fetch(PDO::FETCH_ASSOC)) {
        if ($mot_de_passe_utilisateur === $compte['mdp']) {
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['identifiant_utilisateur'] = $compte['email'];
            $_SESSION['idCompte'] = $compte['idcompte'];
            $_SESSION['typeUtilisateur'] = "membre";

            header("Location: ../" . ($_GET["context"] ?? "accueil/accueil.php"));
            exit();
        } else {
            // Mot de passe incorrect
            $message_erreur_connexion = 'Nom d\'utilisateur ou mot de passe incorrect';
        }
    } else {
        // Aucun compte trouvé avec cet email
        $message_erreur_connexion = 'Nom d\'utilisateur ou mot de passe incorrect';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="./connexionCompteMembre.css">
    <link rel="stylesheet" href="/ui.css">
</head>
<body>
    <div class="conteneur responsive-conteneur">
        <a href="/pages/visiteur/accueil/accueil.php"><p id="retour-accueil">Retour à l'accueil</p></a>
        <!-- Logo de la page -->
        <img alt="Logo" src="/ressources/icone/logo.svg" />
        
        <h1>Connectez-vous à votre compte</h1>
        <hr>

        <!-- Afficher le message d'erreur s'il y en a un -->
        <?php if (isset($message_erreur_connexion)): ?>
            <p style="color: red;"><?php echo $message_erreur_connexion; ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="post" action="" class="responsive-form">
            <div class="groupe-champ">
                <label for="username">Votre identifiant</label>
                <?php Input::render(class : "conect" , type : "text", name:"username", placeholder:"Adresse e-mail", required : true)  ?>

            </div>
            <div class="groupe-champ toggle-mot-de-passe">
                <label for="password">Votre mot de passe</label>
                <?php Input::render(class : "conect" , type : "password", name:"password", placeholder:"Mot de passe", required : true)  ?>
        
            </div>
            <div class="connecte">
                <?php Button::render(class: "bouton-connexion", submit: true, type: "membre", text: "Se connecter", title: "connexion"); ?>
            </div>
        </form>
        <!-- Lien pour créer un compte -->
        <div class="inscription">
            Vous n'avez pas de compte ? <a href="../creationCompteMembre/creationCompteMembre.php">Créez un compte dès maintenant</a>
        </div>
    </div>
</body>
</html>