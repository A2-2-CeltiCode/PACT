#!/usr/bin/php
<?php
require_once "html/connect_params.php";

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $files = [
        "./bdd/crea_PACT.sql",
        "./bdd/populateFixe_PACT.sql",
        "./bdd/populate_PACT.sql",
        "./bdd/vue_PACT.sql"
    ];

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        $queries = explode(';', $sql);
        foreach ($queries as $query) {
            if (trim($query)) {
                $dbh->exec($query);
            }
        }
    }

    echo "Base de données réinitialisée avec succès.\n";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}