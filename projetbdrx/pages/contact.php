<?php
session_start();
if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        echo '<p class="success-message">Votre message a été envoyé avec succès. Vous serez contacté sous peu.</p>';
    } else {
        $errorMessage = isset($_GET['error']) ? urldecode($_GET['error']) : "Une erreur s'est produite lors de l'envoi du message.";
        echo '<p class="error-message">' . $errorMessage . '</p>';
    }
}
?>

<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet"> -->

    <!-- <link rel="stylesheet" href="fonts/icomoon/style.css"> -->


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    
    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/stylenav.css">
    <link rel="stylesheet" href="assets/css/stylefooter.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <title>Contact </title>
  </head>
  <body>
  <?php
        if (isset($_SESSION["login"])) {
            include("navlogin.php");
        } else {
            include("navnotlogin.php");
        }
        ?>
  <div class="content">    
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap">
        <div class="col-md-8">
          <div class="form h-100">
            <h3>Nous contacter</h3>
            <form class="mb-5" method="post" action="process_contact.php" id="contactForm" name="contactForm">
    
              <div class="row">
                <div class="col-md-6 form-group mb-5">
                  <label class="col-form-label">Nom *</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Votre nom ">
                </div>
                <div class="col-md-6 form-group mb-5">
                  <label class="col-form-label">Email *</label>
                  <input type="text" class="form-control" name="email" id="email"  placeholder="Votre email">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-5">
                  <label class="col-form-label">Telephone</label>
                  <input type="text" class="form-control" name="phone" id="phone"  placeholder="Votre numero  #">
                </div>
                <div class="col-md-6 form-group mb-5">
                  <label class="col-form-label">Sujet</label>
                  <input type="text" class="form-control" name="company" id="company"  placeholder="Le sujet">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group mb-5">
                  <label class="col-form-label">Message *</label>
                  <textarea class="form-control" name="message" id="message" cols="30" rows="4"  placeholder="Ecrivez votre message"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <input type="submit" value="envoyer " class="btn btn-primary rounded-0 py-2 px-4">
                  <span class="submitting"></span>
                </div>
              </div>
            </form>

            <div id="formmessagewarning"></div> 
            <div id="formmessagesuccess">
              Your message was sent, thank you!
            </div>

          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info">
            <h3>Contact Informations</h3>
            <p class="mb-5">Etudiant de CY paris université</p>
            <p>Université Saint Martin, Cergy Paris France</p>
            <h4>Nos contacts</h4>
            <ul class="list-unstyled">
              <li>
                <span class="text"><strong>Mebarki Mohammed</strong>  : +(33)7 44 15 54 98</span>
                <span class="text">kenzimebarki2@gmail.com</span>
                <span class="text"><strong>Maibeche Louisa</strong> : +(33)7 44 15 54 98</span>
                <span class="text">maibechelouisa@gmail.com</span>
                <span class="text"><strong>Merzougui Melissa</strong> : +(33)7 81 03 60 09</span>
                <span class="text">merzouguimelissa2002@gmail.com</span>
                <span class="text"><strong>Sfaihi Sabine</strong> : +(33)6 95 52 80 13</span>
                <span class="text">sfahisabine@gmail.com</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
  <?php  include("lefooter.php");?>
    
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("contactForm").addEventListener("submit", function (event) {
                var name = document.getElementById("name").value;
                var email = document.getElementById("email").value;

                // Validation du nom
                if (name.trim() === "") {
                    alert("Veuillez entrer votre nom.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }

                // Validation de l'e-mail
                if (email.trim() === "") {
                    alert("Veuillez entrer votre adresse e-mail.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                } else {
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        alert("Veuillez entrer une adresse e-mail valide.");
                        event.preventDefault(); // Empêche l'envoi du formulaire
                        return;
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>


  </body>
</html>