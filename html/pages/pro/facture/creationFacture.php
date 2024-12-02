<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $offre = $dbh->query('SELECT * FROM pact._offre' . ' WHERE idoffre = 1', PDO::FETCH_ASSOC)->fetch();
    $pro = $dbh->query("SELECT * FROM pact.vue_compte_pro WHERE idcompte = 2", PDO::FETCH_ASSOC)->fetch();
    
    $nomPACT = "PACT";
    $adressePACT = $dbh->query('SELECT * FROM pact._adresse' . ' WHERE idadresse = 1', PDO::FETCH_ASSOC)->fetch();
    $dateDuJour = date("d-m-Y");
    $facture = $dbh->query("SELECT * FROM pact.vue_facture WHERE idfacture = 1", PDO::FETCH_ASSOC)->fetch();


    $mois_prestation = date(format: 'm', timestamp: strtotime($facture['dateprestaservices']));

    switch ($mois_prestation) {
        case "1":
            $mois_prestation = "Janvier";
            break;
        case "2":
            $mois_prestation = "Février";
            break;
        case "3":
            $mois_prestation = "Mars";
            break;
        case "4":
            $mois_prestation = "Avril";
            break;
        case "5":
            $mois_prestation = "Mai";
            break;
        case "6":
            $mois_prestation = "Juin";
            break;
        case "7":
            $mois_prestation = "Juillet";
            break;
        case "8":
            $mois_prestation = "Août";
            break;
        case "9":
            $mois_prestation = "Septembre";
            break;
        case "10":
            $mois_prestation = "Octobre";
            break;
        case "11":
            $mois_prestation = "Novembre";
            break;
        case "12":
            $mois_prestation = "Décembre";
            break;
        default:
            $mois_prestation = "Mois invalide";
            break;
    }
    

    $date_prestation = $mois_prestation . ' ' . date('Y', strtotime($facture['dateprestaservices']));

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
<pre><?php 
   // print_r($facture)?>
</pre>

<header>
    <h1>Facture</h1>
    <img class="logo" src="/ressources/icone/logo.svg" alt="Logo PACT">
</header>
<body>
    <main>
        <table class="partie">
            <tr>
                <th>Vendeur</th>
                <td><?php echo $nomPACT?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $adressePACT['rue'] .', '. $adressePACT['codepostal'] .' '. $adressePACT['ville'] ?></td>
        </table>
        <table class="partie">
            <tr>
                <th>Professionnel</th>
                <td><?php echo $pro['denominationsociale'] . ' - ' . $pro['raisonsocialepro']?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $pro['rue'] .', '. $pro['codepostal'] .' '. $pro['ville'] ?></td>
        </table>

        <table class="info-facture">
            <tr>
                <th>Date d'émission</th>
                <th>Numéro de facture</th>
                <th>Nom de l'offre</th>
                <th>Date de la prestation de services</th>
                <th>Date d'échéance du règlement</th>
            </tr>
            <tr>
                <td><?php echo $dateDuJour?></td>
                <td><?php echo $facture['idfacture']?></td>
                <td><?php echo $offre['titre']?></td>
                <td><?php echo $date_prestation?></td>
                <td><?php echo date("d-m-Y", strtotime($facture['dateecheance']))?></td>
            </tr>
        </table>
        <table class="tarifs-facture" cellspacing="0" cellpadding="0">
            <tr>
                <th>Nom du service</th>
                <th>Quantité du service</th>
                <th>Prix HT</th>
                <th>Prix TTC</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?php echo $offre['nomoption'] ?></td>
                <td>ski</td>
                <td>15€</td>
                <td>18€</td>
                <td>18€</td>
            </tr>
            <tr>
                <td><?php echo $offre['nomforfait']?></td>
                <td>bidi</td>
                <td>15€</td>
                <td>18€</td>
                <td>18€</td>
            </tr>
        </table>
        <table class="total">
        <tr>
                <th>Total HT</th>
                <td>100€</td>
            </tr>            <tr>
                <th>Total TVA</th>
                <td>20€</td>
            </tr>
            <tr>
                <th>Total TTC</t>
                <td>120€</td>
            </tr>
        </table>
    </main>
</body>

</html>
<?php //lien de référence pour la facturation https://www.zervant.com/fr/modele-facture/#pid=1 ?>