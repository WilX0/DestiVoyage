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
    <?php include("lanavbar.php");?>
    <div class="search-container">
        <h1 class="mb-4 text-white">Recherche d'Hôtel</h1>
        <label for="cityCode">Code de la ville:
            <input type="text" id="cityCode" name="ville" placeholder="Entrez votre ville" class="mb-3">
            <button type="submit" id="searchButton">Chercher</button>
        </label>

        <button id="theme-light">Thème clair</button>
        <button id="theme-dark">Thème Sombre</button>
    </div>

    <div id="hotelList"></div>
   
    <?php include("lefooter.php");?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="hotel.js"></script>
    <script src="hoteljsapi.js"></script>
</body>
</html>