<?php  
require "fonction.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// use Google_Client;
require_once "phpmailer/Exception.php";
require_once "phpmailer/PHPMailer.php";
require_once "phpmailer/SMTP.php";
// require_once "vendor/autoload.php";
$mail=new PHPMailer(true);

$errors = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// $errors = signup($_POST);
    $errors = signup($_POST);
	if(empty($errors)){
        $arr['username'] = $_POST['username'];
		$arr['email'] = $_POST['email'];
        $arr['nom'] = $_POST['nom'];
        $arr['prenom'] = $_POST['prenom'];
        $arr['genre'] = $_POST['genre'];
        $arr['verified']=0;
		$arr['password'] = hash('sha256',$_POST['password']);
		$arr['date'] = $_POST['daten'];
		$arr['code'] = rand(10000, 99999);
        $arr['genre'] = $_POST['genre'];
        $arr['date_inscription']=date('Y-m-d H:i:s');
        $query = "insert into user (email,login,nom,prenom,genre,email_verified,password,date_naiss,code_confirmation,date_inscription) values (:email,:username,:nom,:prenom,:genre,:verified,:password,:date,:code,:date_inscription)";
        database_run($query, $arr);
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
            $mail->SMTPDebug = 2; 
            
            $mail->Username="kenzimebarki2@gmail.com";
            $mail->Password ="fjul izar wjnh ubmm";

            

            $mail->CharSet="utf-8";
            $mail->addAddress($arr['email']);
            
            $mail->setFrom("kenzimebarki2@gmail.com");
            $mail->Subject = "Confirmation INSCRIPTION";
            $mail->Body = "VOICI VOTRE CODE POUR CONFIRMER VOTRE INSCRIPTION "." "."http://dwavance/confirm.php?code=".$arr['code'];
            $mail->send();
            echo "<p>un email avec un lien de confirmation a ete envoyer a votre adresse mail.</p>";

        }catch(Exception $e){
            $message = ''. $mail->ErrorInfo;
        }

        
    
	}
}
        // $_SESSION['mdp'] = $_POST['password'];
        // $_SESSION['date'] = date('Y-m-d');
		// $_SESSION['LOGGED_IN'] = true;
		// header("Location: verifier.php");
		// die;
	

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="inscription.css">
    <title>Page inscription</title>
</head>

<body>
   
        <main class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="centered-div">
                    <div class="text-center">
                        <h1 class="display-4 text-truncate">INSCRIPTION</h1>
                    <p id="petit"> Inscrivez-vous sur notre site</p>
                    </div>
                    <form id="form" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control text-muted" id="nom" placeholder="Votre nom" name="nom" required>
                                
                            </div>
                            
                            <div class="col">
                                <input type="text" class="form-control" id="prenom" placeholder="Votre prénom" name="prenom" required>
                            </div>
                            
                            <div id="errordiv" class="mb-3 d-none"><p id="errornom"></p></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control " id="login" placeholder="Votre login" name="username" required>
                        </div>
                        <div id="errordiv2" class="mb-3 d-none"><p id="errorlog"></p></div>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" placeholder="Votre email" name="email" required>
                        </div>
                        <div id="errordiv3" class="mb-3 d-none"><p id="errorlog3"></p></div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="mot-de-passe2"
                                placeholder="Votre mot de passe" name="password" required>
                                <!-- <div id="errormdp" class="text-danger small d-none"><p id="errormdp3"></p></div> -->
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control " id="mot-de-passe3"
                                placeholder="Confirmez votre mot de passe" required>
                                <!-- <div id="errormdp" class="mb-3 d-none"><p id="errormdp3"></p></div> -->
                        </div>
                        <div id="errormdp" class="mb-3 d-none"><p id="errormdp3"></p></div>
                        <div class="mb-3">
                            <label for="date">Date de naissance :</label>
                            <input type="date" class="form-control " id="date" name="daten" required>
                        </div>
                        <div class="mb-3 d-flex justify-content-center align-items-center">
                            <div class=" form-check form-check-inline">
                            <label for="homme">Homme</label>
                            <input type="radio" id="homme" name="genre" value="homme" required>

                        </div>
                        <div class="form-check form-check-inline">

                            <label for="femme">Femme</label>
                            <input type="radio" id="femme" name="genre" value="femme" required>
                        </div>
                </div>
                <!-- <button type="submit" class="g-recaptcha btn btn-primary" 
                                    data-sitekey="6Le5yPIoAAAAAEbt9BcLcXuzVVVzmpahOixv1Wye" 
                                    data-callback='onSubmit' 
                                    data-action='submit'>Submit</button> -->

                <input type="submit" class="form-control g-recaptcha btn btn-primary" data-sitekey="6LeINvYoAAAAACbwqMmU6wD6Eiytu33aD_aAhQmp" 
                                    data-callback='onSubmit' 
                                    data-action='submit' id="btn-inscrip" value="S'inscrire">
                </form>
                <p>Vous avez déjà un compte ? <a href="connexion.html">Se connecter</a></p>
                </div>
            </div>
    </main>
    <div>
        <?php if(count($errors) > 0):?>
            <?php foreach ($errors as $error):?>
                <?= $error?> <br>	
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
        document.getElementById("form").submit();
    }
    </script>
    
    <script src="controleinscri.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>