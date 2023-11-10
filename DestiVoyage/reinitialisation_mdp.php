<?php
require 'fonction.php';

$errors = array();
if(isset($_SESSION['mail'])) {
    echo "Veuillez reinitialiser votre mdp ".$_SESSION['mail'];

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (strlen($new_password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if (count($errors) === 0) {
        // Réinitialisez le mot de passe dans la base de données
        $email = $_SESSION['mail'];
        $vars = array();
        $vars['email'] = $email;
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Hachez le nouveau mot de passe de manière sécurisée
        $query = "UPDATE user SET password = :password WHERE email = :email";
        $vars['password'] = $hashed_password;

        database_run($query, $vars);

        // Redirigez l'utilisateur vers la page de connexion
        session_destroy();
        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
        <?php if (count($errors) > 0): ?>
            <?php foreach ($errors as $error): ?>
                <?= $error ?> <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <form method="POST">
        <input type="password" placeholder="mdp" name="new_password">
        <input type="password" placeholder="confirmer mdp" name="confirm_password" >
        <input type="submit" value="Confirmer">
    </form>
    
</body>
</html>