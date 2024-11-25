function trier(sort) {
    const searchInput = document.querySelector('input[name="titre"]').value;
    const localisationInput = document.querySelector('input[name="localisation"]').value;
    const minPrixInput = document.querySelector('input[name="minPrix"]').value;
    const maxPrixInput = document.querySelector('input[name="maxPrix"]').value;
    const ouvertureInput = document.querySelector('input[name="ouverture"]').value;
    const fermetureInput = document.querySelector('input[name="fermeture"]').value;
    const categoryInputs = Array.from(document.querySelectorAll('input[name="nomcategorie[]"]:checked')).map(input => input.value);

    const params = new URLSearchParams();
    params.append('sort', sort);
    if (searchInput) params.append('titre', searchInput);
    if (localisationInput) params.append('localisation', localisationInput);
    if (minPrixInput) params.append('minPrix', minPrixInput);
    if (maxPrixInput) params.append('maxPrix', maxPrixInput);
    if (ouvertureInput) params.append('ouverture', ouvertureInput);
    if (fermetureInput) params.append('fermeture', fermetureInput);
    if (categoryInputs.length > 0) params.append('nomcategorie', categoryInputs.join(','));

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `listeOffre.php?${params.toString()}`, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const resultatsContainer = document.getElementById('resultats');
            resultatsContainer.innerHTML = response.offres.join('');
            document.getElementById('nombreOffres').innerHTML = `Nombre d'offres affichées : ${response.nombreOffres}`;
            
            applyStyles();
        }
    };
    xhr.send();
}

function rechercher() {
    const searchInput = document.querySelector('input[name="titre"]').value;
    const localisationInput = document.querySelector('input[name="localisation"]').value;
    const minPrixInput = document.querySelector('input[name="minPrix"]').value;
    const maxPrixInput = document.querySelector('input[name="maxPrix"]').value;
    const ouvertureInput = document.querySelector('input[name="ouverture"]').value;
    const fermetureInput = document.querySelector('input[name="fermeture"]').value;
    const categoryInputs = Array.from(document.querySelectorAll('input[name="nomcategorie[]"]:checked')).map(input => input.value);

    const params = new URLSearchParams();
    if (searchInput) params.append('titre', searchInput);
    if (localisationInput) params.append('localisation', localisationInput);
    if (minPrixInput) params.append('minPrix', minPrixInput);
    if (maxPrixInput) params.append('maxPrix', maxPrixInput);
    if (ouvertureInput) params.append('ouverture', ouvertureInput);
    if (fermetureInput) params.append('fermeture', fermetureInput);
    if (categoryInputs.length > 0) params.append('nomcategorie', categoryInputs.join(','));

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `listeOffre.php?${params.toString()}`, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function () {      
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                const resultatsContainer = document.getElementById('resultats');
                resultatsContainer.innerHTML = response.offres.join('');
                document.getElementById('nombreOffres').innerHTML = `Nombre d'offres affichées : ${response.nombreOffres}`;
                // Réappliquer les styles CSS si nécessaire
                applyStyles();
            } catch (e) {
                console.error('Erreur lors du traitement de la réponse JSON:', e);
            }
        } else {
            console.error('Erreur lors de la requête AJAX:', xhr.status, xhr.statusText);
        }
    };
    xhr.send();
}

function applyStyles() {
    const offres = document.querySelectorAll('.offre');
    offres.forEach(offre => {
        offre.style.margin = '10px auto';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchForm');
    const searchInput = document.querySelector('input[name="titre"]');
    const localisationInput = document.querySelector('input[name="localisation"]');
    const minPrixInput = document.querySelector('input[name="minPrix"]');
    const maxPrixInput = document.querySelector('input[name="maxPrix"]');
    const ouvertureInput = document.querySelector('input[name="ouverture"]');
    const fermetureInput = document.querySelector('input[name="fermeture"]');
    const categoryInputs = document.querySelectorAll('input[name="nomcategorie[]"]');

    searchInput.addEventListener('input', function() {
        rechercher();
    });

    localisationInput.addEventListener('input', function() {
        rechercher();
    });

    minPrixInput.addEventListener('input', function() {
        rechercher();
    });

    maxPrixInput.addEventListener('input', function() {
        rechercher();
    });

    ouvertureInput.addEventListener('input', function() {
        rechercher();
    });

    fermetureInput.addEventListener('input', function() {
        rechercher();
    });

    categoryInputs.forEach(input => {
        input.addEventListener('change', function() {
            rechercher();
        });
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        rechercher();
    });
});