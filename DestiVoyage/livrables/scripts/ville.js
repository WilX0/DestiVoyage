

// Fonction pour obtenir un jeton d'accès
function getAccessToken(clientId, clientSecret, apiUrl) {
    const data = {
        grant_type: "client_credentials",
        client_id: "XYsRxC6AXvIZ7zEiGh4LjKGGJB2eH3xU",
        client_secret: "DjenoDscsyCITaP2",
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

function displayErrorMessage(message) {
    var errorContainer = document.getElementById("error-message-container");
    errorContainer.textContent = message;
    errorContainer.style.display = "block";
}
getAccessToken("XYsRxC6AXvIZ7zEiGh4LjKGGJB2eH3xU", "DjenoDscsyCITaP2", "https://test.api.amadeus.com/v1/security/oauth2/token");

// Définissez l'intervalle de rafraîchissement du jeton en millisecondes (12 minutes)
const refreshInterval = 12 * 60 * 1000; // 12 minutes

// Utilisez setInterval pour appeler la fonction de rafraîchissement périodiquement
setInterval(() => {
    getAccessToken("XYsRxC6AXvIZ7zEiGh4LjKGGJB2eH3xU", "DjenoDscsyCITaP2", "https://test.api.amadeus.com/v1/security/oauth2/token");
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
        var response = {};
        // Créez une requête XHR
        const xhr = new XMLHttpRequest();
        xhr.open("GET", requestUrl, true);
        xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

        // Configurez le gestionnaire d'événements pour la réponse de la requête
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Effacez la liste précédente des villes
                response = JSON.parse(xhr.responseText);
                if (Array.isArray(response.data)) {
                    cityList.innerHTML = "";

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
                    // var errorMessage = "Aucune donnée de ville disponible dans la réponse.";
                    // displayErrorMessage(errorMessage);
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
function searchCities2(keywordInput, cityList) {
    var keyword = keywordInput.value;
    var apiUrl = "https://api.amadeus.com/v1/reference-data/locations/cities";

    if (keyword.length >= 3) {
        var requestUrl = `${apiUrl}?keyword=${keyword}&include=AIRPORTS`;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", requestUrl, true);

        xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (Array.isArray(response.data)) {
                    cityList.innerHTML = "";
                    response.data.forEach((city) => {
                        var cityName = city.name;
                        var iataCode = city.iataCode;
                        var listItem = document.createElement("li");
                        listItem.textContent = `${cityName} (${iataCode})`;
                        listItem.addEventListener("click", function () {
                            // Vous pouvez ajouter une logique supplémentaire ici
                            console.log("L'utilisateur a choisi : " + iataCode);
                        });
                        cityList.appendChild(listItem);
                    });
                } else {
                    console.error("Aucune donnée de ville disponible dans la réponse.");
                }
            }
        };

        xhr.send();
    } else {
        cityList.innerHTML = "";
    }
}

var keywordInput1 = document.getElementById("userLocation");
var cityList1 = document.getElementById("suggestionsList");



// keywordInput1.addEventListener("blur", videul);
var keywordInput2 = document.getElementById("arr");
var cityList2 = document.getElementById("suggestionsList2");
function videul() {
    cityList1.innerHTML = "";
    cityList2.innerHTML = "";
}


keywordInput1.addEventListener("input", function () {
    searchCities(keywordInput1, cityList1);
});
cityList1.addEventListener("click", function (event) {
    var clickedElement = event.target;

    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList1) {
        var airportInfo = clickedElement.textContent;


        var match = airportInfo.match(/^(.*),\s*Aéroport\s*:\s*(.*)$/);

        if (match && match.length === 3) {
            var cityName = match[1].trim();
            var airportCode = match[2].trim();

            keywordInput1.value = cityName + ", " + airportCode;
            cityList1.innerHTML = "";

        }

    }
});
document.addEventListener("click", function (event) {
    if (!event.target.closest("#userLocation")) {
        cityList1.innerHTML = "";
    }
});
keywordInput2.addEventListener("input", function () {
    searchCities(keywordInput2, cityList2);
});

cityList2.addEventListener("mousedown", function (event) {
    var clickedElement = event.target;

    // Vérifiez si l'élément cliqué est une suggestion
    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList2) {
        var airportInfo = clickedElement.textContent;

        // Utilisez une expression régulière pour extraire la ville et le code
        var match = airportInfo.match(/^(.*),\s*Aéroport\s*:\s*(.*)$/);

        if (match && match.length === 3) {
            var cityName = match[1].trim();
            var airportCode = match[2].trim();

            keywordInput2.value = cityName + ", " + airportCode;
            cityList2.innerHTML = "";
        }
    }
});
document.addEventListener("click", function (event) {
    if (!event.target.closest("#arr")) {
        // Fermez la liste si l'élément cliqué n'est pas à l'intérieur de l'input
        cityList2.innerHTML = "";
    }
});