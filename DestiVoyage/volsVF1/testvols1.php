
<?php
session_start();
include_once('fonction.php');

$clientId = "nGINyrag4R3hhje0nCS2BqlAhvHR5nL4"; // Remplacez par votre Client ID
$clientSecret = "noVlxZGbluLOSQes"; // Remplacez par votre Client Secret
$apiUrl = "https://test.api.amadeus.com/v1/security/oauth2/token";
$accessToken = null;
function getAccessToken($clientId, $clientSecret, $apiUrl) {
    $data = array(
        "grant_type" => "client_credentials",
        "client_id" => $clientId,
        "client_secret" => $clientSecret,
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );

    $context = stream_context_create($options);
    $response = file_get_contents($apiUrl, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['access_token'])) {
            $_SESSION['accessToken'] = $data['access_token'];
            $_SESSION['lastTokenTime'] = time();
            return $data['access_token'];
        }
    }

    return null;
}

function refreshTokenIfNeeded($clientId, $clientSecret, $apiUrl) {
    if (!isset($_SESSION['accessToken']) || !isset($_SESSION['lastTokenTime'])) {
        // La session est vide, générer un nouveau jeton
        getAccessToken($clientId, $clientSecret, $apiUrl);
    } else {
        $currentTime = time();
        $lastTokenTime = $_SESSION['lastTokenTime'];
        if ($currentTime - $lastTokenTime >= 720) { // 720 secondes = 12 minutes
            // Le jeton a expiré, générer un nouveau jeton
            getAccessToken($clientId, $clientSecret, $apiUrl);
        }
    }
}

refreshTokenIfNeeded($clientId, $clientSecret, $apiUrl);
$accessToken = $_SESSION['accessToken'];

// Maintenant, vous pouvez utiliser $accessToken pour faire des requêtes avec le jeton d'accès actualisé.

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    
    $departure = $_GET['userLocation']; 
    $destination = $_GET['arrivee']; 
    $date = $_GET['datedepart']; 
    $nbradulte=$_GET['nombrepersonne'];
    $class=$_GET['class'];

// Clés d'API
$apiKey = $_SESSION['accessToken'];
// echo $apiKey;
// URL de base de l'API
// $baseUrl = "https://test.api.amadeus.com";

// Fonction pour récupérer les détails du vol
function getCityName($iataCode) {
    global $apiKey;
    $url = "https://test.api.amadeus.com/v1/reference-data/locations?subType=AIRPORT,CITY&keyword=$iataCode&page[limit]=1&sort=analytics.travelers.score&view=FULL";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $apiKey"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['data'][0]['address']['cityName'])) {
        return $data['data'][0]['address']['cityName'];
    } else {
        return null;
    }
}


function getFlightDetails($departure,$destination,$date,$nbradulte,$class,$direction) {
    $result = '<div class="flight-list">';
    global $apiKey;

    if (isset($_GET['dateretour']) && !empty($_GET['dateretour'])) {
        $returnDate = '&returnDate=' . $_GET['dateretour'];
    } else {
        $returnDate = '';
    }
    $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode='.$departure.'&destinationLocationCode='.$destination.'&departureDate='.$date.$returnDate.'&adults='.$nbradulte.'&travelClass='.$class.'&nonStop=true&max=100';
    // Construire l'URL de la requête
    // $url = "$baseUrl/v1/flight-offers?originLocationCode=$departure&destinationLocationCode=$destination&departureDate=$date";
    
    // Initialiser une session cURL
    $ch = curl_init($url);

    // Configurer la session cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $apiKey"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Exécuter la session cURL
    $response = curl_exec($ch);

    // Vérifier les erreurs
    if ($response === false) {
        echo "Erreur lors de la requête.";
        echo "Erreur cURL : " . curl_error($ch);
    } else {
        // Traiter la réponse et afficher les détails du vol
        // echo $response;
    }
    $data = json_decode($response, true);
    ?>
<div class="container">
<div class="text-center text-white"><h1>ALLER-RETOUR</h1></div>
    <?php if (isset($data['data'])): ?>
        <?php foreach ($data['data'] as $flight): ?>
    <?php foreach ($flight['itineraries'] as $itinerary): ?>
        <?php foreach ($itinerary['segments'] as $segment): ?>
            <?php 
                   $flightNumber = $segment['number'];
                   $carrierCode = $segment['carrierCode'];
                   $flightIdentifier = $carrierCode . $flightNumber;
                $duree = new DateInterval($segment['duration']);
                $duree_formatee = $duree->format('%hh%i');
            ?>
            <!-- <div class="text-center"><h1>ALLER-RETOUR</h1></div> -->
            <form id="fo" class="jesuisuneform" method="get" action="insert_fav.php">
            <input type="hidden" name="depart" value="<?= $segment['departure']['iataCode']." ".$_SESSION['depart'] ?>">
<input type="hidden" name="arrivee" value="<?= $segment['arrival']['iataCode']." ".$_SESSION['arr'] ?>">
<input type="hidden" name="duree" value="<?= $segment['duration'] ?>">
<input type="hidden" name="prix" value="<?= $flight['price']['total'] ?>">
<input type="hidden" name="type_vol" value="aller-retour">
<input type="hidden" name="vol_id" value="<?= $flightIdentifier ?>">
            <div class="centered-div bloc" style="margin-top: 20px;">
                <div class="row espace" style="margin-top:5%;">
                    <div class="row claire">
                        <div class="col-md-5">
                            <!-- <h1><?= ($direction === 'aller' ? 'Départ' : 'Retour') ?>:</h1> -->
                        </div>
                        <div class="col-md-7">
                            <p>Durée :<?=$duree_formatee?></p>
                        </div>
                    </div>
                    <?php
                        $carrierCode = $segment['carrierCode'];
                        $imageUrl2 = "https://www.skyscanner.net/images/airlines/{$carrierCode}.png";
                        ?>
                    <div class="col-md-3">
                        <p><?= $data['dictionaries']['carriers'][$segment['carrierCode']] ?></p>
                        <img src=<?=$imageUrl2?> alt="">
                    </div>

                    <div class="col-md-2">
                        <?php
                        $dateTimeString = $segment['departure']['at'] ;

                        
                        $dateTime = new DateTime($dateTimeString);
                        
                        // Formater la date dans le format souhaité (par exemple, Y-m-d H:i:s)
                        $newFormattedDate = $dateTime->format('Y-m-d H:i:s');
                        
                        ?>
                        <p class="mb-0"><?= $newFormattedDate ?></p>
                        <span><?= $segment['departure']['iataCode']." ".$_SESSION['depart']?></span>
                    </div>

                    <div class="col-md-5">
                        <p>
                            <span class="jesuisennoir"><?= $segment['duration'] ?></span>
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#1995ad" d="M20.56 3.91c.59.59.59 1.54 0 2.12l-3.89 3.89l2.12 9.19l-1.41 1.42l-3.88-7.43L9.6 17l.36 2.47l-1.07 1.06l-1.76-3.18l-3.19-1.77L5 14.5l2.5.37L11.37 11L3.94 7.09l1.42-1.41l9.19 2.12l3.89-3.89c.56-.58 1.56-.56 2.12 0Z"/></svg></span>
                            <span class="jesuisennoir"><?=$direction?></span>
                        </p>
                    </div>
                    <div class="col-md-2">
                    <?php
                        $dateTimeString2 = $segment['arrival']['at'] ;

                        // Créer un objet DateTime à partir de la chaîne de date
                        $dateTime2 = new DateTime($dateTimeString2);
                        
                        // Formater la date dans le format souhaité (par exemple, Y-m-d H:i:s)
                        $newFormattedDate2 = $dateTime2->format('Y-m-d H:i:s');
                        
                        ?>
                        <input type="hidden" name="classe" value="<?= $class?>">
                        <input type="hidden" name="date_depart" value="<?= $newFormattedDate?>">
                        <input type="hidden" name="date_arrivee" value="<?= $newFormattedDate2 ?>">
                        <p class="mb-0"><?= $newFormattedDate2 ?></p>
                        <span><?= $segment['arrival']['iataCode']." ".$_SESSION['arr']?></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 space">
              
            </div>
            </div>
            <?php 
            $inet=$_SESSION['arr'];
            $_SESSION['arr']=$_SESSION['depart'];
            $_SESSION['depart']=$inet;
            ?>
        <?php endforeach; ?>
        
    <?php endforeach; ?>
    <div class="right-side">
                        <h1 class="text-white">Prix:</h1>
                        <p class="text-white"><?= $flight['price']['total'] . ' ' . $flight['price']['currency'] ?></p>
                        <!-- <input class="btnselect" type="button" value="Sélectionner"> -->
                    <!-- </form> -->
                </div>
    <div class="text-center"> <!-- Ajoutez cette div avec la classe text-center -->
                    <input class="btnselect custom-button" type="submit" value="Sélectionner">
                </div>

      <!-- <input class="btnselect" type="button" value="Sélectionner"> -->
      </form>
      <div class="text-center text-white"><h1>ALLER-RETOUR</h1></div>
<?php endforeach; ?>

    <?php else: ?>
        <p>Aucun vol disponible pour ces informations.</p>
    <?php endif; ?>
</div>


<?php
echo '<div id="result-container" class="flight-list">' . $result . '</div>';
    // Fermer la session cURL
    curl_close($ch);
}
// getFlightDetails($departure, $destination, $date,$nbradulte,$class);
}
// Exemple d'utilisation de la fonction getFlightDetails
function getFlightDetailsaller($departure,$destination,$date,$nbradulte,$class) {
    $result = '<div class="flight-list">';
    global $apiKey;

    $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode='.$departure.'&destinationLocationCode='.$destination.'&departureDate='.$date.'&adults='.$nbradulte.'&travelClass='.$class.'&nonStop=true&max=100';
    // Construire l'URL de la requête
    // $url = "$baseUrl/v1/flight-offers?originLocationCode=$departure&destinationLocationCode=$destination&departureDate=$date";
    
    // Initialiser une session cURL
    $ch = curl_init($url);

    // Configurer la session cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $apiKey"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Exécuter la session cURL
    $response = curl_exec($ch);

    // Vérifier les erreurs
    if ($response === false) {
        echo "Erreur lors de la requête.";
        echo "Erreur cURL : " . curl_error($ch);
    } else {
        // Traiter la réponse et afficher les détails du vol
        // echo $response;
    }
    $data = json_decode($response, true);
    ?>
<div class="container">
    <?php if (!empty($data['data'])): ?>
        <?php foreach ($data['data'] as $flight): ?>
    <?php foreach ($flight['itineraries'] as $itinerary): ?>
        <?php foreach ($itinerary['segments'] as $segment): ?>
            <?php 
                $flightNumber = $segment['number'];
                $carrierCode = $segment['carrierCode'];
                $flightIdentifier = $carrierCode . $flightNumber;
                $duree = new DateInterval($segment['duration']);
                $duree_formatee = $duree->format('%hh%i');
            ?>
            <form class="jesuisuneform" method="get" action="insert_fav.php">
            <input type="hidden" name="depart" value="<?= $segment['departure']['iataCode']." ".$_SESSION['depart'] ?>">
<input type="hidden" name="arrivee" value="<?= $segment['arrival']['iataCode']." ".$_SESSION['arr'] ?>">
<input type="hidden" name="duree" value="<?= $segment['duration'] ?>">
<input type="hidden" name="prix" value="<?= $flight['price']['total'] ?>">
<input type="hidden" name="type_vol" value="aller">
            <div class="centered-div bloc" style="margin-top: 10px;">
                <div class="row espace" style="margin-top:0%;">
                    <div class="row claire">
                        <div class="col-md-7">
                            <p>Durée :<?=$duree_formatee?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php
                        $carrierCode = $segment['carrierCode'];
                        $imageUrl = "https://www.skyscanner.net/images/airlines/{$carrierCode}.png";
                        ?>
                        <p><?= $data['dictionaries']['carriers'][$segment['carrierCode']] ?></p>
                        <img src=<?=$imageUrl?> alt="Compagnie aerienne">
                    </div>

                    <div class="col-md-2">
                    <?php
                    $dateTimeString1 = $segment['departure']['at'] ;

// Créer un objet DateTime à partir de la chaîne de date
$dateTime1 = new DateTime($dateTimeString1);

// Formater la date dans le format souhaité (par exemple, Y-m-d H:i:s)
$newFormattedDate11 = $dateTime1->format('Y-m-d H:i:s');
?>
                        <p class="mb-0"><?= $newFormattedDate11 ?></p>
                        
                        <span><?= $segment['departure']['iataCode']." ".$_SESSION['depart']?></span>
                    </div>

                    <div class="col-md-5">
                        <p>
                            <span class="jesuisennoir"><?= $segment['duration'] ?></span>
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#1995ad" d="M20.56 3.91c.59.59.59 1.54 0 2.12l-3.89 3.89l2.12 9.19l-1.41 1.42l-3.88-7.43L9.6 17l.36 2.47l-1.07 1.06l-1.76-3.18l-3.19-1.77L5 14.5l2.5.37L11.37 11L3.94 7.09l1.42-1.41l9.19 2.12l3.89-3.89c.56-.58 1.56-.56 2.12 0Z"/></svg></span>
                        </p>
                    </div>

                    <div class="col-md-2">
                        <?php
                    $dateTimeString2 = $segment['arrival']['at'] ;

// Créer un objet DateTime à partir de la chaîne de date
$dateTime2 = new DateTime($dateTimeString2);

// Formater la date dans le format souhaité (par exemple, Y-m-d H:i:s)
$newFormattedDate2 = $dateTime2->format('Y-m-d H:i:s');
?>
                        <input type="hidden" name="classe" value="<?= $class?>">
                        <input type="hidden" name="date_depart" value="<?= $newFormattedDate11?>">
                        <input type="hidden" name="date_arrivee" value="<?= $newFormattedDate2 ?>">
                        <p class="mb-0"><?= $newFormattedDate2 ?></p>
                        <span><?= $segment['arrival']['iataCode']." ".$_SESSION['arr']  ?></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 space">
                <div class="right-side">
                <input type="hidden" name="vol_id" value="<?= $flightIdentifier ?>">
                        <h1>Prix:</h1>
                        <p><?= $flight['price']['total'] . ' ' . $flight['price']['currency'] ?></p>
                        <input class="btnselect" type="submit" value="Sélectionner">
                    
                </div>
            </div>
            </div>
            </form>

        <?php endforeach; ?>
    <?php endforeach; ?>
      <!-- <input class="btnselect" type="button" value="Sélectionner">
      </form> -->
<?php endforeach; ?>

    <?php else: ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
        Aucun vol disponible pour ces informations.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
</div>

<?php
echo '<div id="result-container" class="flight-list">' . $result . '</div>';
    // Fermer la session cURL
    curl_close($ch);
}
// getFlightDetails($departure, $destination, $date,$nbradulte,$class);

?>