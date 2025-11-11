<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SLU Bazaar | Home</title>
  <link rel="stylesheet" href="userhome.css">
  <script src="userhome.js" defer></script>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo">
      <img src="../assets/img/SLU Logo.png" alt="SLU Logo">
    </div>
    <ul>
      <li><a href="#" id="home-link" class="active">🏠</a></li>
      <li><a href="../user/user/index.php" id="profile-link">👤</a></li>
      <li><a href="#" id="market-link">🛒</a></li>
      <li><a href="#" id="settings-link">⚙️</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="content">
    <div class="bg-wrapper">

      <!-- Banner -->
      <section class="banner">
        <h1>SLU Bazaar</h1>
        <p>The official marketplace of Saint Louis University</p>
      </section>

      <!-- Auctions Container -->
      <section class="auction-section">
        <div class="tab-buttons">
          <button class="tab active" data-tab="live">Live Auctions</button>
          <button class="tab" data-tab="bids">My Bids</button>
          <button class="tab" data-tab="watchlist">My Watchlist</button>
        </div>

        <div class="search-filter">
          <input type="text" id="search" placeholder="Search">
          <button id="filter-btn">⚙️ Filters</button>
        </div>

        <!-- Auction List -->
        <div id="auction-container" class="auction-grid">
          <!-- JS will render auction cards here -->
        </div>
      </section>
    </div>
  </main>

  <!-- Item Detail Modal -->
  <div class="modal" id="item-modal">
    <div class="modal-content">
      <div class="item-gallery">
        <div class="thumbnails" id="thumb-container"></div>
        <img id="main-img" src="" alt="Item Image">
      </div>

      <div class="item-details">
        <h2 id="item-name">Item Name</h2>
        <div class="bid-info">
          <p>Current Bid Price: ₱<span id="current-bid">1000</span></p>
          <p>Next Minimum Bid: ₱<span id="next-bid">1100</span></p>
          <p>Time Remaining: <span id="time-left">04:00:59</span></p>
          <button id="submit-bid-btn">Submit a Bid</button>
        </div>
        <div class="description">
          <h4>Description</h4>
          <p id="item-desc">This is the item description.</p>
        </div>

        <div class="bid-history">
          <h4>Bid History</h4>
          <div id="bid-history-list"></div>
        </div>
      </div>

      <button id="close-modal">✖</button>
    </div>
  </div>

</body>
</html>
