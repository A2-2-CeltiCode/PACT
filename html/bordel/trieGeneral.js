function trier(sort) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sort);

    fetch('/bordel/trieGeneral.php?' + urlParams.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultatsDiv = document.getElementById('resultats');
        resultatsDiv.innerHTML = '';
        data.forEach(item => {
            const offreDiv = document.createElement('div');
            offreDiv.innerHTML = item;
            resultatsDiv.appendChild(offreDiv);
        });
    })
    .catch(error => console.error('Error:', error));
}

function rechercher() {
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);
    const urlParams = new URLSearchParams(formData);

    fetch('/bordel/trieGeneral.php?' + urlParams.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultatsDiv = document.getElementById('resultats');
        resultatsDiv.innerHTML = '';
        data.forEach(item => {
            const offreDiv = document.createElement('div');
            offreDiv.innerHTML = item;
            resultatsDiv.appendChild(offreDiv);
        });
    })
    .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const sortInput = document.getElementById('sortInput');
    const searchInput = document.querySelector('input[name="titre"]');
    const minPrixInput = document.querySelector('input[name="minPrix"]');
    const maxPrixInput = document.querySelector('input[name="maxPrix"]');
    const categoryInputs = document.querySelectorAll('input[name="nomcategorie[]"]');

    searchInput.addEventListener('input', function() {
        rechercher();
    });

    minPrixInput.addEventListener('input', function() {
        rechercher();
    });

    maxPrixInput.addEventListener('input', function() {
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