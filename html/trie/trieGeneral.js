let lastRequest;
let searchTimeout;

function rechercher() {
  

  const params = new URLSearchParams();
  const filtreActifCount = [
    {
      name: "titre",
      value: document.querySelector('input[name="titre"]').value,
    },
    {
      name: "localisation",
      value: document.querySelector('input[name="localisation"]').value,
    },
    {
      name: "minPrix",
      value: document.querySelector('input[name="minPrix"]').value,
      default: 0,
    },
    {
      name: "maxPrix",
      value: document.querySelector('input[name="maxPrix"]').value,
      default: 100,
    },
    {
      name: "ouverture",
      value: document.querySelector('input[name="ouverture"]').value,
    },
    {
      name: "fermeture",
      value: document.querySelector('input[name="fermeture"]').value,
    },
    {
      name: "etat",
      value: document.querySelector('select[name="etat"]').value,
      default: "ouvertetferme",
    },
    {
      name: "trie",
      value: document.querySelector('select[name="trie"]').value,
      default: "idoffre DESC",
    },
    {
      name: "status",
      value: document.querySelector('input[name="status"]').value,
    },
    {
      name: "note",
      value: document.querySelector('input[name="note"]').value,
      default: 0,
    },
    {
      name: "inputnote",
      value: document.querySelector('input[name="inputnoteValue"]').value,
    },
    {
      name: "nomcategorie",
      value: Array.from(
        document.querySelectorAll('input[name="nomcategorie[]"]:checked')
      )
        .map((input) => input.value)
        .join(","),
    },
    {
      name: "option",
      value: Array.from(
        document.querySelectorAll('input[name="option[]"]:checked')
      )
        .map((input) => input.value)
        .join(","),
    },
  ].reduce((count, { name, value, default: defaultValue }) => {
    if (value && value != defaultValue) {
      params.append(name, value);
      return count + 1;
    }
    return count;
  }, 0);

  document.getElementById(
    "nombreFiltresActifs"
  ).innerText = `Nombre de filtres actifs : ${Math.max(
    filtreActifCount - 3,
    0
  )}`;

  const xhr = new XMLHttpRequest();
  lastRequest = xhr;
  xhr.open("GET", `listeOffres.php?${params.toString()}`, true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        document.getElementById("resultats").innerHTML =
          response.offres.join("");
        document.getElementById(
          "nombreOffres"
        ).innerHTML = `Nombre d'offres affichées : ${response.nombreOffres}`;
        applyStyles();
      } catch (e) {
        console.error("Erreur lors du traitement de la réponse JSON:", e);
      }
    } else {
      console.error(
        "Erreur lors de la requête AJAX:",
        xhr.status,
        xhr.statusText
      );
    }
  };
  xhr.send();
}

function applyStyles() {
  document.querySelectorAll(".offre").forEach((offre) => {
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
  const inputs = [
    'input[name="titre"]',
    'input[name="localisation"]',
    'input[name="minPrix"]',
    'input[name="maxPrix"]',
    'input[name="ouverture"]',
    'input[name="fermeture"]',
    'select[name="etat"]',
    'select[name="trie"]',
    'input[name="status"]',
    'input[name="note"]',
    'input[name="inputnoteValue"]',
    'input[name="nomcategorie[]"]',
    'input[name="option[]"]',
  ];

  inputs.forEach((selector) => {
    document.querySelectorAll(selector).forEach((input) => {
      input.addEventListener("input", () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(rechercher, 300);
      });
      input.addEventListener("change", rechercher);
    });
  });

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    rechercher();
  });
});
