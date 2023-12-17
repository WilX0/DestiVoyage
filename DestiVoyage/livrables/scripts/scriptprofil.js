
document.addEventListener('DOMContentLoaded', function () {
    function previewImage() {
        var input = document.getElementById('profileImage');
        var preview = document.getElementById('previewImage');

        if (input.files && input.files[0] && preview) {
            var reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Ajoutez un gestionnaire d'événement onchange à l'élément input
    var profileImageInput = document.getElementById('profileImage');
    profileImageInput.addEventListener('change', previewImage);

    // Open modal for changing password
    document.getElementById('changePasswordBtn').addEventListener('click', function () {
        document.getElementById('changePasswordModal').style.display = 'block';
    });

    // Close modal
    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('changePasswordModal').style.display = 'none';
    });
    // var volsBtn = document.getElementById('volsBtn');

    // Ajouter un gestionnaire d'événement au clic sur le bouton "Vols"
    // volsBtn.addEventListener('click', function () {
    //     // Toggle la classe "active" pour mettre en valeur la section des vols

    //     // document.getElementsByClassName('volsSection').style.backgroundColor = 'blue';

    //     // document.getElementsByClassName('volsSection').style.borderRadius = '10px';
    // });
    var volsSection = document.getElementById("volsSection");
    var volsContainer = document.getElementById("volsContainer");

    // Affiche la section des vols lorsque l'utilisateur clique sur le lien "Vols"
    document.getElementById("volsBtn").addEventListener("click", function () {
        volsSection.style.display = "block";
    });

    // Ajoute un bouton de fermeture à la section des vols
    var closeBtn = document.createElement("button");
    closeBtn.innerHTML = "&times; Fermer";
    closeBtn.className = "btn btn-secondary custom-button";
    closeBtn.style.marginTop = "10px";
    closeBtn.addEventListener("click", function () {
        volsSection.style.display = "none";
    });
    volsContainer.appendChild(closeBtn);



});
//   *****************text
const textContainer = document.getElementById('text-container');
const textArray = ['Voyages', 'Destinations', 'Plaisir', 'confort'];

let currentTextIndex = 0;
let currentCharIndex = 0;
let isDeleting = false;

function typeText() {
    const currentText = textArray[currentTextIndex];
    const speed = isDeleting ? 50 : 100;

    if (!isDeleting && currentCharIndex === currentText.length) {
        // Le texte est écrit, commencer à effacer
        isDeleting = true;
        setTimeout(typeText, 1000); // Attendre avant de commencer à effacer
        return;
    } else if (isDeleting && currentCharIndex === 0) {
        // Le texte est effacé, passer au texte suivant
        isDeleting = false;
        currentTextIndex = (currentTextIndex + 1) % textArray.length;
    }

    const newText = isDeleting
        ? currentText.substring(0, currentCharIndex - 1)
        : currentText.substring(0, currentCharIndex + 1);

    textContainer.textContent = newText;
    currentCharIndex += isDeleting ? -1 : 1;

    setTimeout(typeText, speed);
}

typeText();
var np2 = document.getElementById('newPassword');
function validatePassword() {
    var np = np2.value;

    var errors = [];

    if (np.length < 8) {
        errors.push('Le mot de passe doit avoir au moins 8 caractères.');
    }

    if (!/\d/.test(np)) {
        errors.push('Le mot de passe doit contenir au moins un chiffre.');
    }

    if (!/[a-z]/.test(np)) {
        errors.push('Le mot de passe doit contenir au moins une minuscule.');
    }

    if (!/[A-Z]/.test(np)) {
        errors.push('Le mot de passe doit contenir au moins une majuscule.');
    }

    if (!/^[A-Za-z\d!@#$%^&*]+$/i.test(np)) {
        errors.push('Le mot de passe doit contenir au moins un caractère spécial, sauf "","-","_",":",".",",","+,="');
    } else if (!/[!@#$%^&*]/.test(np)) {
        errors.push('Le mot de passe doit contenir au moins un caractère spécial (!,@,#,$,%,^,&,*)');
    }

    updateErrorMessages(errors);

    return errors.length === 0;
}

function updateErrorMessages(errors) {
    var errorMessageContainer = document.getElementById('errorMessages');
    errorMessageContainer.innerHTML = '';

    if (errors.length > 0) {
        for (var i = 0; i < errors.length; i++) {
            var errorItem = document.createElement('p');
            errorItem.textContent = errors[i];
            errorMessageContainer.appendChild(errorItem);
        }
    }
}
function handleSubmit(event) {
    if (!validatePassword()) {
        event.preventDefault();
        alert("contrainte mdp non respectee")// Empêche l'envoi du formulaire si la validation échoue
    }
}

np2.addEventListener("input", validatePassword);

var form = document.getElementById('changepass'); // Remplacez 'yourFormId' par l'ID de votre formulaire
form.addEventListener("submit", handleSubmit);
