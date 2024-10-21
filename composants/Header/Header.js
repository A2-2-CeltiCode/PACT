const selecteurLangue = document.getElementById("selecteur-langue");
const logoLangue = document.getElementById("logo-langue");

selecteurLangue.addEventListener("input", (e) => { logoLangue.src = `../../../ressources/icone/logo${e.target.value}.svg` });