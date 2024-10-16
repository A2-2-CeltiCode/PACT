// Toast.js
function renderToast(message, type) {
    // Créer l'élément toast
    const toast = document.createElement('div');
    toast.classList.add('toast', type);
    toast.textContent = message;

    // Ajouter à la page
    document.body.appendChild(toast);

    // Déclencher l'animation d'affichage
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Supprimer le toast après 3 secondes
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500); // Attendre la fin de la transition d'opacité
    }, 2000);
}
