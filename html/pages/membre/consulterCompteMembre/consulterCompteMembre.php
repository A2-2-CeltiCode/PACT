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
    <script src="consulterCompteMembre.js"></script>
</head>

<body>
    <?php Header::render(HeaderType::Member); ?>

    <main>
        <h1>Vos informations personnelles</h1>

        <!-- Bouton "Modifier mes informations" -->
        <div class="modifier">
            <button type="button" onclick="activerModification()">Modifier mes informations</button>
        </div>

        <!-- Affichage des messages d'erreur -->
        <div id="messageErreur" style="color: red; display: none;"></div>

        <!-- Affichage des informations si disponibles -->
        <form id="formulaireCompteMembre" method="post" action="enregistrerModifications.php">
            <table>
                <!-- Catégorie Identité -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Identité</th>
                </tr>
                <tr>
                    <th>Nom</th>
                    <td>
                        <input type="text" name="nom" class="editable" 
                            value="<?= htmlspecialchars($userInfo['nom'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['nom'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Prénom</th>
                    <td>
                        <input type="text" name="prenom" class="editable" 
                            value="<?= htmlspecialchars($userInfo['prenom'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['prenom'] ?? 'Non renseigné') ?>">
                        </td>
                </tr>
                <tr>
                    <th>Pseudo</th>
                    <td>
                        <input type="text" name="pseudo" class="editable" 
                            value="<?= htmlspecialchars($userInfo['pseudo'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['pseudo'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>

                <!-- Catégorie Coordonnées -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Coordonnées</th>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="email" name="email" class="editable" 
                            value="<?= htmlspecialchars($userInfo['email'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['email'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Numéro de Téléphone</th>
                    <td>
                        <input type="text" name="numtel" class="editable" 
                            value="<?= htmlspecialchars($userInfo['numtel'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['numtel'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>

                <!-- Catégorie Adresse -->
                <tr>
                    <th colspan="2" style="background-color: #075997; color: white; text-align: center;">Adresse</th>
                </tr>
                <tr>
                    <th>Rue</th>
                    <td>
                        <input type="text" name="rue" class="editable" 
                            value="<?= htmlspecialchars($userInfo['rue'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['rue'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Code Postal</th>
                    <td>
                        <input type="text" name="codepostal" class="editable" 
                            value="<?= htmlspecialchars($userInfo['codepostal'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['codepostal'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>
                <tr>
                    <th>Ville</th>
                    <td><input type="text" name="ville" class="editable" 
                    value="<?= htmlspecialchars($userInfo['ville'] ?? 'Non renseigné') ?>" 
                    readonly data-original="<?= htmlspecialchars($userInfo['ville'] ?? 'Non renseigné') ?>"></td>
                </tr>
            </table>

            <div class="bouton">
            <button type="button" id="btnEnregistrer" style="display: none;" onclick="validerFormulaire(event)">Enregistrer</button>
                <button type="button" id="btnAnnuler" style="display: none;" onclick="annulerModification()">Annuler</button>
            </div>
        </form>
    </main>

    <?php Footer::render(FooterType::Member); ?>
</body>
</html>