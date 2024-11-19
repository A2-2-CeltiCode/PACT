<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/ressources/icone/logo.svg" type="image/svg+xml">
    <title>Créez un Compte PACT</title>
    <link rel="stylesheet" href="./creationCompteMembre.css">
    <link rel="stylesheet" href="/ui.css">
    <script src="creationCompteMembre.js" defer></script>
</head>

<body>

    <?php

    use \composants\Input\Input;
    use \composants\Button\Button;

    require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
    require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

    // Déclaration des variables
    $nom = '';
    $prenom = '';
    $pseudo = '';
    $email = '';
    $telephone = '';
    $codePostal = '';
    $ville = '';
    $rue = '';
    $motDePasse = '';
    $confirmMdp = '';

    // Récupération des valeurs du formulaire et vérification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $codePostal = $_POST['codePostal'];
        $ville = $_POST['ville'];
        $rue = $_POST['rue'];
        $motDePasse = $_POST['motDePasse'];
        $confirmMdp = $_POST['confirmMdp'];
    }
        ?>
        <?php if (empty($_POST)) { ?>
        
        <div class="info-display">
            <a href="/pages/visiteur/accueil/accueil.php"><p id="retour-accueil">Retour à l'accueil</p></a>
            <a href="/pages/visiteur/accueil/accueil.php"><img alt="Logo" src="../../../ressources/icone/logo.svg"></a>
            <h1>Créez votre compte Membre</h1>
            <hr>
            <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
            <form name="creerCompteMembre" action="creationCompteMembre.php" method="POST" onsubmit="return formValide()">
                <div>
                    <label for="informations">Vos Informations</label>
                    <?php Input::render(class: "input-box", type: "text", name: "nom", placeholder: "Nom*", value: $nom, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "prenom", placeholder: "Prénom*", value: $prenom, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "pseudo", placeholder: "Pseudo*", value: $pseudo, required: true); ?>
                    <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "telephone", placeholder: "Numéro de Téléphone", value: $telephone, required: false); ?>
                </div>
                <br>
                <div class="div-adresse">
                    <label for="informations">Votre Adresse Postale</label>
                    <?php Input::render(class: "input-box", type: "text", name: "rue", placeholder: "Rue*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "codePostal", placeholder: "Code Postal*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "ville", placeholder: "Ville*", required: true); ?>
                </div>
                <br>
                <div>
                    <label for="informations">Créez un mot de passe</label>
                    <?php Input::render(class: "input-box", type: "password", name: "motDePasse", placeholder: "Mot de Passe*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "password", name: "confirmMdp", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
                    <p class="small">Le mot de passe doit comporter au moins :<br>- 8 caractères<br>- 1 majuscule<br>- 1 minuscule<br>- 1 chiffre<br>- 1 caractère spécial (@$!%*?&).</p>
                </div>
                
                <?php Button::render(class: "sign-upButton", submit: true, type: "membre", text: "S'inscrire");?>
            </form>
            <hr>
            <p class="small">Vous avez déjà un compte ?</p>
            <p class="small"><a href="../connexionCompteMembre/connexionCompteMembre.php">Connectez vous</a> avec votre compte PACT</p>
            <?php exit();?>
        </div>

        <?php 
            } else {
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $pseudo = $_POST['pseudo'];
                $email = $_POST['email'];
                $telephone = $_POST['telephone'];
                $codePostal = $_POST['codePostal'];
                $ville = $_POST['ville'];
                $rue = $_POST['rue'];
                $motDePasse = hash("sha256", $_POST['motDePasse']);
                
                require_once($_SERVER["DOCUMENT_ROOT"] . '/connect_params.php');
                try {
                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

                    $stmt = $dbh->prepare("INSERT INTO pact._adresse(codePostal, ville, nomRue, numTel) VALUES($codePostal, '$ville', '$rue', '$telephone')");
                    $stmt->execute();
                    $idAdresse = $dbh->lastInsertId();
                    
                    $stmt = $dbh->prepare("INSERT INTO pact._compte(idAdresse, mdp, email) VALUES('$idAdresse','$motDePasse','$email')");
                    $stmt->execute();
                    $idCompte = $dbh->lastInsertId();

                    $_SESSION['idCompte'] = $idCompte;

                    $stmt = $dbh->prepare("INSERT INTO pact._compteMembre(idCompte, prenom, nom, pseudo) VALUES('$idCompte','$prenom','$nom','$pseudo')");
                    $stmt->execute();


                    $dbh = null;
                    
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }
            
            }
        ?>
        <script>window.location.href = '../listeOffres/listeOffres.php';</script>
    </body>
</html>