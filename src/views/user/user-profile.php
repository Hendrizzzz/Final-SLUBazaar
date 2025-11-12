<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - SLU Bazaar</title>
    <link rel="stylesheet" href="/assets/css/user/user-market.css">
    <link rel="stylesheet" href="/assets/css/user/user-profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <!-- Reusable Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-top">
                <a href="/profile" class="nav-link active" title="Profile"><i class="fas fa-user"></i></a>
                <a href="/market" class="nav-link" title="Marketplace"><i class="fas fa-store"></i></a>
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
                <div class="profile-container">
                    <!-- Profile Header -->
                    <header class="profile-header">
                        <div class="profile-avatar">
                            <!-- User's avatar image would go here -->
                        </div>
                        <div class="profile-info">
                            <h2><?= htmlspecialchars($user['username']) ?></h2>
                            <div class="star-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                            </div>
                        </div>
                        <a href="/settings" class="edit-profile-btn">Edit Profile</a>
                    </header>
                    
                    <!-- Tab Navigation -->
                    <nav class="sub-nav">
                        <button class="tab-link active-tab" data-tab="active-listings">Active Listings</button>
                        <button class="tab-link" data-tab="sold-items">Sold Items</button>
                        <button class="tab-link" data-tab="bid-history">Bid History</button>
                    </nav>

                    <!-- Tab Content Panels -->
                    <div id="active-listings" class="tab-content active-content">
                        <!-- Reusing the item grid from the market page -->
                        <div class="item-grid">
                            <?php if (!empty($activeListings)): ?>
                                <?php foreach ($activeListings as $item): ?>
                                    <div class="item-card">
                                        <div class="item-image-placeholder">
                                            <img src="<?= htmlspecialchars($item['image_url'] ?? '/assets/images/default-item.png') ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                        </div>
                                        <div class="item-info">
                                            <h3 class="item-name"><?= htmlspecialchars($item['title']) ?></h3>
                                            <div class="item-details">
                                                <span class="item-bid">₱ <?= number_format($item['current_bid'] ?? $item['starting_bid'], 2) ?></span>
                                                <span class="item-category"><?= htmlspecialchars($item['category'] ?? 'General') ?></span>
                                            </div>
                                            <div class="item-actions">
                                                <a href="/item/view?id=<?= $item['item_id'] ?>" class="btn-view">View</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-items-message">You have no active listings.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="sold-items" class="tab-content">
                         <!-- Content for Sold Items, from your UI -->
                        <?php if (!empty($soldItems)): ?>
                            <div class="items-list">
                                <div class="list-header">
                                    <span>Item ID</span>
                                    <span>Item Name</span>
                                    <span>Winning Price</span>
                                </div>
                                <?php foreach ($soldItems as $item): ?>
                                    <a href="/item/view?id=<?= $item['item_id'] ?>" class="list-item">
                                        <span><?= str_pad($item['item_id'], 3, '0', STR_PAD_LEFT) ?></span>
                                        <span class="item-name-col">
                                            <div class="item-image-placeholder-small"></div>
                                            <?= htmlspecialchars($item['title']) ?>
                                        </span>
                                        <span>₱ <?= number_format($item['winning_price'], 2) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-items-message">You have not sold any items yet.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div id="bid-history" class="tab-content">
                         <!-- Reusing the bid history list from the market page -->
                        <?php if (!empty($bidHistory)): ?>
                            <div class="bids-list">
                                 <!-- The PHP loop for bids would go here -->
                            </div>
                        <?php else: ?>
                            <p class="no-items-message">You haven't placed any bids yet.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div id="bid-history" class="tab-content">
                         <!-- Reusing the bid history list from the market page -->
                        <?php if (!empty($bidHistory)): ?>
                            <div class="bids-list">
                                 <!-- The PHP loop for bids would go here -->
                            </div>
                        <?php else: ?>
                            <p class="no-items-message">You haven't placed any bids yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="/assets/js/user/user-profile.js"></script>
</body>
</html>