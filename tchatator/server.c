#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <getopt.h>
#include <string.h>
#include <libpq-fe.h>

#include "server-utils.h"

int main(int argc, char **argv) {
  int verbose = false;
  int help = false;
  int generateConf = false;
  char *configPath = strdup("./config.ini");

  struct option longOptions[] = {
    {"verbose", no_argument, &verbose, 1},
    {"help", no_argument, &help, 1},
    {"config", required_argument, NULL, 'c'},
    {"generate-config", no_argument, &generateConf, 1},
    {0, 0, 0,0 }
  };

  int longIndex = 0;
  int c;

  while ((c = getopt_long(argc, argv, "vhc:", longOptions, &longIndex)) != -1) {
    switch (c) {
      case 0:
        break;
      case 'v':
        verbose = true;
        break;
      case 'h':
        help = true;
        break;
      case 'c':
        free(configPath);
        configPath = malloc((strlen(optarg) + 11) * sizeof(char));
        sprintf(configPath,"%s%sconfig.ini",optarg, optarg[strlen(optarg) - 1] == '/'?"":"/");
        break;
      case '?':
        break;
      default:
        return EXIT_FAILURE;
    }
  }

  printf("server started with options : verbose = %i, help = %i, conf = %s\n",verbose,help,configPath);
  if (generateConf) {
    createConf();
    printf("config file created\n");
    return EXIT_SUCCESS;
  }
  if (help) {
    printf("Usage: server [options]\n");
    printf("Options:\n");
    printf("  --help -h       Show this message\n");
    printf("  --verbose -v    Print detailed log to the stdout\n");
    printf("  --config -c     Specify custom config path (default is in the current folder)\n");
    printf("  --generate-conf Generate default configuration file in the current folder (some field needs to be filled)\n");
    return EXIT_SUCCESS;
  }
  Config config;
  if (parseConfig(configPath, &config) == EXIT_FAILURE) {
    free(configPath);
    return EXIT_FAILURE;
  }
  free(configPath);
  if (verbose) printf("config file loaded\n");
  writeLog(config.logs.path, "Logging started");

  PGconn *conn = PQsetdbLogin(
    config.database.host,
    config.database.port,
    "",
    "",
    config.database.dbName,
    config.database.user,
    config.database.password
    );
  if (PQstatus(conn) != CONNECTION_OK) {
    if (verbose) printf("failed to connect to database\n");
    writeLog(config.logs.path, "failed to connect to database");
    freeConfig(&config);
    return EXIT_FAILURE;
  }

  if (verbose) printf("connected to database successfully\n");
  writeLog(config.logs.path, "connected to database successfully");

  int sock, ret, size, cnx;
  struct sockaddr_in addr;
  struct sockaddr_in conn_addr;
  size = sizeof(conn_addr);

  sock = socket(AF_INET, SOCK_STREAM, 0);
  addr.sin_addr.s_addr = inet_addr("127.0.0.1");
  addr.sin_family = AF_INET;
  addr.sin_port = htons(config.server.port);
  ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));
  ret = listen(sock, 1);
  if (verbose) printf("server is listening on port %li\n", config.server.port);
  fwriteLog(config.logs.path, "server is listening on port %li", 1, config.server.port);

  while (1) {
    cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
    char *clientIp = getIp(cnx);
    if (verbose) printf("client connected with ip %s\n", clientIp);
    fwriteLog(config.logs.path,"client connected with ip %s", 1, clientIp);
    char msg[1024];
    read(cnx, msg, 1024);
    if (strncmp(msg, CONNECT_COMMAND, 6) == 0) {

    } else if ()
    close(cnx);
  }

  if (verbose) printf("server is shutting down\n");
  writeLog(config.logs.path, "Closing, cleaning up");
  close(sock);
  close(cnx);
  freeConfig(&config);
  return EXIT_SUCCESS;
}
