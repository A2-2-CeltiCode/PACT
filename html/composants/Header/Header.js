let selecteurLangue = document.getElementsByClassName("selecteur-langue");
let logoLangue = document.getElementsByClassName("logo-langue");

for (const selecteur of selecteurLangue) {
    selecteur.addEventListener("input", (e) => {
        for (const logo of logoLangue) {
            logo.src = `/ressources/icone/logo${e.target.value}.svg`
        }
        for (const sl of selecteurLangue) {
            sl.value = e.target.value
        }
    });
}

const menu = document.querySelector(".menu")
const menuItems = document.querySelectorAll(".menuItem")
const hamburger = document.querySelector(".hamburger")
const closeIcon = document.querySelector(".closeIcon")
const menuIcon = document.querySelector(".menuIcon")
const overlay = document.getElementById("overlay")

function toggleMenu() {
    if (menu.classList.contains("showMenu")) {
        menu.classList.remove("showMenu")
        closeIcon.style.display = "none"
        menuIcon.style.display = "block"
        document.body.classList.remove("noscroll")
        overlay.style.visibility = "hidden"
    } else {
        menu.classList.add("showMenu");
        closeIcon.style.display = "block"
        menuIcon.style.display = "none"
        document.body.classList.add("noscroll")
        overlay.style.visibility = "visible"
    }
}

function toggleArrow() {
    const profileOption = document.getElementById('profile-option');
    const selectMenu = document.getElementById('selecteur-profil');

    // Ajouter un log pour déboguer
    console.log("Valeur sélectionnée : ", selectMenu.value);

    // Vérifier si l'option sélectionnée est la valeur par défaut ou une autre
    if (selectMenu.value === "default") {
        profileOption.textContent = 'Mon compte ▼'; // Menu fermé
    } else {
        profileOption.textContent = 'Mon compte ▲'; // Menu déroulé
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById("notification-dropdown");
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
}

function openReplyPopup(idAvis) {
    const popup = document.getElementById('popup-repondre');
    const idAvisInput = document.getElementById('popup-idAvis');
    idAvisInput.value = idAvis;
    popup.style.display = 'block';
}

function closeReplyPopup() {
    const popup = document.getElementById('popup-repondre');
    popup.style.display = 'none';
}

function markAsSeen(idAvis) {
    fetch(`/pages/pro/detailsOffre/markAsSeen.php?idAvis=${idAvis}`, {
        method: 'POST'
    }).then(response => {
        if (response.ok) {
            document.querySelector(`.review[data-id="${idAvis}"]`).remove();
            const notificationCount = document.querySelector('.notification-count');
            if (notificationCount) {
                let count = parseInt(notificationCount.textContent);
                count--;
                if (count > 0) {
                    notificationCount.textContent = count;
                } else {
                    notificationCount.remove();
                }
            }
        }
    }).catch(error => console.error('Erreur:', error));
}

function sendReply(idAvis, reponse) {
    const formData = new FormData();
    formData.append('idAvis', idAvis);
    formData.append('reponse', reponse);

    fetch('/pages/pro/detailsOffre/envoyerReponse.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              closeReplyPopup();
              document.querySelector(`.review[data-id="${idAvis}"]`).remove();
              const notificationCount = document.querySelector('.notification-count');
              if (notificationCount) {
                  let count = parseInt(notificationCount.textContent);
                  count--;
                  if (count > 0) {
                      notificationCount.textContent = count;
                  } else {
                      notificationCount.remove();
                  }
              }
          } else {
              console.error('Erreur:', data.message);
          }
      }).catch(error => console.error('Erreur:', error));
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById("notification-dropdown");
    const notificationIcon = document.querySelector(".notification-icon img");
    if (dropdown && !dropdown.contains(event.target) && !notificationIcon.contains(event.target)) {
        dropdown.style.display = "none";
    }
    if (event.target.classList.contains('btn-mark-seen')) {
        const idAvis = event.target.dataset.id;
        markAsSeen(idAvis);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const selectMenu = document.getElementById('selecteur-profil');

    // Vérifier si le selecteur-profil existe
    if (selectMenu) {
        selectMenu.addEventListener('click', function(event) {
            event.stopPropagation(); // Empêcher la propagation de l'événement de clic
            toggleArrow();
        });
    } else {
        console.error("L'élément selecteur-profil n'a pas été trouvé.");
    }

    const closeBtn = document.querySelector('.popup .close');
    closeBtn.addEventListener('click', closeReplyPopup);

    const replyForm = document.querySelector('#popup-repondre form');
    replyForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const idAvis = document.getElementById('popup-idAvis').value;
        const reponse = replyForm.querySelector('textarea[name="reponse"]').value;
        sendReply(idAvis, reponse);
    });

    console.log("JavaScript chargé.");
});

// Empêcher la propagation de l'événement de clic sur l'icône de notification
document.querySelector(".notification-icon img").addEventListener('click', function(event) {
    event.stopPropagation();
});

hamburger.addEventListener("click", toggleMenu);