<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$sql = "SELECT * FROM pact._facture WHERE idoffre = :idCompte";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
    <style>
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
    <?php Header::render(HeaderType::Member); ?>
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
                    $date = date('F Y', strtotime($facture['date']));
                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td><a href='path/to/factures/{$facture['file']}' download>Télécharger</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <?php Footer::render(FooterType::Member); ?>
</body>
</html>