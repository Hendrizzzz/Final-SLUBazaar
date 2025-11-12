document.addEventListener('DOMContentLoaded', function() {
    const photoInputs = document.querySelectorAll('.photo-input');

    photoInputs.forEach(input => {
        input.addEventListener('change', function(event) {
            const label = this.nextElementSibling;
            const icon = label.querySelector('i');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Set the background image of the label to the preview
                    label.style.backgroundImage = `url('${e.target.result}')`;
                    // Hide the icon
                    if (icon) {
                        icon.style.display = 'none';
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
});