<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créez un Compte Professionel PACT</title>
    <link rel="stylesheet" href="./creationComptePro.css">
    <link rel="stylesheet" href="/ui.css">
    
    <script src="creationComptePro.js" defer></script>
</head>

<body>

    <?php

    use \composants\Input\Input;
    use \composants\Button\Button;

    include $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
    include $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
    include $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

    // Déclaration des variables
    $estPrive = true;
    $denomination = '';
    $raisonS = '';
    $siren = '';
    $email = '';
    $telephone = '';
    $codePostal = '';
    $ville = '';
    $numero = '';
    $rue = '';
    $motDePasse = '';
    $confirmMdp = '';
    $iban = '';

    // Récupération des valeurs du formulaire et vérification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $denomination = $_POST['denomination'];
        $raisonS = $_POST['raisonS'];
        $siren = $_POST['siren'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone']; //
        $codePostal = $_POST['codePostal']; //
        $ville = $_POST['ville']; //
        $numero = $_POST['numero']; //
        $rue = $_POST['rue']; //
        $motDePasse = $_POST['motDePasse'];
        $confirmMdp = $_POST['confirmMdp'];
        $iban = $_POST['iban'];
    }
        ?>
        <?php if (empty($_POST)) { ?>
        
        <div class="info-display">
            <a href="/pages/visiteur/accueil/accueil.php"><p id="retour-accueil">Retour à l'accueil</p></a>
            <a href="accueil.php"><img alt="Logo" src="../../../ressources/icone/logo.svg"></a>
            <h1>Créez votre compte Professionnel</h1>
            <hr>
            <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
            <form name="creerComptePro" action="creationComptePro.php" method="POST" onsubmit="return formValide()">
                <div>
                    <label for="informations">Vos Informations</label>
                    <?php Input::render(class: "input-box", type: "text", name: "denomination", placeholder: "Dénomination*", value: $denomination, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "raisonS", placeholder: "Raison Sociale*", value: $raisonS, required: true); ?>
                    <!-- Case à cocher "Entreprise privée" -->
                    <p for="estPrive">
                        <input type="checkbox" id="estPrive" name="estPrive" onclick="afficherChampSiren()"> Entreprise privée
                    </p>
                    <!-- Champ SIREN qui s'affiche uniquement si "Entreprise privée" est coché -->
                    <div id="champSiren" style="display: none;">
                        <?php Input::render(class: "input-box", type: "text", name: "siren", placeholder: "Numéro SIREN*", value: $siren, required: false); ?>
                        <p class="form-texte">SIREN requis pour les entreprises privées.</p>
                    </div>
                    
                    <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "telephone", placeholder: "Numéro de Téléphone", value: $telephone, required: false); ?>
                </div>
                <br>
                <div class="div-adresse">
                    <label for="informations">Votre Adresse Postale</label>
                    <?php Input::render(class: "input-box", type: "text", name: "codePostal", placeholder: "Code Postal*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "ville", placeholder: "Ville*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "rue", placeholder: "Rue*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "numero", placeholder: "Numéro", required: false); ?>
                </div>
                <br>
                <div>
                    <label for="informations">Créez un mot de passe</label>
                    <?php Input::render(class: "input-box", type: "password", name: "motDePasse", placeholder: "Mot de Passe*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "password", name: "confirmMdp", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
                    <p>Le mot de passe doit comporter au moins :<br>- 8 caractères<br>- 1 majuscule<br>- 1 minuscule<br>- 1 chiffre<br>- 1 caractère spécial (@$!%*?&).</p>
                </div>
                <br>
                <div>
                    <label for="informations">Vos Informations Bancaires</label>
                    <?php Input::render(class: "input-box", type: "text", name: "iban", placeholder: "IBAN", value: $iban, required: false); ?>
                    <p class="form-texte">L'IBAN pourra être renseigné plus tard.</p>
                </div>
                
                <?php Button::render(class: "sign-upButton", submit: true, type: "pro", text: "S'inscrire");
                exit();
                ?>
            </form>
            <hr>
            <p>Vous avez déjà un compte ?</p>
            <p><a href="../connexionComptePro/connexionComptePro.php">Connectez vous</a> avec votre compte PACT Professionel</p>
        </div>

        <?php 
            } else {
                $denomination = $_POST['denomination'];
                $raisonS = $_POST['raisonS'];
                $siren = $_POST['siren'];
                $email = $_POST['email'];           
                $telephone = $_POST['telephone'];      
                $codePostal = $_POST['codePostal']; 
                $ville = $_POST['ville'];
                $rue = $_POST['rue'];
                $numero = $_POST['numero'];
                $motDePasse = $_POST['motDePasse'];
                $iban = $_POST['iban'];
                
                include($_SERVER["DOCUMENT_ROOT"] . '/connect_params.php');
                try {
                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

                    $stmt = $dbh->prepare("INSERT INTO pact._adresse(codePostal, ville, nomRue, numRue, numTel) VALUES($codePostal, '$ville', '$rue', '$numero', '$telephone')");
                    $stmt->execute();
                    $idAdresse = $dbh->lastInsertId();
                    
                    $stmt = $dbh->prepare("INSERT INTO pact._compte(idAdresse, mdp, email) VALUES('$idAdresse','$motDePasse','$email')");
                    $stmt->execute();
                    $idCompte = $dbh->lastInsertId();

                    $_SESSION['idCompte'] = $idCompte;

                    $stmt = $dbh->prepare("INSERT INTO pact._comptePro(idCompte,denominationSociale, raisonSocialePro,banqueRib) VALUES('$idCompte','$denomination','$raisonS','$iban')");
                    $stmt->execute();

                    if(strlen($siren) > 0){
                        $stmt = $dbh->prepare("INSERT INTO pact._compteProPrive(idCompte,numSiren) VALUES('$idCompte','$siren')");
                        $stmt->execute();
                    }
                    else{
                        $stmt = $dbh->prepare("INSERT INTO pact._compteProPublic(idCompte) VALUES('$idCompte')");
                        $stmt->execute();
                    }

                    $dbh = null;
                    
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }
            
            }
        ?>
        <!--<script>window.location.href = '/pages/visiteur/accueil/accueil.php';</script> -->
    </body>
</html>