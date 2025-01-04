let validCities = [];
let cityPostcodes = {};
let selectedCity = '';

function suggestVilles() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (input.length < 3) {
        document.getElementById('suggestions').innerHTML = '';
        validCities = [];
        cityPostcodes = {};
        return;
    }

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${input}&type=municipality`)
        .then(response => response.json())
        .then(data => {
            if (!data.features) {
                console.error('Erreur: Réponse inattendue de l\'API', data);
                return;
            }
            validCities = data.features.map(feature => feature.properties.city.toLowerCase());
            cityPostcodes = data.features.reduce((acc, feature) => {
                acc[feature.properties.city.toLowerCase()] = feature.properties.postcode;
                return acc;
            }, {});
            const suggestions = data.features.map(feature => {
                const city = feature.properties.city;
                return `<div onclick="selectVille('${city}', '${feature.properties.postcode}')">${city}</div>`;
            }).join('');
            document.getElementById('suggestions').innerHTML = suggestions;
        })
        .catch(error => console.error('Erreur:', error));
}

function selectVille(city, postcode) {
    document.getElementById('ville').value = city;
    document.getElementById('suggestions').innerHTML = '';
    document.getElementById('postcode').value = postcode;
    selectedCity = city;
    console.log(`Ville sélectionnée : ${selectedCity}`);
}

function suggestAdresses() {
    const input = document.getElementById('adresse').value.toLowerCase();
    if (input.length < 3 || !selectedCity) {
        document.getElementById('adresseSuggestions').innerHTML = '';
        console.log('Pas assez de caractères ou ville non sélectionnée');
        return;
    }

    const query = `${input} ${selectedCity}`;

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.features) {
                console.error('Erreur: Réponse inattendue de l\'API', data);
                return;
            }
            const suggestions = data.features.map(feature => {
                const address = feature.properties.name;
                return `<div onclick="selectAdresse('${address}', ${feature.geometry.coordinates[0]}, ${feature.geometry.coordinates[1]})">${address}</div>`;
            }).join('');
            document.getElementById('adresseSuggestions').innerHTML = suggestions;
        })
        .catch(error => console.error('Erreur:', error));
}

function selectAdresse(address, lon, lat) {
    document.getElementById('adresse').value = address;
    document.getElementById('adresseSuggestions').innerHTML = '';
    document.getElementById('longitude').value = lon;
    document.getElementById('latitude').value = lat;
    console.log(`Adresse sélectionnée : ${address}, Longitude : ${lon}, Latitude : ${lat}`);
}

function validateVille() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (validCities.includes(input)) {
        const postcode = cityPostcodes[input];
        document.getElementById('postcode').value = postcode;
        document.getElementById('villeForm').submit();
    } else {
        alert('Ville non valide. Veuillez sélectionner une ville proposée.');
    }
}

// Ajout du gestionnaire d'événements pour cacher les suggestions
document.addEventListener('click', function(event) {
    const inputVille = document.getElementById('ville');
    const suggestionsVille = document.getElementById('suggestions');
    const inputAdresse = document.getElementById('adresse');
    const suggestionsAdresse = document.getElementById('adresseSuggestions');
    if (event.target !== inputVille && !suggestionsVille.contains(event.target)) {
        suggestionsVille.innerHTML = '';
    }
    if (event.target !== inputAdresse && !suggestionsAdresse.contains(event.target)) {
        suggestionsAdresse.innerHTML = '';
    }
});