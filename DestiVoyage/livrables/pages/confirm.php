<?php
session_start();
include_once("../include/fonction.php");

if (isset($_GET['code'])) {
    $confirmationCode = $_GET['code'];

    // Recherchez l'utilisateur avec le code de confirmation dans la base de données
    $query = "SELECT * FROM user WHERE code_confirmation = :codee";
    $arr['codee'] = $confirmationCode;
    $row = database_run($query, $arr);

    if (!empty($row)) {
        $host = 'mysql-destivoyage.alwaysdata.net';
        $dbname = 'destivoyage_projetdwa';
        $username = '333374_kenzi';
        $password = 'projetdwa';
        $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $con = new PDO($string, $username, $password);

        try {
            $updateQuery = "UPDATE user SET email_verified = 1 WHERE email = :user_id";
            $stmt = $con->prepare($updateQuery);
            $stmt->bindParam(':user_id', $row[0]->email);

            if ($stmt->execute()) {
                $_SESSION['activation_message'] = "Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.";
                echo "<script>Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.</script>";
                include_once("redirect.php");
            } else {
                $_SESSION['activation_message'] = "Une erreur est survenue lors de l'activation de votre compte.";
                echo "Une erreur est survenue lors de l'activation de votre compte.";
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        }
    } else {
        echo "Le lien de confirmation a expiré ou est invalide.";
    }
} else {
    echo "Code de confirmation manquant. Veuillez vérifier le lien ou demander un nouveau code de confirmation.";
}
?>