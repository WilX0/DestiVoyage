document.addEventListener('DOMContentLoaded', function () {
    // Open modal for changing password
    document.getElementById('changePasswordBtn').addEventListener('click', function () {
        document.getElementById('changePasswordModal').style.display = 'block';
    });

    // Close modal
    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('changePasswordModal').style.display = 'none';
    });
    var volsBtn = document.getElementById('volsBtn');

    // Ajouter un gestionnaire d'événement au clic sur le bouton "Vols"
    volsBtn.addEventListener('click', function () {
        // Toggle la classe "active" pour mettre en valeur la section des vols

        document.getElementsByClassName('volsContent').style.backgroundColor = 'black';

        document.getElementsByClassName('volsContent').style.borderRadius = '10px';
    });


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

// Démarrer l'animation
typeText();
