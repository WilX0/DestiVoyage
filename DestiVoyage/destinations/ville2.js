

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

function displayErrorMessage(message) {
    var errorContainer = document.getElementById("error-message-container");
    errorContainer.textContent = message;
    errorContainer.style.display = "block";
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
var cityCodeInput = document.getElementById("cityCode");
var suggestionsList = document.getElementById("suggestions");

cityCodeInput.addEventListener("input", function () {
    searchCities(cityCodeInput, suggestionsList);
});
var selectedIndexCity = -1;
suggestionsList.addEventListener("click", function (event) {
    var clickedElement = event.target;

    // Check if the clicked element is a suggestion
    if (clickedElement.tagName === 'LI' && clickedElement.parentNode === suggestionsList) {

        var cityInfo = clickedElement.textContent;

        // Use a regular expression to extract the city and code
        var match = cityInfo.match(/^(.*),\s*Code\s*:\s*(.*)$/);

        if (match && match.length === 3) {
            var cityName = match[1].trim();
            var cityCode = match[2].trim();

            cityCodeInput.value = cityName + ", " + cityCode;
            suggestionsList.innerHTML = "";
        }
    }
});
// keywordInput2.addEventListener("blur", videul);
// function handleArrowAndEnter(event, keywordInput, cityList) {
//     var suggestions = cityList.querySelectorAll('li');
//     var selectedIndex = Array.from(suggestions).findIndex(suggestion => suggestion.classList.contains('selected'));

//     switch (event.key) {
//         case 'ArrowUp':
//             selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
//             break;
//         case 'ArrowDown':
//             selectedIndex = (selectedIndex + 1) % suggestions.length;
//             break;
//         case 'Enter':
//             if (selectedIndex !== -1) {
//                 var selectedElement = suggestions[selectedIndex];
//                 selectCity(selectedElement, keywordInput, cityList);
//             }
//             break;
//     }

//     // Update the selected item
//     suggestions.forEach((suggestion, index) => {
//         suggestion.classList.toggle('selected', index === selectedIndex);
//     });
// }

// Event listeners for arrow keys and Enter key
// keywordInput1.addEventListener('keydown', function (event) {
//     handleArrowAndEnter(event, keywordInput1, cityList1);
// });

// keywordInput2.addEventListener('keydown', function (event) {
//     handleArrowAndEnter(event, keywordInput2, cityList2);
// });
// function selectCity(selectedElement, keywordInput, cityList) {
//     var airportInfo = selectedElement.textContent;
//     var match = airportInfo.match(/^(.*),\s*Aéroport\s*:\s*(.*)$/);

//     if (match && match.length === 3) {
//         var cityName = match[1].trim();
//         var airportCode = match[2].trim();

//         keywordInput.value = cityName + ", " + airportCode;
//         cityList.innerHTML = "";
//     }
// }

// Event listeners to handle mouse click on suggestion items
// cityList1.addEventListener('click', function (event) {
//     var clickedElement = event.target;
//     if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList1) {
//         selectCity(clickedElement, keywordInput1, cityList1);
//     }
// });

// cityList2.addEventListener('click', function (event) {
//     var clickedElement = event.target;
//     if (clickedElement.tagName === 'LI' && clickedElement.parentNode === cityList2) {
//         selectCity(clickedElement, keywordInput2, cityList2);
//     }
// });