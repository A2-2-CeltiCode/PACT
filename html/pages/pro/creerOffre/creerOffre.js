document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.querySelector('select[name="typeOffre"]');
    const sections = document.querySelectorAll('.section');
    const promotionSelectElement = document.querySelector('select[name="typePromotion"]');
    const datePromotionContainer = document.getElementById('datePromotionContainer');

    // Fonction pour masquer toutes les sections
    function hideAllSections() {
        sections.forEach(section => {
            section.style.display = 'none';
        });
    }

    // Fonction pour afficher la section correspondante
    function showSection(typeOffre) {
        hideAllSections(); // On masque toutes les sections d'abords
        const section = document.getElementById(typeOffre);
        if (section) {
            section.style.display = 'block';
        }
    }

    // Événement déclenché lorsque le type d'offre est modifié
    selectElement.addEventListener('change', function () {
        const selectedValue = this.value;
        showSection(selectedValue);
    });

    // On cache toutes les sections au début
    hideAllSections();

    // Événement déclenché lorsque le type de promotion est modifié
    promotionSelectElement.addEventListener('change', function () {
        const selectedValue = this.value;
        datePromotionContainer.style.display = selectedValue === 'Aucune' ? 'none' : 'block';
    });

    function getSelectedCheckboxes() {
        const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]:checked');
        return Array.from(checkboxes).map(checkbox => checkbox.value);
    }

    function displaySelectedValues() {
        const selectedValues = getSelectedCheckboxes();
        const displayDivs = document.querySelectorAll('.selected-values');
        displayDivs.forEach(displayDiv => {
            displayDiv.innerHTML = '';
            selectedValues.forEach(value => {
                const valueDiv = document.createElement('div');
                valueDiv.textContent = value;
                displayDiv.appendChild(valueDiv);
            });
        });
    }

    function resetCheckboxes() {
        const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        displaySelectedValues(); // Mettre à jour l'affichage après réinitialisation
    }

    // Mettre à jour les valeurs affichées lorsque les cases à cocher sont modifiées
    document.querySelectorAll('.checkbox-select input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', displaySelectedValues);
    });

    // Réinitialiser les cases à cocher lorsque le select typeOffre change
    selectElement.addEventListener('change', resetCheckboxes);

    // Afficher les valeurs sélectionnées au chargement de la page
    displaySelectedValues();

    function toggleLangue(show) {
        const langueDiv = document.getElementById("langue");
        langueDiv.style.display = show ? "block" : "none";
    }

    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle("show");
    }

    function validateForm() {
        const durepromotion = document.getElementById("durepromotion").value;
        if (durepromotion > 4) {
            alert("La durée de la promotion ne doit pas dépasser 4.");
            return false;
        }
        return true;
    }

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

        fetch(`https://api-adresse.data.gouv.fr/search/?q=${input}&type=municipality&limit=10`)
            .then(response => response.json())
            .then(data => {
                if (data.features.length === 0) {
                    document.getElementById('suggestions').innerHTML = '<div>Aucune ville trouvée</div>';
                    return;
                }
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
        selectedCity = city.toLowerCase();
    }

    function suggestAdresses() {
        const input = document.getElementById('adresse').value.toLowerCase();
        const ville = document.getElementById('ville').value.toLowerCase();
        const postcode = document.getElementById('postcode').value;
        if (input.length < 3 || !selectedCity || ville !== selectedCity || !postcode) {
            document.getElementById('adresseSuggestions').innerHTML = '';
            return;
        }

        fetch(`https://api-adresse.data.gouv.fr/search/?q=${input}&postcode=${postcode}&limit=100`)
            .then(response => response.json())
            .then(data => {
                if (data.features.length === 0) {
                    document.getElementById('adresseSuggestions').innerHTML = '<div>Aucune adresse trouvée</div>';
                    return;
                }
                const suggestions = data.features
                    .filter(feature => feature.properties.city.toLowerCase() === selectedCity)
                    .map(feature => {
                        const address = feature.properties.name;
                        const street = feature.properties.street || '';
                        const housenumber = feature.properties.housenumber || '';
                        return `<div onclick="selectAdresse('${housenumber} ${street} ${address}')">${housenumber} ${street} ${address}</div>`;
                    }).join('');
                document.getElementById('adresseSuggestions').innerHTML = suggestions;
            })
            .catch(error => console.error('Erreur:', error));
    }

    function selectAdresse(address) {
        document.getElementById('adresse').value = address;
        document.getElementById('adresseSuggestions').innerHTML = '';

        // Récupérer les coordonnées de l'adresse exacte
        fetch(`https://api-adresse.data.gouv.fr/search/?q=${address}&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data.features.length > 0) {
                    const coordinates = data.features[0].geometry.coordinates;
                    document.getElementById('longitude').value = coordinates[0];
                    document.getElementById('latitude').value = coordinates[1];
                }
            })
            .catch(error => console.error('Erreur:', error));
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

    // Expose functions to global scope if needed
    window.suggestVilles = suggestVilles;
    window.selectVille = selectVille;
    window.suggestAdresses = suggestAdresses;
    window.selectAdresse = selectAdresse;
    window.validateVille = validateVille;
    window.toggleLangue = toggleLangue;
    window.toggleDropdown = toggleDropdown;
    window.validateForm = validateForm;
});

