document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.dataset.tab;
            const targetContent = document.getElementById(targetId);

            tabs.forEach(t => t.classList.remove('active-tab'));
            tab.classList.add('active-tab');

            tabContents.forEach(content => content.classList.remove('active-content'));
            if (targetContent) {
                targetContent.classList.add('active-content');
            }
        });
    });
});