#include <stdio.h>
#include <stdlib.h>
#include "server-utils.h"

int main(int argc, char **argv) {
  Config config;
  parseConfig("./config.ini", &config);
   return EXIT_SUCCESS;
}