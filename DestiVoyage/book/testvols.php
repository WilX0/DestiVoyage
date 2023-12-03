
<?php

session_start();

$clientId = "nGINyrag4R3hhje0nCS2BqlAhvHR5nL4"; // Remplacez par votre Client ID
$clientSecret = "noVlxZGbluLOSQes"; // Remplacez par votre Client Secret
$apiUrl = "https://test.api.amadeus.com/v1/security/oauth2/token";
$accessToken = null;

function getAccessToken($clientId, $clientSecret, $apiUrl)
{
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

function refreshTokenIfNeeded($clientId, $clientSecret, $apiUrl)
{
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
    $nbradulte = $_GET['nombrepersonne'];
    $class = $_GET['class'];

    // Clés d'API
    $apiKey = $_SESSION['accessToken'];
    echo $apiKey;
    // URL de base de l'API
    // $baseUrl = "https://test.api.amadeus.com";

    // Fonction pour récupérer les détails du vol

    function getFlightDetails($departure, $destination, $date, $nbradulte, $class)
    {
        $result = '<div class="flight-list">';
        global $apiKey;
        // Construire l'URL de la requête
        // $url = "$baseUrl/v1/flight-offers?originLocationCode=$departure&destinationLocationCode=$destination&departureDate=$date";
        $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode=' . $departure . '&destinationLocationCode=' . $destination . '&departureDate=' . $date . '&adults=' . $nbradulte . '&travelClass=' . $class;
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

        if (isset($data['data'])) {

            echo $url;
            foreach ($data['data'] as $flight) {
                foreach ($flight['itineraries'] as $itinerary) {
                    foreach ($itinerary['segments'] as $segment) {
                        $result .= '<div class="flight-card">';
                        // ... Autres détails du vol ...
                        $result .= '</div>';
                    }
                }
            }
            
            $result .= '</div>';
            //echo $result;
            // echo '</div>';
            return  $result;
        } else {
            echo "Aucun vol disponible pour ces informations.";
        }
        echo '<div id="result-container" class="flight-list">' . $result . '</div>';
        // Fermer la session cURL
        curl_close($ch);
    }
    // getFlightDetails($departure, $destination, $date,$nbradulte,$class);
}

?>
