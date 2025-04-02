<?php
// Démarre la session pour gérer l'authentification
session_start();

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
    $mot_de_passe_utilisateur = hash("SHA256",$_POST['password']);

    $requete_sql = 'select idCompte,mdp,email, totp from pact.vue_compte_pro WHERE email = :identifiant';

    $requete_preparee = $dbh->prepare($requete_sql);
    $requete_preparee->bindParam(':identifiant', $identifiant_utilisateur);
    $requete_preparee->execute();

    if ($compte = $requete_preparee->fetch(PDO::FETCH_ASSOC)) {
        if ($mot_de_passe_utilisateur === $compte['mdp']) {
            if ($compte['totp'] == null) {
                $_SESSION['utilisateur_connecte'] = true;
                $_SESSION['identifiant_utilisateur'] = $compte['email'];
                $_SESSION['idCompte'] = $compte['idcompte'];
                $_SESSION['typeUtilisateur'] = "pro";
                
                header("Location: ../listeOffres/listeOffres.php");
                exit();
            } else {
                $message_erreur_connexion = "Entrez le code de double authentification";
                $_SESSION["totpid"] = $compte['idcompte'];
                $_SESSION['typeUtilisateur'] = "pro";
                $requireTotp = true;
            }
        } else {
            $message_erreur_connexion = 'Nom d\'utilisateur ou mot de passe incorrect';
        }
    } else {
        $message_erreur_connexion = 'Nom d\'utilisateur ou mot de passe incorrect';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="logo PACT">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="./connexionComptePro.css">
    <link rel="stylesheet" href="/ui.css">
</head>
<body>
    <section class="conteneur">
        <a href="/pages/visiteur/accueil/accueil.php"><p id="retour-accueil">Retour à l'accueil</p></a>
        <!-- Logo de la page -->
        <img alt="Logo" src="/ressources/icone/logo.svg" title="logo de la page"/>
        
        <!-- Titre de la page -->
        <h1>Connectez-vous à votre compte</h1>
        <hr>

        <!-- Afficher le message d'erreur s'il y en a un -->
        <?php if (isset($message_erreur_connexion)): ?>
            <p id="expError" style="color: red;"><?php echo $message_erreur_connexion; ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="post" action="" onkeydown=<?= $requireTotp ? "validateForm(event)" : "" ?> >
            <?php
            if (!$requireTotp) {
            ?>
            <div class="groupe-champ">
                <label for="username">Votre identifiant</label>
                <?php Input::render(class : "conect" , type : "text", name:"username", placeholder:"Adresse e-mail", required : true)  ?>

            </div>
            <div class="groupe-champ toggle-mot-de-passe">
                <label for="password">Votre mot de passe</label>
                <?php Input::render(class : "conect" , type : "password", name:"password", placeholder:"Mot de passe", required : true)  ?>
        
            </div>
            <div class="connecte">
                <?php Button::render(class: "bouton-connexion",title:"bouton de connexion" ,submit: true, type: "pro", text: "Se connecter"); ?>
            </div>
            <?php
            } else {
            ?>
            <div class="groupe-champ">
                <label for="totp">Code de double authentification</label>
                <?php Input::render(class : "conect" , type : "texte", id: "code", name:"totp", placeholder:"code de validation à 6 chiffres", pattern: "^[0-9]{6}$")  ?>
            </div>
            <div class="totpInput">
                <?php Button::render(class: "bouton-connexion", type: "membre", text: "valider", onClick: "validateTOTP()") ?>
            </div>
            <?php
            }
            ?>
        </form>
        <!-- Lien pour créer un compte -->
        <div class="inscription">
            Vous n'avez pas de compte ? <a href="../creationComptePro/creationComptePro.php">Créez un compte dès maintenant</a>
        </div>
    </section>
</body>
<script src="totp.js"></script>
</html>