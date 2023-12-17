<?php

/**
 * @file
 * @brief Fonctions pour la gestion des utilisateurs et de la base de données.
 * @author Sfaihi sabine
 * @date 12-12-2023
 */

/**
 * @brief Fonction d'inscription utilisateur.
 *
 * Cette fonction vérifie et valide les données d'inscription côté serveur.
 *
 * @param array $data Les données du formulaire d'inscription.
 * @return array Tableau d'erreurs s'il y a des problèmes lors de l'inscription, sinon un tableau vide.
 */
function signup($data){
	$errors = array();

	// Validation du login
	if(!preg_match('/^[a-zA-Z0-9-_]{4,20}$/', $data['username'])){
		$errors[] = "Entrez un login valide";
	}

	// Validation du prénom
	if(!preg_match('/^(?!.*\s$)[a-zA-ZÀ-ÿ\- \']+$/u', $data['prenom'])){
		$errors[] = "Entrez un prénom valide";
	}
	
	// Validation du nom
	if(!preg_match('/^(?!.*\s$)[a-zA-ZÀ-ÿ\- \']+$/u', $data['nom'])){
		$errors[] = "Entrez un nom valide";
	}

	// Vérification de l'existence du login dans la base de données
	$check = database_run("select * from user where login = :username limit 1", ['username' => $data['username']]);
	if(is_array($check)){
		$errors[] = "Ce login existe déjà";
	}

	// Validation de l'adresse email
	if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = "Veuillez entrer une adresse e-mail valide";
	}

	// Validation de la longueur du mot de passe
	if(strlen(trim($data['password'])) < 8){
		$errors[] = "Le mot de passe doit comporter au moins 8 caractères";
	}

	// Vérification de l'existence de l'email dans la base de données
	$check = database_run("select * from user where email = :email limit 1", ['email' => $data['email']]);
	if(is_array($check)){
		$errors[] = "Cette adresse e-mail existe déjà";
	}

	return $errors;
}

/**
 * @brief Fonction d'exécution de requêtes SQL sur la base de données.
 *
 * @param string $query La requête SQL à exécuter.
 * @param array $vars Les variables à lier à la requête.
 * @return array|bool Résultat de la requête sous forme d'objet s'il y a des données, sinon false.
 */
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

/**
 * @brief Fonction pour vérifier si le nom de domaine de l'email existe.
 *
 * @param string $email L'adresse e-mail à vérifier.
 * @return bool True si le nom de domaine existe, sinon false.
 */
function verifier_domaine($email) {
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, 'MX');
}

// Définition de constantes pour les clés de captchas
define('key1', '6Lc1IAwpAAAAAIFfldy3J4il3Sl67FodcIBPYpPv');
define('key3', '6Lc1IAwpAAAAABnQFzXYhDMWGK3HPjwoUdwkxWuG');
?>
