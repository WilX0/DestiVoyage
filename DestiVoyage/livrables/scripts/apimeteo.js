let ville = "Bejaia";

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
                let temp = requete.response.main.temp;
                let ville = requete.response.name;
                let icone = requete.response.weather[0].icon;
                let min = requete.response.main.temp_min;
                // let max = requete.response.main.temp.min;

                let newdiv = document.createElement('div');
                let here = min - 273.15;
                newdiv.innerHTML = "<p>min:<span>" + here.toFixed(2) + "C</span>" + "</p>";

                let img = document.createElement("img");
                img.src = "https://openweathermap.org/img/wn/" + icone + "@2x.png";

                let vi = document.getElementById("ville");
                vi.innerHTML = "<h2>" + ville + "</h2>";
                vi.append(img);
                vi.append(newdiv);

                let tmp = document.getElementById("temperature");
                let tru = temp - 273.15;
                tmp.textContent = tru.toFixed(1);
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
    recevoirtemperatue(villechoisie);
});