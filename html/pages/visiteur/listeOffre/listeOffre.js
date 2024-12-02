const openPopupButton = document.getElementById('openPopup');
const closePopupButton = document.getElementById('closePopup');
const popup = document.getElementById('popup');
const popupOverlay = document.getElementById('popupOverlay');

// Fonction pour afficher la popup
openPopupButton.addEventListener('click', () => {
        popup.style.display = 'fixed';
        popupOverlay.style.display = 'fixed';
});

        // Fonction pour cacher la popup
closePopupButton.addEventListener('click', () => {
        popup.style.display = 'none';
        popupOverlay.style.display = 'none';
    });

        // Fermer la popup en cliquant sur l'overlay
popupOverlay.addEventListener('click', () => {
        popup.style.display = 'none';
        popupOverlay.style.display = 'none';
});
