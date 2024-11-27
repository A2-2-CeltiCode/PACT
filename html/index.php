<?php
session_start();
isset($_SESSION['idCompte']) ? header("Location: /pages/pro/accueil/accueil.php") : header("Location: /pages/visiteur/accueil/accueil.php");
exit;