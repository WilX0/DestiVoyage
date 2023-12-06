<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    
    <!-- Style -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylenav.css">
    <link rel="stylesheet" href="stylefooter.css">

    <title>Contact </title>
  </head>
  <body>
 
  <?php  include("lanavbar.php");?>
  <div class="content">




    
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap">
        <div class="col-md-8">
          <div class="form h-100">
            <h3>Nous contacter</h3>
            <form class="mb-5" method="post" action="process_contact.php" id="contactForm" name="contactForm">
    
              <div class="row">
                <div class="col-md-6 form-group mb-5">
                  <label for="" class="col-form-label">Nom *</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Votre nom ">
                </div>
                <div class="col-md-6 form-group mb-5">
                  <label for="" class="col-form-label">Email *</label>
                  <input type="text" class="form-control" name="email" id="email"  placeholder="Votre email">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-5">
                  <label for="" class="col-form-label">Telephone</label>
                  <input type="text" class="form-control" name="phone" id="phone"  placeholder="Votre numero  #">
                </div>
                <div class="col-md-6 form-group mb-5">
                  <label for="" class="col-form-label">Sujet</label>
                  <input type="text" class="form-control" name="company" id="company"  placeholder="Le sujet">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group mb-5">
                  <label for="message" class="col-form-label">Message *</label>
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

            <div id="form-message-warning mt-4"></div> 
            <div id="form-message-success">
              Your message was sent, thank you!
            </div>

          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info">
            <h3>Contact Informations</h3>
            <p class="mb-5">Nous somme jeunes etudiant cergy paris université</p>
            <ul class="list-unstyled">
              <p>Université Saint Martin , Cergy Paris France</p>
              <h4>Nos contacts</h4>
              <li>
                <span class="text"><strong>Mebarki Mohammed</strong>  : +(33)7 44 15 54 98</span>
                <span class="text">kenzimebarki2@gmail.com</span>
                <span class="text"><strong>Maibeche Louisa</strong> : +(33)7 44 1554 98</span>
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
    

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>

  </body>
</html>