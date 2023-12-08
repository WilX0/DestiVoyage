
<?php
session_start();
?>


<!DOCTYPE html>

    <html lang="fr">

        <head>
            <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Recherche d'Hôtel</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="assets/css/stylenav.css">
                            <link rel="stylesheet" href="hotel.css">
                                <link rel="stylesheet" href="assets/css/stylefooter.css">
                                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
                                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                                <style>
        .hotel-container {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
        }

        .hotel-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .map-container {
            width: 100%;
            height: 300px;
            margin-bottom: 20px;
        }

        .activity-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .activity {
            width: 300px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
        }

        .activity img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        #cityResults {
        list-style-type: none;
        margin: 0;
        padding: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    #cityResults li {
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #cityResults li:hover {
        background-color: #f9f9f9;
    }
    </style>
                                </head>

                                <body>
                                    <?php include("navlogin.php"); ?>
                                    <div class="search-container container">
                                        <h1 class="mb-4 text-white">Recherche de destination</h1>
                                        <form action="" method="post">
                                        <div class="search">
                                            <div class="mb-3">
                                                <label for="cityCode" class="form-label">Code de la ville:</label>
                                                <input type="text" id="keyworde2" name="cityCode" placeholder="Entrez une destination" required class="form-control">
                                                <ul id="cityResults" class="list-group"></ul>
                                            </div>

                                            
                                            <input type="submit" id="searchButton" value="Rechercher">
                                        </div>

                                    </div>
                                    </form>
                                    <div id="hotelList">
                                        <?php
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
                                    
                                        function getActivities($latitude, $longitude, $accessToken) {
                                            $activitiesUrl = "https://test.api.amadeus.com/v1/shopping/activities?latitude=$latitude&longitude=$longitude&radius=5";
                                            
                                            $ch = curl_init($activitiesUrl);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $accessToken"));
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                            $activitiesResponse = curl_exec($ch);
                                            $activitiesData = json_decode($activitiesResponse, true);
                                    
                                            // Fermer la session cURL
                                            curl_close($ch);
                                    
                                            return $activitiesData;
                                        }
                                    
                                        refreshTokenIfNeeded($clientId, $clientSecret, $apiUrl);
                                        $accessToken = $_SESSION['accessToken'];
                                        function extractIATACode($input) {
                                            $pattern = '/.*,\s*([A-Z]+),\s*.*/';
                                            preg_match($pattern, $input, $matches);
                                        
                                            if (isset($matches[1])) {
                                                return $matches[1];
                                            }
                                        
                                            return null;
                                        }
                                    
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            // Récupérer le code de la ville depuis le formulaire
                                            $apiKey = $_SESSION['accessToken'];
                                            if(isset($_POST['cityCode'])){
                                                $cityCodeInput = $_POST['cityCode'];
                                                $cityCode = extractIATACode($cityCodeInput);
                                            }
                                            $pageNumber = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                    
                                    // Définir le nombre d'éléments par page
                                            $itemsPerPage = 10;
                                    
                                    // Calculer l'offset en fonction du numéro de page
                                            $offset = ($pageNumber - 1) * $itemsPerPage;
                                            
                                            // Effectuer la requête API pour les villes recommandées
                                            $url = "https://test.api.amadeus.com/v1/reference-data/recommended-locations?cityCodes=$cityCode";
                                            $ch = curl_init($url);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $apiKey"));
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                            $response = curl_exec($ch);
                                            $apiData = json_decode($response, true);
                                    
                                            // Afficher les résultats
                                            if (isset($apiData['data']) && is_array($apiData['data'])) {
                                                echo '<h1>Résultats :</h1>';
                                                echo '<ul>';
                                                
                                                foreach ($apiData['data'] as $location) {
                                                    $latitude = $location['geoCode']['latitude'];
                                                    $longitude = $location['geoCode']['longitude'];
                                    
                                                   
                                                    $activitiesData = getActivities($latitude, $longitude, $accessToken);
                                                    
                    echo '<div class="hotel-container">';
                                                    echo '<li>';
                                                    echo '<h2 class="hotel-title">Ville : ' . $location['name'] . '</h2><br>';
                                                    echo 'Code IATA : ' . $location['iataCode'] . '<br>';
                                                    // echo 'Latitude : ' . $latitude . '<br>';
                                                    // echo 'Longitude : ' . $longitude . '<br>';
                                                    // echo 'Rélevance : ' . $location['relevance'] . '<br>';
                                                    echo '<div id="map_' . $location['iataCode'] . '" class="map map-container" style="width: 100%; height: 250px;"></div>';
                                    
                                                    
                                                    echo '<h3>Activités :</h3>';
                                                    echo '<div class="activity-container">';
                                                    echo '<ul>';
                                                    if (isset($activitiesData['data']) && is_array($activitiesData['data'])) {
                                                    foreach ($activitiesData['data'] as $activity) {
                                                        echo '<div class="activity">';
                                                        echo '<li>';
                                                        echo 'Nom de l\'activité : ' . $activity['name'] . '<br>';
                                                        echo 'Description : ';
                                                        echo isset($activity['description']) ? strip_tags(str_replace(["\r", "\n"], ' ', $activity['description'])) . '<br>' : 'Aucune description disponible.<br>';
                                                        
                                                        // echo 'Note : ' . $activity['rating'] . '<br>';
                                                        if (isset($activity['rating'])) {
                                                            echo 'Note : ' . $activity['rating'] . '<br>';
                                                        } else {
                                                            echo 'Note : Non disponible<br>';
                                                        }
                                                        
                                                        echo '<p>Photos :</p>';
                                                        if (!empty($activity['pictures'])) {
                                                            echo '<ul>';
                                                            $maxPhotos = 3; // Limite maximale
                                                            $displayedPhotos = 0;
                                                            foreach ($activity['pictures'] as $picture) {
                                                                if ($displayedPhotos < $maxPhotos) {
                                                                    echo '<li><img src="' . $picture . '" alt="Photo de l\'activité" width="300"></li>';
                                                                    $displayedPhotos++;
                                                                } else {
                                                                    break; // Sortir de la boucle une fois atteint le nombre maximum de photos
                                                                }
                                                                // echo '<li><img src="' . $picture . '" alt="Photo de l\'activité" width="300"></li>';
                                                            }
                                                            echo '</ul>';
                                                        } else {
                                                            echo '<p>Aucune photo disponible.</p>';
                                                        }
                                    
                                                        // Afficher le lien de réservation
                                                        if (isset($activity['bookingLink'])) {
                                                            echo '<p>Booking Link : <a href="' . $activity['bookingLink'] . '" target="_blank">Réserver</a></p>';
                                                        }
                                    
                                                        echo '</li>';
                                                        echo '</div>';
                                                    }
                                                }   else{
                                                    echo '<p>Aucune activité trouvée.</p>';
                                                    }
                                    
                                                    echo '</ul>';
                                                    
                                                    echo '</div>'; // .activity-container
                                                    echo '</div>';
                                    
                                                    // echo '</div>';
                                                    echo '<script>';
                                                    echo 'var mapElement_' . $location['iataCode'] . ' = document.getElementById("map_' . $location['iataCode'] . '");';
                                                    echo 'var map_' . $location['iataCode'] . ' = L.map(mapElement_' . $location['iataCode'] . ').setView([' . $latitude . ', ' . $longitude . '], 13);';
                                                    
                                                    echo 'L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "© OpenStreetMap contributors" }).addTo(map_' . $location['iataCode'] . ');';
                                                    echo 'L.marker([' . $latitude . ', ' . $longitude . ']).addTo(map_' . $location['iataCode'] . ').bindPopup("' . $location['name'] . '");';
                                                    echo '</script>';
                                                    echo '</li>';
                                                }
                                                echo '</ul>';
                                            } else {
                                                echo '<p>Aucun résultat trouvé.</p>';
                                            }
                                        }
                                        ?>

                                    </div>

                                    <?php include("lefooter.php"); ?>
                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
                                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                                    <script src="ville2.js"></script>
                                    
                                </body>

                            </html>