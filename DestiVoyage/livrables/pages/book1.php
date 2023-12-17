<?php 

/**
 * @file
 * Page de recherche de vols
 * 
 * PHP version 7.3.11
 * 
 * @category Recherche_vols
 * @package  Projet_DWA
 * 
 * @author SFAIHI Sabine
 * @date 1 décembre 2023
 */

include_once("../include/testvols1.php");
// Vérifie si un message d'alerte est passé dans l'URL et l'affiche
if (isset($_GET['alert'])) {
    $alertMessage = urldecode($_GET['alert']);
    echo "<script>alert('" . htmlspecialchars($alertMessage, ENT_QUOTES) . "');</script>";
    echo "<script>history.replaceState(null, null, window.location.pathname);</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/book.css">
    <link rel="stylesheet" href="../Style/vols.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/stylenav.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../Style/stylefooter.css">
    <title>Booking flight</title>
</head>

<body>
    <header>
<?php
// Inclut la barre de navigation en fonction de la connexion
    if (isset($_SESSION["login"])) {
            include("../include/navlogin.php");
            
            
        } else {
            header("Location: connexion.php");
        }
        ?>
    </header>
    <!-- Formulaire de réservation -->
    <div id="booking" class="section">
        <div class="section-center">
            <div class="container">
                <div class="row">
                    <div class="booking-form">
                        <div class="booking-bg">
                            <div class="form-header">
                                <h2>Faites votre réservation</h2>
                                <p id="shad">Simplifiez votre recherche et préparez-vous à décoller vers de nouvelles destinations.</p>
                            </div>
                        </div>
                         <!-- Formulaire de recherche de vols -->
                        <form id="flightForm" method="get">
                            <div class="form-group">
                                <div class="form-checkbox">

                                    <input type="radio" id="allersimple" name="flight-type" checked onclick="toggleReturnDate()">
                                    <label for="allersimple">Aller Simple</label>


                                    <input type="radio" id="allerretour" name="flight-type" onclick="toggleReturnDate()">
                                    <label for="allerretour">Aller retour</label>
<!-- Sélection des aéroports de départ et d'arrivée -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Aéroport de départ</span>
                                                <input class="form-control" type="text" 
                                                    placeholder="Pays/Ville de départ" id="userLocation" name="userLocation" value="<?php echo isset($_GET['userLocation']) ? $_GET['userLocation'] : ''; ?>" required>
                                                    <ul class="suggestions-list" id="suggestionsList" tabindex="0"></ul>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Aéroport d'arrivée</span>
                                                <input class="form-control" type="text" name="arrivee"
                                                    placeholder="Pays/Ville d'arrivée" id="arr" value="<?php echo isset($_GET['arrivee']) ? $_GET['arrivee'] : ''; ?>" required>
                                                    <ul class="suggestions-list" id="suggestionsList2" tabindex="0"></ul>
                                            </div>
                                        </div>
                                        <div id="error-message-container" style="display: none; color: red;"></div>
                                    </div>
                                     <!-- Autres détails du vol -->
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
                                     <!-- Sélection des dates de départ et de retour -->
                                    <div class=row>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="form-label">Date de départ</span>
                                                <input id="datedepart" class="form-control" type="Date" name="datedepart" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="returnDateDiv">
                                            <div class="form-group">
                                                <span class="form-label">Date de retour</span>
                                                <input id="dateret" class="form-control" type="Date" name="dateretour">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <input type="submit" class="button" name="search" value="Recherche">
                                </div>
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="result-container" class="flight-list">
    </div>
   

<?php
// Vérifie si la requête est une demande de recherche de vols
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) :
    // Récupère les données du formulaire
    $userlocation = $_GET['userLocation'];
    $arrive = $_GET['arrivee'];
    $date = $_GET['datedepart'];
    $nbradulte = $_GET['nombrepersonne'];

    $class = $_GET['class'];
    // Divise la chaîne de l'aéroport de départ en ville et code IATA
    list($ville, $codeiata) = explode(', ', $userlocation);

    // Divise la chaîne de l'aéroport d'arrivée en ville et code IATA
    list($ville2,$codeiata2) = explode(', ',$arrive);
    // $destinationCity = getCityName($destination);

    // Initialise les sessions pour la ville de départ et la ville d'arrivée
    $_SESSION['depart']=$ville;
    $_SESSION['arr']=$ville2;


    // if (!$departureCity && !$destinationCity) {
    //     echo "Impossible de trouver les noms de villes pour les codes aéroportuaires spécifiés.";
    //     exit;
    // }
    ?>
     <!-- Conteneur principal -->
    <div class="container">
<!-- Conteneur des résultats de la recherche -->
                <div id="result-container" class="flight-list">
                    <?php
                     // Vérifie si la date de retour est spécifiée dans la requête
                    if (isset($_GET['dateretour']) && !empty($_GET['dateretour'])) {
                        ?>
                        <!-- Affiche les détails des vols pour un aller-retour -->
                        <?php
                        getFlightDetails($codeiata, $codeiata2, $_GET['dateretour'], $nbradulte, $class, 'aller-retour');
                    }else{
                         // Affiche les détails des vols pour un aller simple
                        echo '<h2 class="text-center text-white">VOLS ALLER SIMPLE</h2>';
                        getFlightDetailsaller($codeiata, $codeiata2, $date, $nbradulte, $class);
                    }
                    ?>
                </div>

               
     </div>
 <?php endif; ?>





    
    <script>
        // Fonction pour basculer l'affichage de la date de retour en fonction du type de vol
        toggleReturnDate();
        var allerRetourRadio2 = document.getElementById("allerretour");
function toggleReturnDate() {
    var returnDateDiv = document.getElementById("returnDateDiv");
    var allerRetourRadio = document.getElementById("allerretour");
    

    if (allerRetourRadio.checked) {
        returnDateDiv.style.display = "block";
        returnDateDiv.required = true;
    } else {
        returnDateDiv.required = true;
        returnDateDiv.style.display = "none";
    }
}
 // Fonction pour obtenir la date actuelle au format YYYY-MM-DD
    function getCurrentDate() {
                var today = new Date();
                var year = today.getFullYear();
                var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Ajoute un zéro devant les mois de 1 à 9
                var day = today.getDate().toString().padStart(2, '0'); // Ajoute un zéro devant les jours de 1 à 9
                return year + '-' + month + '-' + day;
            }
            // Initialise la date de départ avec la date actuelle
 document.getElementById("datedepart").valueAsDate = new Date(getCurrentDate());
var dqt1=document.getElementById("datedepart");
var dqt2=document.getElementById("dateret");
var form =document.getElementById("flightForm");
var userLocation = document.getElementById("userLocation");
var arrivee = document.getElementById("arr");

// Fonction de vérification du formulaire avant la soumission
function verifform(event){
    // Vérifie si l'aéroport de départ et d'arrivée sont identiques
    if(userLocation.value===arrivee.value){
        event.preventDefault();
        alert("erreur vous avez choisi un depart et une arrivee identique");
    }
  // Expression régulière pour le format d'aéroport (ex: 'peu importe ici, ABC')
    const airportFormatRegex = /^[A-Za-z\s]+,\s[A-Za-z]+$/;

     // Vérifie si le format de l'aéroport de départ est correct
if (!airportFormatRegex.test(userLocation.value)) {
    alert("Le format de l'aéroport de départ doit être suivi, par exemple, 'peu importe ici, ABC'.");
    event.preventDefault(); // Empêche l'envoi du formulaire
    return;
}

// Vérifie si le format de l'aéroport d'arrivée est correct
if (!airportFormatRegex.test(arrivee.value)) {
    alert("Le format de l'aéroport de départ doit être suivi, par exemple, 'peu importe ici, ABC 33'.");
    event.preventDefault(); // Empêche l'envoi du formulaire
    return;
}
var currentDate = new Date();
    var selectedDate = new Date(dqt1.value);
    var selecteddate2=new Date(dqt2.value);

    // Vérification si la date de départ est inférieure à la date actuelle
    if (selectedDate < currentDate) {
        console.log("currentDate:", currentDate);
console.log("selectedDate:", selectedDate);
console.log("selectedDate2:", selectedDate2);

        alert("La date de départ ne peut pas être antérieure à la date d'aujourd'hui.");
        event.preventDefault(); // Empêche l'envoi du formulaire
        return;
    }
    if(selectedDate>=selecteddate2 && allerRetourRadio2.checked){
        event.preventDefault();
        alert("vouc ne pouvez pas choisir une meme date d'aller et retour ou la date de retour doit etre sup a la date d'aller");
    }
}
// Ajoute un écouteur d'événements pour la soumission du formulaire
form.addEventListener("submit",verifform);
 // Script jQuery pour la soumission du formulaire en AJAX
$(document).ready(function() {
        $('.jesuisuneform').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'GET',
                url: 'insert_fav.php', 
                data: formData,
                success: function(response) {
                    alert(response); 
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
    


</script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="../scripts/ville.js"></script>
    <!-- <script src="assets/js/book.js"></script> -->
    
</body>

</html>