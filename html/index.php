<?php
session_start();
array_key_exists('idComtpe',
    $_SESSION) && !is_null($_SESSION['idCompte']) ? header("Location: /pages/pro/accueil/accueil.php") : header("Location: /pages/visiteur/accueil/accueil.php");
exit;