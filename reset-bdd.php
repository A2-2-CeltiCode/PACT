#!/usr/bin/php
<?php
$dbh = new PDO("pgsql:host=postgresdb;dbname=sae", 'postgres', 'linc-keRRy-gor1lles');
$dbh->query(file_get_contents("./bdd/crea_PACT.sql"));
$dbh->query(file_get_contents("./bdd/populateFIXE_PACT.sql"));
$dbh->query(file_get_contents("./bdd/populate_PACT.sql"));
$dbh->query(file_get_contents("./bdd/vue_PACT.sql"));
