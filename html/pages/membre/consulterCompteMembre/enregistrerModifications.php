<?php
// Démarrer la session
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

$idCompte = $_SESSION['idCompte'];

// Connexion à la base de données
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $dbh->exec("SET search_path TO pact;");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et valider les données du formulaire
        $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
        $prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8');
        $pseudo = htmlspecialchars($_POST['pseudo'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $numtel = htmlspecialchars($_POST['numtel'], ENT_QUOTES, 'UTF-8');
        $rue = htmlspecialchars($_POST['rue'], ENT_QUOTES, 'UTF-8');
        $codepostal = filter_var($_POST['codepostal'], FILTER_VALIDATE_INT);
        $ville = htmlspecialchars($_POST['ville'], ENT_QUOTES, 'UTF-8');

        if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[-\s][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/', $nom)) {
            throw new Exception("Le nom ne doit contenir que des lettres minuscules et majuscules.");
        }

        if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[-\s][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/', $prenom)) {
            throw new Exception("Le prénom ne doit contenir que des lettres minuscules et majuscules.");
        }
        
        if (!preg_match('/^[^\s@]+@[^\s@]+.[^\s@]+$/', $email)) {
            throw new Exception("L'email' doit contenir un '@' et un '.'.");
        }

        if (!preg_match('/^\d{2}([ .]?\d{2}){4}$/', $numtel)) {
            throw new Exception("Le numéro de téléphone doit contenir exactement 10 chiffres.");
        }
        
        if (!preg_match('/^\d{5}$/', $codepostal)) {
            throw new Exception("Le code postal doit contenir exactement 5 chiffres.");
        }


        // Préparer et exécuter la mise à jour de _compte
        $sqlCompte = "UPDATE _compte
                    SET email = :email
                    WHERE idCompte = :idCompte";
        $stmtCompte = $dbh->prepare($sqlCompte);
        $stmtCompte->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtCompte->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtCompte->execute();

        // Préparer et exécuter la mise à jour de _adresse
        $sqlAdresse = "UPDATE _adresse
                    SET rue = :rue, codePostal = :codepostal, ville = :ville, numTel = :numtel
                    WHERE idAdresse = (
                        SELECT idAdresse FROM _compte WHERE idCompte = :idCompte
                    )";
        $stmtAdresse = $dbh->prepare($sqlAdresse);
        $stmtAdresse->bindParam(':rue', $rue, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':codepostal', $codepostal, PDO::PARAM_INT);
        $stmtAdresse->bindParam(':ville', $ville, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':numtel', $numtel, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtAdresse->execute();

        // Préparer et exécuter la mise à jour de _comptemembre
        $sqlCompteMembre = "UPDATE _comptemembre
                        SET nom = :nom, prenom = :prenom, pseudo = :pseudo
                        WHERE idCompte = :idCompte";
        $stmtCompteMembre = $dbh->prepare($sqlCompteMembre);
        $stmtCompteMembre->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmtCompteMembre->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmtCompteMembre->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmtCompteMembre->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtCompteMembre->execute();



        // Retourner un message de succès
        $_SESSION['message'] = "Vos informations ont été mises à jour avec succès.";
    }
} catch (Exception $e) {
    // Retourner un message d'erreur
    $_SESSION['error'] = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
}

// Recharger la page précédente
header("Location: consulterCompteMembre.php");
exit;