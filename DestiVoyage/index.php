<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DestiVoyage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>

    <header>
        <!--////////////////////////////////////////// LA NAVBARRE -->
        <nav class="lanav navbar navbar-expand-lg navbar-dark-5-strong">
            <div class="container">
                <a class="navbar-brand" href="index.php"><img src="images/Logo.png" class="navbar-brand" alt="Logo DestiVoyage" id="idlogo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="#">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Destinations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Contact</a>
                        </li>
                    </ul>
                    <a href="connexion.php"><button type="button" class="btn btn-outline-light">Se connecter</button></a>
                    <a href="inscription.php"><button type="button" class="btn btn-outline-light">S'inscrire</button></a>
                </div>
            </div>
        </nav>
        <!--///////////////////////////////////////////LE CAROUSEL -->
        <div id="carouselExampleCaptions" class="mon_carousel carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active c-item">
                    <img src="images/bgNav-min.jpg" class="imgcarousel w-100 c-img " alt="image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">Les bonnes choses arrivent à ceux qui réservent des vols !</p>
                        <button type="button" class="btncarousel btn btn-primary">Book Now</button>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/myalgeria1-min.jpg" class="imgcarousel w-100 c-img" alt="myalgeria picture">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">L'aventure en vaut la peine !</p>
                        <button type="button" class="btncarousel btn btn-primary">Book Now</button>
                    </div>
                </div>
                <div class="carousel-item c-item">
                    <img src="images/tokyo-min.jpg" class="imgcarousel w-100 c-img" alt="3eme image du carousel">
                    <div class="carousel-caption">
                        <h1 class="DesTitle">DestiVoyage</h1>
                        <p class="paragraphcarousel">We Love You To Tokyo And Back !</p>
                        <button type="button" class="btncarousel btn btn-primary">Book Now</button>
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
    <!-- ///////////////////////////////////////////////////MAIN///////////////////////// -->

    <main class="container-fluid">
        <section class="partie1">
            <div class="row">
                <div class="col">
                    <h2 class="text-center" id="textdecouverte">Nous mettons à votre disposition</h2>
                </div>
            </div>
            <div class="row wrapper">
                <div class="col-sm-12 col-md-6 card">
                    <img src="images/map-min.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="#" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 card">
                    <img src="images/plane-min.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="#" class="btn">Click</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 card">
                    <img src="images/paysage-min.jpg" alt="plane">
                    <div class="info">
                        <h5>Les meilleurs vols</h5>
                        <p>découvrez les meilleurs vols selon vos préférences</p>
                        <a href="book.php" class="btn">Click</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="row partie2">
            <div class="bg"><img src="images/weather-min.jpg" alt=""></div>

        </section>

    </main>
    <!-- /////////////////////////////////////////////// FOOTER -->

    <footer class="footer py-3 my-4" id="footer">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <img src="images/Logo.png" class="navbar-brand" alt="Logo DestiVoyage" id="logofooter">
                </div>
            </div>
        </div>
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="destinations.php" class="tobewhite navfooter nav-link px-2 text-body-secondary">Destinations</a></li>
            <li class="nav-item"><a href="#" class="tobewhite navfooter nav-link px-2 text-body-secondary">Blog</a></li>
            <li class="nav-item"><a href="#" class="tobewhite navfooter nav-link px-2 text-body-secondary">Les Meilleurs Vols</a>
            </li>
            <li class="nav-item"><a href="#" class="tobewhite navfooter nav-link px-2 text-body-secondary">Nous contacter</a>
            </li>
            <li class="nav-item"><a href="#" class="tobewhite navfooter nav-link px-2 text-body-secondary">About</a></li>
        </ul>
        <h4 id="slogan">Découverte,inspiration,voyage</h4>
        <p class="tobewhite text-center text-body-secondary">&copy; 2023 Made by The Crew</p>
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <a href="#"><img src="images/facebook.png" alt="icone facebook"></a>
                    <a href="#"><img src="images/pinterest.png" alt="icone pinterest"></a>
                    <a href="#"><img src="images/instagram.png" alt="icone instagram"></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>