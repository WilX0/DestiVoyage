<?php

/**
 * @file
 * Fichier PHP pour récupérer les adresses e-mail des utilisateurs depuis la base de données.
 * PHP version 7.3.11
 * 
 * @category Gestion_Utilisateur
 * @package  Projet_DWA
 * 
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

// Incluez la configuration de votre base de données
try{
     // Informations de connexion à la base de données
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';

     // Chaîne de connexion PDO
    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    // Création de l'objet PDO
    $con = new PDO($string,$username,$password);

    // Prépare la requête SQL pour récupérer les adresses e-mail des utilisateurs
    $query = $con->prepare('select email from user');

     // Exécute la requête
    $query->execute();

    // Récupère toutes les adresses e-mail sous forme de tableau associatif
    $mails = $query->fetchAll(PDO::FETCH_ASSOC);
    // echo $result;    

    // Définit le type de contenu comme application/json
    header("Content-Type: application/json");

      // Affiche le résultat au format JSON
    echo json_encode($mails);
    
}catch(PDOException $e){
    // En cas d'erreur PDO, affiche le message d'erreur
    echo ''. $e->getMessage();
}

?>
