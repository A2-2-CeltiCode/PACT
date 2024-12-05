<?php
session_start();
isset($_SESSION['idCompte']) && $_SESSION['typeUtilisateur'] == "pro" ? header("Location: /pages/pro/listeOffres/listeOffres.php") : header("Location: /pages/visiteur/accueil/accueil.php");
exit;