<?php

// verification de l'utilisation de POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (empty($_POST['login']) || empty($_POST['password'])) {
        header('Location: connexion.php');
        exit;
     } else{
    // On vérifie si le champ "recaptcha-response" a une valeur
    if(empty($_POST['recaptcha-response'])){
        header('Location: connexion.php');
    }else{
// l'URL a interrogée (clé secrete et réponse)
$url = "https://www.google.com/recaptcha/api/siteverify?secret=6Ldi1eQoAAAAAL-DeVhJN9Oe1ZE2oGNZJhQhfLDS&response={$_POST['recaptcha-response']}";

// On vérifie si curl est installé
if(function_exists('curl_version')){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
}else{
    // Sinon on utilisera file_get_contents
    $response = file_get_contents($url);
}

// verification de la reponse si notre captcha est valide ou pas
if(empty($response) || is_null($response)){
    header('Location: connexion.php');
}else{
    $data = json_decode($response);
    if($data->success){
        // Google a répondu avec un succès
        // On traite le formulaire
        echo 'success';
    }else{
        header('Location: connexion.php');
    }
}
        if(
            isset($_POST['login']) && !empty($_POST['login']) &&
            isset($_POST['password']) && !empty($_POST['password'])
        ){
            // On nettoie le contenu pour enlever tout ce qui est balise HTML(qst de sécurité)
            $login = strip_tags($_POST['login']);
            //hash du mot de passe + verification
            // traitement de données
        }
    }
}
}else{
    //code de mauvaise méthode 
    http_response_code(405);
    echo 'Méthode non autorisée';
}