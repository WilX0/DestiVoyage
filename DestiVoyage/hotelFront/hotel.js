// changer le theme en fonction du param theme
function changeTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);// pour définir l'attribut data-theme
    setThemeCookie(theme); // enregistrer le choix du thème dans un cookie
}

// changer le thème en fonction du bouton cliqué
document.getElementById('theme-light').addEventListener('click', function() {
    changeTheme('light');// restaure le style par défaut
});
 
document.getElementById('theme-dark').addEventListener('click', function() {
    changeTheme('dark'); //prend en compte le truc css dark
});

// enregistre le theme que si l'utilisateur l'accepte
function setThemeCookie(theme) {
    if (document.cookie.indexOf("consent=accept") !== -1) {
        document.cookie = "theme=" + theme + ";path=/";
    }
}

// Fonction pour lire le cookie
function getThemeCookie() {
    var name = "theme=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return "";
}

var d = new Date();
d.setTime(d.getTime() + (1*60*60*1000));

// Fonction pour vérifier le consentement
function checkConsentCookie() {
    if (document.cookie.indexOf("consent=accept") === -1 && document.cookie.indexOf("consent=refuse") === -1) {
        var consent = confirm("Ce site utilise des cookies pour améliorer l'expérience utilisateur. Acceptez-vous l'utilisation de cookies ?");
        if (consent) {
            document.cookie = "consent=accept; expires=" + d.toUTCString() + "; path=/";
        } else {
            document.cookie = "consent=refuse; expires="+ d.toUTCString() + "; path=/";
            changeTheme('light'); // thème clair si l'utilisateur refuse les cookies
        }
    }
}

// vérifier le consentement lors du chargement de la page
checkConsentCookie();

// Appliquer le thème enregistré lors du chargement de la page
var savedTheme = getThemeCookie();
if (savedTheme === "dark") {
    changeTheme('dark');
}