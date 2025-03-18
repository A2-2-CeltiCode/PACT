// Initialisation de la carte
var map = L.map("map");

var Spectacle = L.icon({
  iconUrl: "/Untitled/visite.svg",

  iconSize: [38, 95],
  shadowSize: [50, 64],
  iconAnchor: [22, 94],
  shadowAnchor: [4, 62],
  popupAnchor: [-3, -76],
});

var Visite = L.icon({
  iconUrl: "/Untitled/groupe.svg",

  iconSize: [38, 95],
  shadowSize: [50, 64],
  iconAnchor: [22, 94],
  shadowAnchor: [4, 62],
  popupAnchor: [-3, -76],
});

var Activite = L.icon({
  iconUrl: "/Untitled/parc.svg",

  iconSize: [38, 95],
  shadowSize: [50, 64],
  iconAnchor: [22, 94],
  shadowAnchor: [4, 62],
  popupAnchor: [-3, -76],
});

var Restaurant = L.icon({
  iconUrl: "/Untitled/restaurant.svg",

  iconSize: [38, 95],
  shadowSize: [50, 64],
  iconAnchor: [22, 94],
  shadowAnchor: [4, 62],
  popupAnchor: [-3, -76],
});

var Parc = L.icon({
  iconUrl: "/Untitled/visite.svg",

  iconSize: [38, 95],
  shadowSize: [50, 64],
  iconAnchor: [22, 94],
  shadowAnchor: [4, 62],
  popupAnchor: [-3, -76],
});

// Ajout des tuiles OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);


const ville = document.getElementById("ville").value;
const adresse = document.getElementById("adresse").value;
const postcode = document.getElementById("postcode").value;
if (ville && adresse) {
  fetch(
    `https://api-adresse.data.gouv.fr/search/?q=${adresse},${ville},${postcode}&limit=1`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.features.length > 0) {
        const coordinates = data.features[0].geometry.coordinates;
        document.getElementById("longitude").value = coordinates[0];
        document.getElementById("latitude").value = coordinates[1];
        points = [
          {
            coordonneesx: coordinates[1].toString(),
            coordonneesy: coordinates[0].toString(),
          },
        ];
        addMapMarkers(map, points, 13);
      } else {
        alert("Aucune coordonnée trouvée pour cette adresse.");
      }
    })
    .catch((error) => console.error("Erreur:", error));
} else {
  alert("Veuillez remplir les champs ville et adresse.");
}


function addMapMarkers(map, points, zoom) {
  clearMapMarkers(map);
  points.forEach((point) => {
    L.marker([point.coordonneesx, point.coordonneesy]).addTo(map);
    map.setView([point.coordonneesx, point.coordonneesy], zoom);
  });
}

function clearMapMarkers(map) {
  map.eachLayer(function (layer) {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });
}

// Fonction pour afficher ou masquer un élément spécifique (langueDiv ici)
function toggleLangue(show) {
  var langueDiv = document.getElementById("langue");
  if (show) {
    langueDiv.style.display = "block";
  } else {
    langueDiv.style.display = "none";
  }
}

// Fonction pour récupérer les valeurs des cases à cocher qui sont actuellement sélectionnées
function getSelectedCheckboxes() {
  const checkboxes = document.querySelectorAll(
    '.checkbox-select input[type="checkbox"]:checked'
  );
  const selectedValues = [];
  checkboxes.forEach((checkbox) => {
    selectedValues.push(checkbox.value);
  });
  return selectedValues;
}

// Fonction pour afficher les valeurs sélectionnées dans les divs avec la classe "selected-values"
function displaySelectedValues() {
  const selectedValues = getSelectedCheckboxes();
  const displayDivs = document.querySelectorAll(".selected-values");
  displayDivs.forEach((displayDiv) => {
    displayDiv.innerHTML = "";
    selectedValues.forEach((value) => {
      const valueDiv = document.createElement("div");
      valueDiv.textContent = value;
      displayDiv.appendChild(valueDiv);
    });
  });
}

// Fonction pour réinitialiser toutes les cases à cocher et mettre à jour l'affichage
function resetCheckboxes() {
  const checkboxes = document.querySelectorAll(
    '.checkbox-select input[type="checkbox"]'
  );
  checkboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });
  displaySelectedValues();
}

// Ajoute un gestionnaire d'événement "change" sur chaque case à cocher
document
  .querySelectorAll('.checkbox-select input[type="checkbox"]')
  .forEach((checkbox) => {
    checkbox.addEventListener("change", displaySelectedValues); // Met à jour l'affichage en cas de modification
  });

// Affiche les valeurs sélectionnées dès le chargement de la page
document.addEventListener("DOMContentLoaded", (event) => {
  displaySelectedValues(); // Met à jour l'affichage dès que la page est complètement chargée
});




document.addEventListener("DOMContentLoaded", function () {
  function validateVilleAdresseCodePostal() {
    const ville = document.getElementById("ville").value.toLowerCase();
    const adresse = document.getElementById("adresse").value.toLowerCase();
    const postcode = document.getElementById("postcode").value;
    fetch(
      `https://api-adresse.data.gouv.fr/search/?q=${adresse},${ville},${postcode}&limit=1`
    )
      .then((response) => response.json())
      .then((data) => {
    if (data.features.length === 0) {
      alert("Veuillez sélectionner une ville valide.");
      return false;
    }
    console.log(data.features[0].properties.city);
    return true;
    })
  }

  

  function validateForm() {
    
    if (validateVilleAdresseCodePostal()==false) {
      return false;
    }
    return true;
  }

  let validCities = [];
  let cityCoordinates = {};
  let cityPostcodes = {};
  let selectedCity = "";

  function suggestVilles() {
    const input = document.getElementById("ville").value.toLowerCase();
    if (input.length < 3) {
      document.getElementById("suggestions").innerHTML = "";
      validCities = [];
      cityCoordinates = {};
      cityPostcodes = {};
      return;
    }

    fetch(
      `https://api-adresse.data.gouv.fr/search/?q=${input}&type=municipality&limit=10`
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.features.length === 0) {
          document.getElementById("suggestions").innerHTML =
            "<div>Aucune ville trouvée</div>";
          return;
        }
        validCities = data.features.map((feature) =>
          feature.properties.city.toLowerCase()
        );
        cityCoordinates = data.features.reduce((acc, feature) => {
          acc[feature.properties.city.toLowerCase()] =
            feature.geometry.coordinates;
          return acc;
        }, {});
        cityPostcodes = data.features.reduce((acc, feature) => {
          acc[feature.properties.city.toLowerCase()] =
            feature.properties.postcode;
          return acc;
        }, {});
        const suggestions = data.features
          .map((feature) => {
            const city = feature.properties.city;
            const coordinates = feature.geometry.coordinates;
            return `<div onclick="selectVille('${city}', ${coordinates[0]}, ${coordinates[1]}, '${feature.properties.postcode}')">${city}</div>`;
          })
          .join("");
        document.getElementById("suggestions").innerHTML = suggestions;

        // Mettre à jour la carte si une seule ville est trouvée ou si le nom de la ville correspond à l'entrée
        if (data.features.length === 1 || validCities.includes(input)) {
          const coordinates = data.features[0].geometry.coordinates;
          points = [
            {
              coordonneesx: coordinates[1].toString(),
              coordonneesy: coordinates[0].toString(),
            },
          ];
          addMapMarkers(map, points, 10);
          document.getElementById("postcode").value =
            data.features[0].properties.postcode;
        } else {
          clearMapMarkers(map);
        }
      })
      .catch((error) => console.error("Erreur:", error));
  }

  function selectVille(city, lon, lat, postcode) {
    document.getElementById("ville").value = city;
    document.getElementById("adresse").value="";
    document.getElementById("suggestions").innerHTML = "";
    document.getElementById("longitude").value = lon;
    document.getElementById("latitude").value = lat;
    document.getElementById("postcode").value = postcode;
    selectedCity = city.toLowerCase();
    points = [{ coordonneesx: lat.toString(), coordonneesy: lon.toString() }];
    addMapMarkers(map, points, 10);
  }

  function suggestAdresses() {
    const input = document.getElementById("adresse").value.toLowerCase();
    const ville = document.getElementById("ville").value.toLowerCase();
    const postcode = document.getElementById("postcode").value;
    if (input.length < 3) {
      document.getElementById("adresseSuggestions").innerHTML = "";
      return;
    }

    fetch(
      `https://api-adresse.data.gouv.fr/search/?q=${input}&postcode=${postcode}&limit=8`
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.features.length === 0) {
          document.getElementById("adresseSuggestions").innerHTML =
            "<div>Aucune adresse trouvée</div>";
          return;
        }

        const suggestions = data.features
          .filter(
            (feature) => feature.properties.city.toLowerCase() === selectedCity
          )
          .map((feature) => {
            const address = feature.properties.name;
            const street = feature.properties.street || "";
            const housenumber = feature.properties.housenumber || "";
            return `<div onclick="selectAdresse('${housenumber} ${street}')">${housenumber} ${street}</div>`;
          })
          .join("");
        document.getElementById("adresseSuggestions").innerHTML = suggestions;

        // Mettre à jour la carte si une seule adresse est trouvée ou si l'input est égal à une adresse
        if (
          data.features.length === 1 ||
          data.features.some(
            (feature) => feature.properties.name.toLowerCase() === input
          )
        ) {
          const coordinates = data.features[0].geometry.coordinates;
          points = [
            {
              coordonneesx: coordinates[1].toString(),
              coordonneesy: coordinates[0].toString(),
            },
          ];
          addMapMarkers(map, points, 15);
        } else {
          fetch(
            `https://api-adresse.data.gouv.fr/search/?q=${ville}&type=municipality&limit=10`
          )
            .then((response) => response.json())
            .then((data) => {
              const coordinates = data.features[0].geometry.coordinates;
              points = [
                {
                  coordonneesx: coordinates[1].toString(),
                  coordonneesy: coordinates[0].toString(),
                },
              ];
              addMapMarkers(map, points, 10);
            });
        }
      })
      .catch((error) => console.error("Erreur:", error));
  }

  function selectAdresse(address) {
    document.getElementById("adresse").value = address;
    document.getElementById("adresseSuggestions").innerHTML = "";

    // Récupérer les coordonnées de l'adresse exacte
    fetch(`https://api-adresse.data.gouv.fr/search/?q=${address}&limit=1`)
      .then((response) => response.json())
      .then((data) => {
        if (data.features.length > 0) {
          const coordinates = data.features[0].geometry.coordinates;
          document.getElementById("longitude").value = coordinates[0];
          document.getElementById("latitude").value = coordinates[1];
          points = [
            {
              coordonneesx: coordinates[1].toString(),
              coordonneesy: coordinates[0].toString(),
            },
          ];
          addMapMarkers(map, points, 15);
        }
      })
      .catch((error) => console.error("Erreur:", error));
  }

  // Ajout du gestionnaire d'événements pour cacher les suggestions
  document.addEventListener("click", function (event) {
    const inputVille = document.getElementById("ville");
    const suggestionsVille = document.getElementById("suggestions");
    const inputAdresse = document.getElementById("adresse");
    const suggestionsAdresse = document.getElementById("adresseSuggestions");
    if (
      event.target !== inputVille &&
      !suggestionsVille.contains(event.target)
    ) {
      suggestionsVille.innerHTML = "";
    }
    if (
      event.target !== inputAdresse &&
      !suggestionsAdresse.contains(event.target)
    ) {
      suggestionsAdresse.innerHTML = "";
    }
  });







  // Expose functions to global scope if needed
  window.suggestVilles = suggestVilles;
  window.selectVille = selectVille;
  window.suggestAdresses = suggestAdresses;
  window.selectAdresse = selectAdresse;
  window.validateVille = selectVille;
  window.toggleLangue = toggleLangue;
  window.toggleDropdown = toggleDropdown;
  window.validateForm = validateForm;


});
