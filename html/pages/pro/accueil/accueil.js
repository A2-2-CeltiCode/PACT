const tailleDeplacements = 332;

for (const carousel of document.getElementsByClassName("carrousel")) {
    carousel.nextElementSibling.children[0].onclick = () => {
        carousel.scrollLeft -= tailleDeplacements
    }
    carousel.nextElementSibling.children[1].onclick = () => {
        carousel.scrollLeft += tailleDeplacements
    }
}
