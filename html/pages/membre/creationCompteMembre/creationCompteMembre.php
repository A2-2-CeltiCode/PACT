<?php
session_start();
use \composants\Input\Input;
use \composants\Button\Button;

require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] .  "/connect_params.php";

// Déclaration des variables
$nom = $prenom = $pseudo = $email = $telephone = $codePostal = $ville = $rue = $motDePasse = $confirmMdp = '';
$pseudoUtilise = false;
$emailUtilise = false;

 // Récupération des valeurs du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $codePostal = $_POST['codePostal'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $rue = $_POST['rue'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';
    $confirmMdp = $_POST['confirmMdp'] ?? '';

    // Connexion à la base de données
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $dbh->exec("SET search_path TO pact;");
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Vérification de l'email
    $requete_sql = 'SELECT * FROM pact.vue_compte_membre WHERE email = :email';
    $requete_preparee = $dbh->prepare($requete_sql);
    $requete_preparee->bindParam(':email', $email);
    $requete_preparee->execute();
    $emailUtilise = $requete_preparee->fetch(PDO::FETCH_ASSOC) ? true : false;

    // Vérification du pseudo si l'email est disponible
    if (!$emailUtilise) {
        $requete_sql = 'SELECT * FROM pact.vue_compte_membre WHERE pseudo = :pseudo';
        $requete_preparee = $dbh->prepare($requete_sql);
        $requete_preparee->bindParam(':pseudo', $pseudo);
        $requete_preparee->execute();
        $pseudoUtilise = $requete_preparee->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Si aucune erreur, on traite les données
    if (!$emailUtilise && !$pseudoUtilise) {
        $motDePasse = hash("sha256", $motDePasse);

        // Insertion des données dans la base
        try {
            $stmt = $dbh->prepare("INSERT INTO pact._adresse(codePostal, ville, rue, numTel) VALUES(:codePostal, :ville, :rue, :telephone)");
            $stmt->bindParam(':codePostal', $codePostal);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':rue', $rue);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->execute();
            $idAdresse = $dbh->lastInsertId();

            $stmt = $dbh->prepare("INSERT INTO pact._compte(idAdresse, mdp, email) VALUES(:idAdresse, :mdp, :email)");
            $stmt->bindParam(':idAdresse', $idAdresse);
            $stmt->bindParam(':mdp', $motDePasse);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $idCompte = $dbh->lastInsertId();
            $_SESSION['idCompte'] = $idCompte;
            $_SESSION['typeUtilisateur'] = "membre";

            $stmt = $dbh->prepare("INSERT INTO pact._compteMembre(idCompte, prenom, nom, pseudo) VALUES(:idCompte, :prenom, :nom, :pseudo)");
            $stmt->bindParam(':idCompte', $idCompte);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();

            // Redirection après succès
            header("Location: ../../visiteur/accueil/accueil.php");
            exit();
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créez un Compte PACT</title>
    <link rel="stylesheet" href="./creationCompteMembre.css">
    

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <script src="creationCompteMembre.js"></script>
    <script>
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                document.getElementById("btnS").click();
            } 
        });
    </script>
</head>
<body>
<?php if ((empty($_POST)) || $emailUtilise ||$pseudoUtilise ){ ?>
        
        <section>
            <a href="/"><p id="retour-accueil">Retour à l'accueil</p></a>
            <a href="/"><img alt="Logo" src="../../../ressources/icone/logo.svg"></a>
            <h1>Créez votre compte Membre</h1>
            <hr>
            <p id="necessary-fields-label">(*) - Champs Obligatoires</p>
            <form name="creerCompteMembre" action="creationCompteMembre.php" method="POST" onsubmit="return formValide()">
                <div>
                    <label for="informations">Vos Informations</label>
                    <?php Input::render(class: "input-box", type: "text", name: "nom", placeholder: "Nom*", value: $nom, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "prenom", placeholder: "Prénom*", value: $prenom, required: true); ?>
                    <?php Input::render(class: "input-box", type: "text", name: "pseudo", placeholder: "Pseudo*", value: $pseudo, required: true); ?>
                    <!-- Erreur si le pseudo est déjà présent dans la bdd (erreur visible si l'email n'est) -->
                    <?php if ($pseudoUtilise && !$emailUtilise) { ?>
                        <p class="form-texte small">Pseudo déjà utilisé, veuillez en choisir un autre.</p>
                    <?php } ?>
                    <?php Input::render(class: "input-box", type: "email", name: "email", placeholder: "Adresse Email*", value: $email, required: true); ?>
                    <!-- Erreur si l'email est déjà présent dans la bdd -->
                    <?php if ($emailUtilise) { ?>
                        <p class="form-texte small">Cet email est déjà liée à un compte.</p>
                    <?php } ?>
                    <?php Input::render(class: "input-box", type: "text", name: "telephone", placeholder: "Numéro de Téléphone", value: $telephone, required: false); ?>
                </div>
                <br>
                <div class="div-adresse">
                    <label for="informations">Votre Adresse Postale</label>
                    <?php Input::render(class: "input-box",id: "ville", type: "text", name: "ville", placeholder: "Ville*", value: $ville, required: true,onkeyup: "suggestVilles()"); ?>
                    <div id="suggestions"></div>
                    <?php Input::render(class: "input-box",id: "postcode", type: "text", name: "codePostal", placeholder: "Code Postal*", value: $codePostal, required: true); ?>
                    <?php Input::render(class: "input-box",id: "adresse", type: "text", name: "rue", placeholder: "Rue*", value: $rue, required: true,onkeyup: "suggestAdresses()"); ?>
                    <div id="adresseSuggestions"></div>
                    <div id="map" style="height: 300px; width: 100%;"></div>
                    <input type="hidden" id="longitude" name="longitude">
                    <input type="hidden" id="latitude" name="latitude">
                </div>
                <br>
                <div>
                    <label for="informations">Créez un mot de passe</label>
                    <?php Input::render(class: "input-box", type: "password", name: "motDePasse", placeholder: "Mot de Passe*", required: true); ?>
                    <?php Input::render(class: "input-box", type: "password", name: "confirmMdp", placeholder: "Confirmer le Mot de Passe*", required: true); ?>
                    <p class="small">Le mot de passe doit comporter au moins :<br>- 8 caractères<br>- 1 majuscule<br>- 1 minuscule<br>- 1 chiffre<br>- 1 caractère spécial (@$!%*?&).</p>
                </div>
                
                <?php Button::render(class: "sign-upButton",title:"S'inscire en tant que membre" ,submit: true, type: "member", text: "S'inscrire",id:"btnS");?>
            </form>
            <hr>
            <p class="small">Vous avez déjà un compte ?</p>
            <p class="small"><a href="../connexionCompteMembre/connexionCompteMembre.php">Connectez vous</a> avec votre compte PACT</p>
            <?php exit();?>
        </section>
    </body>
    
</html>

<?php } ?>
