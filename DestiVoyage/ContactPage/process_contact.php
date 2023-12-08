<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'destivoyage06@gmail.com'; // Adresse e-mail Gmail expéditrice
    $mail->Password = 'qmce hhyf mahv tfmm'; // Mot de passe Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $to = "kenzimebarki2@gmail.com"; // Adresse e-mail destinataire

    $subject = "Nouveau message depuis le formulaire de contact";

    $messageBody = "
        Nom: $name\n
        Email: $email\n
        Téléphone: $phone\n
        Sujet: $company\n
        Message:\n$message
    ";

    $mail->setFrom('destivoyage06@gmail.com', 'Expéditeur'); // Adresse et nom de l'expéditeur
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $messageBody;

    try {
        $mail->send();
        echo "Votre message a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "Une erreur s'est produite lors de l'envoi du message. Erreur : {$mail->ErrorInfo}";
    }
} else {
    header("Location: contact.php");
}
?>
