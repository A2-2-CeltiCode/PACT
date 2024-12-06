const modal = document.getElementById("myModal");

const span = document.getElementsByClassName("close")[0];

const modalImage = document.getElementById("modal-image");

function openUp(e) {
    const src = e.srcElement.src;
    modal.style.display = "block";
    modalImage.src = src;
    document.body.classList.add("noscroll")
}

span.onclick = function () {
    modal.style.display = "none";
    document.body.classList.remove("noscroll")
};

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
        document.body.classList.remove("noscroll")
    }
};

function submitForm() {
    let valid = true;
    if (new Date() < new Date(document.getElementById("datevisite").value)) {
        valid = false;
    } else if (document.getElementById("contexte").selectedIndex === 0) {
        valid = false;
    }
    return valid;
}