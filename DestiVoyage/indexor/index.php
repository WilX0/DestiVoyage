<?php
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/stylenav.css">
    <link rel="stylesheet" href="assets/css/stylefooter.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>

<body>
    <header>
        <?php
        if (isset($_SESSION["login"])) {
            include("navlogin.php");
        } else {
            include("navnotlogin.php");
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
                    <img src="images/smallparis.jpg" class="imgcarousel w-100 c-img " alt="image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">Les bonnes choses arrivent à ceux qui réservent des vols !</p>
                        <a href="#" class="mybtns btncarousel btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/empire-view-nyc.jpg" class="imgcarousel w-100 c-img" alt="myalgeria picture">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">L'aventure en vaut la peine !</p>
                        <a href="#" class="mybtns btncarousel btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/travel2.jpg" class="imgcarousel w-100 c-img" alt="3eme image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">We Love You To Tokyo And Back !</p>
                        <a href="#" class="mybtns btncarousel btn btn-primary">Book Now</a>
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
    <main class="container-fluid">
        <section class="partie1">
            <div class="row">
                <div class="col">
                    <h2 class="text-center" id="textdecouverte">Nous mettons à votre disposition</h2>
                </div>
            </div>
            <div class="row catwrapper">
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/mapppp.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="#" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/mykooonos.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="#" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 catcard">
                    <img src="images/planeee.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="#" class="btn">Click</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="row partie2">
            <div class="bg"><img src="images/smallweather.jpg" alt=""></div>
            <div class="weathercard card">
                <div class="weatherpartie container">
                    <!-- <div class="cloud front">
                        <span class="left-front"></span>
                        <span class="right-front"></span>
                    </div> -->
                    <!-- <span class="sun sunshine"></span> -->
                    <!-- <span class="sun"></span> -->
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
            <div id="map"></div>
        </section>
    </main>
    <?php
    include("lefooter.php");
    ?>
    <?php
    if (isset($_SESSION['activation_message'])) {
        echo "<script>alert('" . $_SESSION['activation_message'] . "');</script>";
        unset($_SESSION['activation_message']); // Supprimez le message de la session après l'avoir affiché
    }
    ?>
    <script src="assets/js/apimeteobis.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YQMHXQ8513"></script>
<script>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>