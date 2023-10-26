<?php
require "fonction.php";

echo "un mail a ete envoyer a ".$_SESSION["mail"]."";
$errors=array();
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $user_code = $_POST['code'];
    $email = $_SESSION['mail'];
    $vars = array();
    $vars['email'] = $email;
    $query = "SELECT code_mdpo, expir_mdpo FROM user WHERE email = :email";
    $result = database_run($query, $vars);
    if (is_array($result) && count($result) === 1) {
        $row = $result[0];
        $stored_code = $row->code_mdpo;
        $expiration_date = strtotime($row->expir_mdpo);
        $current_date = time();

        if ($user_code === $stored_code && $current_date <= $expiration_date) {
            $vars['null']=null;
            $query = "UPDATE user SET code_mdpo = :null , expir_mdpo = :null WHERE email =:email ";
            database_run($query, $vars);
            header("Location: reinitialisation_mdp.php");
            die;
        } else {
            $errors[] = "Code incorrect ou expiré. Veuillez réessayer.";
        }
    } else {
        $errors[] = "Erreur lors de la récupération du code de récupération.";
    }



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>codeverif</title>
</head>
<body>
<div>
        <?php if (count($errors) > 0): ?>
            <?php foreach ($errors as $error): ?>
                <?= $error ?> <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <form action="" method="post">
        <input type="text" name="code" placeholder="code">
        <input type="submit" value="confirmer">
    </form>
</body>
</html>