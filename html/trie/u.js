function trier(sort) {
    const searchInput = document.querySelector('input[name="titre"]').value;
    const localisationInput = document.querySelector('input[name="localisation"]').value;

    const ouvertureInput = document.querySelector('input[name="ouverture"]').value;
    const fermetureInput = document.querySelector('input[name="fermeture"]').value;
    const etatInput = document.querySelector('select[name="etat"]').value;
    const trieInput = document.querySelector('select[name="trie"]').value;

    const params = new URLSearchParams(window.location.search);
    if (sort) {
        params.set('sort', sort);
    } else if (!params.has('sort')) {
        params.set('sort', 'default'); // Valeur par défaut si aucun tri n'est spécifié
    }
    if (searchInput) params.set('titre', searchInput);
    if (localisationInput) params.set('localisation', localisationInput);

    if (ouvertureInput) params.set('ouverture', ouvertureInput);
    if (fermetureInput) params.set('fermeture', fermetureInput);
    if (etatInput) params.set('etat', etatInput);
    if (trieInput) params.set('trie',trieInput);

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
    const searchInput = document.querySelector('input[name="titre"]')?.value || '';
    const localisationInput = document.querySelector('input[name="localisation"]')?.value || '';

    const ouvertureInput = document.querySelector('input[name="ouverture"]')?.value || '';
    const fermetureInput = document.querySelector('input[name="fermeture"]')?.value || '';
    const etatInput = document.querySelector('select[name="etat"]')?.value || '';
    const trieInput = document.querySelector('select[name="trie"]')?.value || '';

    const params = new URLSearchParams(window.location.search);
    if (searchInput) params.set('titre', searchInput);
    if (localisationInput) params.set('localisation', localisationInput);

    if (ouvertureInput) params.set('ouverture', ouvertureInput);
    if (fermetureInput) params.set('fermeture', fermetureInput);
    if (etatInput) params.set('etat', etatInput);
    if (trieInput) params.set('trie', trieInput);

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
    const ouvertureInput = document.querySelector('input[name="ouverture"]');
    const fermetureInput = document.querySelector('input[name="fermeture"]');
    const etatInput = document.querySelector('select[name="etat"]');
    const trieInput = document.querySelector('select[name="trie"]');

    searchInput.addEventListener('input', function() {
        rechercher();
    });

    localisationInput.addEventListener('input', function() {
        rechercher();
    });

    

    ouvertureInput.addEventListener('input', function() {
        rechercher();
    });

    fermetureInput.addEventListener('input', function() {
        rechercher();
    });

    etatInput.addEventListener('change', function() {
        rechercher();
    });

    trieInput.addEventListener('change', function() {
        rechercher();
    });

    

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        rechercher();
    });
});