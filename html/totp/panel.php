<?php

session_start();

header('Content-type: application/json');

if (isset($_POST["id"])) {
    $_SESSION["idCompte"] = $_POST["id"];
    echo json_encode(array("idCompte" => $_SESSION["idCompte"]));
}