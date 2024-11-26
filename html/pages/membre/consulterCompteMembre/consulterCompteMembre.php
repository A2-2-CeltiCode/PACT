<?php
// Démarrer la session
session_start();

use \composants\Button\Button;
use \composants\Button\ButtonType;
use \composants\Label\Label;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";

// Vérifier si l'utilisateur est connecté
//if (!isset($_SESSION['idCompte'])) {
//    header("Location: login.php"); // Redirige vers une page de connexion si l'utilisateur n'est pas connecté
//    exit;
//}


// Initialisation des variables
$message = "";
$userInfo = [];
$idCompte = $_SESSION['idCompte']; //$_SESSION['idCompte']; // Récupération de l'ID depuis la session

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    

    // Définir le schéma "pact" pour la session
    $dbh->exec("SET search_path TO pact;");

    // Requête pour récupérer les informations d'un compte professionnel privé
    $sql = "SELECT idcompte, pseudo, email, numtel, nom, prenom, codepostal, ville, rue
            FROM vue_compte_membre
            WHERE idCompte = :idCompte";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $stmt->execute();

    // Vérification des résultats
    if ($stmt->rowCount() > 0) {
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Aucune information trouvée pour cet utilisateur.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="consulterCompteMembre.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<body>
    <?php Header::render(HeaderType::Member); ?>

    <main>
        <h1>Vos informations personnelles</h1>
        <?php Button::render("btn", "", "Modifier mes informations", ButtonType::Member, "", true); ?>

        <!-- Message d'erreur ou confirmation -->
        <?php if ($message): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Affichage des informations si disponibles -->
        <?php if (!empty($userInfo)): ?>
            <table border="1">
                <!-- Catégorie Identité -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Identité</th>
                </tr>
                <tr>
                    <th>Nom</th>
                    <td><?= isset($userInfo['nom']) ? htmlspecialchars($userInfo['nom']) : "Non renseigné" ?></td>
                </tr>
                <tr>
                    <th>Prénom</th>
                    <td><?= isset($userInfo['prenom']) ? htmlspecialchars($userInfo['prenom']) : "Non renseigné" ?></td>
                </tr>
                <tr>
                    <th>Pseudo</th>
                    <td><?= isset($userInfo['pseudo']) ? htmlspecialchars($userInfo['pseudo']) : "Non renseigné" ?></td>
                </tr>

                <!-- Catégorie Coordonnées -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Coordonnées</th>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : "Non renseigné" ?></td>
                </tr>
                <tr>
                    <th>Numéro de Téléphone</th>
                    <td><?= isset($userInfo['numtel']) ? htmlspecialchars($userInfo['numtel']) : "Non renseigné" ?></td>
                </tr>

                <!-- Catégorie Adresse -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Adresse</th>
                </tr>
                <tr>
                    <th>Rue</th>
                    <td><?= isset($userInfo['rue']) ? htmlspecialchars($userInfo['rue']) : "Non renseigné" ?></td>
                </tr>
                <tr>
                    <th>Code Postal</th>
                    <td><?= isset($userInfo['codepostal']) ? htmlspecialchars($userInfo['codepostal']) : "Non renseigné" ?></td>
                </tr>
                <tr>
                    <th>Ville</th>
                    <td><?= isset($userInfo['ville']) ? htmlspecialchars($userInfo['ville']) : "Non renseigné" ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </main>

    <?php Footer::render(FooterType::Member); ?>
</body>
</html>