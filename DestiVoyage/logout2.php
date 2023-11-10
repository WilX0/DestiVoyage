<?php
session_start(); // Démarrez la session

// Détruisez toutes les données de la session
session_unset();
session_destroy();

// Redirigez l'utilisateur vers la page de connexion ou toute autre page de votre choix
header("Location: login.php");
exit;
?>
