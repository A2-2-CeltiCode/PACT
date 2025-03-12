#!/bin/bash

# filepath: /home/raphael/Documents/PACT/run_sql_scripts.sh

# Définir les variables

DB_NAME="pact"
DB_USER="postgres"
DB_HOST="localhost"
DB_PORT="5432"
DB_PASSWORD="derfDERF29"

# Exécuter les scripts SQL
export PGPASSWORD=$DB_PASSWORD
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /home/raphael/Documents/PACT/bdd/crea_PACT.sql
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /home/raphael/Documents/PACT/bdd/populateFixe_PACT.sql
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /home/raphael/Documents/PACT/bdd/populate_PACT.sql
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /home/raphael/Documents/PACT/bdd/vue_PACT.sql
unset PGPASSWORD