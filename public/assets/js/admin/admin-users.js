document.addEventListener('DOMContentLoaded', () => {

    // --- Custom Alert/Confirm Modal ---
    function showModal(message, isConfirm = false) {
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

    // ===================================
    // === USERS PANEL LOGIC ===
    // ===================================

    // This code only runs if the user-table-body element is on the page
    const userTableBody = document.getElementById('user-table-body');
    if (!userTableBody) {
        return; // We are not on the Users page, stop executing this script.
    }

    let allUsers = [
        { id: 2240191, date: '2024-10-28', name: 'Joeffrey Edrian', email: 'j.edrian@example.com', status: 'Active', type: 'Student' },
        { id: 2240192, date: '2024-10-27', name: 'Jane Doe', email: 'j.doe@example.com', status: 'Suspended', type: 'Faculty' },
        { id: 2240193, date: '2024-10-26', name: 'John Smith', email: 'j.smith@example.com', status: 'Active', type: 'Student' },
        { id: 2240194, date: '2024-10-25', name: 'Sarah Lee', email: 's.lee@example.com', status: 'Banned', type: 'Student' },
        { id: 2240195, date: '2024-10-24', name: 'Michael Brown', email: 'm.brown@example.com', status: 'Active', type: 'Student' },
        { id: 2240196, date: '2024-10-23', name: 'Emily Davis', email: 'e.davis@example.com', status: 'Reported', type: 'Student' },
        { id: 2240197, date: '2024-10-22', name: 'Chris Wilson', email: 'c.wilson@example.com', status: 'Active', type: 'Faculty' },
        { id: 2240198, date: '2024-10-21', name: 'David Clark', email: 'd.clark@example.com', status: 'Active', type: 'Student' },
        { id: 2240199, date: '2024-10-20', name: 'Laura Evans', email: 'l.evans@example.com', status: 'Reported', type: 'Student' },
        { id: 2240200, date: '2024-10-19', name: 'James Taylor', email: 'j.taylor@example.com', status: 'Active', type: 'Student' },
        { id: 2240201, date: '2024-10-18', name: 'Olivia White', email: 'o.white@example.com', status: 'Deleted', type: 'Student' },
        { id: 2240202, date: '2024-10-17', name: 'Daniel Harris', email: 'd.harris@example.com', status: 'Active', type: 'Faculty' },
    ];

    let currentUserQuery = 'all'; // 'all', 'reported', 'deleted'
    let currentUserPage = 1;
    const usersRowsPerPage = 8;

    // Get all interactive elements for USERS
    const userPagination = document.getElementById('user-pagination');
    const userQueries = document.getElementById('user-queries');
    const userTableTitle = document.getElementById('user-table-title');
    const userSearchInput = document.getElementById('user-search-input');
    const selectAllUsersCheckbox = document.getElementById('select-all-users');
    const deleteUserButton = document.getElementById('delete-user-btn');

    function getFilteredUserData() {
        let filteredUsers = [];
        if (currentUserQuery === 'all') {
            filteredUsers = allUsers.filter(user => user.status !== 'Deleted');
        } else if (currentUserQuery === 'reported') {
            filteredUsers = allUsers.filter(user => user.status === 'Reported');
        } else if (currentUserQuery === 'deleted') {
            filteredUsers = allUsers.filter(user => user.status === 'Deleted');
        }

        // Check if userSearchInput exists before getting its value
        const searchTerm = userSearchInput ? userSearchInput.value.toLowerCase() : '';
        if (searchTerm) {
            filteredUsers = filteredUsers.filter(user =>
                user.name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                String(user.id).includes(searchTerm)
            );
        }
        return filteredUsers;
    }

    function renderUserTable() {
        const filteredData = getFilteredUserData();
        const startIndex = (currentUserPage - 1) * usersRowsPerPage;
        const endIndex = startIndex + usersRowsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);

        userTableBody.innerHTML = ''; // Clear existing table body
        if (paginatedData.length === 0) {
            userTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 20px; color: #777;">No users found.</td></tr>`;
        } else {
            paginatedData.forEach(user => {
                const row = document.createElement('tr');
                row.setAttribute('data-user-id', user.id);
                row.innerHTML = `
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>${user.id}</td>
                    <td>${user.date}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.status}</td>
                    <td>${user.type}</td>
                `;
                userTableBody.appendChild(row);
            });
        }
        updateSelectAllUserCheckbox();
    }

    function renderUserPagination() {
        const filteredData = getFilteredUserData();
        const totalRows = filteredData.length;
        const totalPages = Math.ceil(totalRows / usersRowsPerPage);
        userPagination.innerHTML = '';

        if (totalPages <= 1) return;

        const prevButton = createPageElement(currentUserPage - 1, '&lt;', 'page-control', currentUserPage === 1);
        userPagination.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = createPageElement(i, i, 'page-num', false, i === currentUserPage);
            userPagination.appendChild(pageButton);
        }

        const nextButton = createPageElement(currentUserPage + 1, '&gt;', 'page-control', currentUserPage === totalPages);
        userPagination.appendChild(nextButton);
    }

    function createPageElement(page, text, className, disabled = false, active = false) {
        const el = document.createElement('span');
        el.innerHTML = text;
        el.classList.add(className);
        el.dataset.page = page;
        if (disabled) el.classList.add('disabled');
        if (active) el.classList.add('active-page');
        return el;
    }

    function updateUserView() {
        renderUserTable();
        renderUserPagination();
    }

    // --- Event Listeners for USERS ---

    userQueries.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            currentUserQuery = e.target.dataset.query;
            currentUserPage = 1;
            userSearchInput.value = '';
            userQueries.querySelectorAll('li').forEach(li => li.classList.remove('active-query'));
            e.target.classList.add('active-query');
            userTableTitle.innerText = e.target.innerText;
            updateUserView();
        }
    });

    userSearchInput.addEventListener('input', () => {
        currentUserPage = 1;
        updateUserView();
    });

    userPagination.addEventListener('click', (e) => {
        const target = e.target.closest('span[data-page]');
        if (target && !target.classList.contains('disabled')) {
            currentUserPage = parseInt(target.dataset.page);
            updateUserView();
        }
    });

    selectAllUsersCheckbox.addEventListener('change', () => {
        const rowCheckboxes = userTableBody.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllUsersCheckbox.checked;
        });
    });

    userTableBody.addEventListener('change', (e) => {
        if (e.target.classList.contains('row-checkbox')) {
            updateSelectAllUserCheckbox();
        }
    });

    function updateSelectAllUserCheckbox() {
        const rowCheckboxes = userTableBody.querySelectorAll('.row-checkbox');
        if (rowCheckboxes.length === 0) {
            selectAllUsersCheckbox.checked = false;
            return;
        }
        const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
        selectAllUsersCheckbox.checked = allChecked;
    }

    deleteUserButton.addEventListener('click', async () => {
        const checkedBoxes = userTableBody.querySelectorAll('.row-checkbox:checked');

        if (checkedBoxes.length === 0) {
            // *** FIXED: Use showModal instead of alert ***
            await showModal('Please select users to delete.', false);
            return;
        }

        // *** FIXED: Use showModal instead of confirm ***
        const confirmed = await showModal(`Are you sure you want to delete ${checkedBoxes.length} user(s)?`, true);
        if (!confirmed) return;

        const idsToDelete = [];
        checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            idsToDelete.push(parseInt(row.dataset.userId));
        });

        allUsers = allUsers.map(user => {
            if (idsToDelete.includes(user.id)) {
                return { ...user, status: 'Deleted' };
            }
            return user;
        });

        currentUserPage = 1;
        updateUserView();
    });

    // --- INITIALIZATION ---
    updateUserView();
});