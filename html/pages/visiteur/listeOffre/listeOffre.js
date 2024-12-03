// document.addEventListener('DOMContentLoaded', function () {
//     const toggleButton = document.getElementById('toggleBarretrieButton');
//     const filterContainer = document.querySelector('.input'); // Utilise querySelector pour sélectionner le premier élément avec la classe 'input'

//     toggleButton.addEventListener('click', () => {
//         if (filterContainer) {
//             filterContainer.classList.toggle('open'); // Ajoute ou enlève la classe 'open'
//         }
//     });
    
// });



document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleBarretrieButton');
    const filterContainer = document.querySelector('.input'); // Sélectionne la barre de tri
    const pageOverlay = document.getElementById('pageOverlay'); // Sélectionne l'overlay

    toggleButton.addEventListener('click', () => {
        if (filterContainer) {
            filterContainer.classList.toggle('open'); // Ajoute ou enlève la classe 'open'
            pageOverlay.classList.toggle('active'); // Montre ou cache l'overlay
        }
    });

    // Ajoute un gestionnaire pour fermer la barre et l'overlay lorsqu'on clique en dehors
    pageOverlay.addEventListener('click', () => {
        if (filterContainer.classList.contains('open')) {
            filterContainer.classList.remove('open');
            pageOverlay.classList.remove('active');
        }
    });
});
