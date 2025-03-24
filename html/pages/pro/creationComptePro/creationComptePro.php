<?php
session_start();
use \composants\Input\Input;
use \composants\Button\Button;

require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

// Déclaration des variables
$denomination = $raisonS = $siren = $email = $telephone = $codePostal = $ville = $rue = $motDePasse = $confirmMdp = $iban = '';
$sirenUtilise = $emailUtilise = false;

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $denomination = $_POST['denomination'] ?? '';
    $raisonS = $_POST['raisonS'] ?? '';
    $siren = $_POST['siren'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $codePostal = $_POST['codePostal'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $rue = $_POST['rue'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';
    $confirmMdp = $_POST['confirmMdp'] ?? '';
    $iban = $_POST['iban'] ?? '';

    try {
        // Connexion à la base de données
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $dbh->exec("SET search_path TO pact;");

        // Vérification si le SIREN existe déjà
        if (!empty($siren)) {
            $stmt = $dbh->prepare("SELECT 1 FROM pact._compteProPrive WHERE numSiren = :siren");
            $stmt->bindParam(':siren', $siren);
            $stmt->execute();
            $sirenUtilise = $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
        }

        // Vérification si l'email existe déjà
        $stmt = $dbh->prepare("SELECT 1 FROM pact._compte WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailUtilise = $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;

        // Si ni le SIREN ni l'email ne sont utilisés, insertion des données
        if (!$sirenUtilise && !$emailUtilise) {
            // Insertion de l'adresse
            $stmt = $dbh->prepare("INSERT INTO pact._adresse(codePostal, ville, rue, numTel) VALUES(:codePostal, :ville, :rue, :telephone)");
            $stmt->bindParam(':codePostal', $codePostal);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':rue', $rue);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->execute();
            $idAdresse = $dbh->lastInsertId();

            // Insertion du compte
            $motDePasseHash = hash("sha256", $motDePasse);
            $stmt = $dbh->prepare("INSERT INTO pact._compte(idAdresse, mdp, email) VALUES(:idAdresse, :mdp, :email)");
            $stmt->bindParam(':idAdresse', $idAdresse);
            $stmt->bindParam(':mdp', $motDePasseHash);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $idCompte = $dbh->lastInsertId();

            // Insertion des données professionnelles
            $stmt = $dbh->prepare("INSERT INTO pact._comptePro(idCompte, denominationSociale, raisonSocialePro, banqueRib) VALUES(:idCompte, :denomination, :raisonS, :iban)");
            $stmt->bindParam(':idCompte', $idCompte);
            $stmt->bindParam(':denomination', $denomination);
            $stmt->bindParam(':raisonS', $raisonS);
            $stmt->bindParam(':iban', $iban);
            $stmt->execute();

            // Insertion dans la table privée ou publique selon le type d'entreprise
            if (!empty($siren)) {
                $stmt = $dbh->prepare("INSERT INTO pact._compteProPrive(idCompte, numSiren) VALUES(:idCompte, :siren)");
                $stmt->bindParam(':idCompte', $idCompte);
                $stmt->bindParam(':siren', $siren);
                $stmt->execute();
            } else {
                $stmt = $dbh->prepare("INSERT INTO pact._compteProPublic(idCompte) VALUES(:idCompte)");
                $stmt->bindParam(':idCompte', $idCompte);
                $stmt->execute();
            }

            // Redirection après succès
            $_SESSION['idCompte'] = $idCompte;
            $_SESSION['typeUtilisateur'] = "pro";
            header("Location: ../listeOffres/listeOffres.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Erreur lors de la connexion ou de l'insertion : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créez un Compte Professionnel PACT</title>
        <link rel="stylesheet" href="./creationComptePro.css">
        <script src="creationComptePro.js"></script>
    </head>
    <body>
        <section>
            <a href="/pages/visiteur/accueil/accueil.php"><p id="retour-accueil">Retour à l'accueil</p></a>
            <a href="/pages/visiteur/accueil/accueil.php"><img alt="Logo" src="../../../ressources/icone/logo.svg"></a>
            <h1>Créez votre compte Professionnel</h1>
            <hr>
            <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
            <form name="creerComptePro" action="creationComptePro.php" method="POST" onsubmit="return formValide()">
                <div>
                    <label for="informations">Vos Informations</label>
                    <?php Input::render(class: "input-box", type: "text", name: "denomination", placeholder: "Dénomination*", value: $denomination, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "raisonS", placeholder: "Raison Sociale*", value: $raisonS, required: true); ?>
                    <!-- Case à cocher "Entreprise privée" -->
                    <p class="small" for="estPrive">
                        <input type="checkbox" id="estPrive" name="estPrive" onclick="afficherChampSiren()" <?= isset($_POST['estPrive']) ? 'checked' : '' ?>> Entreprise privée
                    </p>

                    <!-- Champ SIREN qui s'affiche uniquement si "Entreprise privée" est coché -->
                    <div id="champSiren" style="display: <?= isset($_POST['estPrive']) ? 'block' : 'none'; ?>;">
                        <?php Input::render(class: "input-box", type: "text", name: "siren", placeholder: "Numéro SIREN*", value: $siren, required: false); ?>
                        <?php if ($sirenUtilise): ?>
                            <p class="messagesErreurs form-texte small">Ce numéro de SIREN est déjà lié à un compte.</p>
                        <?php endif; ?>
                        <p class="form-texte small">SIREN requis pour les entreprises privées.</p>
                    </div>

                    
                    <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                    <?php if ($emailUtilise): ?>
                        <p class="messagesErreurs form-texte small">Cette adresse email est déjà liée à un compte.</p>
                    <?php endif; ?>
                    <?php Input::render(class: "input-box", type: "text", name: "telephone", placeholder: "Numéro de Téléphone", value: $telephone, required: false); ?>
                </div>
                <br>
                <div class="div-adresse">
                    <label for="informations">Votre Adresse Postale</label>
                    <?php Input::render(class: "input-box", type: "text", name: "rue", placeholder: "Rue*", value: $rue, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "codePostal", placeholder: "Code Postal*", value: $codePostal, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "ville", placeholder: "Ville*", value: $ville, required: true); ?>
                </div>
                <br>
                <div>
                    <label for="informations">Créez un mot de passe</label>
                    <?php Input::render(class: "input-box", type: "password", name: "motDePasse", placeholder: "Mot de Passe*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "password", name: "confirmMdp", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
                    <p class="small">Le mot de passe doit comporter au moins :<br>- 8 caractères<br>- 1 majuscule<br>- 1 minuscule<br>- 1 chiffre<br>- 1 caractère spécial (@$!%*?&).</p>
                </div>
                <br>
                <div>
                    <label for="informations">Vos Informations Bancaires</label>
                    <?php Input::render(class: "input-box", type: "text", name: "iban", placeholder: "IBAN", value: $iban, required: false); ?>
                    <p class="form-texte small">L'IBAN pourra être renseigné plus tard.</p>
                </div>
                
                <?php Button::render(class: "sign-upButton",title:"bouton pour s'inscrire en temp que pro" ,submit: true, type: "pro", text: "S'inscrire",id:"btn-val");?>
            </form>
            <hr>
            <p class="small">Vous avez déjà un compte ?</p>
            <p class="small"><a href="../connexionComptePro/connexionComptePro.php">Connectez vous</a> avec votre compte PACT Professionel</p>
            <?php exit();?>
        </section>
    </body>
</html>
