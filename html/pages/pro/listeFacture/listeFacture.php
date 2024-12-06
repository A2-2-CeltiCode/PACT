<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/pages/pro/facture/creationFacture.php";
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';
$idOffre = isset($_POST['idOffre']) ? $_POST['idOffre'] : '1';

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
    <title>Liste des Factures</title>
    <style>
        body {
            margin: 0 ;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
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
                    
                    setlocale(LC_TIME, 'fr_FR.UTF-8');
                    $date = strftime('%B %Y', strtotime($facture['dateprestaservices']));
                    $moisAnnee = date('m-Y', strtotime($facture['dateprestaservices']));
                    
                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td><a href='renderFacture.php?idfacture={$facture['idfacture']}&idoffre={$idOffre}&mois={$moisAnnee}' target='_blank'>Télécharger ({$moisAnnee})</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <?php Footer::render(FooterType::Pro); ?>
</body>
</html>

