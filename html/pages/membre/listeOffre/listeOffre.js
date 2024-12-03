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
    const filterContainer = document.querySelector('.input'); // Barre de tri
    const pageOverlay = document.getElementById('pageOverlay'); // Overlay

    toggleButton.addEventListener('click', () => {
        if (filterContainer) {
            if (!filterContainer.classList.contains('open')) {
                // Ouvre la barre de tri
                filterContainer.style.display = 'flex'; // Assure qu'elle est visible avant animation
                pageOverlay.style.display = 'block'; // Affiche l'overlay
                setTimeout(() => filterContainer.classList.add('open'), 10); // Délai pour déclencher l'animation
                toggleButton.textContent = 'Masquer la barre de tri';
            } else {
                // Ferme la barre de tri avec un délai avant display none
                filterContainer.classList.remove('open');
                pageOverlay.style.display = 'none';
                toggleButton.textContent = 'Afficher la barre de tri';
                filterContainer.addEventListener(
                    'transitionend',
                    () => {
                        if (!filterContainer.classList.contains('open')) {
                            filterContainer.style.display = 'none'; // Cache une fois l'animation terminée
                        }
                    },
                    { once: true } // S'exécute une seule fois
                );
            }
        }
    });

    pageOverlay.addEventListener('click', () => {
        if (filterContainer.classList.contains('open')) {
            filterContainer.classList.remove('open');
            pageOverlay.style.display = 'none';
            toggleButton.textContent = 'Afficher la barre de tri';
            filterContainer.addEventListener(
                'transitionend',
                () => {
                    if (!filterContainer.classList.contains('open')) {
                        filterContainer.style.display = 'none';
                    }
                },
                { once: true }
            );
        }
    });
});


