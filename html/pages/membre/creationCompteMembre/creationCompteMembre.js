function formValide() {
  // Validation du nom (max 50)
  var nom = document.forms["creerCompteMembre"]["nom"].value;
  if (!/^.{1,50}$/.test(nom) && nom !== "") {
    alert("Le nom doit contenir au maximum 50 caractères.");
    return false;
  }

  // Validation du prenom (max 50)
  var prenom = document.forms["creerCompteMembre"]["prenom"].value;
  if (!/^.{1,50}$/.test(prenom) && prenom !== "") {
    alert("Le prenom doit contenir au maximum 50 caractères.");
    return false;
  }

  // Validation du pseudo (max 255)
  var pseudo = document.forms["creerCompteMembre"]["pseudo"].value;
  if (!/^.{1,255}$/.test(pseudo) && pseudo !== "") {
    alert("Le pseudo doit contenir au maximum 255 caractères.");
    return false;
  }

  // Validation de l'email : doit contenir un "@" et un "."
  var email = document.forms["creerCompteMembre"]["email"].value;
  if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,255}$/.test(email)) {
    alert(
      "L'adresse email n'est pas valide et doit contenir au maximum 255 caractères."
    );
    return false;
  }

  // Validation du numéro de téléphone : uniquement des chiffres (max 10)
  var telephone = document.forms["creerCompteMembre"]["telephone"].value;
  if (!/^\d{10}$/.test(telephone) && telephone !== "") {
    alert("Le numéro de téléphone doit contenir uniquement 10 chiffres.");
    return false;
  }

  // Validation de la rue (max 50)
  var rue = document.forms["creerCompteMembre"]["rue"].value;
  if (!/^.{1,50}$/.test(rue) && rue !== "") {
    alert("La rue doit contenir au maximum 50 caractères.");
    return false;
  }

  // Validation du code postal : 5 chiffres
  var codePostal = document.forms["creerCompteMembre"]["codePostal"].value;
  if (!/^\d{5}$/.test(codePostal)) {
    alert("Le code postal doit contenir 5 chiffres.");
    return false;
  }

  // Validation de la ville : uniquement des lettres
  var ville = document.forms["creerCompteMembre"]["ville"].value;
  if (!/^[A-Za-z\s-]{1,50}$/.test(ville)) {
    alert(
      "La ville peut contenir uniquement des lettres, des espaces et des tirets."
    );
    return false;
  }

  // Validation du mot de passe : respect des règles
  var motDePasse = document.forms["creerCompteMembre"]["motDePasse"].value;
  if (
    !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,255}/.test(
      motDePasse
    )
  ) {
    alert(
      "Le mot de passe doit comporter au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial et au maximum 255 caractères."
    );
    return false;
  }

  // Validation de la confirmation du mot de passe : doit correspondre
  var confirmMdp = document.forms["creerCompteMembre"]["confirmMdp"].value;
  if (motDePasse !== confirmMdp) {
    alert("Les mots de passe ne correspondent pas.");
    return false;
  }

  return true; // Si toutes les validations passent, soumettre le formulaire
}
document.addEventListener("DOMContentLoaded", function () {
  console.log("formValide");
  // Initialisation de la carte
  var map = L.map("map").setView([48.202, -2.9326], 7);

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
          document.getElementById("postcode").value =""
        }
      })
      .catch((error) => console.error("Erreur:", error));
  }

  function selectVille(city, lon, lat, postcode) {
    document.getElementById("ville").value = city;
    document.getElementById("adresse").value = "";
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
    if (input.length < 3 || ville !== selectedCity || !postcode) {
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

  window.suggestVilles = suggestVilles;
  window.selectVille = selectVille;
  window.suggestAdresses = suggestAdresses;
  window.selectAdresse = selectAdresse;
  window.validateVille = selectVille;

});
