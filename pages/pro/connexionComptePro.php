<?php
// Démarre la session pour gérer l'authentification
session_start();

include "components\Input\Input.php";

$hote_base_donnees = 'localhost';
$nom_base_donnees = 'nom_base_donnees';
$utilisateur_base_donnees = 'nom_utilisateur';
$mot_de_passe_base_donnees = 'mot_de_passe';

try {
    // Connexion à la base de données avec PDO
    $connexion_base_donnees = new PDO("postgres:host=$hote_base_donnees;dbname=$nom_base_donnees;charset=utf8", $utilisateur_base_donnees, $mot_de_passe_base_donnees);
    $connexion_base_donnees->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erreur_connexion) {
    die("Erreur de connexion : " . $erreur_connexion->getMessage());
}

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les valeurs soumises dans le formulaire
    $identifiant_utilisateur = $_POST['username'];
    $mot_de_passe_utilisateur = $_POST['password'];

    // Prépare la requête SQL pour vérifier l'identifiant et le mot de passe dans la table ComptePro
    $requete_sql = "SELECT * FROM ComptePro WHERE (email = :identifiant OR siren = :identifiant)";
    $requete_preparee = $connexion_base_donnees->prepare($requete_sql);
    $requete_preparee->bindParam(':identifiant', $identifiant_utilisateur);
    $requete_preparee->execute();
    
    // Vérifie si un compte correspondant a été trouvé
    if ($compte = $requete_preparee->fetch(PDO::FETCH_ASSOC)) {
        // Vérifie la correspondance du mot de passe
        if (password_verify($mot_de_passe_utilisateur, $compte['password'])) {
            // Si les informations sont correctes, démarrer la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['identifiant_utilisateur'] = $compte['email'];
            // Sauvegarder l'ID du compte dans la session
            $_SESSION['id_compte_utilisateur'] = $compte['id']; // Assurez-vous que la colonne 'id' est présente dans la table
            
            // Redirige vers le tableau de bord
            header('Location: tableauDeBord.php');
            exit();
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
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="connexionComptePro.css">

</head>
<body>
    <div class="conteneur">
        <!-- Logo de la page -->
        <img alt="Logo" src="assets/icon/logo.svg" />
        
        <!-- Titre de la page -->
        <h1>Connectez-vous à votre compte professionnel</h1>
        <div class="soulignement"></div>

        <!-- Afficher le message d'erreur s'il y en a un -->
        <?php if (isset($message_erreur_connexion)): ?>
            <p style="color: red;"><?php echo $message_erreur_connexion; ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="post" action="">
            <div class="groupe-champ">
                <label for="username">Votre identifiant</label>
                <?php Input::render(class : "conect" , type : "text", name:"username", placeholder:"Adresse e-mail / Numéro SIREN", required : true)  ?>

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
            Vous n'avez pas de compte ? <a href="creationComptePro.php">Créez un compte</a> PACT professionnel dès maintenant
        </div>
    </div>
</body>
</html>