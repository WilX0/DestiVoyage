<?php

/**
 * @file
 * Page d'inscription utilisateur
 *
 * Version PHP 7.3.11
 *
 * @category Inscription_Utilisateur
 * @package  Projet_DWA
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

 // Inclusion des fonctions nécessaires
require "../include/fonction.php";

// Démarrage de la session pour maintenir l'état de connexion de l'utilisateur
session_start();

// Utilisation de la bibliothèque PHPMailer pour l'envoi d'e-mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\Exception\SMTPException;

// Inclusion des fichiers PHPMailer nécessaires
require_once "../phpmailer/Exception.php";
require_once "../phpmailer/PHPMailer.php";
require_once "../phpmailer/SMTP.php";

// Création d'une instance PHPMailer
$mail = new PHPMailer(true);

// Clés reCAPTCHA pour la validation du formulaire
$key1 = '6Lc1IAwpAAAAAIFfldy3J4il3Sl67FodcIBPYpPv';
$key2 = '6Lc1IAwpAAAAABnQFzXYhDMWGK3HPjwoUdwkxWuG';

// Tableau d'erreurs
$errors = array();

// Vérification de la méthode de requête (POST)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = signup($_POST);
    // Validation des données d'inscription avec la fonction signup et verification du recaptcha
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        // $verify_response=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key2.'&response='.$_POST['g-recaptcha-reponse']);
        $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $key2 . '&response=' . $_POST['g-recaptcha-response']);
        $respondadata = json_decode($verify_response);

        if ($respondadata->success) {
            // Vérification du domaine de l'email
            if (verifier_domaine($_POST['email'])) {
                $errors = signup($_POST);


                if (empty($errors)) {
                    // Création d'un tableau d'informations utilisateur
                    $arr['username'] = $_POST['username'];
                    $arr['email'] = $_POST['email'];
                    $arr['nom'] = $_POST['nom'];
                    $arr['prenom'] = $_POST['prenom'];
                    $arr['verified'] = 0;
                    $arr['password'] = hash('sha256', $_POST['password']);
                    $arr['code'] = rand(10000, 99999);
                    $arr['date_inscription'] = date('Y-m-d H:i:s');
                    // Requête SQL pour insérer l'utilisateur dans la base de données
                    $query = "insert into user (email,login,nom,prenom,email_verified,password,code_confirmation,date_inscription) values (:email,:username,:nom,:prenom,:verified,:password,:code,:date_inscription)";
                     // Configuration de PHPMailer pour l'envoi d'un email de confirmation
                    try {
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
                        $mail->addAddress($arr['email']);

                        $mail->setFrom("kenzimebarki2@gmail.com");
                        $mail->Subject = "Confirmation INSCRIPTION";
                        $mail->Body = "VOICI VOTRE CODE POUR CONFIRMER VOTRE INSCRIPTION " . " " . "http://destivoyage.alwaysdata.net/confirm.php?code=" . $arr['code'];
                        // echo "<p>un email avec un lien de confirmation a ete envoyer a votre adresse mail.</p>";
                        if ($mail->send()) {
                            database_run($query, $arr);
                            header("Location: email.html");
                            exit();
                        } else {
                            echo " <script>alert('Adresse email INTROUVABLE !!');</script>";
                            if (strpos($mail->ErrorInfo, "Email address is not found on the server") !== false) {
                                echo "Votre adresse e-mail est introuvable ou ne peut pas recevoir de messages. Veuillez vérifier l'adresse e-mail que vous avez fournie.";
                            }
                        }
                    } catch (Exception $e) {
                        $message = '' . $mail->ErrorInfo;
                    }
                }
            }
        } else {
            echo "<script>alert('Nom de domaine de l'email incorrect. Veuillez vérifier votre adresse email.');</script>";
        }
    } else {
        echo "<script>alert('Erreur avec le reCapctha.');</script>";
    }
}
?>
    
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="Style/style.css">
    <link rel="stylesheet" href="Style/stylefooter.css">
    <link rel="stylesheet" href="Style/stylenav.css">
    <title>Page inscription</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>
</head>

<body>
    
    <?php
    if(!isset($_SESSION["login"])){
        include("../include/navnotlogin.php");
    }else{
        header("Location: ../index.php");
    }
      ?>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="centered-div">
                    <div class="text-center">
                        <h1 id="titreinscri" class="display-4 text-truncate">INSCRIPTION</h1>
                        <p id="petit">Inscrivez-vous sur notre site</p>
                    </div>
                    <?php if (count($errors) > 0) : ?>
            <?php foreach ($errors as $error) : ?>
               <p class="text-danger"> <?= $error ?> <br></p>
            <?php endforeach; ?>
        <?php endif; ?>
                    
 
                    <form id="form" method="POST">
            
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="hello text-muted" id="nom" placeholder="Votre nom" name="nom" required>

                            </div>

                            <div class="col">
                                <input type="text" class="hello" id="prenom" placeholder="Votre prénom" name="prenom" required>
                            </div>
                            <div id="errordivpre" class="mb-2 d-none">
                                <p id="errorprenom"></p>
                            </div>
                            <div id="errordiv" class="mb-2 d-none">
                                <p id="errornom"></p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="hello" id="login" placeholder="Votre login" name="username" required>
                        </div>
                        <div id="errordiv2" class="mb-3 d-none">
                            <p id="errorlog"></p>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="hello" id="email" placeholder="Votre email" name="email" required>
                        </div>
                        <div id="errordiv3" class="mb-3 d-none">
                            <p id="errorlog3"></p>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="hello" id="mot-de-passe2" placeholder="Votre mot de passe" name="password" required>
                            <!-- <div id="errormdp" class="text-danger small d-none"><p id="errormdp3"></p></div> -->
                        </div>
                        <div class="mb-3">
                            <input type="password" class="hello" id="mot-de-passe3" placeholder="Confirmez votre mot de passe" required>
                            <!-- <div id="errormdp" class="mb-3 d-none"><p id="errormdp3"></p></div> -->
                        </div>
                        <div id="errormdp" class="mb-3 d-none">
                            <p id="errormdp3"></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-3 recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="<?php echo $key1 ?>"></div>
                        </div>
                        <input type="submit" id="btn-inscrip" class="mybtns btn btn-primary" value="S'inscrire">
                    </form>
                    <p id="compte">Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
                </div>
            </div>

        </div>
    </main>
    <!-- <div>
        <?php if (count($errors) > 0) : ?>
            <?php foreach ($errors as $error) : ?>
                <?= $error ?> <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div> -->
    <?php include("../include/lefooter.php");?>
    <script src="../scripts/controleinscri.js"></script>
    <script type="text/javascript">
        var onloadCallback = function() {
            // alert("grecaptcha is ready!");
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>