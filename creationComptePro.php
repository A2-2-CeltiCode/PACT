<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créez un Compte Professionel PACT</title>
    <link rel="stylesheet" href="./creationComptePro.css">
    <link rel="stylesheet" href="./variables.css">

    <script src="creationComptePro.js" defer></script>
</head>

<body>
    <?php include "components/Input/Input.php"?>
    <?php include "components/Button/Button.php"?>
    <?php include "components/Toast/Toast.php"?>

    <?php
    // Déclaration des variables
    $estPrive = true;
    $denomination = '';
    $raisonS = '';
    $siren = '';
    $email = '';
    $telephone = '';
    $motDePasse = '';
    $confirmMdp = '';
    $iban = '';
    $erreurSiren = false;
    $mdpDifferents = false;  // Variable pour vérifier si les mots de passe sont différents

    $valide = true; // Deviendra false si une erreur dans le formulaire

    // Récupération des valeurs du formulaire et vérification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $denomination = $_POST['denomination'];
        $raisonS = $_POST['raisonS'];
        $siren = $_POST['siren'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $motDePasse = $_POST['motDePasse'];
        $confirmMdp = $_POST['confirmMdp'];
        $iban = $_POST['iban'];

        // Vérifications
        if ($motDePasse !== $confirmMdp) {
                $mdpDifferents = true;  // Flag activé si les mots de passe sont différents
                $valide = false;
        }
    }
        ?>
        <?php if (($mdpDifferents) || (empty($_POST))) { ?>
        
        <div class="info-display">
            <img alt="Logo" src="assets/icon/logo.svg">
            <h1>Créez votre compte Professionnel</h1>
            <hr>
            <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
            <!-- Affichage du message d'erreur si les mots de passe sont différents -->
            <?php if ($mdpDifferents) { ?>
                <p class="messagesErreurs">Les mots de passe ne correspondent pas. Veuillez les entrer à nouveau.</p>
            <?php } ?>
            <form name="creerComptePro" action="creationComptePro.php" method="POST">
                <div>
                    <label for="informations">Vos Informations</label>
                    <?php Input::render(class: "input-box", type: "text", name: "denomination", placeholder: "Dénomination*", value: $denomination, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "raisonS", placeholder: "Raison Sociale*", value: $raisonS, required: true); ?>
                    <!-- Case à cocher "Entreprise privée" -->
                    <p for="estPrive">
                        <input type="checkbox" id="estPrive" name="estPrive" onclick="toggleSirenField()"> Entreprise privée
                    </p>
                    <!-- Champ SIREN qui s'affiche uniquement si "Entreprise privée" est coché -->
                    <div id="champSiren" style="display: none;">
                        <?php Input::render(class: "input-box", type: "text", name: "siren", placeholder: "Numéro SIREN*", value: $siren, required: true); ?>
                    </div>
                    <br>
                    <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "telephone", placeholder: "Numéro de Téléphone", value: $telephone, required: false); ?>
                </div>

                <div>
                    <label for="informations">Créez un mot de passe</label>
                    <?php Input::render(class: "input-box", type: "password", name: "motDePasse", placeholder: "Mot de Passe*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "password", name: "confirmMdp", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
                </div>

                <div>
                    <label for="informations">Vos Informations Bancaires</label>
                    <?php Input::render(class: "input-box", type: "text", name: "iban", placeholder: "IBAN", value: $iban, required: false); ?>
                    <p id="iban-texte">L'IBAN pourra être renseigné plus tard.</p>
                </div>
                
                <?php Button::render(class: "sign-upButton", submit: true, type: "pro", text: "S'inscrire") ?>
            </form>
            <hr>
            <p>Vous avez déjà un compte ?</p>
            <p><a href="connexionComptePro.php">Connectez vous</a> avec votre compte PACT Professionel</p>
        </div>

        <?php 
            } else {
                $nouvelUtilisateur = $_POST['denomination'].";".$_POST['raisonS'].";".$_POST['siren'].";".$_POST['email'].";".$_POST['telephone'].";".$_POST['motDePasse'].";".$_POST['iban'];
                file_put_contents("user_data.csv", "\n".$nouvelUtilisateur);
                ?>
                <div class="info-display">
                    <h1>Tout est prêt !</h1>
                    <hr>
                    <p>Votre compte à été créé. Veuillez-vous connecter</p>
                    <?php Button::render(text: "Se connecter", type: ButtonType::Pro, onClick: "renderToast('COMPTE CREE AHA', 'success')"); ?>
                    <?php Toast::render("", ToastType::WARNING); ?>
                </div>

                <?php
            } 
        ?>
    </body>
</html>