<?php include_once("testvols.php");?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/book.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Booking flight</title>
</head>

<body>
    <div id="booking" class="section">
        <div class="section-center">
            <div class="container">
                <div class="row">
                    <div class="booking-form">
                        <div class="booking-bg">
                            <div class="form-header">
                                <h2>Faites votre réservation</h2>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate laboriosam
                                    numquam at</p>
                            </div>
                        </div>
                        <form id="flightForm" method="get">
                            <div class="form-group">
                                <div class="form-checkbox">

                                    <input type="radio" id="allersimple" name="flight-type" checked onclick="toggleReturnDate()">
                                    <label for="allersimple">Aller Simple</label>


                                    <input type="radio" id="allerretour" name="flight-type" onclick="toggleReturnDate()">
                                    <label for="allerretour">Aller retour</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Aéroport de départ</span>
                                                <input class="form-control" type="text" 
                                                    placeholder="Pays/Ville de départ" id="userLocation" name="userLocation" required>
                                                    <ul class="suggestions-list" id="suggestionsList"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Aéroport d'arrivée</span>
                                                <input class="form-control" type="text" name="arrivee"
                                                    placeholder="Pays/Ville d'arrivée" id="arr" required>
                                                    <ul class="suggestions-list" id="suggestionsList2"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Nombre de personnes</span>
                                                <input class="form-control" type="number" name="nombrepersonne"
                                                    value="1" min="1" max="10" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Classe</span>
                                                <select class="form-control" name="class" required>
                                                    <option value selected hidden>Selectionner la classe</option>
                                                    <option>FIRST</option>
                                                    <option>BUSINESS</option>
                                                    <option>ECONOMY</option>
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=row>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Date de départ</span>
                                                <input class="form-control" type="Date" name="datedepart" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="returnDateDiv" style="display:none;">
                                            <!-- <div class="form-group">
                                                <span class="form-label">Date de retour</span>
                                                <input class="form-control" type="Date" name="dateretour" required>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <input type="submit" class="button" name="search" value="Recherche">
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="result-container" class="flight-list">
    <!-- Les résultats de la recherche seront affichés ici -->
    </div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) :
    $departure = $_GET['userLocation'];
    $destination = $_GET['arrivee'];
    $date = $_GET['datedepart'];
    $nbradulte = $_GET['nombrepersonne'];
    $class = $_GET['class'];
    ?>
    // Appeler la fonction getFlightDetails pour afficher les résultats
    <div id="result-container" class="flight-list">
   <?php getFlightDetails($departure, $destination, $date, $nbradulte, $class);?>
    <div>
    <?php endif; ?>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
    <script src="ville.js"></script>
    <script src="assets/js/book.js"></script>
</body>

</html>