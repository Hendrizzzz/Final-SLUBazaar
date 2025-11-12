<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> - SLU Bazaar</title>
    <link rel="stylesheet" href="/assets/css/user/user-market.css">
    <link rel="stylesheet" href="/assets/css/user/user-auction.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
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

                <div class="auction-container">
                     <div class="auction-top-panel">
                        <div class="gallery">
                            <div class="thumbnails"><div class="thumb active"></div><div class="thumb"></div><div class="thumb"></div></div>
                            <div class="main-image"></div>
                        </div>
                        <div class="bidding-panel">
                            <h2><?= htmlspecialchars($item['title']) ?></h2>
                            <div class="bid-info-grid">
                                <div><label>Current Bid Price</label><span>₱ <?= number_format($item['current_bid'] ?? $item['starting_bid'], 2) ?></span></div>
                                <div><label>Next Minimum Bid</label><span>₱ <?= number_format(($item['current_bid'] ?? $item['starting_bid']) + 1.00, 2) ?></span></div>
                                <div><label>Time Remaining</label><span class="countdown" data-end-time="<?= $item['auction_end'] ?>">--:--:--</span></div>
                                <button id="openBidModalBtn" class="submit-bid-btn">Submit a Bid</button>
                            </div>
                        </div>
                    </div>
                    <div class="auction-bottom-panel">
                        <div class="description-section"><h3>Description</h3><p><?= nl2br(htmlspecialchars($item['description'])) ?></p></div>
                        <div class="bid-history-section">
                            <h3>Bid History:</h3>
                            <div class="bid-history-header"><span>User</span><span>Date</span><span>Submitted Bid</span></div>
                            <div class="bid-history-list">
                                <?php if (!empty($bidHistory)): ?>
                                    <?php foreach ($bidHistory as $bid): ?>
                                        <div class="bid-history-item">
                                            <span class="bid-user"><img src="/assets/images/default-avatar.png" alt="avatar"> <?= htmlspecialchars($bid['username']) ?></span>
                                            <span class="bid-date"><?= date('M j, Y', strtotime($bid['bid_timestamp'])) ?></span>
                                            <span class="bid-amount">₱ <?= number_format($bid['bid_amount'], 2) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-bids">No bids have been placed yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ======== BID MODAL (POPUP PANEL) ======== -->
    <div id="bidModal" class="modal-overlay">
        <div class="modal-content">
            <header class="modal-header">
                <h3>Place Your Bid</h3>
                <button id="closeBidModalBtn" class="close-btn">&times;</button>
            </header>
            
            <form action="/bids/place" method="POST" class="modal-body">
                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                <p class="modal-item-title">Bidding on: <strong><?= htmlspecialchars($item['title']) ?></strong></p>
                <div class="modal-info-row">
                    <span>Current Bid:</span>
                    <strong>₱ <?= number_format($item['current_bid'] ?? $item['starting_bid'], 2) ?></strong>
                </div>
                <hr>
                <div class="bid-input-group">
                    <label for="bid_amount">Your Bid Amount</label>
                    <div class="bid-input-wrapper">
                        <span>₱</span>
                        <input type="number" id="bid_amount" name="bid_amount" step="0.01" 
                               placeholder="<?= number_format(($item['current_bid'] ?? $item['starting_bid']) + 1.00, 2) ?>" 
                               min="<?= number_format(($item['current_bid'] ?? $item['starting_bid']) + 1.00, 2, '.', '') ?>" 
                               required>
                    </div>
                    <small>You must enter a value greater than the current bid.</small>
                </div>
                <button type="submit" class="modal-submit-btn">Place Bid</button>
            </form>
        </div>
    </div>

    <script src="/assets/js/user/user-auction.js"></script>
</body>
</html>