<?php
session_start();
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';
    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($string,$username,$password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (isset($_POST['newPassword']) && isset($_SESSION["email"])) {
        $newPassword = $_POST['newPassword'];
        $userId = $_SESSION["email"]; 
        

        // Vérifiez si les nouveaux mots de passe correspondent
        
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
            
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . '<br>';
                }

        } else {
            $hashedPassword = hash('sha256', $newPassword);
            
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
