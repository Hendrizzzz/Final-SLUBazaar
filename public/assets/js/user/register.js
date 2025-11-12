document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            // Get all the form fields
            const firstName = form.querySelector('#first_name');
            const username = form.querySelector('#username');
            const email = form.querySelector('#email');
            const password = form.querySelector('#password');
            const passwordConfirm = form.querySelector('#password_confirm');
            
            // Prevent the form from submitting immediately
            event.preventDefault();

            // --- VALIDATION CHECKS ---

            // 1. Check if all fields are filled
            if (firstName.value.trim() === '' || username.value.trim() === '' || email.value.trim() === '' || password.value.trim() === '') {
                alert('Please fill out all required fields.');
                return; // Stop the function
            }
            
            // 2. Check if passwords match
            if (password.value !== passwordConfirm.value) {
                alert('The passwords do not match. Please try again.');
                return; // Stop the function
            }

            // 3. Check for password length
            if (password.value.length < 8) {
                alert('Password must be at least 8 characters long.');
                return; // Stop the function
            }

            // If all checks pass, submit the form
            // The `novalidate` attribute on the form is important to let our script take control
            console.log('Validation successful. Submitting form.');
            form.submit();
        });
    }
});