// ...existing code...
function syncInputValue(slider) {
    const input = document.getElementById(slider.id + 'Input');
    if (input) {
        input.value = slider.value;
    }
}

function syncSliderValue(input) {
    const slider = document.getElementById(input.id.replace('Input', ''));
    if (slider) {
        slider.value = input.value;
    }
}
// ...existing code...
