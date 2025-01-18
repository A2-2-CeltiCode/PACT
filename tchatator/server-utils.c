#include "server-utils.h"

#include <iso646.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>

int parseConfig(const char *path, Config *config) {
    int exitCode = 0;

    FILE *configFile = fopen(path, "r");
    size_t bufSize = 512;
    char *lineBuf = malloc(bufSize * sizeof(char));
    int i=1;
    while (getline(&lineBuf, &bufSize, configFile) != -1) {
        switch (lineBuf[0]) {
            case '[':
                break;

            // ignore les commentaire et whitespaces
            case ';':
            case ' ':
            case '\n':
            case '\t':
                break;

            default:
                if ((lineBuf[0] >= 'a' and lineBuf[0] <= 'z') or (lineBuf[0] >= 'A' and lineBuf[0] <= 'Z')) {
                    char option[256];
                    char value[256];
                    parseLine(lineBuf, option, value);
                    if (isValidOption(option)) {
                        applyOption(config, option, value);
                    } else {
                        fprintf(stderr, "option %s non reconnue à la ligne %i", option, i);
                    }

                } else {
                    exitCode = EXIT_FAILURE;
                    fprintf(stderr,
                        "Erreur de lecture du fichier de configuration: %c trouvé ã la ligne %i",
                        lineBuf[0], i);
                }
                break;
        }
        i++;
     }

    return exitCode;
}

void parseLine(const char *line, char *option, char *value) {
    int i = 0;
    int j=0;
    while (line[i] != '=') {
        if (line[i] == ' ') {
            i++;
            continue;
        }
        option[j++] = line[i++];
    }
    option[j] = 0;
    j = 0;
    i++;
    while (line[i] != '\n' and line[i] != 0 and line[i] != ';') {
        if (line[i] == ' ') {
            i++;
            continue;
        }
        value[j++] = line[i++];
    }
    value[j] = 0;
}

bool isValidOption(const char *option) {
    const char* const validOption[] = {
        "host",
        "port",
        "user",
        "password",
        "database_name",
        "api_key",
        "max_length",
        "max_timeout",
        "max_per_minute",
        "max_per_hour",
        "path",
        0
    };

    bool isValid = false;

    int i = 0;
    while (validOption[i] != 0 and !isValid) {
        if (strcmp(validOption[i], option) == 0) {
            isValid = true;
        }
        i++;
    }
    return isValid;
}

void applyOption(Config *config, const char *option, const char *value) {
    if (strcmp(option, "host") == 0) {
        config->database.host = strdup(value);
    } else if (strcmp(option, "port") == 0) {
        config->database.port = strdup(value);
    } else if (strcmp(option, "user") == 0) {
        config->database.user = strdup(value);
    } else if (strcmp(option, "password") == 0) {
        config->database.password = strdup(value);
    } else if (strcmp(option, "database_name") == 0) {
        config->database.dbName = strdup(value);
    } else if (strcmp(option, "api_key") == 0) {
        strcpy(config->admin.apiKey, value);
    } else if (strcmp(option, "max_length") == 0) {
        char *end;
        config->message.maxLength = strtol(value, &end, 10);
    } else if (strcmp(option, "max_timeout") == 0) {
        char *end;
        config->message.maxLength = strtol(value, &end, 10);
    } else if (strcmp(option, "max_per_minute") == 0) {
        char *end;
        config->requetes.maxPerMinute = strtol(value, &end, 10);
    } else if (strcmp(option, "max_per_hour") == 0) {
        char *end;
        config->requetes.maxPerHour = strtol(value, &end, 10);
    } else if (strcmp(option, "path") == 0) {
        config->logs.path = strdup(value);
    }
}

void freeConfig(Config *config) {
    free(config->database.host);
    free(config->database.user);
    free(config->database.password);
    free(config->database.dbName);
    free(config->database.port);
    free(config->admin.apiKey);
    free(config->logs.path);
    free(config);
}
