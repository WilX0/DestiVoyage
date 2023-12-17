<?php

session_start();
include_once("fonction.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $user_id = $_SESSION["email"]; 

    $vol_id = $_GET['vol_id'];
    $depart = $_GET['depart'];
    $arrivee = $_GET['arrivee'];
    $duree = $_GET['duree'];
    $prix = $_GET['prix'];
    $type_vol = $_GET['type_vol'];
    $class = $_GET['classe'];

    $date_depart = $_GET['date_depart'];
    $date_arrivee = $_GET['date_arrivee'];

 $query_check = "SELECT * FROM favvol WHERE num_vols = ? AND user_id = ?";
$vars_check = [$vol_id, $user_id];
$result_check = database_run($query_check, $vars_check);
if($result_check){
    $response = "Ce vol est déjà ajouté à vos favoris.";
    // echo"<script>alert(".$response.");</script>";
    echo $response;
    // header('Location: Book1.php?alert=' . urlencode($response));
    exit();

}else{

    $query = "INSERT INTO favvol (num_vols, depart, arrive, datedep, datearr, duree, prix, classe, type_vol,user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
    $vars = [$vol_id, $depart, $arrivee, $date_depart, $date_arrivee, $duree, $prix, $class, $type_vol, $user_id];

    $result = database_run($query, $vars);

    if (!$result) {
        $response = "Vol ajouté aux favoris avec succès.";
        echo $response;
        // header('Location: Book1.php?alert=' . urlencode($response));
    } else {
        $response = "Erreur lors de l'ajout du vol aux favoris.";
        echo $response;
        // header('Location: Book1.php?alert=' . urlencode($response));
    }
}
}
?>