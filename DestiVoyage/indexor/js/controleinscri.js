var nomRegex = /^[a-zA-Z]+$/;
var prenomRegex = /^[a-zA-Z]+$/;
var loginRegex = /^[a-zA-Z0-9-_]{4,20}$/;
var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
// var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;
// var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;

var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
// var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;


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
    if (!nomRegex.test(named)) {
        errornom.textContent = "Le nom n'est pas valide.";
        divnom.classList.remove('d-none');
        divnom.classList.add('text-danger');
        divnom.classList.add('small');
    } else {
        errornom.textContent = "";
        divnom.classList.add('d-none');
    }
});

var errorpre = document.getElementById("errorprenom");
var divpre = document.getElementById("errordivpre");
function validateField(value, regex, errorElement, errorMessage) {
    if (!regex.test(value)) {
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('d-none');
        errorElement.classList.add('text-danger', 'small');
        return false;
    } else {
        errorElement.textContent = '';
        errorElement.classList.add('d-none');
        return true;
    }
}
prenom.addEventListener("input", function () {
    var named2 = prenom.value;
    if (!nomRegex.test(named2)) {
        // Affichez un message d'erreur
        errorpre.textContent = "Le prenom n'est pas valide. Il doit contenir uniquement des lettres.";
        divpre.classList.remove('d-none');
        divpre.classList.add('text-danger');
        divpre.classList.add('small');
    } else {
        errorpre.textContent = "";
        divpre.classList.add('d-none');
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
            // console.log(response);
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
                // console.log("coucou");
                divlog.classList.remove("d-none");
                divlog.classList.add('text-danger');
                errorlog.textContent = "Ce login existe déjà.";
            } else {
                divlog.classList.add("d-none");
                errorlog.textContent = "OK login disponible.";
            }



        }
    };
    xhr.send();
}
function checkMailAvailability() {
    var xhr = new XMLHttpRequest();
    var mai = email.value;
    xhr.open("GET", "check_mail.php", true);
    // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            console.log(response);
            var mails = JSON.parse(response);
            // console.log(logins);
            var mailExists = false;
            for (var i = 0; i < mails.length; i++) {
                if (mails[i].email === mai) {
                    mailExists = true;
                    break;
                }
            }

            if (mailExists) {
                divmail.classList.remove("d-none");
                divmail.classList.add('text-danger');
                errormail.textContent = "Email existe déjà.";
            } else {
                divmail.classList.add("d-none");
            }



        }
    };
    xhr.send();
}

function checkMail() {
    var lg = email.value;
    if (!lg) {
        errormail.textContent = "";
        divmail.classList.add('d-none');

    } else
        if (!emailRegex.test(lg)) {
            errormail.textContent = "le format de l'email n'est pas respecter";
            divmail.classList.remove('d-none');
            divmail.classList.add('text-danger');
            divmail.classList.add('small');
        } else {
            errormail.textContent = "";
            divlog.classList.add('d-none');
            checkMailAvailability();
        }
}
email.addEventListener('input', checkMail);

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

    if (!/[a-z]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins une minuscule.');
    }

    if (!/[A-Z]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins une majuscule.');
    }

    if (!/^[A-Za-z\d!@#$%^&*]+$/i.test(password)) {
        errors.push('Le mot de passe doit contenir au moins un caractère spécial, sauf - et _.');
    } else if (!/[!@#$%^&*]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins un caractère spécial.');
    }

    updateErrorMessages();
}
function updateErrorMessages() {
    if (errors.length > 0) {
        err.innerHTML = errors.join('<br>');
        divmdp.classList.add('text-danger');
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

// var dateCourante = new Date();
// var annee = dateCourante.getFullYear();
// var mois = (dateCourante.getMonth() + 1).toString().padStart(2, '0');
// var jour = dateCourante.getDate().toString().padStart(2, '0');
// var dateFormatee = annee + '-' + mois + '-' + jour;

// var dat = document.getElementById('date');
// dat.value = dateFormatee;
// var today = new Date().toISOString().split('T')[0];
// dat.setAttribute("max", today);
// dat.addEventListener("input", function () {
//     var datechoice = new Date(dat.value);
//     var date2 = new Date();
//     if (datechoice > date2) {

//         alert("erreur date: date choisie est superieur a la date Courante .Veuillez choisir une autre date svp");
//         dat.value = dateFormatee;
//         dat.focus();
//     }

// });
// var form = document.getElementById("form");

function formula(event) {
    var log = login.value;
    var named = nom.value;
    var lg = email.value;
    var named2 = prenom.value;
    var password = password2.value;
    var confirmPassword = passwordconfirm.value;
    var loginRegex = /^[a-zA-Z0-9-_]{4,20}$/;
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    // var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;
    // var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    var recaptchaResponse = document.getElementById('g-recaptcha-response').value;

    if (recaptchaResponse === '' || !log.match(loginRegex) || !named.match(prenomRegex) || !named2.match(prenomRegex) || !lg.match(emailRegex) || !password.match(passwordRegex) || !confirmPassword.match(passwordRegex) || password !== confirmPassword) {
        event.preventDefault(); // Prevent the form submission
        alert("Il y a un problème avec le formulaires.");
    }
    if (named === '' || named2 === '' || log === '' || lg === '' || password === '' || confirmPassword === '') {
        event.preventDefault(); // Empêche l'envoi du formulaire

        // Affiche un message d'erreur
        alert('Veuillez remplir tous les champs');
    }
}
form.addEventListener('submit', formula);
// function onSubmit(token) {
//     var named = nom.value;
//     var named2 = prenom.value;
//     var log = login.value;
//     var lg = email.value;
//     var password = password2.value;
//     var confirmPassword = passwordconfirm.value;
//     var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
//     var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;
//     var hasErrors = false;

//     // Valider les champs ici
//     if (!named.match(prenomRegex) || !named2.match(prenomRegex) || !lg.match(emailRegex) || !password.match(passwordRegex) || !confirmPassword.match(passwordRegex) || password !== confirmPassword) {
//         hasErrors = true;
//         alert("Il y a un problème avec le formulaire.");
//     }
//     if (named === '' || named2 === '' || log === '' || lg === '' || password === '' || confirmPassword === '') {
//         hasErrors = true;
//         alert('Veuillez remplir tous les champs et choisir une option du genre.');
//     }

//     if (!hasErrors) {
//         // Si aucune erreur n'est détectée, soumettre le formulaire
//         document.getElementById("form").submit();
//     }
// }
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

