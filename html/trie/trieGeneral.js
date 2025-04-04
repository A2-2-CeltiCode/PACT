function rechercher() {
  const searchInput = document.querySelector('input[name="titre"]').value;
  const localisationInput = document.querySelector('input[name="localisation"]').value;
  const minPrixInput = document.querySelector('input[name="minPrix"]').value;
  const maxPrixInput = document.querySelector('input[name="maxPrix"]').value;
  const ouvertureInput = document.querySelector('input[name="ouverture"]').value;
  const fermetureInput = document.querySelector('input[name="fermeture"]').value;
  const etatInput = document.querySelector('select[name="etat"]').value;
  const trieInput = document.querySelector('select[name="trie"]').value;
  const statusInput = document.querySelector('input[name="status"]').value;
  const noteInput = document.querySelector('input[name="note"]').value;
  const inputNote = document.querySelector('input[name="inputnoteValue"]').value;

  const categoryInputs = Array.from(document.querySelectorAll('input[name="nomcategorie[]"]:checked')).map((input) => input.value);
  const optionInputs = Array.from(document.querySelectorAll('input[name="option[]"]:checked')).map((input) => input.value);

  const params = new URLSearchParams();
  if (searchInput) params.append("titre", searchInput);
  if (localisationInput) params.append("localisation", localisationInput);
  if (minPrixInput) params.append("minPrix", minPrixInput);
  if (maxPrixInput) params.append("maxPrix", maxPrixInput);
  if (ouvertureInput) params.append("ouverture", ouvertureInput);
  if (fermetureInput) params.append("fermeture", fermetureInput);
  if (etatInput) params.append("etat", etatInput);
  if (trieInput) params.append("trie", trieInput);
  if (statusInput) params.append("status", statusInput);
  if (noteInput) params.append("note", noteInput);
  if (inputNote) params.append("inputnote", inputNote);
  if (categoryInputs.length > 0) params.append("nomcategorie", categoryInputs.join(","));
  if (optionInputs.length > 0) params.append("option", optionInputs.join(","));

  // Compter le nombre de filtres actifs
  let filtreActifCount = 0;
  if (searchInput) filtreActifCount++;
  if (localisationInput) filtreActifCount++;
  if (minPrixInput && minPrixInput != 0) filtreActifCount++;
  if (maxPrixInput && maxPrixInput != 100) filtreActifCount++;
  if (ouvertureInput) filtreActifCount++;
  if (fermetureInput) filtreActifCount++;
  if (etatInput && etatInput !== 'ouvertetferme') filtreActifCount++;
  if (trieInput && trieInput !== 'idoffre DESC') filtreActifCount++;
  if (statusInput) filtreActifCount++;
  if (noteInput && noteInput != 0) filtreActifCount++;
  if (inputNote) filtreActifCount++;
  if (categoryInputs.length > 0) filtreActifCount++;
  if (optionInputs.length > 0) filtreActifCount++;

  // Afficher le nombre de filtres actifs
  const nombreFiltresActifs = filtreActifCount - 3;
  document.getElementById("nombreFiltresActifs").innerText = `Nombre de filtres actifs : ${nombreFiltresActifs < 0 ? 0 : nombreFiltresActifs}`;

  const xhr = new XMLHttpRequest();
  xhr.open("GET", `listeOffres.php?${params.toString()}`, true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        const resultatsContainer = document.getElementById("resultats");
        resultatsContainer.innerHTML = response.offres.join("");
        document.getElementById("nombreOffres").innerHTML = `Nombre d'offres affichées : ${response.nombreOffres}`;
        applyStyles();
      } catch (e) {
        console.error("Erreur lors du traitement de la réponse JSON:", e);
      }
    } else {
      console.error("Erreur lors de la requête AJAX:", xhr.status, xhr.statusText);
    }
  };
  xhr.send();
}

function applyStyles() {
  const offres = document.querySelectorAll(".offre");
  offres.forEach((offre) => {
    offre.style.margin = "10px auto";
  });
}

function changerStatus(nouveauStatus) {
  document.querySelectorAll(".onglet").forEach((onglet) => {
    onglet.classList.remove("actif");
  });

  event.target.classList.add("actif");

  const url = new URL(window.location);
  url.searchParams.set("status", nouveauStatus);
  window.history.pushState({}, "", url);

  document.querySelector('input[name="status"]').value = nouveauStatus;

  rechercher();
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("searchForm");
  const searchInput = document.querySelector('input[name="titre"]');
  const localisationInput = document.querySelector('input[name="localisation"]');
  const minPrixInput = document.querySelector('input[name="minPrix"]');
  const maxPrixInput = document.querySelector('input[name="maxPrix"]');
  const ouvertureInput = document.querySelector('input[name="ouverture"]');
  const fermetureInput = document.querySelector('input[name="fermeture"]');
  const etatInput = document.querySelector('select[name="etat"]');
  const trieInput = document.querySelector('select[name="trie"]');
  const statusInput = document.querySelector('input[name="status"]');
  const categoryInputs = document.querySelectorAll('input[name="nomcategorie[]"]');
  const optionInputs = document.querySelectorAll('input[name="option[]"]');
  const noteInput = document.querySelector('input[name="note"]');
  const inputNote = document.querySelector('input[name="inputnoteValue"]');

  searchInput.addEventListener("input", function () {
    rechercher();
  });

  localisationInput.addEventListener("input", function () {
    rechercher();
  });

  minPrixInput.addEventListener("mouseout", function () {
    rechercher();
  });

  maxPrixInput.addEventListener("mouseout", function () {
    rechercher();
  });

  ouvertureInput.addEventListener("input", function () {
    rechercher();
  });

  fermetureInput.addEventListener("input", function () {
    rechercher();
  });

  etatInput.addEventListener("change", function () {
    rechercher();
  });

  trieInput.addEventListener("change", function () {
    rechercher();
  });

  statusInput.addEventListener("input", function () {
    rechercher();
  });

  categoryInputs.forEach((input) => {
    input.addEventListener("change", function () {
      rechercher();
    });
  });

  optionInputs.forEach((input) => {
    input.addEventListener("change", function () {
      rechercher();
    });
  });

  noteInput.addEventListener("mouseout", function () {
    rechercher();
  });

  inputNote.addEventListener("mouseout", function () {
    rechercher();
  });

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    rechercher();
  });

  form.addEventListener("change", rechercher); 
});
