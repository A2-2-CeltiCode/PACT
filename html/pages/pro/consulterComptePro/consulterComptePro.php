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

// Initialisation des variables
$message = "";
$userInfo = [];

// Configuration de la base de données
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$password = '13phenix';
$idCompte = 2; //$_SESSION['idCompte']; // ID de l'utilisateur connecté

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
            FROM vue_compte_pro
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
    <script src="consulterComptePro.js"></script>
      

</head>

<body>
    <?php Header::render(HeaderType::Pro); ?>

    <main>
        <h1>Vos informations professionnelles</h1>

        <!-- Bouton "Modifier mes informations" -->
        <div class="modifier">
            <button onclick="activerModification()">Modifier mes informations</button>

            <!-- Affichage des messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <p style="color: green; margin-top: 10px;"><?= htmlspecialchars($_SESSION['message']) ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red; margin-top: 10px;"><?= htmlspecialchars($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire pour afficher et modifier les informations -->
        <form method="post" action="enregistrerModifications.php">
            <table border="1">
                <!-- Catégorie Identité -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Identité</th>
                </tr>
                <tr>
                    <th>Dénomination Sociale</th>
                    <td>
                        <input type="text" name="denominationsociale" 
                               value="<?= htmlspecialchars($userInfo['denominationsociale'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['denominationsociale'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Raison Sociale</th>
                    <td>
                        <input type="text" name="raisonsocialepro" 
                               value="<?= htmlspecialchars($userInfo['raisonsocialepro'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['raisonsocialepro'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Numéro Siren</th>
                    <td>
                        <input type="text" name="numsiren" 
                               value="<?= htmlspecialchars($userInfo['numsiren'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['numsiren'] ?? 'Non disponible') ?>">
                    </td>
                </tr>

                <!-- Catégorie Coordonnées -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Coordonnées</th>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="email" name="email" class="editable" 
                               value="<?= htmlspecialchars($userInfo['email'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['email'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Numéro de Téléphone</th>
                    <td>
                        <input type="text" name="numtel" class="editable" 
                               value="<?= htmlspecialchars($userInfo['numtel'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['numtel'] ?? 'Non disponible') ?>">
                    </td>
                </tr>

                <!-- Catégorie Adresse -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Adresse</th>
                </tr>
                <tr>
                    <th>Rue</th>
                    <td>
                        <input type="text" name="rue" class="editable" 
                               value="<?= htmlspecialchars($userInfo['rue'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['rue'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Code Postal</th>
                    <td>
                        <input type="text" name="codepostal" class="editable" 
                               value="<?= htmlspecialchars($userInfo['codepostal'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['codepostal'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Ville</th>
                    <td>
                        <input type="text" name="ville" class="editable" 
                               value="<?= htmlspecialchars($userInfo['ville'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['ville'] ?? 'Non disponible') ?>">
                    </td>
                </tr>

                <!-- Catégorie Informations bancaires -->
                <tr>
                    <th colspan="2" style="background-color: #6b3d84; color: white; text-align: center;">Informations Bancaires</th>
                </tr>
                <tr>
                    <th>RIB</th>
                    <td>
                        <input type="text" name="banquerib" class="editable" 
                               value="<?= htmlspecialchars($userInfo['banquerib'] ?? 'Non disponible') ?>" 
                               readonly data-original="<?= htmlspecialchars($userInfo['banquerib'] ?? 'Non disponible') ?>">
                    </td>
                </tr>
            </table>

            <!-- Boutons -->
            <div class="bouton">
                <button type="submit" id="btnEnregistrer" style="display: none;">Enregistrer</button>
                <button type="button" id="btnAnnuler" style="display: none;" onclick="annulerModification()">Annuler</button>
            </div>
        </form>
    </main>

    <?php Footer::render(FooterType::Pro); ?>
</body>

</html>