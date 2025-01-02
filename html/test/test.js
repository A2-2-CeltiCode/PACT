let validCities = [];
let cityCoordinates = {};
let cityPostcodes = {};
let selectedCity = '';

function suggestVilles() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (input.length < 3) {
        document.getElementById('suggestions').innerHTML = '';
        validCities = [];
        cityCoordinates = {};
        cityPostcodes = {};
        return;
    }

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${input}&type=municipality`)
        .then(response => response.json())
        .then(data => {
            validCities = data.features.map(feature => feature.properties.city.toLowerCase());
            cityCoordinates = data.features.reduce((acc, feature) => {
                acc[feature.properties.city.toLowerCase()] = feature.geometry.coordinates;
                return acc;
            }, {});
            cityPostcodes = data.features.reduce((acc, feature) => {
                acc[feature.properties.city.toLowerCase()] = feature.properties.postcode;
                return acc;
            }, {});
            const suggestions = data.features.map(feature => {
                const city = feature.properties.city;
                const coordinates = feature.geometry.coordinates;
                return `<div onclick="selectVille('${city}', ${coordinates[0]}, ${coordinates[1]}, '${feature.properties.postcode}')">${city}</div>`;
            }).join('');
            document.getElementById('suggestions').innerHTML = suggestions;
        })
        .catch(error => console.error('Erreur:', error));
}

function selectVille(city, lon, lat, postcode) {
    document.getElementById('ville').value = city;
    document.getElementById('suggestions').innerHTML = '';
    document.getElementById('longitude').value = lon;
    document.getElementById('latitude').value = lat;
    document.getElementById('postcode').value = postcode;
    selectedCity = city;
}

function suggestAdresses() {
    const input = document.getElementById('adresse').value.toLowerCase();
    if (input.length < 3 || !selectedCity) {
        document.getElementById('adresseSuggestions').innerHTML = '';
        return;
    }

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${input}&city=${selectedCity}`)
        .then(response => response.json())
        .then(data => {
            const suggestions = data.features.map(feature => {
                const address = feature.properties.name;
                return `<div onclick="selectAdresse('${address}')">${address}</div>`;
            }).join('');
            document.getElementById('adresseSuggestions').innerHTML = suggestions;
        })
        .catch(error => console.error('Erreur:', error));
}

function selectAdresse(address) {
    document.getElementById('adresse').value = address;
    document.getElementById('adresseSuggestions').innerHTML = '';
}

function validateVille() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (validCities.includes(input)) {
        const coordinates = cityCoordinates[input];
        const postcode = cityPostcodes[input];
        document.getElementById('longitude').value = coordinates[0];
        document.getElementById('latitude').value = coordinates[1];
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