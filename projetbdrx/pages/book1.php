<?php 
include_once("testvols1.php");
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
    <link rel="stylesheet" href="assets/css/book.css">
    <link rel="stylesheet" href="assets/css/vols.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/stylenav.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/stylefooter.css">
    <title>Booking flight</title>
</head>

<body>
    <header>
<?php
    if (isset($_SESSION["login"])) {
        // echo $_SESSION["email"];
            include("navlogin.php");
            
            
        } else {
            header("Location: connexion.php");
        }
        ?>
    </header>
    
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
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) :
    $userlocation = $_GET['userLocation'];
    $arrive = $_GET['arrivee'];
    $date = $_GET['datedepart'];
    $nbradulte = $_GET['nombrepersonne'];

    $class = $_GET['class'];
    list($ville, $codeiata) = explode(', ', $userlocation);
    list($ville2,$codeiata2) = explode(', ',$arrive);
    // $destinationCity = getCityName($destination);

    $_SESSION['depart']=$ville;
    $_SESSION['arr']=$ville2;


    // if (!$departureCity && !$destinationCity) {
    //     echo "Impossible de trouver les noms de villes pour les codes aéroportuaires spécifiés.";
    //     exit;
    // }
    ?>
    <div class="container">

                <div id="result-container" class="flight-list">
                    <?php
                    if (isset($_GET['dateretour']) && !empty($_GET['dateretour'])) {
                        ?>
                        
                        <?php
                        getFlightDetails($codeiata, $codeiata2, $_GET['dateretour'], $nbradulte, $class, 'aller-retour');
                    }else{
                        echo '<h2 class="text-center text-white">VOLS ALLER SIMPLE</h2>';
                        getFlightDetailsaller($codeiata, $codeiata2, $date, $nbradulte, $class);
                    }
                    ?>
                </div>

               
     </div>
 <?php endif; ?>





    
    <script>
        
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
    function getCurrentDate() {
                var today = new Date();
                var year = today.getFullYear();
                var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Ajoute un zéro devant les mois de 1 à 9
                var day = today.getDate().toString().padStart(2, '0'); // Ajoute un zéro devant les jours de 1 à 9
                return year + '-' + month + '-' + day;
            }
 document.getElementById("datedepart").valueAsDate = new Date(getCurrentDate());
var dqt1=document.getElementById("datedepart");
var dqt2=document.getElementById("dateret");
var form =document.getElementById("flightForm");
var userLocation = document.getElementById("userLocation");
var arrivee = document.getElementById("arr");
function verifform(event){
    if(userLocation.value===arrivee.value){
        event.preventDefault();
        alert("erreur vous avez choisi un depart et une arrivee identique");
    }
 
    const airportFormatRegex = /^[A-Za-z\s]+,\s[A-Za-z]+$/;

if (!airportFormatRegex.test(userLocation.value)) {
    alert("Le format de l'aéroport de départ doit être suivi, par exemple, 'peu importe ici, ABC'.");
    event.preventDefault(); // Empêche l'envoi du formulaire
    return;
}
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
form.addEventListener("submit",verifform);
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
    <script src="ville.js"></script>
    <!-- <script src="assets/js/book.js"></script> -->
    
</body>

</html>