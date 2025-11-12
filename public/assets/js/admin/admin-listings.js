document.addEventListener('DOMContentLoaded', () => {

    // --- Custom Alert/Confirm Modal ---
    function showModal(message, isConfirm = false) {
        // ... (modal code) ...
        // Remove existing modal if any
        let existingOverlay = document.querySelector('.modal-overlay');
        if (existingOverlay) existingOverlay.remove();

        // Create modal structure
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'modal-overlay';

        const modalContent = document.createElement('div');
        modalContent.className = 'modal-content';

        const modalMessage = document.createElement('p');
        modalMessage.textContent = message;

        const modalButtons = document.createElement('div');
        modalButtons.className = 'modal-buttons';

        modalContent.appendChild(modalMessage);
        modalContent.appendChild(modalButtons);
        modalOverlay.appendChild(modalContent);
        document.body.appendChild(modalOverlay);

        // Show the modal
        setTimeout(() => modalOverlay.classList.add('visible'), 10);

        return new Promise((resolve) => {
            if (isConfirm) {
                const confirmButton = document.createElement('button');
                confirmButton.textContent = 'Confirm';
                confirmButton.className = 'modal-btn-confirm';
                confirmButton.onclick = () => {
                    modalOverlay.classList.remove('visible');
                    setTimeout(() => modalOverlay.remove(), 200);
                    resolve(true);
                };

                const cancelButton = document.createElement('button');
                cancelButton.textContent = 'Cancel';
                cancelButton.className = 'modal-btn-cancel';
                cancelButton.onclick = () => {
                    modalOverlay.classList.remove('visible');
                    setTimeout(() => modalOverlay.remove(), 200);
                    resolve(false);
                };

                modalButtons.appendChild(cancelButton);
                modalButtons.appendChild(confirmButton);
            } else {
                // It's just an alert
                const okButton = document.createElement('button');
                okButton.textContent = 'OK';
                okButton.className = 'modal-btn-cancel'; // Use cancel style for OK
                okButton.onclick = () => {
                    modalOverlay.classList.remove('visible');
                    setTimeout(() => modalOverlay.remove(), 200);
                    resolve(true);
                };
                modalButtons.appendChild(okButton);
            }
        });
    }

    // Helper function for pagination (used by listings)
    function createPageElement(page, text, className, disabled = false, active = false) {
        const el = document.createElement('span');
        el.innerHTML = text;
        el.classList.add(className);
        el.dataset.page = page;
        if (disabled) el.classList.add('disabled');
        if (active) el.classList.add('active-page');
        return el;
    }

    // ===================================
    // === LISTING PANEL LOGIC ===
    // ===================================

    // This code only runs if the listing-table-body element is on the page
    const listingTableBody = document.getElementById('listing-table-body');
    if (!listingTableBody) {
        return; // We are not on the Listings page, stop executing this script.
    }

    // Mock data for listings
    let allListings = [
        { id: 1001, date: '2024-11-01', name: 'Old Physics Textbook', seller: 'Jane Doe', price: 500, status: 'Active' },
        { id: 1002, date: '2024-11-01', name: 'Engineering Drawing Tools', seller: 'John Smith', price: 800, status: 'Active' },
        { id: 1003, date: '2024-10-30', name: 'Calculator (Slightly Used)', seller: 'Sarah Lee', price: 1200, status: 'Sold' },
        { id: 1004, date: '2024-10-29', name: 'Unused Lab Gown', seller: 'Michael Brown', price: 350, status: 'Active' },
        { id: 1005, date: '2024-10-29', name: 'Nike Basketball Shoes', seller: 'Joeffrey Edrian', price: 2500, status: 'Reported' },
        { id: 1006, date: '2024-10-28', name: 'Dorm Bedside Lamp', seller: 'Emily Davis', price: 200, status: 'Active' },
        { id: 1007, date: '2024-10-28', name: 'Acoustic Guitar', seller: 'Chris Wilson', price: 3000, status: 'Sold' },
        { id: 1008, date: '2024-10-27', name: 'Mini Fridge', seller: 'David Clark', price: 4000, status: 'Active' },
        { id: 1009, date: '2024-10-27', name: 'Fake iPhone 15', seller: 'Laura Evans', price: 1000, status: 'Reported' },
        { id: 1010, date: '2024-10-26', name: 'Complete Set of Notes', seller: 'James Taylor', price: 100, status: 'Active' },
        { id: 1011, date: '2024-10-25', name: 'Old Laptop (for parts)', seller: 'Olivia White', price: 1500, status: 'Deleted' },
        { id: 1012, date: '2024-10-25', name: 'Mechanical Keyboard', seller: 'Daniel Harris', price: 2200, status: 'Active' },
        { id: 1013, date: '2024-10-24', name: 'Coffee Maker', seller: 'Jane Doe', price: 700, status: 'Sold' },
    ];

    let currentListingQuery = 'all'; // 'all', 'sold', 'reported', 'deleted'
    let currentListingPage = 1;
    const listingsRowsPerPage = 8;

    // Get all interactive elements for LISTINGS
    const listingPagination = document.getElementById('listing-pagination');
    const listingQueries = document.getElementById('listings-queries');
    const listingTableTitle = document.getElementById('listing-table-title');
    const listingSearchInput = document.getElementById('listing-search-input');
    const selectAllListingsCheckbox = document.getElementById('select-all-listings');
    const deleteListingButton = document.getElementById('delete-listing-btn');

    function getFilteredListingData() {
        let filteredListings = [];
        if (currentListingQuery === 'all') {
            filteredListings = allListings.filter(listing => listing.status !== 'Deleted');
        } else if (currentListingQuery === 'sold') {
            filteredListings = allListings.filter(listing => listing.status === 'Sold');
        } else if (currentListingQuery === 'reported') {
            filteredListings = allListings.filter(listing => listing.status === 'Reported');
        } else if (currentListingQuery === 'deleted') {
            filteredListings = allListings.filter(listing => listing.status === 'Deleted');
        }

        const searchTerm = listingSearchInput ? listingSearchInput.value.toLowerCase() : '';
        if (searchTerm) {
            filteredListings = filteredListings.filter(listing =>
                listing.name.toLowerCase().includes(searchTerm) ||
                listing.seller.toLowerCase().includes(searchTerm) ||
                String(listing.id).includes(searchTerm)
            );
        }
        return filteredListings;
    }

    function renderListingTable() {
        const filteredData = getFilteredListingData();
        const startIndex = (currentListingPage - 1) * listingsRowsPerPage;
        const endIndex = startIndex + listingsRowsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);

        listingTableBody.innerHTML = ''; // Clear existing table body

        if (paginatedData.length === 0) {
            listingTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 20px; color: #777;">No listings found.</td></tr>`;
        } else {
            paginatedData.forEach(listing => {
                const row = document.createElement('tr');
                row.setAttribute('data-listing-id', listing.id);
                row.innerHTML = `
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>${listing.id}</td>
                    <td>${listing.date}</td>
                    <td>${listing.name}</td>
                    <td>${listing.seller}</td>
                    <td>${listing.price.toLocaleString()}</td>
                    <td>${listing.status}</td>
                `;
                listingTableBody.appendChild(row);
            });
        }
        updateSelectAllListingCheckbox();
    }

    function renderListingPagination() {
        const filteredData = getFilteredListingData();
        const totalRows = filteredData.length;
        const totalPages = Math.ceil(totalRows / listingsRowsPerPage);
        listingPagination.innerHTML = '';

        if (totalPages <= 1) return;

        const prevButton = createPageElement(currentListingPage - 1, '&lt;', 'page-control', currentListingPage === 1);
        listingPagination.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = createPageElement(i, i, 'page-num', false, i === currentListingPage);
            listingPagination.appendChild(pageButton);
        }

        const nextButton = createPageElement(currentListingPage + 1, '&gt;', 'page-control', currentListingPage === totalPages);
        listingPagination.appendChild(nextButton);
    }

    function updateListingView() {
        renderListingTable();
        renderListingPagination();
    }

    // --- Event Listeners for LISTINGS ---

    listingQueries.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            currentListingQuery = e.target.dataset.query;
            currentListingPage = 1;
            listingSearchInput.value = '';
            listingQueries.querySelectorAll('li').forEach(li => li.classList.remove('active-query'));
            e.target.classList.add('active-query');
            listingTableTitle.innerText = e.target.innerText;
            updateListingView();
        }
    });

    listingSearchInput.addEventListener('input', () => {
        currentListingPage = 1;
        updateListingView();
    });

    listingPagination.addEventListener('click', (e) => {
        const target = e.target.closest('span[data-page]');
        if (target && !target.classList.contains('disabled')) {
            currentListingPage = parseInt(target.dataset.page);
            updateListingView();
        }
    });

    selectAllListingsCheckbox.addEventListener('change', () => {
        const rowCheckboxes = listingTableBody.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllListingsCheckbox.checked;
        });
    });

    listingTableBody.addEventListener('change', (e) => {
        if (e.target.classList.contains('row-checkbox')) {
            updateSelectAllListingCheckbox();
        }
    });

    function updateSelectAllListingCheckbox() {
        const rowCheckboxes = listingTableBody.querySelectorAll('.row-checkbox');
        if (rowCheckboxes.length === 0) {
            selectAllListingsCheckbox.checked = false;
            return;
        }
        const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
        selectAllListingsCheckbox.checked = allChecked;
    }

    deleteListingButton.addEventListener('click', async () => {
        const checkedBoxes = listingTableBody.querySelectorAll('.row-checkbox:checked');

        if (checkedBoxes.length === 0) {
            // *** FIXED: Use showModal instead of alert ***
            await showModal('Please select listings to delete.', false);
            return;
        }

        // *** FIXED: Use showModal instead of confirm ***
        const confirmed = await showModal(`Are you sure you want to delete ${checkedBoxes.length} listing(s)?`, true);
        if (!confirmed) return;

        const idsToDelete = [];
        checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            idsToDelete.push(parseInt(row.dataset.listingId));
        });

        allListings = allListings.map(listing => {
            if (idsToDelete.includes(listing.id)) {
                return { ...listing, status: 'Deleted' };
            }
            return listing;
        });

        currentListingPage = 1;
        updateListingView();
    });


    // --- INITIALIZATION ---
    updateListingView();
});