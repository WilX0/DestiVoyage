<?php

/**
 * Page de modification du profil utilisateur.
 *
 * Cette page permet à l'utilisateur connecté de modifier son image de profil et
 * sa citation personnelle.
 *
 * @category Modification_Profil
 * @package  Projet_DWA
 * @version  PHP 7.3.11
 */

 // Démarrage de la session PHP
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit();
}

// Inclusion du fichier de fonctions
include '../include/fonction.php';

// Paramètres de connexion à la base de données
$host = 'mysql-destivoyage.alwaysdata.net';
$dbname = 'destivoyage_projetdwa';
$username = '333374_kenzi';
$password = 'projetdwa';

// Connexion à la base de données
$string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$con = new PDO($string, $username, $password);

// Identifiant de l'utilisateur actuel
$userId = $_SESSION["email"];

// Statut de la modification de l'image et de la citation
$uploadStatusImage = "";
$uploadStatusDescription = "";

// Vérification si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Vérification si une nouvelle image de profil a été téléchargée
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        // Vérification du type de fichier (image/jpeg, image/png, image/gif)
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($_FILES["profileImage"]["type"], $allowedTypes)) {
            // Lecture des données de l'image
        $imageData = file_get_contents($_FILES['profileImage']['tmp_name']);
        // Préparation et exécution de la requête pour mettre à jour l'image de profil
        $stmtUpdateImage = $con->prepare("UPDATE user SET photo = :photo WHERE email = :userId");
        $stmtUpdateImage->bindParam(':photo', $imageData, PDO::PARAM_LOB);
        $stmtUpdateImage->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmtUpdateImage->execute();
        $uploadStatusImage = "Modification de l'image effectuée avec succès.";
        
        }
        else{
            $uploadStatusImage = "Le fichier téléchargé n'est pas une image valide (l'image doit être au format png, jpeg ou gif).";
        }

    }else{
        $uploadStatusImage = "Aucun fichier image n'a été téléchargé.";
    }

 // Vérification si une nouvelle citation a été soumise
    if (isset($_POST['citation'])) {
        $newCitation = $_POST['citation'];
         // Préparation et exécution de la requête pour mettre à jour la citation
        $stmtUpdateCitation = $con->prepare("UPDATE user SET citation = :citation WHERE email = :userId");
        $stmtUpdateCitation->bindParam(':citation', $newCitation, PDO::PARAM_STR);
        $stmtUpdateCitation->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmtUpdateCitation->execute();
        $uploadStatusDescription = "Modification de la description effectuée avec succès.";
        // $uploadStatus = "Modification faite avec succès.";
    }

    // Construction des paramètres de requête pour la redirection avec les statuts de modification
    $queryParams = http_build_query([
        'uploadStatusImage' => $uploadStatusImage,
        'uploadStatusDescription' => $uploadStatusDescription,
    ]);
    // Redirection vers la page de profil avec les paramètres de requête
    header("Location: hello.php?" . $queryParams);
    exit();

}
?>
