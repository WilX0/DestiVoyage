<?php
session_start();
include_once("../include/fonction.php");
?>
<?php
if (isset($_SESSION['activation_message'])) {
    echo "<script>alert('" . $_SESSION['activation_message'] . "');</script>";
    unset($_SESSION['activation_message']); // Supprimez le message de la session après l'avoir affiché
}
?>
<?php
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['mail']) && isset($_POST['password'])) {
        $email = $_POST['mail'];
        $password = hash('sha256', $_POST['password']);

        $query = "SELECT login , password , email_verified FROM user WHERE email = :email";
        $arr["email"] = $email;
        $row = database_run($query, $arr);

        if ($row) {
            $login = $row[0]->login;
            
            // $mail = $row[0]->email;
            $storedPassword = $row[0]->password;
            $verified = $row[0]->email_verified;

            if ($verified == 1 && hash_equals($storedPassword, $password)) {
                $_SESSION["login"] = $login;
                $_SESSION["email"] = $arr["email"];
                header("Location: ../index.php");
                echo "Connexion réussie pour l'utilisateur $login.";
            } elseif ($verified == 0) {
                // Compte non vérifié
                $errors[] = "Erreur : Votre compte n'a pas encore été vérifié. Veuillez vérifier votre e-mail.";
            } else {
                // Mot de passe incorrect
                $errors[] = "Erreur : Mot de passe incorrect.";
            }
        } else {
            // Compte inexistant
            $errors[] = "Erreur : Veuillez fournir une adresse e-mail et un mot de passe.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Style/style.css">
    <link rel="stylesheet" href="../Style/stylenav.css">
    <link rel="stylesheet" href="../Style/stylefooter.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <title>PAGE CONNEXION</title>
</head>

<body>
<?php
    if(!isset($_SESSION["login"])){
        include("../include/navnotlogin.php");
    }else{
        header("Location: ../index.php");
    }
      ?>
    <div class="container espace">
        <div class="row">
            <div class="col-md-12">
                <div class="monespace centered-div2">
                    <div class="centered-div-image">
                        <img class="img-fluid" src="images/Logo.png" alt="mot-de-passe">
                    </div>
                    <h1 id="titre2" class="display-4 text-truncate">CONNEXION</h1>
                    <p>Connectez-vous à notre site</p>
                    <form action="" method="POST" class="monformulaire" id="demo-form">
                        <?php foreach ($errors as $error) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="mb-6">
                            <input type="text" class="form-control" name="mail" id="mail" placeholder="E-mail">
                        </div>
                        <div class="mb-6">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe">
                        </div>
                        <input type="submit" class="mybtns btn btn-primary" value="Se connecter">
                        <input type="hidden" id="recaptchaResponse" name="recaptcha-response">
                    </form>
                    <p id="lep">Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
                    <a href="mdpoublierecherche.php">mot de passe oublié ?</a>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/lefooter.php'); ?>
    <?php
    if (isset($_SESSION['activation_message'])) {
        echo "<script>alert('" . $_SESSION['activation_message'] . "');</script>";
        unset($_SESSION['activation_message']); // Supprimez le message de la session après l'avoir affiché
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>