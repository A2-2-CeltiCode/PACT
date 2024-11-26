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

// Configuration de la base de données
$host = 'localhost';       // Remplacez par votre hôte
$dbname = 'postgres'; // Remplacez par le nom de votre base de données
$user = 'postgres'; // Remplacez par votre utilisateur
$password = '13phenix';    // Remplacez par votre mot de passe

// Initialisation des variables
$message = "";
$userInfo = [];
$idCompte = 2; //$_SESSION['idCompte']; // Récupération de l'ID depuis la session

try {
    // Connexion à la base de données
    $pdo = new PDO("pgsql:host=$host;port=5433;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Définir le schéma "pact" pour la session
    $pdo->exec("SET search_path TO pact");

    // Requête pour récupérer les informations d'un compte professionnel privé
    $sql = "SELECT idcompte, mdp, email, numtel, denominationsociale, 
                   raisonsocialepro, banquerib, numsiren,
                   codepostal, ville, rue
            FROM vue_compte_pro_prive
            WHERE idCompte = :idCompte";
    $stmt = $pdo->prepare($sql);
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
    <link rel="stylesheet" href="consulterComptePro.css">
    <link rel="stylesheet" href="../../../ui.css">
</head>

<body>
    <?php Header::render(HeaderType::Pro); ?>

    <main>
        <h1>Vos informations professionnelles</h1>
        <div class="modifier">
            <?php Button::render("btn", "", "Modifier mes informations", ButtonType::Pro, "", true); ?>
        </div>
        <!-- Message d'erreur ou confirmation -->
        <?php if ($message): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Affichage des informations si disponibles -->
        <?php if (!empty($userInfo)): ?>
            <table border="1">
                <!-- Catégorie Identité -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Identité</th>
                </tr>
                <tr>
                    <th>Dénomination Sociale</th>
                    <td><?= isset($userInfo['denominationsociale']) ? htmlspecialchars($userInfo['denominationsociale']) : "Non disponible" ?></td>
                </tr>
                <tr>
                    <th>Raison Sociale</th>
                    <td><?= isset($userInfo['raisonsocialepro']) ? htmlspecialchars($userInfo['raisonsocialepro']) : "Non disponible" ?></td>
                </tr>
                <tr>
                    <th>Numéro Siren</th>
                    <td><?= isset($userInfo['numsiren']) ? htmlspecialchars($userInfo['numsiren']) : "Non disponible" ?></td>
                </tr>

                <!-- Catégorie Coordonnées -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Coordonnées</th>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : "Non disponible" ?></td>
                </tr>
                <tr>
                    <th>Numéro de Téléphone</th>
                    <td><?= isset($userInfo['numtel']) ? htmlspecialchars($userInfo['numtel']) : "Non disponible" ?></td>
                </tr>

                <!-- Catégorie Adresse -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Adresse</th>
                </tr>
                <tr>
                    <th>Rue</th>
                    <td><?= isset($userInfo['rue']) ? htmlspecialchars($userInfo['rue']) : "Non disponible" ?></td>
                </tr>
                <tr>
                    <th>Code Postal</th>
                    <td><?= isset($userInfo['codepostal']) ? htmlspecialchars($userInfo['codepostal']) : "Non disponible" ?></td>
                </tr>
                <tr>
                    <th>Ville</th>
                    <td><?= isset($userInfo['ville']) ? htmlspecialchars($userInfo['ville']) : "Non disponible" ?></td>
                </tr>

                <!-- Catégorie Informations bancaires -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Informations Bancaires</th>
                </tr>
                <tr>
                    <th>RIB</th>
                    <td><?= isset($userInfo['banquerib']) ? htmlspecialchars($userInfo['banquerib']) : "Non disponible" ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </main>

    <?php Footer::render(FooterType::Pro); ?>
</body>
</html>