#ifndef SERVER_UTILS_H
#define SERVER_UTILS_H

#define API_KEY_LENGTH 64
#define CONNECT_COMMAND "LOGIN:"
#define SEND_COMMAND "SEND:"
#define GETUNREAD_COMMAND "GETUNREAD:"
#define GETHISTORY_COMMAND "GETHISTORY:"
#include <stdbool.h>

static char defaultConf[] = R"(; Tous les paramètres de configuration sont stockés ici.


[DATABASE]
host =   ; Adresse du serveur de base de données
port =   ; Port de la base de données
user =   ; Nom d'utilisateur pour la BDD
password =   ; Mot de passe pour la BDD
database_name =   ; Nom de la base de données utilisée

[CLIENT]
api_key =  ; La clé d'API

[MESSAGES]
max_length = 1000 ; La taille maximale de messages

[CONVERSATION]
block_time = 24 ; Le temps maximum d'un blocage de conversation
msg_block_size = 20 ; La taille max des block de message envoyé lors de la récéption d'un historique

[REQUESTS]
max_per_minute = 12 ; Le nombre maximum de requêtes par minute
max_per_hour = 90 ; Le nombre maximum de requêtes par heure

[LOGS]
path = logs/ ; Le chemin du dossier de logs

[SERVER]
listening_port = 8080)";

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
    long msgBlockSize;
} Conversation;

typedef struct {
    long maxPerMinute;
    long maxPerHour;
} Requetes;

typedef struct {
    char *path;
} Logs;

typedef struct {
    long port;
} Server;

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
    Server server;
} Config;

int parseConfig(const char *path, Config *config);

void parseLine(const char *line, char *option, char *value);

bool isValidOption(const char *option);

void applyOption(Config *config, const char *option, const char *value);

void freeConfig(const Config *config);

void writeLog(const char *logPath, const char *log);

void fwriteLog(const char *logPath, const char *flog, const int size, ...);

void createConf();

char *getIp(int sockFd);

#endif //SERVER_UTILS_H
