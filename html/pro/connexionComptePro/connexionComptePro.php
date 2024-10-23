<?php
// Démarre la session pour gérer l'authentification
session_start();

include "../../../composants/Input/Input.php";

include('./connect_params.php');
try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    
    // Définit explicitement le schéma 'pact'
    $dbh->exec("SET search_path TO pact;");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les valeurs soumises dans le formulaire
    $identifiant_utilisateur = $_POST['username'];
    $mot_de_passe_utilisateur = $_POST['password'];

    // Requête pour vérifier l'email et récupérer le mot de passe depuis la table _compte
    $requete_sql = 'SELECT * FROM pact._compte WHERE email = :identifiant';
    
    $requete_preparee = $dbh->prepare($requete_sql);
    $requete_preparee->bindParam(':identifiant', $identifiant_utilisateur);
    $requete_preparee->execute();
    
    // Vérifie si un compte correspondant a été trouvé
    if ($compte = $requete_preparee->fetch(PDO::FETCH_ASSOC)) {
        // Comparaison simple des mots de passe (sans hachage)
        if ($mot_de_passe_utilisateur === $compte['mdp']) {
            // Si les informations sont correctes, démarrer la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['identifiant_utilisateur'] = $compte['email'];
            // Sauvegarder l'ID du compte dans la session
            $_SESSION['id_compte_utilisateur'] = $compte['idcompte'];
            
            // Redirige vers le tableau de bord
            header('Location: tableauDeBord.php');
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
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="connexionComptePro.css">
</head>
<body>
    <div class="conteneur">
        <!-- Logo de la page -->
        <img alt="Logo" src="../../../ressources/icone/logo.svg" />
        
        <!-- Titre de la page -->
        <h1>Connectez-vous à votre compte</h1>
        <div class="soulignement"></div>

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
            <a class="lien-mdp-oublie" href="#">Mot de passe oublié ?</a>
            <button class="bouton-connexion" type="submit">Se connecter</button>
        </form>
        
        <!-- Lien pour créer un compte -->
        <div class="inscription">
            Vous n'avez pas de compte ? <a href="creationComptePro.php">Créez un compte dès maintenant</a>
        </div>
    </div>
</body>
</html>
