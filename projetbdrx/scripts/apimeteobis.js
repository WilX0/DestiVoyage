// function obtenirCoordonneesEtMeteo() {
//     mymap.on('moveend', function () {
//         var center = mymap.getCenter();
//         var latitude = center.lat;
//         var longitude = center.lng;
//         recevoirtemperatureParCoordonnees(latitude, longitude);
//     });
// }
// function recevoirtemperatureParCoordonnees(latitude, longitude) {
//     var cleapi = "c0c29e47fa5fa00960b95e425bf6fcf0";
//     var url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + latitude + '&lon=' + longitude + '&appid=' + cleapi + '&lang=fr&units=metric';

//     let requete = new XMLHttpRequest();
//     requete.open('GET', url, true);
//     requete.responseType = "json";
//     requete.send();

//     requete.onload = function () {
//         if (requete.readyState == XMLHttpRequest.DONE) {
//             if (requete.status === 200) {
//                 var temp = requete.response.main.temp;
//                 var ville = requete.response.name;
//                 var pays = requete.response.sys.country;
//                 var ds = requete.response.weather[0].description;
//                 var icone = requete.response.weather[0].icon;
//                 vill.textContent = ville;
//                 country.textContent = pays;
//                 descri.textContent = ds;
//                 ico.src = "https://openweathermap.org/img/wn/" + icone + "@2x.png";
//                 var tru = temp - 273.15;
//                 tempe.textContent = tru.toFixed(1) + "°";
//             } else {
//                 alert("Il y a une erreur avec AJAX");
//             }
//         }
//     };
// }

document.addEventListener("DOMContentLoaded", function () {
    // obtenirCoordonneesEtMeteo();
    // let ville = "Bejaia";
    var vill = document.getElementById("ville");
    var country = document.getElementById("country");
    var ico = document.getElementById("imgmeteo");
    var tempe = document.getElementById("temp1");
    var da = document.getElementById("date");
    var descri = document.getElementById("description");
    var currentDate = new Date();
    const monthNames = ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre"];

    var month = monthNames[currentDate.getMonth()];
    var day = currentDate.getDate();


    var formattedDate = month + " " + day;

    da.textContent = formattedDate;

    function recevoirtemperatue(latitude, longitude) {
        var cleapi2 = "47028a938995ccb5e0f7d2938fac03a9";
        var url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + latitude + '&lon=' + longitude + '&appid=' + cleapi2 + '&lang-fr&units-metric';

        let requete = new XMLHttpRequest();
        requete.open('GET', url, true);
        requete.responseType = "json";
        requete.send();

        requete.onload = function () {
            if (requete.readyState == XMLHttpRequest.DONE) {
                if (requete.status === 200) {
                    var temp = requete.response.main.temp;
                    var ville = requete.response.name;
                    var pays = requete.response.sys.country;
                    var ds = requete.response.weather[0].description;
                    var icone = requete.response.weather[0].icon;
                    vill.textContent = ville;
                    country.textContent = pays;
                    descri.textContent = ds;
                    ico.src = "https://openweathermap.org/img/wn/" + icone + "@2x.png";
                    var tru = temp - 273.15;
                    tempe.textContent = tru.toFixed(1) + "°";
                } else {
                    alert("Il y a une erreur avec AJAX2");
                }
            }
        }
    }

    var mapElement = document.getElementById("map");
    var mymap = L.map(mapElement).setView([36.7498, 5.0556], 13);
    L.marker([36.7498, 5.0556]).addTo(mymap);

    // Ajoute une couche de carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);
    // function obtenirCoordonneesEtMeteo() {
    mymap.on('click', function (event) {
        var latitude = event.latlng.lat;
        var longitude = event.latlng.lng;
        recevoirtemperatue(latitude, longitude);
    });
    // }

    // Obtient la géolocalisation de l'utilisateur
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            // Récupère les coordonnées de la géolocalisation
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            recevoirtemperatue(latitude, longitude);

            // Centre la carte sur les coordonnées de la géolocalisation
            mymap.setView([latitude, longitude], 13);

            // Ajoute un marqueur à la position de la géolocalisation
            L.marker([latitude, longitude]).addTo(mymap);
        }, function (error) {
            console.error("Erreur de géolocalisation : ", error.message);
            recevoirtemperature("bejaia");
        });
    } else {
        console.error("La géolocalisation n'est pas prise en charge par ce navigateur.");
        recevoirtemperature("bejaia");
    }

    // recevoirtemperatue(ville);
    // obtenirCoordonneesEtMeteo();
    function recevoirtemperature(ville) {
        var cleapi = "c0c29e47fa5fa00960b95e425bf6fcf0";
        var url = 'https://api.openweathermap.org/data/2.5/weather?q=' + ville + '&appid=' + cleapi + '&lang-fr&units-metric';

        let requete = new XMLHttpRequest();
        requete.open('GET', url, true);
        requete.responseType = "json";
        requete.send();

        requete.onload = function () {
            // console.log(requete);
            if (requete.readyState == XMLHttpRequest.DONE) {
                if (requete.status === 200) {
                    var temp = requete.response.main.temp;
                    var ville = requete.response.name;
                    var pays = requete.response.sys.country;
                    var ds = requete.response.weather[0].description;
                    // console.log(pays);
                    var icone = requete.response.weather[0].icon;
                    vill.textContent = ville;
                    country.textContent = pays;
                    descri.textContent = ds;
                    ico.src = "https://openweathermap.org/img/wn/" + icone + "@2x.png";
                    var tru = temp - 273.15;
                    // updateCloudBackStyle(tru);

                    tempe.textContent = tru.toFixed(1) + "°";
                    // var tru = temp - 273.15;
                    // tempe.textContent += tru.toFixed(1) + "°";
                } else {
                    alert("il y'a une erreur avec AJAX");
                }

            }

        }
    }
    // obtenirCoordonneesEtMeteo();
    let btn = document.getElementById("changer");
    btn.addEventListener('click', function () {
        let villechoisie = ville;
        villechoisie = prompt("quelle ville souhaitez vous-choisir");
        if (villechoisie !== null && villechoisie !== "") {
            recevoirtemperature(villechoisie);
        }
    });
});