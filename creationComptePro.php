<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créez un Compte Professionel PACT</title>
    <link rel="stylesheet" href="./creationComptePro.css">
    <link rel="stylesheet" href="./variables.css">
</head>

<body>
    <?php include "components/Input/Input.php"?>
    <?php include "components/Button/Button.php"?>
    <?php include "components/Toast/Toast.php"?>

    <?php
    // Déclaration des variables
    $denomination = '';
    $siren = '';
    $email = '';
    $phone = '';
    $password = '';
    $confirmPwd = '';
    $iban = '';
    $passwordMismatch = false;  // Variable pour vérifier si les mots de passe sont différents

    // Récupération des valeurs du formulaire et vérification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $denomination = $_POST['denomination'];
        $siren = $_POST['siren'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirmPwd = $_POST['confirmpwd'];
        $iban = $_POST['iban'];

        // Vérification si les mots de passe ne correspondent pas
        if ($password !== $confirmPwd) {
            $passwordMismatch = true;  // Flag activé si les mots de passe sont différents
        }
    }
    ?>
    <?php if (($passwordMismatch) || (empty($_POST))) { ?>
    
    <div class="info-display">
        <img alt="Logo" src="assets/icon/logo.svg">
        <h1>Créez votre compte Professionnel</h1>
        <hr>
        <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
        <!-- Affichage du message d'erreur si les mots de passe sont différents -->
        <?php if ($passwordMismatch) { ?>
                <p id="passwordMismatch" style="color: red;">Les mots de passe ne correspondent pas. Veuillez les entrer à nouveau.</p>
        <?php } ?>
        <form name="creerComptePro" action="creationComptePro.php" method="POST">
            <div>
                <label for="informations">Vos Informations</label>
                <?php Input::render(class: "input-box", type: "text", name: "denomination", placeholder: "Dénomination / Raison Sociale*", value: $denomination, required: true); ?>
                <?php Input::render(class: "input-box", type: "text", name: "siren", placeholder: "Numéro SIREN*", value: $siren, required: true); ?>
                <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                <?php Input::render(class: "input-box", type: "text", name: "phone", placeholder: "Numéro de Téléphone", value: $phone, required: false); ?>
            </div>

            <div>
                <label for="informations">Créez un mot de passe</label>
                <?php Input::render(class: "input-box", type: "password", name: "password", placeholder: "Mot de Passe*", required: true); ?>
                <?php Input::render(class: "input-box", type: "password", name: "confirmpwd", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
            </div>

            <div>
                <label for="informations">Vos Informations Bancaires</label>
                <?php Input::render(class: "input-box", type: "text", name: "iban", placeholder: "IBAN", value: $iban, required: false); ?>
                <p>L'IBAN pourra être renseigné plus tard.</p>
            </div>
            
            <?php Button::render(class: "sign-upButton", submit: true, type: "pro", text: "S'inscrire") ?>
        </form>
        <hr>
        <p>Vous avez déjà un compte ?</p>
        <p><a href="connexionComptePro.php">Connectez vous</a> avec votre compte PACT Professionel</p>
    </div>

    <?php 
        } else {
            $newuser = $_POST['denomination'].";".$_POST['siren'].";".$_POST['email'].";".$_POST['phone'].";".$_POST['password'].";".$_POST['iban'];
            file_put_contents("user_data.csv", "\n".$newuser);
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