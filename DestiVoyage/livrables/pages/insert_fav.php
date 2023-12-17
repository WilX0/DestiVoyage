<?php

/**
 * @file
 * Ajout de vol aux favoris
 *
 * Version PHP 7.3.11
 *
 * @category Insertion_Favoris
 * @package  Projet_DWA
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */


// Démarrage de la session pour maintenir l'état de connexion de l'utilisateur
session_start();

// Inclusion du fichier de fonctions
include_once("../include/fonction.php");

// Vérification de la méthode de requête (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    // Récupération de l'identifiant de l'utilisateur depuis la session
    $user_id = $_SESSION["email"]; 

    // Récupération des données du vol depuis les paramètres GET
    $vol_id = $_GET['vol_id'];
    $depart = $_GET['depart'];
    $arrivee = $_GET['arrivee'];
    $duree = $_GET['duree'];
    $prix = $_GET['prix'];
    $type_vol = $_GET['type_vol'];
    $class = $_GET['classe'];

    $date_depart = $_GET['date_depart'];
    $date_arrivee = $_GET['date_arrivee'];

    // Requête SQL pour vérifier si le vol est déjà dans les favoris de l'utilisateur
 $query_check = "SELECT * FROM favvol WHERE num_vols = ? AND user_id = ?";
$vars_check = [$vol_id, $user_id];
$result_check = database_run($query_check, $vars_check);
if($result_check){
     // Le vol est déjà dans les favoris
    $response = "Ce vol est déjà ajouté à vos favoris.";
    // echo"<script>alert(".$response.");</script>";
    echo $response;
    // header('Location: Book1.php?alert=' . urlencode($response));
    exit();

}else{
// Le vol n'est pas encore dans les favoris, insertion dans la base de données
    $query = "INSERT INTO favvol (num_vols, depart, arrive, datedep, datearr, duree, prix, classe, type_vol,user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
    $vars = [$vol_id, $depart, $arrivee, $date_depart, $date_arrivee, $duree, $prix, $class, $type_vol, $user_id];

    $result = database_run($query, $vars);

    if (!$result) {
        // Succès de l'ajout du vol aux favoris
        $response = "Vol ajouté aux favoris avec succès.";
        echo $response;
        // header('Location: Book1.php?alert=' . urlencode($response));
    } else {
        // Erreur lors de l'ajout du vol aux favoris
        $response = "Erreur lors de l'ajout du vol aux favoris.";
        echo $response;
        // header('Location: Book1.php?alert=' . urlencode($response));
    }
}
}
?>