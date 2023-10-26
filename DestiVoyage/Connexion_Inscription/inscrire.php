<?php  

require "fonction.php";

$errors = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// $errors = signup($_POST);

	
        $_SESSION['USER'] = $_POST['username'];
        $_SESSION['MAIL'] = $_POST['email'];
        $_SESSION['mdp'] = $_POST['password'];
        $_SESSION['date'] = date('Y-m-d');
		$_SESSION['LOGGED_IN'] = true;
		header("Location: verifier.php");
		die;
	
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
    <!-- <form action="get" method="post">
        <input type="text" placeholder="nom">
        <input type="text" placeholder="prenom">
        <input type="email"placeholder="email">
        <input type="text" placeholder="pseudo">
        <input type="date" placeholder="">
        <input type="submit">
    </form> -->
    <h1>Signup</h1>

<!-- <?php include('header.php')?> -->

<div>
    <div>
        <?php if(count($errors) > 0):?>
            <?php foreach ($errors as $error):?>
                <?= $error?> <br>	
            <?php endforeach;?>
        <?php endif;?>

    </div>
    <form method="post">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="text" name="email" placeholder="Email"><br>
        <input type="text" name="password" placeholder="Password"><br>
        <input type="text" name="password2" placeholder="Retype Password"><br>
        <br>
        <input type="submit" value="Signup">
    </form>
</div>
</body>
</html>