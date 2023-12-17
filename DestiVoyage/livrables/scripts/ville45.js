
function getAccessToken(clientId, clientSecret, apiUrl) {
    const data = {
        grant_type: "client_credentials",
        client_id: "aAoArVebAjWqFZtsDS0AY1dVcwyo96Yc",
        client_secret: "5ZiCEFfurin1irBp",
    };

    const xhr = new XMLHttpRequest();
    xhr.open("POST", apiUrl, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.access_token) {
                accessToken = data.access_token;
                // console.log(accessToken);
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
getAccessToken("aAoArVebAjWqFZtsDS0AY1dVcwyo96Yc", "5ZiCEFfurin1irBp", "https://test.api.amadeus.com/v1/security/oauth2/token");


const refreshInterval = 12 * 60 * 1000; // 12 minutes


setInterval(() => {
    getAccessToken("aAoArVebAjWqFZtsDS0AY1dVcwyo96Yc", "5ZiCEFfurin1irBp", "https://test.api.amadeus.com/v1/security/oauth2/token");
}, refreshInterval);



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
                                    airportListItem.textContent = `${cityName},${iatacode}, Aéroport : ${airportCode}`;
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
                        listItemistItem.setAttribute("tabindex", "0");
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
var keyworde2 = document.getElementById("keyworde2");
var liste = document.getElementById("cityResults");
keyworde2.addEventListener("input", function () {
    searchCities(keyworde2, liste)
});
var selectedIndex = -1;
liste.addEventListener("click", function (event) {
    var clickedElement = event.target;

    // Vérifiez si l'élément cliqué est une suggestion
    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === liste) {

        var airportInfo = clickedElement.textContent;

        // Utilisez une expression régulière pour extraire la ville et le code
        var match = airportInfo.match(/^(.*),\s*Aéroport\s*:\s*(.*)$/);

        if (match && match.length === 3) {
            var cityName = match[1].trim();
            var airportCode = match[2].trim();

            keyworde2.value = cityName + ", " + airportCode;
            liste.innerHTML = "";
        }
    }
});
keyworde2.addEventListener("keydown", function (e) {
    if (e.key === "ArrowDown" || e.key === "ArrowUp") {
        // Navigation avec les flèches haut et bas
        e.preventDefault();
        handleArrowNavigation(e.key === "ArrowDown");
    } else if (e.key === "Enter") {
        // Sélection avec la touche Entrée
        e.preventDefault();
        handleEnterSelection();
    }
});

function handleArrowNavigation(isDownArrow) {
    var cityResultsList = liste.getElementsByTagName("li");

    if (isDownArrow) {
        selectedIndex = Math.min(selectedIndex + 1, cityResultsList.length - 1);
    } else {
        selectedIndex = Math.max(selectedIndex - 1, -1);
    }

    updateSelection(cityResultsList);
}

function handleEnterSelection() {
    var cityResultsList = liste.getElementsByTagName("li");

    if (selectedIndex >= 0 && selectedIndex < cityResultsList.length) {
        var selectedElement = cityResultsList[selectedIndex];

        // Déclencher un clic sur l'élément sélectionné pour simuler la sélection
        selectedElement.click();
    }
}

function updateSelection(cityResultsList) {
    for (var i = 0; i < cityResultsList.length; i++) {
        cityResultsList[i].classList.toggle("selected", i === selectedIndex);
    }
}

