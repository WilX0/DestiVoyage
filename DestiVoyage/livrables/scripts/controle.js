var divmdp = document.getElementById("errormdp");
var err = document.getElementById("errormdp3");
var form = document.getElementById('form');
var password2 = document.getElementById('password');
var passwordconfirm = document.getElementById('passwordconfirm');

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
        errors.push('Le mot de passe doit contenir au moins un caractère spécial, sauf - et _et.et +');
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



var form = document.getElementById('form');
var password2 = document.getElementById('password');
var passwordconfirm = document.getElementById('passwordconfirm');
function formula(event) {

    var password = password2.value;
    var confirmPassword = passwordconfirm.value;
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    if (!password.match(passwordRegex) || !confirmPassword.match(passwordRegex) || password !== confirmPassword) {
        event.preventDefault(); // Prevent the form submission
        alert("Il y a un problème avec le mot de passe il doit contenir au moins une majuscule,miniscule,");
    }
    if (password === '' || confirmPassword === '') {
        event.preventDefault(); // Empêche l'envoi du formulaire
        // Affiche un message d'erreur
        alert('Veuillez remplir tous les champs');
    }
}
form.addEventListener('submit', formula);