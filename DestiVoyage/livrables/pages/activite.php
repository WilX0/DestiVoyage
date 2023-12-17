
<?php
session_start();
?>


<!DOCTYPE html>

    <html lang="fr">

        <head>
            <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Recommandation de destination</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="../Style/stylenav.css">
                            <link rel="stylesheet" href="../Style/activite.css">
                                <link rel="stylesheet" href="../Style/stylefooter.css">
                                <link rel="icon" href="../favicon.ico" type="image/x-icon">
                                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
                                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                                </head>

                                <body>
                                <?php
    if (isset($_SESSION["login"])) {
        // echo $_SESSION["email"];
            include("../include/navlogin.php");
            
            
        } else {
            header("Location: connexion.php");
        }
        ?>
                                    <div class="search-container container">
                                        <h1 class="mb-4 text-white">Recommandation de destination</h1>
                                        <form method="post" class="search-box">
                                        <div class="search">
                                            <div class="mb-3">
                                                <label for="keyworde2" class="form-label">Entrer votre dernière destination :</label>
                                                <input type="text" id="keyworde2" name="cityCode" placeholder="Entrer une ville" required class="form-control">
                                                <ul id="cityResults" class="list-group"></ul>
                                            </div>

                                            
                                            <input type="submit" id="searchButton" class="btn btn-primary" value="Rechercher">
                                        </div>
                                        </form>
                                    </div>
                                    
                                    <div id="List" class="container">
                                    <ul class="custom-list">
                                        <?php   
                                        $clientId ="XYsRxC6AXvIZ7zEiGh4LjKGGJB2eH3xU";
                                        $clientSecret = "DjenoDscsyCITaP2"; 
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
                                    
                                        $apiKey = $_SESSION['accessToken'];
                                    
                                    
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
                                            
                                    
                                    // Définir le nombre d'éléments par page
                                           
                                    
                                    // Calculer l'offset en fonction du numéro de page
                                       
                                            
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
                                                    echo '<h2 id="titre">Destinations recommandées pour '.$_POST['cityCode'].'</h2>';
                                                echo '<ul>';
                                                
                                                foreach ($apiData['data'] as $location) {
                                                    $latitude = $location['geoCode']['latitude'];
                                                    $longitude = $location['geoCode']['longitude'];
                                    
                                                    $activitiesData = getActivities($latitude, $longitude, $accessToken);
                                                    
                    
                                                    echo '<li>';
                                                    echo '<h1 class="text-center"> ' . $location['name'].",<small>".$location['iataCode'].'</small></h1><br>';
                                                    
                                                  
                                                    echo '<div id="map_' . $location['iataCode'] . '" class="map mx-auto" style="width: 60%; height: 250px;"></div>';
                                                    
                                                    if(isset($activitiesData['meta']['count'])) {
                                                    echo '<h2>Activités  ('.$activitiesData['meta']['count'].')</h2>';
                                                    }
                                                    echo '<ul>';
                                                    if (isset($activitiesData['data']) && is_array($activitiesData['data']) && !empty($activitiesData['data'])) {
                                                    foreach ($activitiesData['data'] as $activity) {
                                                        echo '<div class="activity">';
                                                        echo '<li>';
                                                        echo '<p class="color">Nom de l\'activité :</p> ' . $activity['name'] . '<br>';
                                                        echo '<p class="color">Description :</p> ';
                                                        echo isset($activity['description']) ? strip_tags(str_replace(["\r", "\n"], ' ', $activity['description'])) . '<br>' : 'Aucune description disponible.<br>';
                                                        
                                                        // echo 'Note : ' . $activity['rating'] . '<br>';
                                                        if (isset($activity['rating'])) {
                                                            echo '<p class="color">Note : </p>' . $activity['rating'] . '<br>';
                                                        } else {
                                                            echo '<p class="color">Note :</p> Non disponible<br>';
                                                        }
                                                        
                                                        echo '<p class="color">Photos :</p>';
                                                        if (!empty($activity['pictures'])) {
                                                            echo '<ul id="photos" class="list-unstyled d-flex flex-wrap justify-content-center">';
                                                            $maxPhotos = 3; // Limite maximale
                                                            $displayedPhotos = 0;
                                                            foreach ($activity['pictures'] as $picture) {
                                                                if ($displayedPhotos < $maxPhotos) {
                                                                    echo '<li class="mb-3"><img src="' . $picture . '" alt="Photo de l\'activité" width="300"></li>';
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
                                                            echo '<p>Lien de réservation : <a href="' . $activity['bookingLink'] . '" target="_blank">Réserver</a></p>';
                                                        }
                                    
                                                        echo '</li>';
                                                       
                                                    }
                                                }   else{
                                                    echo '<p>Aucune activité trouvée.</p>';
                                                    }
                                    
                                                    echo '</ul>';
                                        
                                    
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
                                                echo '<h2 class="resultats">Aucun résultat trouvé.</h2>';
                                            }
                                        }
                                        ?>
</ul>
                                    </div>
                                    <?php include("lefooter.php"); ?>
                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
                                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                                    <script src="../scripts/ville45.js"></script>
                                    
                                </body>

                            </html>