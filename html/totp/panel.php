<?php

session_start();

header('Content-type: application/json');

if (isset($_POST["id"])) {
    $_SESSION["idCompte"] = $_POST["id"];
    echo json_encode(array("idCompte" => $_SESSION["idCompte"]));
}

if (isset($_POST["totpid"])) {
    $_SESSION["totpid"] = $_POST["totpid"];
    echo json_encode(array("totpid" => $_SESSION["totpid"]));
}