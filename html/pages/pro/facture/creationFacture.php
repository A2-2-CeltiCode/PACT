<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $stmt = $dbh->prepare("SELECT * FROM pact._facture WHERE idfacture = 1");
    $stmt->execute();
    $offres = $stmt->fetchAll();


} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./creationFacture.css">
    <link rel="stylesheet" href="../../../ui.css">
    <title>Facturation</title>
</head>

<header>
    <h1>Facture</h1>
    <img class="logo" src="/ressources/icone/logo.svg" alt="Logo PACT">
</header>

<body>
    <main>
        <section>
            <p>Vendeur</p>
            <p>Adresse de fou</p>
            <p>31 Rue de ton père le chauve</p>
        </section>
        <section>
            <p>Client</p>
            <p>Le tonton bandeur</p>
            <p>24 impasse des vierges plus vierges</p>
        </section>

    </main>
</body>

</html>
<?php //lien de référence pour la facturation https://www.zervant.com/fr/modele-facture/#pid=1 ?>