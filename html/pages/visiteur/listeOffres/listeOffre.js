document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleBarretrieButton');
    const filterContainer = document.getElementById('barretrieContainer'); // Utilisez l'ID ici
    const pageOverlay = document.getElementById('pageOverlay');

    toggleButton.addEventListener('click', () => {
        if (filterContainer) {
            if (!filterContainer.classList.contains('open')) {
                filterContainer.style.display = 'flex';
                pageOverlay.style.display = 'block';
                setTimeout(() => filterContainer.classList.add('open'), 10);
                toggleButton.textContent = 'Masquer la barre de tri';
            } else {
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





