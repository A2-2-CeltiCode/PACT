<?php
// Démarrer la session
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

// Configuration de la base de données
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$password = '13phenix';
$idCompte = 2; //$_SESSION['idCompte']; // ID de l'utilisateur connecté

try {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $ancienMdp = $_POST['ancienMdp'] ?? '';
        $nouveauMdp = $_POST['nouveauMdp'] ?? '';
        $confirmerMdp = $_POST['confirmerMdp'] ?? '';

        // Validation des champs
        if (empty($ancienMdp) || empty($nouveauMdp) || empty($confirmerMdp)) {
            throw new Exception("Tous les champs sont requis.");
        }

        if (strlen($nouveauMdp) < 8) {
            throw new Exception("Le nouveau mot de passe doit contenir au moins 8 caractères.");
        }

        if ($nouveauMdp !== $confirmerMdp) {
            throw new Exception("Le nouveau mot de passe et sa confirmation ne correspondent pas.");
        }

        // Connexion à la base de données
        $pdo = new PDO("pgsql:host=$host;port=5433;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définir le schéma "pact" pour la session
        $pdo->exec("SET search_path TO pact");

        // Hacher l'ancien mot de passe pour la vérification
        $ancienMdpHash = hash('sha256', $ancienMdp);

        // Vérifier l'ancien mot de passe
        $sql = "SELECT mdp FROM _compte WHERE idCompte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['mdp'] !== $ancienMdpHash) {
            throw new Exception("L'ancien mot de passe est incorrect.");
        }

        // Hacher le nouveau mot de passe
        $nouveauMdpHash = hash('sha256', $nouveauMdp);

        // Mettre à jour le mot de passe dans la base de données
        $sqlUpdate = "UPDATE _compte SET mdp = :nouveauMdp WHERE idCompte = :idCompte";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':nouveauMdp', $nouveauMdpHash, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Message de succès
        $_SESSION['message'] = "Le mot de passe a été changé avec succès.";
    }
} catch (Exception $e) {
    // Gestion des erreurs
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

// Rediriger vers la page précédente
header("Location: consulterComptePro.php");
exit;