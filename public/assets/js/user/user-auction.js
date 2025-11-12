document.addEventListener('DOMContentLoaded', function() {
    
    // --- Countdown Timer Logic ---
    const countdownElement = document.querySelector('.countdown');
    if (countdownElement) {
        // ... (The countdown timer code remains the same as before)
    }

    // --- Image Gallery Logic ---
    const thumbnails = document.querySelectorAll('.thumb');
    const mainImageContainer = document.querySelector('.main-image');
    
    if (thumbnails.length > 0 && mainImageContainer) {
        // Set up click handlers for thumbnails
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Get the image source from the clicked thumbnail
                const thumbImg = this.querySelector('img');
                if (thumbImg) {
                    // Clear the main image container
                    mainImageContainer.innerHTML = '';
                    
                    // Create new image element
                    const mainImg = document.createElement('img');
                    mainImg.src = thumbImg.src;
                    mainImg.alt = thumbImg.alt;
                    
                    // Add to main image container
                    mainImageContainer.appendChild(mainImg);
                }
            });
        });
    }

    // --- NEW: Bid Modal Logic ---
    const openModalBtn = document.getElementById('openBidModalBtn');
    const closeModalBtn = document.getElementById('closeBidModalBtn');
    const bidModal = document.getElementById('bidModal');
    const bidAmountInput = document.getElementById('bid_amount');

    // Function to open the modal
    const openModal = () => {
        if (bidModal) {
            bidModal.classList.add('show');
            // Automatically focus the input field for a better user experience
            if(bidAmountInput) {
                setTimeout(() => bidAmountInput.focus(), 100); // Small delay for the transition
            }
        }
    };

    // Function to close the modal
    const closeModal = () => {
        if (bidModal) {
            bidModal.classList.remove('show');
        }
    };

    // Event listeners
    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    if (bidModal) {
        // Close modal if user clicks on the overlay (outside the content)
        bidModal.addEventListener('click', (event) => {
            if (event.target === bidModal) {
                closeModal();
            }
        });
        // Close modal if user presses the "Escape" key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    }

});