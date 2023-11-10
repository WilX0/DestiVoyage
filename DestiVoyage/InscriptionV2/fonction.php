<?php 

// session_start();

function signup($data)
{
	$errors = array();
 
	//validate 
	if(!preg_match('/^[a-zA-Z0-9-_]{4,20}$/', $data['username'])){
		$errors[] = "Please enter a valid username";
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

	//save
	// if(count($errors) == 0){

	// 	$arr['username'] = $data['username'];
	// 	$arr['email'] = $data['email'];
	// 	$arr['password'] = hash('sha256',$data['password']);
	// 	$arr['date'] = date("Y-m-d");
	// 	$arr['code'] = 

	// 	$query = "insert into user (login,email,password,date_naiss,code_confirmation) values 
	// 	(:username,:email,:password,:date)";

	// 	database_run($query,$arr);
	// }
	return $errors;
}

function login($data)
{
	$errors = array();
 
	//validate 
	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 4){
		$errors[] = "Password must be atleast 4 chars long";
	}
 
	//check
	if(count($errors) == 0){

		$arr['email'] = $data['email'];
		// $password = hash('sha256', $data['password']);
		$password=$data['password'];

		$query = "select * from user where email = :email and email_verified= :email limit 1";
		$row = database_run($query,$arr);
		if(is_array($row)){
			$row = $row[0];
			if($password === $row->password){
				$_SESSION['USER'] = $row;
				$_SESSION['LOGGED_IN'] = true;
			}else{
				$errors[] = "wrong email or password";
			}

		}else{
			$errors[] = "wrong email or password";
		}
	}
	return $errors;
}

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

function check_login($redirect = true){

	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

		return true;
	}

	if($redirect){
		header("Location: login.php");
		die;
	}else{
		return false;
	}
	
}
function verifier_domaine($email) {
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, 'MX');
}
function check_verified(){

	$id = $_SESSION['MAIL'];
	$query = "select * from user where login = '$id' limit 1";
	$row = database_run($query);
	if(is_array($row)){
		$row = $row[0];
		if($row->email == $row->email_verified){

			return true;
		}
	}
 
	return false;
 	
}
define('key1','6LfGPQkpAAAAACXv0dhGu7mknJQ6zZlg7nMNwY9s');
define('key3','6LfGPQkpAAAAANCoMMbURHHU-CI9f9rggzudoaFY');

function checktoken($token,$secretoken){
	$url_verif='https://www.google.com/recaptcha/api/siteverify?secret=$secretoken&response=$token';
		$curl=curl_init($url_verif);	
		curl_setopt($curl, CURLOPT_HEADER,false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		$verif_response=curl_exec($curl);
		if(empty($verif_response) )return false;
		else{
			$json=json_decode($verif_response);
			return $json->success;
		}

}