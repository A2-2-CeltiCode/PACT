<?php
error_reporting(E_ALL ^ E_WARNING);

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

// Initialisation des variables
$message = "";
$userInfo = [];
$idCompte = $_SESSION['idCompte'];

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    

    // Définir le schéma "pact" pour la session
    $dbh->exec("SET search_path TO pact;");
    $sql = "SELECT idcompte, pseudo, email, numtel, nom, prenom, codepostal, ville, rue, cleapi
            FROM vue_compte_membre LEFT JOIN _cleApi USING (idcompte)
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
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml" title="icone PACT">
    <script src="consulterCompteMembre.js"></script>
      

</head>
<body>
    <?php Header::render(HeaderType::Member); ?>

    <main>
        <h1>Vos informations personnelles</h1>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (isset($_SESSION['message'])): ?>
            <div style="color: green; margin-bottom: 20px; text-align: center;">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>


        <!-- Boutons principaux -->
        <div class="modifier">
            <button type="button" title="Modifier information" class="button" onclick="activerModification()">Modifier mes informations</button>
            <button type="button" title="Changer Mot de Passe" class="button" onclick="ouvrirPopupMotDePasse()">Changer le mot de passe</button>
        </div>

        <!-- Message d'erreur -->
        <div id="messageErreur" style="color: red; display: none;"></div>

        <!-- Affichage des messages d'erreur -->
        <div id="messageErreur" style="color: red; display: none;"></div>

        <hr id="separation">

        <!-- Formulaire -->
        <form id="formulaireCompteMembre" method="post" action="enregistrerModifications.php">
        <table>
                <!-- Catégorie Identité -->
                <tr>
                    <th colspan="2" class="thhead" style="background-color: #075997; color: white; text-align: center;">Identité</th>
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
                    <td class="nonE">
                        <input type="text" name="pseudo" class="nonEditable"
                            value="<?= htmlspecialchars($userInfo['pseudo'] ?? 'Non renseigné') ?>" 
                            readonly data-original="<?= htmlspecialchars($userInfo['pseudo'] ?? 'Non renseigné') ?>">
                    </td>
                </tr>

                <!-- Catégorie Coordonnées -->
                <tr>
                    <th colspan="2" class="thhead" style="background-color: #075997; color: white; text-align: center;">Coordonnées</th>
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
                    <th colspan="2" class="thhead"  style="background-color: #075997; color: white; text-align: center;">Adresse</th>
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
                <!-- Catégorie API -->
                <tr>
                    <th colspan="2" class="thhead"  style="background-color: #075997; color: white; text-align: center;">Tchatator</th>
                </tr>
                <tr>
                    <th>Clé d'API</th>
                    <td>
                        <input id="genText" type="text" readonly="" value="<?= htmlspecialchars(!empty($userInfo['cleapi']) ? 'Généré' : "Non généré") ?>">
                        <?php Button::render(id: "copyButton", text: "<span>Copier</span>",title: "copier cle API" ,onClick: "copyKey('" . $userInfo['cleapi'] . "')") ?>
                        <?php Button::render(id: "generateButton",title:"generé une cle API" ,text: "<span>" . htmlspecialchars(empty($userInfo['cleapi']) ? 'Générer' : "Regénérer") . "</span>", onClick: "generateKey()") ?>
                    </td>
                </tr>
            </table>

            <!-- Boutons -->
            <div class="bouton">
                <button type="button" title="Enregistrer Modification" id="btnEnregistrer" style="display: none;" onclick="validerFormulaire(event)">Enregistrer</button>
                <button type="button" title="Annuler Modification" id="btnAnnuler" style="display: none;" onclick="annulerModification()">Annuler</button>
            </div>
        </form>
        <!-- Popup de changement de mot de passe -->
        <div id="popupMotDePasse" style="display: <?= isset($_SESSION['error']) ? 'block' : 'none' ?>;">
            <h2>Changer le mot de passe</h2>
            <form id="formulaireMotDePasse" method="post" action="changerMotDePasse.php">
                <div>
                    <label for="ancienMdp">Ancien mot de passe :</label>
                    <input type="password" class="champsMdp" id="ancienMdp" name="ancienMdp" required>
                </div>
                <div>
                    <label for="nouveauMdp">Nouveau mot de passe :</label>
                    <input type="password" class="champsMdp" id="nouveauMdp" name="nouveauMdp" required>
                </div>
                <div>
                    <label for="confirmerMdp">Confirmer le mot de passe :</label>
                    <input type="password" class="champsMdp" id="confirmerMdp" name="confirmerMdp" required>
                    <p>Le mot de passe doit comporter au moins :<br>- 8 caractères<br>- 1 majuscule<br>- 1 minuscule<br>- 1 chiffre<br>- 1 caractère spécial (@$!%*?&).</p>
                    <br>
                </div>
                <!-- Affichage des messages d'erreur ou de succès -->
                <div id="erreurPopup" style="color: red; text-align: center; margin-bottom: 20px;">
                    <?php if (isset($_SESSION['error'])): ?>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                </div>
                <div id="btnMdp"style="margin-top: 20px;">
                    <button type="submit" title="Enregistrer mot de passe" id="btnMdpEnregistrer">Enregistrer</button>
                    <button type="button" title="Annuler Modification mot de passe" id="btnMdpAnnuler" onclick="fermerPopupMotDePasse()">Annuler</button>
                </div>
            </form>
        </div>
    </main>

    <?php Footer::render(HeaderType::Member); ?>
</body>

</html>