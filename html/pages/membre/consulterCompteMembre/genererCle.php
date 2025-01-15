<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/connect_params.php";

session_start();

$cleApi = '';
for ($i=0; $i < 32; $i++) {
    $cleApi .= str_pad(dechex(random_int(0, 255)), 2, "0");
}
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
$stmt = $dbh->prepare("insert into pact._cleapi (idCompte, cleApi) values (?, ?) ON CONFLICT (idCompte) DO UPDATE SET cleApi = excluded.cleApi");
$stmt->execute([$_SESSION['idCompte'], $cleApi]);
?>
