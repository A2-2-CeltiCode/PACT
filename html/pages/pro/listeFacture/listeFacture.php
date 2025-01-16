<?php
session_start();
error_reporting(0);
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/pages/pro/facture/creationFacture.php";
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';
$idOffre = $_GET['idOffre'];    
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$sql = "SELECT * FROM pact._facture WHERE idoffre = :idoffre";
$sth = $dbh->prepare($sql);
$sth->bindValue(':idoffre', $idOffre, PDO::PARAM_INT);
$sth->execute();
$factures = $sth->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/pages/pro/listeFacture/listeFacture.css">
    <link rel="stylesheet" href="../../../ui.css">
    <title>Liste des Factures</title>
</head>
<body>
    <?php Header::render(HeaderType::Pro); ?>
    <main>
        <h1>Liste des Factures</h1>
        <table>
            <thead>
                <tr>
                    <th>Date de la Facture</th>
                    <th>Télécharger</th>
                </tr>
            </thead>
            <tbody>
            <?php
        foreach ($factures as $facture) {
            $timestamp = strtotime($facture['dateprestaservices']);
            $date = strftime('%B %Y', $timestamp);
            setlocale(LC_TIME, 'fr_FR.UTF-8');
            $moisAnnee = date('m-Y', $timestamp);
            
            echo "<tr>";
            echo "<td>{$date}</td>";
            echo "<td><a href='#' onclick='imprimerFacture({$facture['idfacture']}, {$idOffre}, \"{$moisAnnee}\")'>Télécharger ({$moisAnnee})</a></td>";
            echo "</tr>";
        }
        ?>
            </tbody>
        </table>
        <div id="factureContent" style="display: none;"></div>
    </main>
    <?php Footer::render(FooterType::Pro); ?>

    <script>
function imprimerFacture(idfacture, idoffre, mois) {
    const url = `renderFacture.php?idfacture=${idfacture}&idoffre=${idoffre}&mois=${mois}`;
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('factureContent').innerHTML = html;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <link rel="stylesheet" href="/pages/pro/facture/creationFacture.css">
                </head>
                <body>
                    ${html}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        });
}
</script>
</body>

</html>

