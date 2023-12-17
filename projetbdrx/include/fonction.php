<?php 


//fonction pour verifier si il y'a des erreurs et valider les donnees de l'insciption (verification cote serveur)
// exemple : la validite de l'email, le regex pour le nom, prenom et le login
function signup($data){
	$errors = array();

	if(!preg_match('/^[a-zA-Z0-9-_]{4,20}$/', $data['username'])){
		$errors[] = "entrez un login valide";
	}
	if(!preg_match('/^(?!.*\s$)[a-zA-ZÀ-ÿ\- \']+$/u',$data['prenom'])){
		$errors[]="entrer un prenom valide";
	}
	
	if(!preg_match('/^(?!.*\s$)[a-zA-ZÀ-ÿ\- \']+$/u',$data['nom'])){
		$errors[]="entrer un nom valide";
	}

	$check = database_run("select * from user where login = :username limit 1",['username'=>$data['username']]);
	if(is_array($check)){
		$errors[] = "That email already exists";
	}

	
	

	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 8){
		$errors[] = "Password must be atleast 4 chars long";
	}

	$check = database_run("select * from user where email = :email limit 1",['email'=>$data['email']]);
	if(is_array($check)){
		$errors[] = "That email already exists";
	}
	return $errors;
}
//fonction pour effectuer des requetes sql sur la base de donnees


function database_run($query, $vars = array())
{
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';

    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $con = new PDO($string, $username, $password, $options);
        $stm = $con->prepare($query);
        $check = $stm->execute($vars);

        if ($check) {
            $data = $stm->fetchAll(PDO::FETCH_OBJ);

            if (count($data) > 0) {
                return $data;
            }
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    
    return false;
}
// fonction pour verifier si le nom de domaine de l'email existe
function verifier_domaine($email) {
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, 'MX');
}

// Définition de constantes pour les clés de captchas
define('key1','6Lc1IAwpAAAAAIFfldy3J4il3Sl67FodcIBPYpPv');
define('key3','6Lc1IAwpAAAAABnQFzXYhDMWGK3HPjwoUdwkxWuG');

