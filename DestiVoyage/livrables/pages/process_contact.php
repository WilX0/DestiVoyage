<?php

/**
 * Gestion de l'envoi de formulaire de contact par e-mail.
 * Utilisation de la bibliothèque PHPMailer.
 * 
 * @category Formulaire de Contact
 * @package  Projet_DWA
 * @version  PHP 7.3.11
 * 
 * @uses PHPMailer\PHPMailer\PHPMailer
 * @uses PHPMailer\PHPMailer\SMTP
 * @uses PHPMailer\PHPMailer\Exception
 */

// require "fonction.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\Exception\SMTPException;
// use Google_Client;

// Inclusion des fichiers de PHPMailer
require_once "phpmailer/Exception.php";
require_once "phpmailer/PHPMailer.php";
require_once "phpmailer/SMTP.php";
// require_once "vendor/autoload.php";
$mail = new PHPMailer(true);

// Vérification si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $company = isset($_POST['company']) ? $_POST['company'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

     // Vérification des champs obligatoires
    if (empty($name) || empty($email) || empty($message)) {
        $errorMessage="VEUILLEZ REMPLIR LES CHAMPS OBLIGATOIRE";
        header("Location: contact.php?success=0&error= ".urlencode($errorMessage));
        exit();
    }

    // Configuration de PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    // $mail->SMTPAuth = true;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    // $mail->SMTPSecure = 'tls';
    $mail->Username = 'destivoyage06@gmail.com'; // Adresse e-mail Gmail expéditrice
    $mail->Password = 'qmce hhyf mahv tfmm'; // Mot de passe Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPAutoTLS = false;

    $to = "kenzimebarki2@gmail.com"; // Adresse e-mail destinataire

    $subject = "Nouveau message depuis le formulaire de contact";

// Corps du message
    $messageBody = "
        Nom: $name\n
        Email: $email\n
        Téléphone: $phone\n
        Sujet: $company\n
        Message:\n$message
    ";

    // Paramètres de l'e-mail
    $mail->setFrom('destivoyage06@gmail.com', 'Expéditeur');
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $messageBody;

    try {
        $mail->send();
        // echo "Votre message a été envoyé avec succès.";
        header("Location: contact.php?success=1");
    } catch (Exception $e) {
        $errorMessage = "Une erreur s'est produite lors de l'envoi du message. Erreur : {$mail->ErrorInfo}";
        header("Location: contact.php?success=0&error=" . urlencode($errorMessage));
        exit();
        // echo "Une erreur s'est produite lors de l'envoi du message. Erreur : {$mail->ErrorInfo}";
    }
} else {
    // Redirection si la méthode de requête n'est pas POST
    header("Location: contact.php");
}
?>
