document.addEventListener('DOMContentLoaded', function() {
    const filtreButton = document.getElementById('filtreButton');
    const barre = document.querySelector('.barre');

    filtreButton.addEventListener('click', function() {
        barre.classList.toggle('trie-hidden');
        barre.classList.toggle('trie-visible');
    });
});