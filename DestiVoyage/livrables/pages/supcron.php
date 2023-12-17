<?php

/**
 * Script pour supprimer les utilisateurs non vérifiés inscrits il y a plus de 12 heures.
 *
 * Ce script se connecte à la base de données et supprime les utilisateurs dont
 * l'adresse e-mail n'a pas été vérifiée et qui se sont inscrits il y a plus de
 * 12 heures.
 *
 * @category Suppression_Utilisateurs
 * @package  Projet_DWA
 * @version  PHP 7.3.11
 */

 // Inclusion du fichier de fonctions
require "fonction.php";

// Paramètres de connexion à la base de données
$host = 'mysql-destivoyage.alwaysdata.net';
$dbname = 'destivoyage_projetdwa';
$username = '333374_kenzi';
$password = 'projetdwa';

// Connexion à la base de données
$string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$con = new PDO($string,$username,$password);
// $query = $con->prepare('select login from user');
// $query->execute();

// Calcul de la date d'il y a 12 heures
$currentDateTime = new DateTime();


$dateTime24HoursAgo = $currentDateTime->sub(new DateInterval('PT12H'));


$date24HoursAgo = $dateTime24HoursAgo->format('Y-m-d H:i:s');

// Requête de suppression des utilisateurs non vérifiés inscrits il y a plus de 12 heures
$query = "DELETE FROM user WHERE email_verified = 0 AND date_inscription < :date24HoursAgo";

// Exécution de la requête en utilisant la fonction personnalisée database_run
database_run($query, array('date24HoursAgo' => $date24HoursAgo));
?>
