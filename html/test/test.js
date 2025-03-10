// Initialisation de la carte
var map = L.map("map").setView([48.5146, -2.7653], 8);

// Ajout des tuiles OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Récupération des points depuis le serveur
fetch("get_points.php")
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
  points.forEach(function (point) {
    L.marker([point.coordonneesx, point.coordonneesy])
      .addTo(map)
      .bindPopup(
        `

          <a href="../pages/visiteur/detailsOffre/detailsOffre.php?id=${point.idoffre}">
            <div class="image-container">
              <img class="offre-image" alt="" src="/ressources/${
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
  });
}

function generateStars(rating) {
  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;

  return "★".repeat(fullStars) + "☆".repeat(emptyStars);
}
