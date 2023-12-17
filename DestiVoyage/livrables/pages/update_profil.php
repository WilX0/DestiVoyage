<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit();
}

include '../include/fonction.php';

$host = 'mysql-destivoyage.alwaysdata.net';
$dbname = 'destivoyage_projetdwa';
$username = '333374_kenzi';
$password = 'projetdwa';

$string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$con = new PDO($string, $username, $password);

$userId = $_SESSION["email"];

$uploadStatusImage = "";
$uploadStatusDescription = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($_FILES["profileImage"]["type"], $allowedTypes)) {
        $imageData = file_get_contents($_FILES['profileImage']['tmp_name']);
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

 
    if (isset($_POST['citation'])) {
        $newCitation = $_POST['citation'];
        $stmtUpdateCitation = $con->prepare("UPDATE user SET citation = :citation WHERE email = :userId");
        $stmtUpdateCitation->bindParam(':citation', $newCitation, PDO::PARAM_STR);
        $stmtUpdateCitation->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmtUpdateCitation->execute();
        $uploadStatusDescription = "Modification de la description effectuée avec succès.";
        // $uploadStatus = "Modification faite avec succès.";
    }
    $queryParams = http_build_query([
        'uploadStatusImage' => $uploadStatusImage,
        'uploadStatusDescription' => $uploadStatusDescription,
    ]);
    header("Location: hello.php?" . $queryParams);
    exit();

}
?>
