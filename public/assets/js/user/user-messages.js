document.addEventListener('DOMContentLoaded', function () {
    const conversationItems = document.querySelectorAll('.conversation-item');
    const chatUsernameEl = document.getElementById('chat-username');
    const chatItemTitleEl = document.getElementById('chat-item-title');

    conversationItems.forEach(item => {
        item.addEventListener('click', function () {
            // Get data from the clicked element's data attributes
            const username = this.dataset.username;
            const itemTitle = this.dataset.itemTitle;
            const conversationId = this.dataset.conversationId;

            // Update the chat header
            if (chatUsernameEl) chatUsernameEl.textContent = username;
            if (chatItemTitleEl) chatItemTitleEl.textContent = 'Regarding: ' + itemTitle;
            
            // Manage active class
            conversationItems.forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            
            // In a real application, you would now use the 'conversationId'
            // to make an AJAX/fetch call to get all messages for this conversation
            // and then populate the message-area.
            console.log(`Switched to conversation ID: ${conversationId}`);
        });
    });
});