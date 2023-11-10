<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="connexion.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>PAGE CONNEXION</title>
</head>
<body>
    <div class="container espace">
        <div class="row">
            <div class="col-md-12">
                <div class="centered-div">
                    <div class="centered-div-image">
                        <img class="img-fluid" src="Logo.png" alt="mot-de-passe">
                    </div>
                    <h1 class="display-4 text-truncate">CONNEXION</h1>
                    <p>Connectez-vous à notre site</p>
                    <form action ="verify.php" method="POST" class="monformulaire" id="demo-form">
                        <div class="mb-6">
                            <input type="text" class="form-control" name="login" id="login" placeholder="Login">
                        </div>
                        <div class="mb-6">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Se connecter">
                        <input type="hidden" id="recaptchaResponse" name="recaptcha-response">
                    </form>
                    <p>Pas encore inscrit ? <a href="inscription.html">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldi1eQoAAAAAFUuk9zEpBm7Zle-3PJyexI1v_1q"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6Ldi1eQoAAAAAFUuk9zEpBm7Zle-3PJyexI1v_1q', {action: 'homepage'}).then(function(token) {
        document.getElementById('recaptchaResponse').value = token
    });
});
</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>