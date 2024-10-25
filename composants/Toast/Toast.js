function renderToast() {
    // Récupère le conteneur de toast
    const toastContainer = document.getElementById('toast-container');
    
    // Vérifie si le conteneur existe
    if (!toastContainer) {
        console.error('Aucun toast à afficher');
        return;
    }

    // Récupère le message et le type
    const message = toastContainer.getAttribute('data-message');
    const type = toastContainer.getAttribute('data-type');

    // Crée et configure le toast
    const toast = document.createElement('div');
    toast.classList.add('toast', type);
    toast.textContent = message;

    // Ajoute le toast au document
    document.body.appendChild(toast);

    // Affiche le toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Masque et supprime le toast après un délai
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500);
    }, 2000);
}

