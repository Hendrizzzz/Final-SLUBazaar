<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - SLU Bazaar</title>
    <link rel="stylesheet" href="/assets/css/user/user-market.css">
    <link rel="stylesheet" href="/assets/css/user/user-messages.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <!-- Reusable Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-top">
                <a href="/profile" class="nav-link" title="Profile"><i class="fas fa-user"></i></a>
                <a href="/market" class="nav-link" title="Marketplace"><i class="fas fa-store"></i></a>
                <a href="/items/create" class="nav-link" title="Sell Item"><i class="fas fa-plus-circle"></i></a>
                <a href="/messages" class="nav-link active" title="Messages"><i class="fas fa-paper-plane"></i></a>
            </div>
            <div class="sidebar-bottom">
                 <a href="/logout" class="nav-link" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="content-wrapper">
            <div class="background-overlay"></div>
            <div class="content">
                <!-- Main Messages Container -->
                <div class="messages-container">
                    <!-- Left Panel: Conversation List -->
                    <aside class="conversation-list">
                        <header class="list-header">
                            <h2>Messages</h2>
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search">
                            </div>
                        </header>
                        <div class="conversations">
                            <?php if (!empty($conversations)): ?>
                                <?php foreach ($conversations as $convo): ?>
                                    <div class="conversation-item" data-conversation-id="<?= $convo['conversation_id'] ?>"
                                         data-username="<?= htmlspecialchars($convo['other_username']) ?>"
                                         data-item-title="<?= htmlspecialchars($convo['item_title']) ?>">
                                        <div class="avatar-placeholder"></div>
                                        <div class="convo-details">
                                            <span class="convo-username"><?= htmlspecialchars($convo['other_username']) ?></span>
                                            <span class="convo-preview">Last message snippet...</span>
                                        </div>
                                        <div class="convo-status-dot"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-conversations">You have no messages yet.</p>
                            <?php endif; ?>
                        </div>
                    </aside>

                    <!-- Right Panel: Chat Window -->
                    <section class="chat-window">
                        <header class="chat-header">
                            <h3 id="chat-username">Select a conversation</h3>
                            <p id="chat-item-title">to start messaging</p>
                        </header>
                        <div class="message-area">
                            <!-- Message bubbles will be loaded here -->
                            <div class="message received">
                                <p>This is a received message.</p>
                            </div>
                             <div class="message sent">
                                <p>This is a sent message.</p>
                                <p>It can have multiple lines.</p>
                            </div>
                        </div>
                        <footer class="chat-footer">
                            <input type="text" placeholder="Send a message">
                            <button class="attach-btn"><i class="fas fa-paperclip"></i></button>
                            <button class="send-btn"><i class="fas fa-paper-plane"></i></button>
                        </footer>
                    </section>
                </div>
            </div>
        </main>
    </div>
    <script src="/assets/js/user/user-messages.js"></script>
</body>
</html>