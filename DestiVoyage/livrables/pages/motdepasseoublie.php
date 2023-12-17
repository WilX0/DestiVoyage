<?php

/**
 * @file
 * Réinitialisation du mot de passe - Modification du mot de passe
 *
 * Version PHP 7.3.11
 *
 * @category Gestion_Utilisateur
 * @package  Projet_DWA
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

 // Démarrage de la session et inclusion du fichier de fonctions
session_start();
include_once("../include/fonction.php");

// Vérification si le code de confirmation est présent dans l'URL
if (isset($_GET['code'])) {
    // Récupération du code de confirmation
    $confirmationCode = $_GET['code'];
     // Requête pour récupérer les informations utilisateur en fonction du code
    $query = "SELECT * FROM user WHERE code_mdpo = :codee";
    $arr['codee'] = $confirmationCode;
    $row = database_run($query, $arr);

    // Vérification si des informations sont récupérées
    if (!empty($row)) {
        // Paramètres de connexion à la base de données
        $host = 'mysql-destivoyage.alwaysdata.net';
        $dbname = 'destivoyage_projetdwa';
        $username = '333374_kenzi';
        $password = 'projetdwa';
        $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        // Connexion à la base de données
        $con = new PDO($string, $username, $password);
        // echo $row[0]->email;

         // Vérification de la méthode de requête (POST)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
             // Récupération des nouveaux mots de passe
            $newPassword = $_POST['pass'];
            $confirmPassword = $_POST['confpass'];

             // Vérification de la correspondance des mots de passe
          if($newPassword===$confirmPassword){     
         try {
            // Hashage du nouveau mot de passe
            $hashedPassword = hash('sha256', $newPassword);

            // Requête pour mettre à jour le mot de passe dans la base de données
            $updateQuery = "UPDATE user SET password = :password1 WHERE email = :user_id";
            $stmt = $con->prepare($updateQuery);
            $stmt->bindParam(':user_id', $row[0]->email);
            $stmt->bindParam(':password1', $hashedPassword);

            // Exécution de la requête
            if ($stmt->execute()) {
                 // Requête pour mettre à jour le code_mdpo à NULL
                $updateCodeQuery = "UPDATE user SET code_mdpo = NULL WHERE email = :user_id";
                $stmtCode = $con->prepare($updateCodeQuery);
                $stmtCode->bindParam(':user_id', $row[0]->email);

                // Exécution de la requête de mise à jour du code_mdpo
                if ($stmtCode->execute()) {
                    // echo "Le mot de passe a été mis à jour et le code_mdpo a été mis à NULL avec succès.";
                    $_SESSION['activation_message'] = "Une erreur est survenue lors de l'activation de votre compte.";
                    header("Location: connexion.php");
                    exit();
                } else {
                    echo "Une erreur est survenue lors de la mise à jour du code_mdpo.";
                    var_dump($stmtCode->errorInfo()); // Affiche les informations d'erreur
                }
            } else {
                $_SESSION['activation_message'] = "Une erreur est survenue.";
                echo "Une erreur est survenue .";
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        }
    }
}
} else {
    echo "Le lien de confirmation a expiré ou est invalide.";
    exit();
}

} else {
    echo "Code de confirmation manquant. Veuillez vérifier le lien ou demander un nouveau code de confirmation.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Style/motdepasseoublie.css">
    <link rel="stylesheet" href="../Style/stylenav.css">
    <link rel="stylesheet" href="../Style/stylefooter.css">

    <title>MOT DE PASSE OUBLIE ? </title>

</head>

<body>

<?php include('../include/navnotlogin.php')?>
        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <div class="centered-div">

                        <div class="centered-div-image">

                            <img src="../images/ForgotPass (1).png" alt="mot-de-passe">

                        </div>

                        <h1>rénitialiser votre mot de passe</h1>

                       

                        <form id="form" method="POST">

                            <div class="mb-3 input-with-icon">

                                <label for="oldpass" class="label-left"> Nouveau mot de passe</label>

                                <div class="input-container">

                                    <!-- <svg width="17" height="26" viewBox="0 0 17 26" fill="none" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M8.96536 19.8023C8.43606 19.8023 7.92844 19.5509 7.55417 19.1033C7.17989 18.6557 6.96963 18.0486 6.96963 17.4156C6.96963 16.0909 7.85773 15.0288 8.96536 15.0288C9.49467 15.0288 10.0023 15.2802 10.3766 15.7278C10.7508 16.1754 10.9611 16.7825 10.9611 17.4156C10.9611 18.0486 10.7508 18.6557 10.3766 19.1033C10.0023 19.5509 9.49467 19.8023 8.96536 19.8023ZM14.9526 23.3825V11.4486H2.97816V23.3825H14.9526ZM14.9526 9.06178C15.4819 9.06178 15.9895 9.31325 16.3638 9.76086C16.738 10.2085 16.9483 10.8156 16.9483 11.4486V23.3825C16.9483 24.0156 16.738 24.6226 16.3638 25.0703C15.9895 25.5179 15.4819 25.7693 14.9526 25.7693H2.97816C2.44886 25.7693 1.94123 25.5179 1.56696 25.0703C1.19269 24.6226 0.982422 24.0156 0.982422 23.3825V11.4486C0.982422 10.1239 1.87052 9.06178 2.97816 9.06178H3.97603V6.67499C3.97603 5.09245 4.50169 3.57472 5.43737 2.4557C6.37305 1.33667 7.64211 0.708008 8.96536 0.708008C9.62057 0.708008 10.2694 0.862348 10.8747 1.16222C11.48 1.46209 12.0301 1.90161 12.4934 2.4557C12.9567 3.00978 13.3242 3.66758 13.5749 4.39152C13.8257 5.11547 13.9547 5.89139 13.9547 6.67499V9.06178H14.9526ZM8.96536 3.0948C8.17141 3.0948 7.40998 3.472 6.84857 4.14341C6.28716 4.81483 5.97176 5.72546 5.97176 6.67499V9.06178H11.959V6.67499C11.959 5.72546 11.6436 4.81483 11.0822 4.14341C10.5208 3.472 9.75932 3.0948 8.96536 3.0948Z" fill="black" fill-opacity="0.33"/>

                                    </svg> -->

                                    <input id="password" type="password" class="form-control" name="pass" placeholder="Mot de passe">

                                </div>

                            </div>

                            <div class="mb-3 input-with-icon">

                                <label for="newpasse" class="label-left">confirmation du nouveau mot de passe</label>

                                <div class="input-container">

                                    <!-- <svg width="17" height="26" viewBox="0 0 17 26" fill="none" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M8.96536 19.8023C8.43606 19.8023 7.92844 19.5509 7.55417 19.1033C7.17989 18.6557 6.96963 18.0486 6.96963 17.4156C6.96963 16.0909 7.85773 15.0288 8.96536 15.0288C9.49467 15.0288 10.0023 15.2802 10.3766 15.7278C10.7508 16.1754 10.9611 16.7825 10.9611 17.4156C10.9611 18.0486 10.7508 18.6557 10.3766 19.1033C10.0023 19.5509 9.49467 19.8023 8.96536 19.8023ZM14.9526 23.3825V11.4486H2.97816V23.3825H14.9526ZM14.9526 9.06178C15.4819 9.06178 15.9895 9.31325 16.3638 9.76086C16.738 10.2085 16.9483 10.8156 16.9483 11.4486V23.3825C16.9483 24.0156 16.738 24.6226 16.3638 25.0703C15.9895 25.5179 15.4819 25.7693 14.9526 25.7693H2.97816C2.44886 25.7693 1.94123 25.5179 1.56696 25.0703C1.19269 24.6226 0.982422 24.0156 0.982422 23.3825V11.4486C0.982422 10.1239 1.87052 9.06178 2.97816 9.06178H3.97603V6.67499C3.97603 5.09245 4.50169 3.57472 5.43737 2.4557C6.37305 1.33667 7.64211 0.708008 8.96536 0.708008C9.62057 0.708008 10.2694 0.862348 10.8747 1.16222C11.48 1.46209 12.0301 1.90161 12.4934 2.4557C12.9567 3.00978 13.3242 3.66758 13.5749 4.39152C13.8257 5.11547 13.9547 5.89139 13.9547 6.67499V9.06178H14.9526ZM8.96536 3.0948C8.17141 3.0948 7.40998 3.472 6.84857 4.14341C6.28716 4.81483 5.97176 5.72546 5.97176 6.67499V9.06178H11.959V6.67499C11.959 5.72546 11.6436 4.81483 11.0822 4.14341C10.5208 3.472 9.75932 3.0948 8.96536 3.0948Z" fill="black" fill-opacity="0.33"/>

                                    </svg> -->

                                    <input id="passwordconfirm" type="password" class="form-control" name="confpass" placeholder="Mot de passe">

                                </div>

                            </div>

                            <div id="errormdp"><p id="errormdp3"></p></div>

                            <input type="submit" id="suivant" value="Valider">

                        </form>

                    </div>

                </div>

            </div>

        </div>

   <?php include('../include/lefooter.php')?>
    <script src="../scripts/controle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>