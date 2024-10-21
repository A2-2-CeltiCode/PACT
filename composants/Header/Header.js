const selecteurLangue = document.getElementById("selecteur-langue");
const logoLangue = document.getElementById("logo-langue");

selecteurLangue.addEventListener("input", (e) => { logoLangue.src = `assets/icon/logo${e.target.value}.svg` });