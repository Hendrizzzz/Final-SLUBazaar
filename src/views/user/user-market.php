<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - SLU Bazaar</title>
    <link rel="stylesheet" href="/assets/css/user/user-market.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <!-- Vertical Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-top">
                <a href="/profile" class="nav-link" title="Profile"><i class="fas fa-user"></i></a>
                <a href="/market" class="nav-link active" title="Marketplace"><i class="fas fa-store"></i></a>
                <a href="/items/create" class="nav-link" title="Sell Item"><i class="fas fa-plus-circle"></i></a>
                <a href="/messages" class="nav-link" title="Messages"><i class="fas fa-paper-plane"></i></a>
            </div>
            <div class="sidebar-bottom">
                 <a href="/logout" class="nav-link" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="content-wrapper">
            <div class="background-overlay"></div>
            <div class="content">
                <header class="page-header">
                    <h1>SLU Bazaar</h1>
                    <p>The official marketplace of Saint Louis University.</p>
                </header>
                
                <div class="market-container">
                    <!-- Tab Navigation -->
                    <nav class="sub-nav">
                        <button class="tab-link active-tab" data-tab="live-auctions">Live Auctions</button>
                        <button class="tab-link" data-tab="my-bids">My Bids</button>
                        <button class="tab-link" data-tab="my-watchlist">My Watchlist</button>
                    </nav>

                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <?php
                        $isSearch = !empty($_GET['query']);
                        $searchQuery = $_GET['query'] ?? '';
                        if ($isSearch): ?>
                            <form method="GET" action="/search" style="display: flex; width: 100%;">
                                <input type="text" name="query" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
                            </form>
                        <?php else: ?>
                            <form method="GET" action="/search" style="display: flex; width: 100%;">
                                <input type="text" name="query" placeholder="Search">
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Tab Content Panels -->
                    <div id="live-auctions" class="tab-content active-content">
                        <div class="item-grid">
                            <?php if (!empty($liveAuctions)): ?>
                                <?php foreach ($liveAuctions as $item): ?>
                                    <div class="item-card">
                                        <a href="/item/view?id=<?= $item['item_id'] ?>" class="card-link">
                                            <div class="item-image-placeholder">
                                                <img src="<?= htmlspecialchars($item['image_url'] ?? '/assets/images/default-item.png') ?>" alt="Item"> <!-- Need to be fix inorder to show the items imagine-->
                                            </div>
                                            <div class="item-info">
                                                <h3 class="item-name"><?= htmlspecialchars($item['title']) ?></h3>
                                                <div class="item-details">
                                                    <span class="item-bid">₱ <?= number_format($item['current_bid'] ?? $item['starting_bid'], 2) ?></span>
                                                    <span class="item-time">04:00:59</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-items-message">No live auctions at the moment. Check back soon!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="my-bids" class="tab-content">
                        <?php if (!empty($myBids)): ?>
                            <div class="bids-list">
                                <div class="bids-header">
                                    <span class="col-id">Item ID</span>
                                    <span class="col-name">Item Name</span>
                                    <span class="col-status">Status</span>
                                    <span class="col-category">Category</span>
                                    <span class="col-bid">Submitted Bid</span>
                                </div>
                                <?php foreach ($myBids as $bid): ?>
                                    <a href="/user/auction?id=<?= $bid['item_id'] ?>" class="bid-item">
                                        <span class="col-id"><?= str_pad($bid['item_id'], 3, '0', STR_PAD_LEFT) ?></span>
                                        <span class="col-name">
                                            <div class="item-image-placeholder-small"></div>
                                            <?= htmlspecialchars($bid['item_name']) ?>
                                        </span>
                                        <span class="col-status">
                                            <?php 
                                                $isWinner = ($bid['status'] === 'Completed' && $bid['bid_amount'] >= $bid['winning_bid']);
                                                if ($isWinner) {
                                                    echo '<span class="status-tag status-won">Won</span>';
                                                } else if ($bid['status'] === 'Completed' || ($bid['status'] === 'Active' && $bid['bid_amount'] < $bid['winning_bid'])) {
                                                    echo '<span class="status-tag status-outbid">Outbid</span>';
                                                } else {
                                                    echo '<span class="status-tag status-active">Active</span>';
                                                }
                                            ?>
                                        </span>
                                        <span class="col-bid">₱ <?= number_format($bid['bid_amount'], 2) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-items-message">You haven't placed any bids yet.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div id="my-watchlist" class="tab-content">
                        <p class="no-items-message">Your watchlist is empty.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/user/user-market.js"></script>
</body>
</html>