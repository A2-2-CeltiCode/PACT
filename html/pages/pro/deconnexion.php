<?php 
session_start();
session_unset();
header("Location: ../visiteur/accueil/accueil.php");
?>