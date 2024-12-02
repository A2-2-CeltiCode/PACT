document.getElementById('toggleBarretrieButton').addEventListener('click', function() {
        var barretrie = document.getElementById('barretrie');
        if (barretrie.style.display === 'none' || barretrie.style.display === '') {
            barretrie.style.display = 'block';
        } else {
            barretrie.style.display = 'none';
        }
    });