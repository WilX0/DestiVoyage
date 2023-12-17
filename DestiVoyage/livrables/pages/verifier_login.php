<?php

/**
 * Script PHP pour récupérer la liste des logins d'utilisateurs depuis la base de données.
 *
 * Ce script se connecte à la base de données, récupère les logins des utilisateurs
 * et les renvoie sous forme d'un tableau associatif JSON.
 *
 * @category Script_Utilisateurs
 * @package  Projet_DWA
 * @version  PHP 7.3.11
 */

// Incluez la configuration de votre base de données
try{
     // Configuration de la base de données
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';

    // Connexion à la base de données
    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($string,$username,$password);

    // Préparation et exécution de la requête SQL pour récupérer les logins
    $query = $con->prepare('select login from user');
    $query->execute();

     // Récupération des logins sous la forme d'un tableau associatif
    $logins = $query->fetchAll(PDO::FETCH_ASSOC);
    // echo $result;    

    // Définition de l'en-tête HTTP pour indiquer que le contenu est au format JSON
    header("Content-Type: application/json");

    // Encodage et affichage des données JSON
    echo json_encode($logins);
    
}catch(PDOException $e){
    // En cas d'erreur PDO, affichage du message d'erreur
    echo ''. $e->getMessage();
}

?>
