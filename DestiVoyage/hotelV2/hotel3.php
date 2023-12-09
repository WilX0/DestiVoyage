<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'Hôtel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylenav.css">
    <link rel="stylesheet" href="hotel.css">
    <link rel="stylesheet" href="stylefooter.css">
</head>

<body>
    <?php include("lanavbar.php"); ?>
    <div class="search-container container">
        <h1 class="mb-4 text-white">Recherche d'Hôtel</h1>
        <div class="search">
            <div class="mb-3">
                <label for="cityCode" class="form-label">Code de la ville:</label>
                <input type="text" id="cityCode" name="ville" placeholder="Entrez votre ville" class="form-control">
                <ul id="suggestions"></ul>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="adults" class="form-label">Nombre d'adultes:</label>
                    <input type="number" id="adults" name="adults" placeholder="Nombre d'adultes" class="form-control"
                        value=1 min=1>
                </div>

                <div class="col-md-6">
                    <label for="rooms" class="form-label">Nombre de chambres:</label>
                    <input type="number" id="rooms" name="rooms" placeholder="Nombre de chambres" class="form-control"
                        value=1 min=1>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="checkinDate" class="form-label">Date d'arrivée:</label>
                    <input type="date" id="checkinDate" name="checkinDate" class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="checkoutDate" class="form-label">Date de départ:</label>
                    <input type="date" id="checkoutDate" name="checkoutDate" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label for="abo" class="form-label">Abonnement:</label>
                <select id="abo" name="abonnement" class="form-select">
                    <option value="" selected disabled>Sélectionner votre choix</option>
                    <option value="ROOM_ONLY">Room Only</option>
                    <option value="BREAKFAST">Breakfast</option>
                    <option value="HALF_BOARD">Half Board</option>
                    <option value="FULL_BOARD">Full Board</option>
                    <option value="ALL_INCLUSIVE">All inclusive</option>

                </select>
            </div>

            <button type="submit" id="searchButton">Chercher</button>
        </div>

    </div>
    <button id="theme-light">Thème clair</button>
        <button id="theme-dark">Thème Sombre</button>
    <div id="hotelList"></div>

    <?php include("lefooter.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="hotel.js"></script>
    <script src="hoteljsapi.js"></script>

</body>

</html>