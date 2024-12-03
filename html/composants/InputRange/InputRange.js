document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('input[type="range"]');
    const inputs = document.querySelectorAll('input[type="number"]');

    sliders.forEach((slider, index) => {
        const input = inputs[index];

        function syncSliderAndInput() {
            input.value = slider.value;
        }

        function syncInputAndSlider() {
            slider.value = input.value;
        }

        slider.addEventListener('input', syncSliderAndInput);
        input.addEventListener('input', syncInputAndSlider);
    });
});