<?php
require_once 'connect_params.php';
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    foreach ($dbh->query('SELECT * from pact._offre', PDO::FETCH_ASSOC) as $row) {
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}
