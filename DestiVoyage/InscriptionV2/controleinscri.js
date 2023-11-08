var nomRegex = /^[a-zA-Z]+$/;
var prenomRegex = /^[a-zA-Z]+$/;
var loginRegex = /^[a-zA-Z0-9-_]{4,20}$/;
var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
var dateNaissanceRegex = /^\d{4}-\d{2}-\d{2}$/;

var nom = document.getElementById('nom');
var prenom = document.getElementById('prenom');
var login = document.getElementById('login');
var email = document.getElementById('email');
var password2 = document.getElementById('mot-de-passe2');
var passwordconfirm = document.getElementById('mot-de-passe3');
var datn = document.getElementById('date');
var errornom = document.getElementById('errornom');
var errorlog = document.getElementById('errorlog');
var errormail = document.getElementById('errorlog3');
var divmail = document.getElementById('errordiv3');
var divnom = document.getElementById('errordiv');
var divlog = document.getElementById('errordiv2');

nom.addEventListener("input", function () {
    var named = nom.value;
    if (!nomRegex.test(named) || named.length < 2) {
        // Affichez un message d'erreur
        errornom.textContent = "Le nom n'est pas valide. Il doit contenir uniquement des lettres et taille sup a 3.";
        divnom.classList.remove('d-none');
        divnom.classList.add('text-danger');
        divnom.classList.add('small');
    } else {
        errornom.textContent = "";
        divnom.classList.add('d-none');
    }
});
prenom.addEventListener("input", function () {
    var named2 = prenom.value;
    if (!nomRegex.test(named2) || named2.length < 3) {
        // Affichez un message d'erreur
        errornom.textContent = "Le prenom n'est pas valide. Il doit contenir uniquement des lettres et taille sup a 3.";
        divnom.classList.remove('d-none');
        divnom.classList.add('text-danger');
        divnom.classList.add('small');
    } else {
        errornom.textContent = "";
        divnom.classList.add('d-none');
    }
});
// 
function checkLoginAvailability() {
    var xhr = new XMLHttpRequest();
    var username = login.value;
    xhr.open("GET", "verifier_login.php", true);
    // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            var logins = JSON.parse(response);
            console.log(logins);
            var usernameExists = false;
            for (var i = 0; i < logins.length; i++) {
                if (logins[i].login === username) {
                    usernameExists = true;
                    break;
                }
            }

            if (usernameExists) {
                console.log("coucou");
                divlog.classList.remove("d-none");
                errorlog.textContent = "Ce login existe déjà.";
            } else {
                divlog.classList.add("d-none");
                errorlog.textContent = "OK login disponible.";
            }



        }
    };
    xhr.send();
}
// login.addEventListener("input", function () {

//     checkLoginAvailability();
// });


// login.addEventListener("input", function () {
//     var log = login.value;
//     if (!loginRegex.test(log) || log.length < 3) {
//         errorlog.textContent = "Le login n'est pas valide. Il doit contenir uniquement lettres, chiffres, tirets et underscores, 4 à 20 caractères.";
//         divlog.classList.remove('d-none');
//         divlog.classList.add('text-danger');
//         divlog.classList.add('small');
//     } else {
//         errorlog.textContent = "";
//         divlog.classList.add('d-none');
//     }
// });
email.addEventListener("input", function () {
    var lg = email.value;
    if (!lg) {
        errormail.textContent = "";
        divmail.classList.add('d-none');

    } else
        if (!emailRegex.test(lg) || lg.length < 3) {
            errormail.textContent = "le format de l'email n'est pas respecter";
            divmail.classList.remove('d-none');
            divmail.classList.add('text-danger');
            divmail.classList.add('small');
        } else {
            errormail.textContent = "";
            divlog.classList.add('d-none');
        }
});

var divmdp = document.getElementById("errormdp");
var err = document.getElementById("errormdp3");

function validatePassword() {
    var password = password2.value;
    var confirmPassword = passwordconfirm.value;
    errors = [];
    if (password === "") {
        // Si le champ du mot de passe est vide, efface les erreurs
        updateErrorMessages();
        return;
    }

    if (password !== confirmPassword) {
        errors.push('Les mots de passe ne correspondent pas.');
    }
    if (password.length < 8) {
        errors.push('Le mot de passe doit avoir au moins 8 caractères.');
    }

    if (!/\d/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins un chiffre.');
    }

    if (!/[A-Z]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins une majuscule.');
    }

    if (!/[^a-zA-Z0-9]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins un caractère spécial.');
    }


    updateErrorMessages();
}
function updateErrorMessages() {
    if (errors.length > 0) {
        err.innerHTML = errors.join('<br>');
        divmdp.classList.remove('d-none');
    } else {
        err.innerHTML = '';
        divmdp.classList.add('d-none');
    }
}

password2.addEventListener('input', validatePassword);
passwordconfirm.addEventListener('input', validatePassword);


function verifierLogin() {
    var log = login.value;
    if (!log) {
        errorlog.textContent = "";
        divlog.classList.add('d-none');
    } else
        if (!loginRegex.test(log) || log.length < 3) {
            errorlog.textContent = "Le login n'est pas valide. Il doit contenir uniquement lettres, chiffres, tirets et underscores, 4 à 20 caractères.";
            divlog.classList.remove('d-none');
            divlog.classList.add('text-danger');
            divlog.classList.add('small');
        } else {
            errorlog.textContent = "";
            divlog.classList.add('d-none');
            checkLoginAvailability();
        }

}

login.addEventListener("input", verifierLogin);

var dateCourante = new Date();
var annee = dateCourante.getFullYear();
var mois = (dateCourante.getMonth() + 1).toString().padStart(2, '0');
var jour = dateCourante.getDate().toString().padStart(2, '0');
var dateFormatee = annee + '-' + mois + '-' + jour;

var dat = document.getElementById('date');
dat.value = dateFormatee;
var today = new Date().toISOString().split('T')[0];
dat.setAttribute("max", today);
dat.addEventListener("input", function () {
    var datechoice = new Date(dat.value);
    var date2 = new Date();
    if (datechoice > date2) {

        alert("erreur date: date choisie est superieur a la date Courante .Veuillez choisir une autre date svp");
        dat.value = dateFormatee;
        dat.focus();
    }

});
var form = document.getElementById("form");

form.addEventListener("submit", function (event) {
    var log = login.value;
    var named = nom.value;
    var lg = email.value;
    var named2 = prenom.value;
    var password = password2.value;
    var confirmPassword = passwordconfirm.value;
    var homme = document.getElementById('homme').checked;
    var femme = document.getElementById('femme').checked;

    if (!loginRegex.test(log) || !named.test(nomRegex) || !lg.test(emailRegex) || !named2.test(nomRegex) || !password.test(passwordRegex) || !confirmPassword.test(passwordRegex)) {
        event.preventDefault(); // Empêche l'envoi du formulaire
        alert("ya un probleme !!!");

    }
    if (named === '' || named2 === '' || log === '' || lg === '' || password === '' || confirmPassword === '' || date === '' || (!homme && !femme)) {
        event.preventDefault(); // Empêche l'envoi du formulaire

        // Affiche un message d'erreur
        alert('Veuillez remplir tous les champs et choisir une option du genre.');
    }

    // if()
});

// login.addEventListener("input", function () {
//     var username = login.value;
//     var xhr = new XMLHttpRequest();
//     xhr.open("POST", "verifier_login.php", true);
//     console.log("helo");
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === 4 && xhr.status === 200) {
//             var response = JSON.parse(xhr.responseText);
//             if (response.exists) {
//                 errorlog.textContent = "Ce login existe dans la base de données.";
//             } else {
//                 errorlog.textContent = "Ce login n'existe pas dans la base de données.";
//             }
//         }
//     };
//     xhr.send("username=" + username);

// });

