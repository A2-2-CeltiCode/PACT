<?php

$host = 'localhost';
$port = '5432';
$dbname = 'pact';
$user = 'postgres';
$password = 'derfDERF29';

// Connexion à la base de données
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

// Fonction pour exécuter un fichier SQL
function execute_sql_file($conn, $file_path) {
    $query = file_get_contents($file_path);
    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Erreur lors de l'exécution du fichier $file_path: " . pg_last_error($conn) . "\n";
    } else {
        echo "Fichier $file_path exécuté avec succès.\n";
    }
}

// Exécuter les scripts SQL
execute_sql_file($conn, '../bdd/crea_PACT.sql');
execute_sql_file($conn, '../bdd/populateFixe_PACT.sql');
execute_sql_file($conn, '../bdd/populate_PACT.sql');
execute_sql_file($conn, '../bdd/vue_PACT.sql');

// Fermer la connexion
pg_close($conn);