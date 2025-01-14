document.addEventListener("DOMContentLoaded", function () {
  const repondreButtons = document.querySelectorAll(".btn-repondre");
  const popup = document.getElementById("popup-repondre");
  const closeBtn = popup.querySelector(".close");
  const idAvisInput = document.getElementById("popup-idAvis");

  repondreButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const idAvis = this.closest(".avi").dataset.idavis;
      idAvisInput.value = idAvis;
      popup.style.display = "block";
    });
  });

  closeBtn.addEventListener("click", function () {
    popup.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === popup) {
      popup.style.display = "none";
    } else {
      document.querySelectorAll(".avi.prioritaire").forEach((element) => {
        element.classList.remove("prioritaire");
      });
    }
  });

  const signalerButtons = document.querySelectorAll(".btn-signaler");
  const toast = document.getElementById("toast");

  signalerButtons.forEach((button) => {
    button.addEventListener("click", function () {
      toast.classList.add("show");
      setTimeout(() => {
        toast.classList.remove("show");
      }, 3000);
    });
  });

  const sortBySelect = document.getElementById("sortBy");
  const filterBySelect = document.getElementById("filterBy");

  function fetchAvis() {
    const sortBy = sortBySelect.value;
    const filterBy = filterBySelect.value;

    fetch(
      `listeAvis.php?idOffre=${idOffre}&sortBy=${sortBy}&filterBy=${filterBy}`
    )
      .then((response) => response.text())
      .then((data) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, "text/html");
        const avisList = doc.querySelector(".liste-avis > div:last-child");
        document.querySelector(".liste-avis > div:last-child").innerHTML = avisList.innerHTML;
        initButtons(); // Réinitialiser les écouteurs d'événements
      });
  }

  sortBySelect.addEventListener("change", fetchAvis);
  filterBySelect.addEventListener("change", fetchAvis);

  function initButtons() {
    const repondreButtons = document.querySelectorAll(".btn-repondre");
    const signalerButtons = document.querySelectorAll(".btn-signaler");
    const idAvisInput = document.getElementById("popup-idAvis");
    const popup = document.getElementById("popup-repondre");
    const closeBtn = popup.querySelector(".close");

    repondreButtons.forEach(button => {
      button.addEventListener("click", function () {
        const idAvis = this.closest(".avi").dataset.idavis;
        idAvisInput.value = idAvis;
        popup.style.display = "block";
      });
    });

    closeBtn.addEventListener("click", function () {
      popup.style.display = "none";
    });

    window.addEventListener("click", function (event) {
      if (event.target === popup) {
        popup.style.display = "none";
      }
    });

    signalerButtons.forEach((button) => {
      button.addEventListener("click", function () {
        toast.classList.add("show");
        setTimeout(() => {
          toast.classList.remove("show");
        }, 3000);
      });
    });

    const thumbsUpButtons = document.querySelectorAll(".thumbs-up");
    const thumbsDownButtons = document.querySelectorAll(".thumbs-down");

    thumbsUpButtons.forEach((button) => {
      button.addEventListener("click", function () {
        if (this.disabled) return;
        this.disabled = true;
        const idAvis = this.dataset.idavis;
        fetch(`thumbs.php?idAvis=${idAvis}&type=up`)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              this.textContent = `👍 ${data.thumbs_up}`;
              const thumbsDownButton = this.nextElementSibling;
              thumbsDownButton.textContent = `👎 ${data.thumbs_down}`;
            }
            this.disabled = false;
          });
      });
    });

    thumbsDownButtons.forEach((button) => {
      button.addEventListener("click", function () {
        if (this.disabled) return;
        this.disabled = true;
        const idAvis = this.dataset.idavis;
        fetch(`thumbs.php?idAvis=${idAvis}&type=down`)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              this.textContent = `👎 ${data.thumbs_down}`;
              const thumbsUpButton = this.previousElementSibling;
              thumbsUpButton.textContent = `👍 ${data.thumbs_up}`;
            }
            this.disabled = false;
          });
      });
    });
  }

  initButtons(); // Initialiser les écouteurs d'événements au chargement de la page
});
