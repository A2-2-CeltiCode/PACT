let currentIndex = 0;
const images = document.querySelectorAll(".carousel-image");
const totalImages = images.length;
let compteur = 1;

function updateCarousel() {
  const offset = -currentIndex * 100;
  document.querySelector(
    ".carousel-images"
  ).style.transform = `translateX(${offset}%)`;
}

if (images.length > 1) {
  document.querySelector(".next").classList.remove("desactive");
}

document.querySelector(".next").addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % totalImages;
  updateCarousel();
  if (compteur < totalImages) {
    compteur++;
  }
  if (compteur == totalImages) {
    document.querySelector(".next").classList.add("desactive");
    document.querySelector(".prev").classList.remove("desactive");
  }
});

document.querySelector(".prev").addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + totalImages) % totalImages;
  updateCarousel();
  if (compteur > 1) {
    compteur--;
  }
  if (compteur == 1) {
    document.querySelector(".prev").classList.add("desactive");
    document.querySelector(".next").classList.remove("desactive");
  }
});

const modal = document.getElementById("myModal");

const span = document.getElementsByClassName("close")[0];

const modalImage = document.getElementById("modal-image");

function openUp(e) {
  const src = e.srcElement.src;
  modal.style.display = "block";
  modalImage.src = src;
  document.body.classList.add("noscroll");
}

span.onclick = function () {
  modal.style.display = "none";
  document.body.classList.remove("noscroll");
};

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
    document.body.classList.remove("noscroll");
  }
};

document.querySelectorAll(".btn-repondre").forEach((button) => {
  button.addEventListener("click", (event) => {
    const repondreModal = document.getElementById("repondreModal");
    const idAvis = event.target.closest(".avi").dataset.idavis;
    document.getElementById("idAvis").value = idAvis;
    repondreModal.style.display = "block";
    document.body.classList.add("noscroll");
  });
});

document.querySelector(".close-repondre").onclick = function () {
  document.getElementById("repondreModal").style.display = "none";
  document.body.classList.remove("noscroll");
};

window.onclick = function (event) {
  const repondreModal = document.getElementById("repondreModal");
  if (event.target === repondreModal) {
    repondreModal.style.display = "none";
    document.body.classList.remove("noscroll");
  }
};
