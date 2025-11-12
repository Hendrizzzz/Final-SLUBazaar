document.addEventListener('DOMContentLoaded', function() {
    
    // --- Countdown Timer Logic ---
    const countdownElement = document.querySelector('.countdown');
    if (countdownElement) {
        // ... (The countdown timer code remains the same as before)
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