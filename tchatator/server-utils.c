#include "server-utils.h"

#include <iso646.h>
#include <stdarg.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <sys/stat.h>

int parseConfig(const char *path, Config *config) {
    int exitCode = 0;

    if (access(path, F_OK) != 0) {
        fprintf(stderr, "config file : %s does not exist\n", path);
        return EXIT_FAILURE;
    }
    if (access(path, R_OK) != 0) {
        fprintf(stderr, "config file : %s is not readable\n", path);
        return EXIT_FAILURE;
    }

    FILE *configFile = fopen(path, "r");
    size_t bufSize = 512;
    char *lineBuf = malloc(bufSize * sizeof(char));
    int i=1;
    while (getline(&lineBuf, &bufSize, configFile) != -1) {
        switch (lineBuf[0]) {
            // ignore les commentaire et whitespaces
            case '[':
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
    fclose(configFile);
    free(lineBuf);
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
        "block_time",
        "max_per_minute",
        "max_per_hour",
        "path",
        "listening_port",
        "msg_block_size",
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
    } else if (strcmp(option, "block_time") == 0) {
        char *end;
        config->conversation.blockTime = strtol(value, &end, 10);
    } else if (strcmp(option, "max_per_minute") == 0) {
        char *end;
        config->requetes.maxPerMinute = strtol(value, &end, 10);
    } else if (strcmp(option, "max_per_hour") == 0) {
        char *end;
        config->requetes.maxPerHour = strtol(value, &end, 10);
    } else if (strcmp(option, "path") == 0) {
        config->logs.path = strdup(value);
        struct stat st = {0};
        if (stat(config->logs.path, &st) == -1) {
            mkdir("logs", 770);
        }
    } else if (strcmp(option, "listening_port") == 0) {
        char *end;
        config->server.port = strtol(value, &end, 10);
    } else if (strcmp(option, "msg_port_size") == 0) {
        char *end;
        config->conversation.msgBlockSize = strtol(value, &end, 10);
    }
}

void freeConfig(const Config *config) {
    free(config->database.host);
    free(config->database.user);
    free(config->database.password);
    free(config->database.dbName);
    free(config->database.port);
    free(config->logs.path);
}

void writeLog(const char *logPath, const char *log) {
    char *configPath = malloc((13 + strlen(logPath)) * sizeof(char));
    sprintf(configPath, "%sserveur.logs", logPath);
    FILE *logsFile = fopen(configPath, "a");
    free(configPath);
    const time_t t = time(NULL);
    const struct tm tm = *localtime(&t);
    fprintf(logsFile, "[%d/%d/%d %d:%d:%d] %s\n", tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, tm.tm_hour, tm.tm_min, tm.tm_sec, log);
    fclose(logsFile);
}

void fwriteLog(const char *logPath, const char *flog, const int size, ...) {
    va_list args;
    va_start(args, size);
    char buffer[1024];
    vsnprintf(buffer, 1024, flog, args);
    writeLog(logPath, buffer);
}

void createConf() {
    if (access("config.ini", F_OK) == 0) {
        fprintf(stderr, "config file already exist\n");
    } else {
        FILE *config = fopen("config.ini", "w");
        fprintf(config, defaultConf);
        fclose(config);
    }
}

char * getIp(const int sockFd) {
    struct sockaddr_in addr;
    socklen_t addr_size = sizeof(struct sockaddr_in);
    getpeername(sockFd, (struct sockaddr *)&addr, &addr_size);
    char *clientip = strdup(inet_ntoa(addr.sin_addr));
    return clientip;
}

