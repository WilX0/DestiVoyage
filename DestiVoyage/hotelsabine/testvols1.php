
<?php

session_start();

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

    if (isset($data['data'][0]['detailedName'])) {
        return $data['data'][0]['detailedName'];
    } else {
        return null;
    }
}

function getFlightDetails($departure,$destination,$date,$nbradulte,$class) {
    $result = '<div class="flight-list">';
    global $apiKey;
    // Construire l'URL de la requête
    // $url = "$baseUrl/v1/flight-offers?originLocationCode=$departure&destinationLocationCode=$destination&departureDate=$date";
    $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode='.$departure.'&destinationLocationCode='.$destination.'&departureDate='.$date.'&adults='.$nbradulte.'&travelClass='.$class;
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
    <?php if (isset($data['data'])): ?>
        <?php foreach ($data['data'] as $flight): ?>
            <?php foreach ($flight['itineraries'] as $itinerary): ?>
                <?php foreach ($itinerary['segments'] as $segment): ?>
                    <?php 
                        $duree = new DateInterval($segment['duration']);
                        $duree_formatee = $duree->format('%hh%i');
                    ?>
                    <div class="centered-div bloc" style="margin-top: 20px;">
                        <div class="row espace" style="margin-top:5%;">
                            <div class="row claire">
                                <div class="col-md-5">
                                    <h1>Départ:</h1>
                                </div>
                                <div class="col-md-7">
                                    <p>Duree :<?=$duree_formatee?></p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <p><?= $data['dictionaries']['carriers'][$segment['carrierCode']] ?></p>
                            </div>

                            <div class="col-md-2">
                                <p class="mb-0"><?= $segment['departure']['at'] ?></p>
                                <span><?= $segment['departure']['iataCode']." ".$_SESSION['depart'] ?></span>
                            </div>

                            <div class="col-md-5">
                                <p>
                                    <span class="jesuisennoir"><?= $segment['duration'] ?></span>
                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#1995ad" d="M20.56 3.91c.59.59.59 1.54 0 2.12l-3.89 3.89l2.12 9.19l-1.41 1.42l-3.88-7.43L9.6 17l.36 2.47l-1.07 1.06l-1.76-3.18l-3.19-1.77L5 14.5l2.5.37L11.37 11L3.94 7.09l1.42-1.41l9.19 2.12l3.89-3.89c.56-.58 1.56-.56 2.12 0Z"/></svg></span>
                                    <span class="jesuisennoir">aller direct</span>
                                </p>
                            </div>

                            <div class="col-md-2">
                                <p class="mb-0"><?= $segment['arrival']['at'] ?></p>
                                <span><?= $segment['arrival']['iataCode']." ".$_SESSION['arr']  ?></span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 space">
                        <div class="right-side">
                            <form class="jesuisuneform" action="">
                                <h1>Prix:</h1>
                                <p><?= $flight['price']['total'] . ' ' . $flight['price']['currency'] ?></p>
                                <input class="btnselect" type="button" value="Sélectionner">
                            </form>
                        </div>
                    </div>
                    </div>

                    
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun vol disponible pour ces informations.</p>
    <?php endif; ?>
</div>





<!-- // if (isset($data['data'])) {
    

    
//     // echo $url; 
//     foreach ($data['data'] as $flight) {
//         foreach ($flight['itineraries'] as $itinerary) {
//           foreach ($itinerary['segments'] as $segment) {
//         // echo '<div class="flight-item">';
//         // echo '<h2>' . $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] . ' to ' . $flight['itineraries'][0]['segments'][count($flight['itineraries'][0]['segments']) - 1]['arrival']['iataCode'] . '</h2>';
//         // echo '<p>Compagnie aérienne: ' . $flight['itineraries'][0]['segments'][0]['carrierCode'] . '</p>';
//         // // echo '<p>Durée du vol: ' . $flight['itineraries'][0]['duration'] . '</p>';
//         // $duree = new DateInterval($segment['duration']);
//         $result .= '<div class="flight-list">';
//                         $result.='<h1>'.$_SESSION['depart'].''.$_SESSION['arr'].'</h1>';
//                         $result .= '<h2>' . $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] . ' to ' . $flight['itineraries'][0]['segments'][count($flight['itineraries'][0]['segments']) - 1]['arrival']['iataCode'] . '</h2>';
//                         $result .= '<p>Compagnie aérienne: ' . $flight['itineraries'][0]['segments'][0]['carrierCode'] . '</p>';
//                         $duree = new DateInterval($segment['duration']);
//                         $duree_formatee = $duree->format('%hh%i');
//                         $result .= '<p>Durée du vol: ' . $duree_formatee. '</p>';
//                         $result .= '<p>Prix: ' . $flight['price']['total'] . ' ' . $flight['price']['currency'] . '</p>';
//                         $compagnie_aerienne = $data['dictionaries']['carriers'][$segment['carrierCode']] . "\n";
//                         $result .= "<p>Compagnie aérienne : ". $compagnie_aerienne ."</p>";
//                         $result .= '</div>';
//         // $duree_formatee = $duree->format('%hh%i');
//         // echo '<p>Durée du vol: ' . $duree_formatee. '</p>';

//         // echo '<p>Prix: ' . $flight['price']['total'] . ' ' . $flight['price']['currency'] . '</p>';
//         // $compagnie_aerienne = $data['dictionaries']['carriers'][$segment['carrierCode']] . "\n";
//         // echo "<p>compagnie aerienne : ". $compagnie_aerienne ."<p>";
//         // echo '</div>';
//     }
// }
//     }
//     $result .= '</div>';
//             //echo $result;
//     // echo '</div>';
// } else {
//     echo "Aucun vol disponible pour ces informations.";
// } -->
<?php
echo '<div id="result-container" class="flight-list">' . $result . '</div>';
    // Fermer la session cURL
    curl_close($ch);
}
// getFlightDetails($departure, $destination, $date,$nbradulte,$class);
}
// Exemple d'utilisation de la fonction getFlightDetails

?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vols</title>
</head>
<body>
    <form action="" method="GET">
        <input type="text" id="depart" name="dep" required>
        <div id="sugggestions"></div>
        <input type="text" id="dest" name="dest" required>
        <div id="siggestions"></div>
        <input type="date" id="dat" name="date" required >
        <input type="submit" name="search" id="sub">
    </form>
    <script src="vols.js"></script>
</body>
</html> -->