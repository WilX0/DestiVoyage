<?php

/**
 * @file
 * Réinitialisation du mot de passe - Recherche de compte
 *
 * Version PHP 7.3.11
 *
 * @category Gestion_Utilisateur
 * @package  Projet_DWA
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

 // Inclusion du fichier de fonctions
require "../include/fonction.php";

// Inclusion des classes PHPMailer nécessaires
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\Exception\SMTPException;

// Inclusion des fichiers PHPMailer
require_once "../phpmailer/Exception.php";
require_once "../phpmailer/PHPMailer.php";
require_once "../phpmailer/SMTP.php";

// Initialisation de l'objet PHPMailer
$mail = new PHPMailer(true);

// Tableau d'erreurs
$errors = array();

// Vérification de la méthode de requête (POST)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
     // Vérification si l'e-mail existe dans la base de données
    $email = $_POST['rechercher'];
    $query = "SELECT * FROM user WHERE email = :email";
    $vars['email'] = $email;

    $user = database_run($query, $vars); // Implement a function to retrieve user by email from your database

    if (is_array($user) && count($user) > 0) {
         // Génération d'un code aléatoire pour la réinitialisation du mot de passe
        $code = rand(10000, 99999);

        // Mise à jour du code dans la base de données
        $update_query = "UPDATE user SET code_mdpo = :code WHERE email = :email";
        $update_vars = array('code' => $code, 'email' => $email);
        database_run($update_query, $update_vars);
       
        try {
             // Configuration de PHPMailer pour l'envoi d'un e-mail de réinitialisation
             $mail->isSMTP();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPDebug = 2;
            $mail->Username = "kenzimebarki2@gmail.com";
            $mail->Password = "fjul izar wjnh ubmm";
            $mail->CharSet = "utf-8";
            $mail->addAddress($update_vars['email']);
            $code = $update_vars['code']; // Replace with your code generation logic

            $mail->setFrom("kenzimebarki2@gmail.com");
            $mail->Subject = "Réinitialisation de mot de passe";
            $mail->Body = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe: " . " " . "https://destivoyage.alwaysdata.net/motdepasseoublie.php?code=" . $code;

            if ($mail->send()) {
                // Redirection vers une page de confirmation
                header("Location: password_sent.html");
                exit();
            } else {
                echo "<script>alert('Erreur lors de l\'envoi du courrier. Veuillez réessayer plus tard.');</script>";
            }
        } catch (Exception $e) {
            $message = $mail->ErrorInfo;
        }
    } else {
        $erreur = "Adresse email introuvable. Veuillez vérifier votre adresse email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Style/motdepasseoublierecherche.css">
    <link rel="stylesheet" href="../Style/stylenav.css">
    <link rel="stylesheet" href="../Style/stylefooter.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <title>RECHERCHE COMPTE </title>
</head>

<body>


<?php include('../include/navnotlogin.php')?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="centered-div">
                    <div class="centered-div-image">
                        <img src="../images/Logo.png" alt="mot-de-passe">
                    </div>
                    <h1>Rechercher votre compte</h1>
                    <p>Entrez l'adresse email associé à votre compte pour modifier votre mot de passe.</p>
                    <form method="POST">
                        <div class="mb-3">
                            <input type="email" class="form-control" id="rechercher" name="rechercher" placeholder="Adresse email">
                        </div>

                        <?php if (isset($erreur)) {
                            echo '<div class="alert alert-danger">' . $erreur . '</div>';
                        }
                        ?>
                        <input type="submit" id="suivant" value="Suivant">
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('lefooter.php')?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>