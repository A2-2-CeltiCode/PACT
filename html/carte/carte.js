// Initialisation de la carte
var map = L.map("map").setView([48.5146, -2.7653], 8);

var Spectacle = L.icon({
  iconUrl: '/Untitled/visite.svg',
  
  iconSize:     [38, 95],
  shadowSize:   [50, 64], 
  iconAnchor:   [22, 94], 
  shadowAnchor: [4, 62],  
  popupAnchor:  [-3, -76] 
});

var Visite = L.icon({
  iconUrl: '/Untitled/groupe.svg',

  iconSize:     [38, 95],
  shadowSize:   [50, 64], 
  iconAnchor:   [22, 94], 
  shadowAnchor: [4, 62],  
  popupAnchor:  [-3, -76] 
});

var Activite = L.icon({
  iconUrl: '/Untitled/parc.svg',

  iconSize:     [38, 95],
  shadowSize:   [50, 64], 
  iconAnchor:   [22, 94], 
  shadowAnchor: [4, 62],  
  popupAnchor:  [-3, -76] 
});

var Restaurant = L.icon({
  iconUrl: '/Untitled/restaurant.svg',

  iconSize:     [38, 95],
  shadowSize:   [50, 64], 
  iconAnchor:   [22, 94], 
  shadowAnchor: [4, 62],  
  popupAnchor:  [-3, -76] 
});

var Parc = L.icon({
  iconUrl: '/Untitled/visite.svg',

  iconSize:     [38, 95],
  shadowSize:   [50, 64], 
  iconAnchor:   [22, 94], 
  shadowAnchor: [4, 62],  
  popupAnchor:  [-3, -76] 
});

// Ajout des tuiles OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Récupération des points depuis le serveur
fetch("/trie/getOffre.php")
  .then((response) => response.json())
  .then((points) => {
    // Ajout des points sur la carte
    addMapMarkers(map, points);
  })
  .catch((error) => console.error("Erreur:", error));

initializeSearchForm("test");

//fonction

function clearMapMarkers(map) {
  map.eachLayer(function (layer) {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });
}

function addMapMarkers(map, points) {
  var markers = L.markerClusterGroup();

  points.forEach(function (point) {
    var iconType;
    switch (point.nomcategorie) {
      case 'Spectacle':
      iconType = Spectacle;
      break;
      case 'Visite':
      iconType = Visite;
      break;
      case 'Activite':
      iconType = Activite;
      break;
      case 'Restaurant':
      iconType = Restaurant;
      break;
      case 'Parc':
      iconType = Parc;
      break;
      default:
      iconType = Parc;
    }

    var marker = L.marker([point.coordonneesx, point.coordonneesy], { icon: iconType })
      .bindPopup(
      `
        <a href="../pages/visiteur/detailsOffre/detailsOffre.php?id=${
        point.idoffre
        }">
        <div class="carte-image-container">
          <img class="carte-offre-image" alt="" src="/ressources/${
          point.idoffre
          }/images/${point.nomimage}">
        </div>
        <div>
          <div>
          <p>${point.titre}</p>
          </div>
          <div>
          <p>${generateStars(point.moynotes)}</p>
          </div>
        </div>
        <div>
          <div>
          <p>${point.ville}</p>
          </div>
          <div>
          <p>${parseFloat(point.valprix).toFixed(2)}€</p>
          </div>
        </div>
        <div>
          <div>
          <p>${point.heureouverture.slice(
            0,
            5
          )} - ${point.heurefermeture.slice(0, 5)}</p>
          </div>
        </div>
        </a>
      `
      );
    markers.addLayer(marker);
  });
  map.addLayer(markers);
}

function generateStars(rating) {
  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;

  return "★".repeat(fullStars) + "☆".repeat(emptyStars);
}
