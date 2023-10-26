<?php
    	use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
    
        // use Google_Client;
        require_once "phpmailer/Exception.php";
        require_once "phpmailer/PHPMailer.php";
        require_once "phpmailer/SMTP.php";
        // require_once "vendor/autoload.php";
        $mail=new PHPMailer(true);
    	require "fonction.php";
        $errors = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email=$_POST['email'];
            $vars['email'] = $email;
            $check = database_run("select * from user where email = :email limit 1", $vars);
	     if(!is_array($check)){
            
		$errors[] = "L'email n'existe pas";
	   }else{

       
        $vars['expire']= date('Y-m-d H:i:s', strtotime('+1 hour'));
        $vars['code']= rand(10000, 99999);
        $query = "UPDATE user SET code_mdpo = :code , expir_mdpo = :expire WHERE email =:email ";
        database_run($query, $vars);
        try{
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
            $mail->SMTPDebug = 2; // Activez le débogage SMTP pour afficher les détails
            
            $mail->Username="kenzimebarki2@gmail.com";
            $mail->Password ="fjul izar wjnh ubmm";
            // Authentification OAuth 2.0
            // $mail->AuthType = 'XOAUTH2';
            // $mail->oauthUserEmail = 'votre-email@gmail.com'; // Votre adresse Gmail
            // $mail->oauthClientId = 'votre-client-id';
            // $mail->oauthClientSecret = 'votre-client-secret';
            // $mail->oauthRefreshToken =
            

            $mail->CharSet="utf-8";
            $mail->addAddress($vars['email']);
            
            $mail->setFrom("kenzimebarki2@gmail.com");
        

            $mail->Subject = "Code de verification mdp oublie";
            $mail->Body = "VOICI VOTRE CODE POUR reinitialiser votre mdp "." ". $vars['code'];
            $mail->send();
            echo "Message envoye";
        }catch(Exception $e){
            $message = ''. $mail->ErrorInfo;
        }
        
        $_SESSION['mail']=$email;
        header("Location: codeverifmdpo.php");

        }
            
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mdpoublier</title>
</head>
<body>
<div>
        <?php if(count($errors) > 0):?>
            <?php foreach ($errors as $error):?>
                <?= $error?> <br>	
            <?php endforeach;?>
        <?php endif;?>

    </div>
<form method="POST">
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" required>
            <button type="submit">Envoyer le code de récupération</button>
        </form>
</body>
</html>