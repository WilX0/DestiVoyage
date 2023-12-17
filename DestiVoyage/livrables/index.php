<?php

/**
 * @file
 * Gestion des cookies et configuration de la page d'accueil.
 * @author SFAIHI Sabine
 * @date 5 décembre 2023
 * @link https://destivoyage.alwaysdata.net/
 */

// Vérifiez si l'utilisateur a déjà accepté les cookies en vérifiant la présence de la valeur du cookie si il est a true alors on le laisse
if (isset($_COOKIE['cookies_accepted'])) {
    $cookiesAccepted = ($_COOKIE['cookies_accepted'] === 'true');
    $cookiesRefused = ($_COOKIE['cookies_accepted'] === 'false');
} else {
    $cookiesAccepted = false;
    $cookiesRefused = false;
}
// Enregistre la première visite si les cookies sont acceptés.
if ($cookiesAccepted && !isset($_COOKIE['first_visit'])) {
    setcookie('first_visit', date('Y-m-d H:i:s'), time()+ (365 * 24 * 60 * 60), '/', "destivoyage.alwaysdata.net");
}

// Traite les actions liées aux cookies depuis la requête GET.
if (isset($_GET['accept'])) {
    if ($_GET['accept'] === 'yes') {
        setcookie('cookies_accepted', 'true',time() + (365 * 24 * 60 * 60), '/',"destivoyage.alwaysdata.net");
        $cookiesAccepted = true;
        header("Location: index.php");
        exit;
    } elseif ($_GET['accept'] === 'no') {
        setcookie('cookies_accepted', 'false',time() + (365 * 24 * 60 * 60), '/', "destivoyage.alwaysdata.net");
        $cookiesRefused = true;
        header("Location: index.php");
        exit;
    }
}
?>

<?php

/**
 * Début de la session et configuration de la page HTML.
 */

session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="date" content="2023-11-17T11:39:26+0100" />
    <meta name="description" content="Explorez DestiVoyage, Votre source de Voyage, nous offrons les meilleurs Vols et Les meilleurs hotels ainsi qu'une possibilite de vous renseigner sur la meteo de votre prochaine destination, Creez votre propre souvenir !" />
    <meta name="robots" content="index, follow" />
    <meta name="google-site-verification" content="QLaP-njUyG74YVAW3vY-RE2JFe95u_PgsY-1AkMKJdc" />
    <title>DestiVoyage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="Style/stylenav.css">
    <link rel="stylesheet" href="Style/stylefooter.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>

<body>
    <header>
        <?php
         // Inclusion de la barre de navigation en fonction de l'état de connexion de l'utilisateur
        if (isset($_SESSION["login"])) {
            include("include/navlogin.php");
        } else {
            include("include/navnotlogin.php");
        }
        ?>
        <div id="carouselExampleCaptions" class="mon_carousel carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
            <div class="carousel-item active c-item">
                    <img src="images/empire-view-nyc.jpg" class="imgcarousel w-100 c-img" alt="3eme image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">We Love You To Tokyo And Back !</p>
                        <a href="vol" class="mybtns btncarousel btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/travel2.jpg" class="imgcarousel w-100 c-img" alt="myalgeria picture">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">L'aventure en vaut la peine !</p>
                        <a href="vol" class="mybtns btncarousel btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/smallparis.jpg" class="imgcarousel w-100 c-img " alt="image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">Les bonnes choses arrivent à ceux qui réservent des vols !</p>
                        <a href="vol" class="mybtns btncarousel btn btn-primary">Book Now</a>
                    </div>
                </div>
               
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>
    <!-- Contenu principal de la page -->
    <main class="container-fluid">
        <!-- Section 1 : Présentation des catégories -->
        <section class="partie1">
            <div class="row">
                <div class="col">
                    <h2 class="text-center" id="textdecouverte">Nous mettons à votre disposition</h2>
                </div>
            </div>
            <div class="row catwrapper">
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/mapppp.jpg" alt="Destination">
                    <div class="info">
                        <h5>Destinations</h5>
                        <p>Partagez votre dernière destination ou celle de vos rêves pour recevoir  une variéte de destinations similaires, et laissez-vous séduire par des activités passionnantes</p>
                        <a href="destination" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/mykooonos.jpg" alt="Hotel">
                    <div class="info">
                        <h5>Les meilleurs hotels</h5>
                        <p>Découvrez les joyaux hôteliers d'une ville en un clin d'œil</p>
                        <a href="hotel" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/planeee.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="vol" class="btn">Click</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section 2 : Carte météo et informations -->
        <section class="row partie2">
        <div id="map" class="mx-auto" style="width: 100%; height: 250px;"></div>
            <div class="bg"><img src="images/smallweather.jpg" alt=""></div>
            <div class="weathercard card">
                <div class="weatherpartie container">
                    <div class="ml-2 cloud Back front">
                        <img id="imgmeteo" src="" alt="meteo">
                        <span class="left-back"></span>
                        <span class="right-back"></span>
                    </div>
                </div>
                
                <div class="card-header">
                    <span id="ville"></span>
                    <span id="country"></span>
                    <span id="description" class="d-inline-block"></span>
                    <span id=date class="d-inline-block"></span>
                </div>

                <span id="temp1" class="temp"></span>

                <div class="temp-scale">
                    <span id="changer">Changer</span>
                </div>
                
            </div>
            
        </section>
        
    </main>
    <?php 
    // Affiche la bannière des cookies seulement si le cookie n'est pas défini ou s'il est défini à false
    if (!isset($_COOKIE['cookies_accepted'])) {
    ?>
    <div class="cookie-banner">
        <p>Acceptez-vous l'utilisation de cookies sur ce site?<a href='?accept=yes'>Accepter</a></p>
        <button class="close">&times;</button>
    </div>
    <?php
    }
    ?>
    </div>
    <?php
    include("include/lefooter.php");
    ?>
    <!-- Affichage d'un message d'activation de compte, le cas échéant -->
    <?php
    if (isset($_SESSION['activation_message'])) {
        echo "<script>alert('" . $_SESSION['activation_message'] . "');</script>";
        unset($_SESSION['activation_message']); // Supprimez le message de la session après l'avoir affiché
    }
    ?>
    <!-- Scripts JavaScript -->
    <script src="scripts/apimeteobis.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YQMHXQ8513"></script>

     <!-- Script de suivi Matomo -->
<script>
    $('.close').click(function(e) {
  $('.cookie-banner').fadeOut(); 
});
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-YQMHXQ8513');
</script>
    <script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://kenzimebarki20.matomo.cloud/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '2']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src='//cdn.matomo.cloud/kenzimebarki20.matomo.cloud/matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
    <!-- Liens vers les fichiers JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>