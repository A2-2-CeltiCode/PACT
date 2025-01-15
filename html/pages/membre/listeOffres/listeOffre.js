 document.addEventListener('DOMContentLoaded', function () {
     const toggleButton = document.getElementById('toggleBarretrieButton');
     const filterContainer = document.querySelector('.input'); // Utilise querySelector pour sélectionner le premier élément avec la classe 'input'

     toggleButton.addEventListener('click', () => {
         if (filterContainer) {
             filterContainer.classList.toggle('open'); // Ajoute ou enlève la classe 'open'
         }
     });
    
 });



document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleBarretrieButton');
    const filterContainer = document.querySelector('.input'); 
    const pageOverlay = document.getElementById('pageOverlay'); 

    toggleButton.addEventListener('click', () => {
        if (filterContainer) {
            if (!filterContainer.classList.contains('open')) {
                // Ouvre la barre de tri
                filterContainer.style.display = 'flex';
                pageOverlay.style.display = 'block'; 
                setTimeout(() => filterContainer.classList.add('open'), 10); 
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
                            filterContainer.style.display = 'none';
                        }
                    },
                    { once: true } 
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



