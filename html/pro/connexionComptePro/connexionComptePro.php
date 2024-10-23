<?php
// Démarre la session pour gérer l'authentification
session_start();

require_once "../../../composants/Input/Input.php";
require_once "../../../composants/Button/Button.php";

require_once('./connect_params.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="connexionComptePro.css">
    <link rel="stylesheet" href="../../../variables.css">
</head>
<body>
    <div class="conteneur">
        <a href="placeholder"><p id="retour-accueil">Retour à l'accueil</p></a>
        <!-- Logo de la page -->
        <img alt="Logo" src="../../../ressources/icone/logo.svg" />
        
        <!-- Titre de la page -->
        <h1>Connectez-vous à votre compte</h1>
        <hr>

        <!-- Afficher le message d'erreur s'il y en a un -->
        <?php if (isset($message_erreur_connexion)): ?>
            <p style="color: red;"><?php echo $message_erreur_connexion; ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="post" action="">
            <div class="groupe-champ">
                <label for="username">Votre identifiant</label>
                <?php Input::render(class : "conect" , type : "text", name:"username", placeholder:"Adresse e-mail", required : true)  ?>

            </div>
            <div class="groupe-champ toggle-mot-de-passe">
                <label for="password">Votre mot de passe</label>
                <?php Input::render(class : "conect" , type : "password", name:"password", placeholder:"Mot de passe", required : true)  ?>
        
            </div>
            <div class="connecte">
                <a class="lien-mdp-oublie" href="#">Mot de passe oublié ?</a>
                <?php Button::render(class: "bouton-connexion", submit: true, type: "pro", text: "Se connecter"); ?>
            </div>
        </form>
        <!-- Lien pour créer un compte -->
        <div class="inscription">
            Vous n'avez pas de compte ? <a href="../creationComptePro/creationComptePro.php">Créez un compte dès maintenant</a>
        </div>
    </div>
</body>
</html>
