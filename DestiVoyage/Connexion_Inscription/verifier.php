<?php
	// require "eemail.php";
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	// use Google_Client;
	require_once "phpmailer/Exception.php";
	require_once "phpmailer/PHPMailer.php";
	require_once "phpmailer/SMTP.php";
	// require_once "vendor/autoload.php";
	$mail=new PHPMailer(true);
// 	$client = new Google_Client();
// $client->setApplicationName('Mon Application Gmail');
// $client->setScopes(Google_Service_Gmail::MAIL_GOOGLE_COM);
// $client->setAuthConfig('chemin_vers_votre_fichier_json_de_credentials.json');
// $client->setAccessType('offline');


	require "fonction.php";
	if (!isset($_SESSION["USER"])) {
		// Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
		header("Location: inscrire.php");
		exit;
	}
	$errors = array();
	echo("tu dois confirmer ton mail"." ".$_SESSION['USER']);
    echo(" ".$_SESSION['MAIL']);


	if($_SERVER['REQUEST_METHOD'] == "GET" && !check_verified()){
		$query = "select code from verify where email = :email";
		$vars = array();
		$vars['email'] = $_SESSION['MAIL'];
		$row = database_run($query, $vars);
	
		if (!is_array($row)) {
			// L'utilisateur n'a pas encore de code, donc générez-en un
			$vars['code'] = rand(10000, 99999);
			$vars['expire'] = (time() + (60 * 10));
			$vars['email'] = $_SESSION['MAIL'];
			$query = "insert into verify (code, expire, email) values (:code, :expire, :email)";
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
			

				$mail->Subject = "Confirmation INSCRIPTION";
				$mail->Body = "VOICI VOTRE CODE POUR CONFIRMER VOTRE INSCRIPTION "." ". $vars['code'];
				$mail->send();
				echo "Message envoye";


			// $message = "your code is " . $vars['code'];
			// $subject = "Email verification";
			// $recipient = $vars['email'];
			}catch(Exception $e){
				$message = ''. $mail->ErrorInfo;
			}

		}else{

		}
		//send email
		
		// send_mail($recipient,$subject,$message);
	}

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		if(!check_verified()){

			$query = "select * from verify where code = :code && email = :email";
			$vars = array();
			$vars['email'] = $_SESSION['MAIL'];
			$vars['code'] = $_POST['code'];
			$row = database_run($query,$vars);

			if(is_array($row)){
				$row = $row[0];
				$time = time();

				if($row->expire > $time){

					
					$id = $_SESSION['USER'];
                $query2 = "insert into user (login, email, password, date_naiss) values (:username, :email, :password, :date)";
                $vars2 = array();
                $vars2['username'] = $id;
                $vars2['email'] = $_SESSION['MAIL'];
                $vars2['password'] = $_SESSION['mdp']; // Vous devez définir le mot de passe souhaité
                $vars2['date'] = $_SESSION['date']; // Vous devez définir la date de naissance souhaitée
                database_run($query2, $vars2);
				$id = $_SESSION['USER'];
				$query = "update user set email_verified = email where login = '$id' limit 1";

		
				database_run($query);

					
					session_destroy();
					header("Location: login.php");
					die;
				}else{
					echo "Code expired";
				}

			}else{
				echo "wrong code";
			}
		}else{
			echo "You're already verified";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Verify</title>
</head>
<body>

	<h1>Verify</h1>

	<!-- <?php include('header.php');?> -->
  
	<br><br>
 	<div>
			<br>an email was sent to your address. paste the code from the email here<br>
		<div>
			<?php if(count($errors) > 0):?>
				<?php foreach ($errors as $error):?>
					<?= $error?> <br>	
				<?php endforeach;?>
			<?php endif;?>
		</div><br>
		<form method="post">
			<input type="text" name="code" placeholder="Enter your Code"><br>
 			<br>
			<input type="submit" value="Verify">
		</form>
	</div>

</body>
</html>