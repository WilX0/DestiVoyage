document.addEventListener("DOMContentLoaded", function () {
    let ville = "Bejaia";
    var vill = document.getElementById("ville");
    var country = document.getElementById("country");
    var ico = document.getElementById("imgmeteo");
    var tempe = document.getElementById("temp1");
    var da = document.getElementById("date");
    var currentDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    // Récupère le mois et le jour actuels
    var month = monthNames[currentDate.getMonth()];
    var day = currentDate.getDate();

    // Formate la date
    var formattedDate = month + " " + day;

    da.textContent = formattedDate;

    recevoirtemperatue(ville);

    function recevoirtemperatue(ville) {
        var cleapi = "c0c29e47fa5fa00960b95e425bf6fcf0";
        var url = 'https://api.openweathermap.org/data/2.5/weather?q=' + ville + '&appid=' + cleapi + '&lang-fr&units-metric';

        let requete = new XMLHttpRequest();
        requete.open('GET', url, true);
        requete.responseType = "json";
        requete.send();

        requete.onload = function () {
            console.log(requete);
            if (requete.readyState == XMLHttpRequest.DONE) {
                if (requete.status === 200) {
                    console.log = requete.responseText;
                    var temp = requete.response.main.temp;
                    var ville = requete.response.name;
                    var pays = requete.response.sys.country;
                    console.log(pays);
                    var icone = requete.response.weather[0].icon;
                    vill.textContent = ville;
                    country.textContent = pays;
                    ico.src = "https://openweathermap.org/img/wn/" + icone + "@2x.png";
                    var tru = temp - 273.15;
                    tempe.textContent = tru.toFixed(1) + "°";
                    // var tru = temp - 273.15;
                    // tempe.textContent += tru.toFixed(1) + "°";
                } else {
                    alert("il y'a une erreur avec AJAX");
                }

            }

        }
    }

    let btn = document.getElementById("changer");
    btn.addEventListener('click', function () {
        let villechoisie = ville;
        villechoisie = prompt("quelle ville souhaitez vous-choisir");
        if (villechoisie !== null && villechoisie !== "") {
            recevoirtemperatue(villechoisie);
        }
    });
});