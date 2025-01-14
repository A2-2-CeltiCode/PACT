#ifndef SERVER_UTILS_H
#define SERVER_UTILS_H

#define API_KEY_LENGTH 64
#include <stdbool.h>

typedef struct {
    char *host;
    char *port;
    char *user;
    char *password;
    char *dbName;
} Database;

typedef struct {
    long maxLength;
} Message;

typedef struct {
    long blockTime;
} Conversation;

typedef struct {
    long maxPerMinute;
    long maxPerHour;
} Requetes;

typedef struct {
    char *path;
} Logs;

typedef struct {
    char apiKey[API_KEY_LENGTH];
} Admin;

typedef struct {
    Database database;
    Message message;
    Conversation conversation;
    Requetes requetes;
    Logs logs;
    Admin admin;
} Config;

int parseConfig(const char *path, Config *config);

void parseLine(const char *line, char *option, char *value);

bool isValidOption(const char *option);

void applyOption(Config *config, const char *option, const char *value);

void freeConfig(Config *config);

#endif //SERVER_UTILS_H
