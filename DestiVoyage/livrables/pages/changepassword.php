<?php

/**
 * @file
 * Mise à jour du mot de passe utilisateur.
 * 
  * PHP version 7.3.11
 * 
 * @category Gestion_Utilisateur
 * @package  Projet_DWA
 * 
 * @author   SFAIHI Sabine
 * @date 1 décembre 2023
 */

 // Démarrage de la session
session_start();

// Informations de connexion à la base de données
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';
    // Chaîne de connexion PDO
    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    // Création de l'objet PDO
    $con = new PDO($string,$username,$password);


    /**
 * Vérifie la méthode de la requête HTTP avant de traiter la mise à jour du mot de passe.
 * 
 * @return void
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
     /**
     * Vérifie si les paramètres nécessaires sont définis dans la requête POST.
     * 
     * @return void
     */

    if (isset($_POST['newPassword']) && isset($_SESSION["email"])) {
        $newPassword = $_POST['newPassword'];
        $userId = $_SESSION["email"]; 
        

        // Vérifiez si les nouveaux mots de passe correspondent
        
         /**
         * Vérifie si le nouveau mot de passe respecte les critères de sécurité.
         */

        // Vérifie si la longueur du mot de passe est inférieure à 8 caractères
            if (strlen($newPassword) < 8) {
                $errors[] = 'Le mot de passe doit avoir au moins 8 caractères.';
            }
            
            // Valider la présence d'au moins un chiffre
            if (!preg_match('/\d/', $newPassword)) {
                $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
            }
            
            // Valider la présence d'au moins une minuscule
            if (!preg_match('/[a-z]/', $newPassword)) {
                $errors[] = 'Le mot de passe doit contenir au moins une minuscule.';
            }
            
            // Valider la présence d'au moins une majuscule
            if (!preg_match('/[A-Z]/', $newPassword)) {
                $errors[] = 'Le mot de passe doit contenir au moins une majuscule.';
            }
            
            // Valider la présence d'au moins un caractère spécial
            if (!preg_match('/[!@#$%^&*]/', $newPassword)) {
                $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial (!,@,#,$,%,^,&,*)';
            }
            
            // Si des erreurs de validation sont présentes, les affiche
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . '<br>';
                }

        } else {
             // Hachage du nouveau mot de passe
            $hashedPassword = hash('sha256', $newPassword);
            
            // Requête SQL pour mettre à jour le mot de passe dans la base de données
            $queryUpdatePassword = 'UPDATE user SET password = :password1 WHERE email = :email';
            $stmtUpdatePassword = $con->prepare($queryUpdatePassword);
            $stmtUpdatePassword->bindParam(':password1', $hashedPassword, PDO::PARAM_STR);
            $stmtUpdatePassword->bindParam(':email', $userId, PDO::PARAM_STR);

            // Exécutez la requête
            if ($stmtUpdatePassword->execute()) {
                echo 'Mot de passe mis à jour avec succès.';
                header("Location: profil.php?success=1");
                exit();
            } else {
                echo 'Erreur lors de la mise à jour du mot de passe.';
            }
            
        }
    } else {
        echo 'Les champs nécessaires ne sont pas définis.';
    }
} else {
    echo 'Requête non autorisée.';
}
?>
