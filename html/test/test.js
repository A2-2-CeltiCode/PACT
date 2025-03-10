// Initialisation de la carte
var map = L.map("map").setView([48.5146, -2.7653], 8);

// Ajout des tuiles OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Récupération des points depuis le serveur
fetch("get_points.php?trie=" + encodeURIComponent(document.querySelector('select[name="trie"]').value))
  .then((response) => response.json())
  .then((points) => {
    // Ajout des points sur la carte
    points.forEach(function (point) {
    L.marker([point.coordonneesx, point.coordonneesy])
      .addTo(map)
      .bindPopup(
        `<div>
          <h3>${point.titre}</h3>
          <img src="/ressources/${point.idoffre}/images/${point.nomimage}" style="width:200px;height:auto;">
        </div>`
      );
    });
  })
  .catch((error) => console.error("Erreur:", error));


