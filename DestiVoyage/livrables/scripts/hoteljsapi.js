// Code JavaScript pour gérer la recherche de vols en utilisant XMLHttpRequest
document.addEventListener("DOMContentLoaded", function () {
    let accessToken = null;


    // Fonction pour obtenir un jeton d'accès
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
                    // Appeler la fonction de recherche de vols après avoir obtenu le jeton d'accès
                    // searchhotel();
                }
            }
        };

        xhr.send(new URLSearchParams(data));
    }

    // Fonction pour effectuer une recherche de vols
    function searchhotel() {
        if (!accessToken) {
            console.error("Aucun jeton d'accès disponible.");
            return;
        }

        const radius = 5;
        const radiusUnit = "KM";
        const hotelSource = "ALL";
        var cityCode = document.getElementById("cityCode").value;
        var match = cityCode.match(/,\s*([^,]+)$/);
        if (match && match.length === 2) {
            var airportCode = match[1].trim();
            console.log(airportCode); // Cela devrait afficher "NYC"
        } else {
            alert("Pas de correspondance trouvée.");
            return;
        }

        const url = `https://test.api.amadeus.com/v1/reference-data/locations/hotels/by-city?cityCode=${airportCode}&radius=${radius}&radiusUnit=${radiusUnit}&hotelSource=${hotelSource}`;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var hotelList = document.getElementById("hotelList");
                    hotelList.innerHTML = ""; // Effacer le contenu précédent


                    if (response.data && response.data.length > 0) {
                        console.log(response);
                        response.data.forEach(hotel => {

                            var hotelName = hotel.name;
                            var hotelAddress = hotel.address.countryCode;
                            // var hotelid = hotel.hotelId;
                            var hotelContainer = document.createElement("div");
                            hotelContainer.classList.add("hotel-container");


                            // Créer un élément <p> pour afficher les informations de l'hôtel
                            var hotelInfo = document.createElement("p");
                            hotelInfo.classList.add("hotel-info"); // Ajouter la classe hotel-info
                            hotelInfo.textContent = `Nom de l'hôtel: ${hotelName}, Pays: ${hotelAddress}`;

                            var detailsButton = document.createElement("button");
                            detailsButton.classList.add("details-button"); // Ajouter la classe details-button
                            detailsButton.textContent = "Détails de l'hôtel";
                            detailsButton.addEventListener("click", function () {
                                getHotelDetails(hotel.hotelId, hotelContainer);
                            });


                            hotelContainer.appendChild(hotelInfo);
                            hotelContainer.appendChild(detailsButton);
                            hotelList.appendChild(hotelContainer);
                        });
                    } else {
                        hotelList.textContent = "Aucun hôtel trouvé pour ce code de ville.";
                    }

                } else {
                    console.error("Erreur lors de la requête :", xhr.statusText);
                }
            }

        };

        xhr.send();
    }

    function getHotelDetails(hotelId, hotelContainer) {
        if (!accessToken) {
            console.error("Aucun jeton d'accès disponible.");
            return;
        }

        var adults = document.getElementById("adults").value;
        var rooms = document.getElementById("rooms").value;
        var checkinDate = document.getElementById("checkinDate").value;
        var checkoutDate = document.getElementById("checkoutDate").value;
        var abonnement = document.getElementById("abo").value;


        // console.log(hotelId);
        const url = `https://test.api.amadeus.com/v3/shopping/hotel-offers?hotelIds=${hotelId}&adults=${adults}&checkInDate=${checkinDate}&checkOutDate=${checkoutDate}&roomQuantity=${rooms}&boardType=${abonnement}`;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    // Affichez les détails de l'hôtel, les chambres disponibles, les offres, etc.
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(hotelData => {
                            if (hotelData.offers && hotelData.offers.length > 0) {
                                hotelData.offers.forEach(offers => {
                                    var price = offers.price.total;
                                    var checkin = offers.checkInDate;
                                    var chekout = offers.checkOutDate;
                                    var abo = offers.boardType;
                                    var offerInfo = document.createElement("p");
                                    offerInfo.innerHTML = `<strong>Prix :</strong> ${price}<br><strong>Date d'arrivée :</strong> ${checkin}<br><strong>Date de départ :</strong> ${chekout}<br><strong>Type de plan :</strong> ${abo}`;
                                    // hotelList.appendChild(offerInfo);
                                    hotelContainer.appendChild(offerInfo);
                                })
                            }
                        })
                        console.log(response);
                    }
                } else {
                    console.error("Erreur lors de la requête :", xhr.statusText);
                }
            }
        };

        xhr.send();
    }

    getAccessToken("nGINyrag4R3hhje0nCS2BqlAhvHR5nL4", "noVlxZGbluLOSQes", "https://test.api.amadeus.com/v1/security/oauth2/token");
    // document.getElementById("searchButton").addEventListener("click", searchhotel);

    document.getElementById("hotelSearchForm").addEventListener("submit", function (event) {
        event.preventDefault();
        var currentDate = new Date();
        var checkinDate = document.getElementById("checkinDate").value;
        var checkoutDate = document.getElementById("checkoutDate").value;


        var checkin = new Date(checkinDate);
        var checkout = new Date(checkoutDate);

        if (checkin < currentDate) {
            alert("La date d'arrivée ne peut pas être inférieure à la date actuelle. Veuillez sélectionner une date d'arrivée valide.");
        }
        else if (checkout < checkin) {
            alert("La date de départ ne peut pas être inférieure à la date d'arrivée. Veuillez sélectionner des dates valides.");
        } else {
            searchhotel();
        }

    });



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


    var apiUrl = "https://test.api.amadeus.com/v1/reference-data/locations/cities";

    function searchCities(keywordInput, cityList) {
        var keyword = keywordInput.value;
        if (keyword.length >= 3) {
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


    var cityCodeInput = document.getElementById("cityCode");
    var suggestionsList = document.getElementById("suggestions");
    cityCodeInput.addEventListener("input", function () {
        searchCities(cityCodeInput, suggestionsList);
    });
    suggestionsList.addEventListener("click", function (event) {
        var clickedElement = event.target;

        if (clickedElement.tagName === 'LI' && clickedElement.parentNode === suggestionsList) {
            var cityInfo = clickedElement.textContent;

            // Utiliser une expression régulière pour extraire la partie souhaitée
            var match = cityInfo.match(/([^,]+,[^,]+)(?:,\s*Aéroport : [^,]+)?/);

            if (match && match.length === 2) {
                var cityNameAndCode = match[1].trim();

                cityCodeInput.value = cityNameAndCode;
                suggestionsList.innerHTML = "";
            }
        }
    });
    document.addEventListener("click", function (event) {
        if (!event.target.closest("#cityCode")) {
            // Fermez la liste si l'élément cliqué n'est pas à l'intérieur de l'input
            suggestionsList.innerHTML = "";
        }
    });
});