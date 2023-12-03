// Code JavaScript pour gérer la recherche de vols en utilisant XMLHttpRequest
let accessToken = null;


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

    const url = `https://test.api.amadeus.com/v1/reference-data/locations/hotels/by-city?cityCode=${cityCode}&radius=${radius}&radiusUnit=${radiusUnit}&hotelSource=${hotelSource}`;

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

                        // Créer un élément <p> pour afficher les informations de l'hôtel
                        var hotelInfo = document.createElement("p");
                        hotelInfo.classList.add("hotel-info"); // Ajouter la classe hotel-info
                        hotelInfo.textContent = `Nom de l'hôtel: ${hotelName}, Pays: ${hotelAddress}`;

                        var detailsButton = document.createElement("button");
                        detailsButton.classList.add("details-button"); // Ajouter la classe details-button
                        detailsButton.textContent = "Détails de l'hôtel";
                        detailsButton.addEventListener("click", function () {
                            getHotelDetails(hotel.hotelId);
                        });

                        // Ajoutez le nom de l'hôtel et le bouton au conteneur
                        var hotelContainer = document.createElement("div");
                        hotelContainer.classList.add("hotel-container"); // Ajouter la classe hotel-container
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

function getHotelDetails(hotelId) {
    if (!accessToken) {
        console.error("Aucun jeton d'accès disponible.");
        return;
    }
    console.log(hotelId);
    const url = `https://test.api.amadeus.com/v3/shopping/hotel-offers?hotelIds=${hotelId}&adults=1`;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.setRequestHeader("Authorization", `Bearer ${accessToken}`);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log(response);
                // Affichez les détails de l'hôtel, les chambres disponibles, les offres, etc.
                if (response.data && response.data[0].offers) {
                    var totalPrice = response.data[0].offers[0].price.total;
                alert("Prix total de l'hôtel : " + totalPrice);
                }
                console.log(response);
            } else {
                console.error("Erreur lors de la requête :", xhr.statusText);
            }
        }
    };

    xhr.send();
}


// Fonction pour afficher les détails des vols dans le flux HTML
// function displayFlightDetails(data) {
//     const resultDiv = document.getElementById("flight-results");

//     if (data.data) {
//         resultDiv.innerHTML = "";
//         data.data.forEach((flight) => {
//             const flightInfo = document.createElement("div");
//             flightInfo.classList.add("flight-item");

//             flightInfo.innerHTML = `
//         <h2>${flight.itineraries[0].segments[0].departure.iataCode} to ${flight.itineraries[0].segments[flight.itineraries[0].segments.length - 1].arrival.iataCode}</h2>
//         <p>Compagnie aérienne: ${flight.itineraries[0].segments[0].carrierCode}</p>
//         <p>Durée du vol: ${flight.itineraries[0].duration}</p>
//         <p>Prix: ${flight.price.total} ${flight.price.currency}</p>
//       `;

//             resultDiv.appendChild(flightInfo);
//         });
//     } else {
//         resultDiv.innerHTML = "Aucun vol disponible pour ces informations.";
//     }
// }

// Appeler la fonction pour obtenir le jeton d'accès
getAccessToken("nGINyrag4R3hhje0nCS2BqlAhvHR5nL4", "noVlxZGbluLOSQes", "https://test.api.amadeus.com/v1/security/oauth2/token");
document.getElementById("searchButton").addEventListener("click", searchhotel);