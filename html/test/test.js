let validCities = [];
let cityCoordinates = {};

function suggestVilles() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (input.length < 3) {
        document.getElementById('suggestions').innerHTML = '';
        validCities = [];
        cityCoordinates = {};
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
            const suggestions = data.features.map(feature => {
                const city = feature.properties.city;
                const coordinates = feature.geometry.coordinates;
                return `<div onclick="selectVille('${city}', ${coordinates[0]}, ${coordinates[1]})">${city}</div>`;
            }).join('');
            document.getElementById('suggestions').innerHTML = suggestions;
        })
        .catch(error => console.error('Erreur:', error));
}

function selectVille(city, lon, lat) {
    document.getElementById('ville').value = city;
    document.getElementById('suggestions').innerHTML = '';
    document.getElementById('coordinates').innerHTML = `Coordonnées GPS : Longitude ${lon}, Latitude ${lat}`;
}

function validateVille() {
    const input = document.getElementById('ville').value.toLowerCase();
    if (validCities.includes(input)) {
        const coordinates = cityCoordinates[input];
        document.getElementById('coordinates').innerHTML = `Coordonnées GPS : Longitude ${coordinates[0]}, Latitude ${coordinates[1]}`;
    } else {
        alert('Ville non valide. Veuillez sélectionner une ville proposée.');
    }
}

// Ajout du gestionnaire d'événements pour cacher les suggestions
document.addEventListener('click', function(event) {
    const input = document.getElementById('ville');
    const suggestions = document.getElementById('suggestions');
    if (event.target !== input && !suggestions.contains(event.target)) {
        suggestions.innerHTML = '';
    }
});