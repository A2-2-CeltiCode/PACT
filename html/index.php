<?php
session_start();
isset($_SESSION['idCompte']) ? header("Location: /pages/pro/listeOffres/listeOffres.php") : header("Location: /pages/visiteur/accueil/accueil.php");
exit;