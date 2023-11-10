<?php  
require "fonction.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// use Google_Client;
require_once "phpmailer/Exception.php";
require_once "phpmailer/PHPMailer.php";
require_once "phpmailer/SMTP.php";
// require_once "vendor/autoload.php";
$mail=new PHPMailer(true);

$errors = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	// $errors = signup($_POST);
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = array(
            'secret' => key3,
            'response' => $recaptcha_response,
            'remoteip' => $remoteip
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $responseKeys = json_decode($result, true);

        if (isset($responseKeys["success"]) && $responseKeys["success"] === true) {
    $errors = signup($_POST);

    
	if(empty($errors) && verifier_domaine($_POST['email'])){
        $arr['username'] = $_POST['username'];
		$arr['email'] = $_POST['email'];
        $arr['nom'] = $_POST['nom'];
        $arr['prenom'] = $_POST['prenom'];
        $arr['verified']=0;
		$arr['password'] = hash('sha256',$_POST['password']);
		$arr['code'] = rand(10000, 99999);
        $arr['date_inscription']=date('Y-m-d H:i:s');
        $query = "insert into user (email,login,nom,prenom,email_verified,password,code_confirmation,date_inscription) values (:email,:username,:nom,:prenom,:verified,:password,:code,:date_inscription)";
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
            $mail->Body = "VOICI VOTRE CODE POUR CONFIRMER VOTRE INSCRIPTION "." "."http://destivoyage.alwaysdata.net/confirm.php?code=".$arr['code'];
            $mail->send();
            echo "<p>un email avec un lien de confirmation a ete envoyer a votre adresse mail.</p>";

        }catch(Exception $e){
            $message = ''. $mail->ErrorInfo;
        }

        header("Location: email.html");
        exit();
    
	}else{
        echo "<script>alert('Erreur avec l\'adresse e-mail.');</script>";
        header("Location: inscription.php");
    }
}
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- <link rel="shortcut icon" href="images/" type="image/x-icon"> -->
    <link rel="stylesheet" href="inscription.css">
    <link rel="stylesheet" href="stylefooter.css">
    <link rel="stylesheet" href="stylenav.css">
    <title>Page inscription</title>
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>
    <?php include("lanavbar.php")?>
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
                            <div id="errordivpre" class="mb-2 d-none"><p id="errorprenom"></p></div>
                            <div id="errordiv" class="mb-2 d-none"><p id="errornom"></p></div>
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
                        <input type="submit" id="btn-inscrip" class="g-recaptcha" 
        data-sitekey="6LfGPQkpAAAAACXv0dhGu7mknJQ6zZlg7nMNwY9s" 
        data-callback='onSubmit' 
        data-action='submit' value="S'inscrire">
                </form>
                <p>Vous avez déjà un compte ? <a href="connexion.html">Se connecter</a></p>
                </div>
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
    <?php include("lefooter.php") ?>

  
    <script>
//    function onSubmit(token) {
//     //  document.getElementById("form").submit();
//    }
 </script>
    <script src="controleinscri.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>