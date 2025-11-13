<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Listings - SLU Bazaar</title>
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
                <a href="/profile" class="nav-link" title="Profile"><i class="fas fa-user"></i></a>
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
                <?php 
                    $successMessage = $_SESSION['flash_success'] ?? null;
                    $errorMessage = $_SESSION['flash_error'] ?? null;
                    unset($_SESSION['flash_success'], $_SESSION['flash_error']);
                ?>
                <?php if ($successMessage): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-error" style="margin-bottom: 20px;">
                        <?= htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>
                <div class="profile-container">
                    <!-- Profile Header -->
                    <header class="profile-header">
                        <div class="profile-info">
                            <h2>Your Active Listings</h2>
                        </div>
                        <a href="/items/create" class="edit-profile-btn">Add New Item</a>
                    </header>
                    
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
                                            <a href="/items/edit?id=<?= $item['item_id'] ?>" class="btn-edit">Edit</a>
                                            <form method="POST" action="/items/delete" style="display: inline;">
                                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-items-message">You have no active listings.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="/assets/js/user/user-profile.js"></script>
</body>
</html>