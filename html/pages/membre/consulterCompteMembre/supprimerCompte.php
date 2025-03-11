<?php
session_start();

require_once "../../../connect_params.php";

if (!isset($_SESSION['idCompte'])) {
    header("Location: /pages/visiteur/accueil/accueil.php");
}

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $id = $_SESSION["idCompte"];

    $stmt = $dbh->prepare("UPDATE pact._compteMembre SET prenom = NULL, nom = NULL, pseudo = NULL WHERE idcompte = ?");
    $stmt->execute([$id]);

    $stmt = $dbh->prepare("DELETE FROM pact._cleapi WHERE idcompte = ?");
    $stmt->execute([$id]);

    $stmt = $dbh->prepare("DELETE FROM pact._compte WHERE idcompte = ?");
    $stmt = $dbh->prepare("UPDATE pact._compte SET mdp = NULL, email = NULL WHERE idcompte = ?");
    $stmt->execute([$id]);

    $stmt = $dbh->prepare("DELETE FROM pact._adresse USING pact._compte WHERE pact._compte.idadresse = pact._adresse.idadresse AND idcompte = ?");
    $stmt = $dbh->prepare("UPDATE pact._adresse SET codepostal = NULL, ville = NULL, rue = NULL, numtel = NULL FROM pact._compte WHERE pact._compte.idadresse = pact._adresse.idadresse AND idcompte = ?");
    $stmt->execute([$id]);

    $dbh = null;

    header("Location: /deconnexion.php");
} catch (PDOException $e) {
    echo "<pre>" . PHP_EOL;
    print_r($e->getMessage());
    echo "</pre>" . PHP_EOL;
    die();
}