document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            let isValid = true;

            // Simple check to ensure fields are not empty
            if (emailInput.value.trim() === '') {
                console.log('Email field is empty.');
                isValid = false;
            }

            if (passwordInput.value.trim() === '') {
                console.log('Password field is empty.');
                isValid = false;
            }

            if (!isValid) {
                // Prevent form submission if validation fails
                event.preventDefault(); 
                alert('Please fill in both email/username and password.');
            }
        });
    }
});