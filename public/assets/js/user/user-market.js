document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Get the target tab content ID from the data-tab attribute
            const targetId = tab.dataset.tab;
            const targetContent = document.getElementById(targetId);

            // Update active state on tab links
            tabs.forEach(t => t.classList.remove('active-tab'));
            tab.classList.add('active-tab');

            // Show/hide tab content panels
            tabContents.forEach(content => {
                content.classList.remove('active-content');
            });

            if (targetContent) {
                targetContent.classList.add('active-content');
            }
        });
    });
});