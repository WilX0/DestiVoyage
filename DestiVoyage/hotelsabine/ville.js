

// Fonction pour obtenir un jeton d'accès
function getAccessToken(clientId, clientSecret, apiUrl) {
    const data = {
        grant_type: "client_credentials",
        client_id: "nGINyrag4R3hhje0nCS2BqlAhvHR5nL4",
        client_secret: "noVlxZGbluLOSQes",
    };

    const xhr = new XMLHttpRequest();
    xhr.open("POST", apiUrl, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.access_token) {
                accessToken = data.access_token;
                console.log(accessToken);
                // Appeler la fonction de recherche de vols après avoir obtenu le jeton d'accès
                // searchhotel();
            }
        }
    };

    xhr.send(new URLSearchParams(data));
}
getAccessToken("nGINyrag4R3hhje0nCS2BqlAhvHR5nL4", "noVlxZGbluLOSQes", "https://test.api.amadeus.com/v1/security/oauth2/token");

// Définissez l'intervalle de rafraîchissement du jeton en millisecondes (12 minutes)
const refreshInterval = 12 * 60 * 1000; // 12 minutes

// Utilisez setInterval pour appeler la fonction de rafraîchissement périodiquement
setInterval(() => {
    getAccessToken("nGINyrag4R3hhje0nCS2BqlAhvHR5nL4", "noVlxZGbluLOSQes", "https://test.api.amadeus.com/v1/security/oauth2/token");
}, refreshInterval);

// Récupérez la référence de l'élément d'entrée (input) où l'utilisateur saisira les mots-clés
// var keywordInput = document.getElementById("userLocation");

// // Récupérez la référence de la liste (ul) où vous afficherez les villes
// var cityList = document.getElementById("suggestionsList");

// Définissez l'URL de l'API Amadeus
var apiUrl = "https://test.api.amadeus.com/v1/reference-data/locations/cities";

// Fonction pour effectuer une recherche de villes à partir d'un mot-clé
function searchCities(keywordInput, cityList) {
    // Récupérez la valeur actuelle de l'élément d'entrée
    var keyword = keywordInput.value;
    if (keyword.length >= 3) {
        // Créez l'URL de la requête avec le mot-clé
        var requestUrl = `${apiUrl}?keyword=${keyword}&max=3&include=AIRPORTS`;

        // Créez une requête XHR
        const xhr = new XMLHttpRequest();
        xhr.open("GET", requestUrl, true);
        xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

        // Configurez le gestionnaire d'événements pour la réponse de la requête
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Effacez la liste précédente des villes
                var response = JSON.parse(xhr.responseText);
                if (Array.isArray(response.data)) {
                    cityList.innerHTML = "";
                    // Analysez la réponse JSON


                    // Parcourez les villes de la réponse et ajoutez-les à la liste
                    response.data.forEach((city) => {
                        var cityName = city.name;
                        var iatacode = city.iataCode;
                        var listItem = document.createElement("li");
                        if (city.relationships && city.relationships.length > 0) {
                            var airports = city.relationships.filter(relationship => relationship.type === "Airport");
                            if (airports.length > 0) {
                                // Pour chaque aéroport, créez un élément de liste distinct
                                airports.forEach(airport => {
                                    var airportCode = airport.id;
                                    var airportListItem = document.createElement("li");
                                    airportListItem.textContent = `${cityName}, Aéroport : ${airportCode}`;
                                    cityList.appendChild(airportListItem);
                                });
                            } else {
                                listItem.textContent = `${cityName}, Pas d'aéroports connus`;
                                cityList.appendChild(listItem);
                            }
                        } else {
                            listItem.textContent = `${cityName}, Pas d'aéroports connus`;
                            cityList.appendChild(listItem);
                        }


                        // listItem.textContent = ` ${cityName},  ${iatacode}`;
                        // cityList.appendChild(listItem);
                    });
                } else {
                    console.error("Aucune donnée de ville disponible dans la réponse.");
                }
            }
        };

        // Envoyez la requête
        xhr.send();
    } else {
        cityList.innerHTML = "";
    }
}

// getAccessToken("nGINyrag4R3hhje0nCS2BqlAhvHR5nL4", "noVlxZGbluLOSQes", "https://test.api.amadeus.com/v1/security/oauth2/token");
// Ajoutez un gestionnaire d'événements pour le changement de valeur dans l'élément d'entrée
var keywordInput1 = document.getElementById("userLocation");
var cityList1 = document.getElementById("suggestionsList");

var keywordInput2 = document.getElementById("arr");
var cityList2 = document.getElementById("suggestionsList2");

keywordInput1.addEventListener("input", function () {
    searchCities(keywordInput1, cityList1);
});
cityList1.addEventListener("click", function (event) {
    var clickedElement = event.target;
    // Vérifiez si l'élément cliqué est une suggestion
    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList1) {
        var airportCode = clickedElement.textContent.trim().split(':')[1].trim();
        keywordInput1.value = airportCode;
        cityList1.innerHTML = "";
    }
});
keywordInput2.addEventListener("input", function () {
    searchCities(keywordInput2, cityList2);
});
cityList2.addEventListener("click", function (event) {
    var clickedElement = event.target;
    // Vérifiez si l'élément cliqué est une suggestion
    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList2) {
        var airportCode = clickedElement.textContent.trim().split(':')[1].trim();
        keywordInput2.value = airportCode;
        cityList2.innerHTML = "";
    }
});
