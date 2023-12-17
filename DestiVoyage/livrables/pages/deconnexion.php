<?php

/**
 * @file
 * Fichier PHP pour gérer la déconnexion de l'utilisateur.
 * 
 * PHP version 7.3.11
 * 
 * @category Deconnexion
 * @package  Projet_DWA
 * 
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

 // Démarrage de la session PHP
session_start();

// Supprime toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Redirection après la déconnexion
header("Location: ../index.php");
exit();
?>