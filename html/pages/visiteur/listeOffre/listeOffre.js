document.getElementById('toggleBarretrieButton').addEventListener('click', function() {
        var barretrie = document.getElementsByClassName('input');
        for (var i = 0; i < barretrie.length; i++) {
                if (barretrie[i].style.display === 'none' || barretrie[i].style.display === '') {
                        barretrie[i].style.display = 'flex';
                } else {
                        barretrie[i].style.display = 'none';
                }
        }
});