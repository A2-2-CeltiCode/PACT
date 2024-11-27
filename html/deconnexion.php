<?php
session_start();
session_unset();
header("Location: pages/visiteur/accueil/accueil.php");