let selecteurLangue = document.getElementById("selecteur-langue");
let logoLangue = document.getElementById("logo-langue");

selecteurLangue.addEventListener("input", (e) => { logoLangue.src = `../../../ressources/icone/logo${e.target.value}.svg` });