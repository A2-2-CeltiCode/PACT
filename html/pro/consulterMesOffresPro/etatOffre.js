document.addEventListener('DOMContentLoaded', () => {
    const sectionEnLigne = document.getElementById('sectionEnLigne');
    const sectionHorsLigne = document.getElementById('sectionHorsLigne');
    const buttonOn = document.getElementById('on');
    const buttonOff = document.getElementById('off');

    buttonOn.addEventListener('click', () => {
        sectionEnLigne.style.display = 'block';
        sectionHorsLigne.style.display = 'none';
        buttonOn.classList.add('active');
        buttonOff.classList.remove('active');
            
    });

    buttonOff.addEventListener('click', () => {
        sectionEnLigne.style.display = 'none';
        sectionHorsLigne.style.display = 'block';
        buttonOff.classList.add('active');
        buttonOn.classList.remove('active');
    });
});