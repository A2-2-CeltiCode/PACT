#include <stdio.h>
#include <stdlib.h>
#include <libpq-fe.h>

int main(int argc, char **argv) {
    PGconn *dbh = PQsetdbLogin("localhost", "5432", "", "", "sae", "postgres", "postgres");
    PGresult *result = PQexec(dbh, "SELECT * FROM pact._compte");
    if (PQresultStatus(result) != PGRES_TUPLES_OK) {
        printf("%s", PQerrorMessage(dbh));
        PQfinish(dbh);
        return EXIT_FAILURE;
    }
    for (int i = 0; i < PQntuples(result); i++) {
        for (int j = 0; j < PQnfields(result); j++) {
            printf("%s ", PQgetvalue(result, i, j));
        }
        printf("\n");
    }
    PQclear(result);
    PQfinish(dbh);
    return EXIT_SUCCESS;
}
