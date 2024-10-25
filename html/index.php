<?php
session_start();
is_null($_SESSION['idCompte']) ? header("Location: /pages/visiteur/accueil/accueil.php") : header("Location: /pages/pro/accueil/accueil.php");
exit;