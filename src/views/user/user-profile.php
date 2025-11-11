<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SLU Bazaar | User Profile</title>
  <link rel="stylesheet" href="user.css">
  <script src="user.js" defer></script>
</head>
<body>

  <nav class="sidebar">
    <div class="logo">
      <img src="../assets/img/SLU Logo.png" alt="SLU Logo">
    </div>
    <ul>
      <li><a href="#" id="home-link">🏠</a></li>
      <li><a href="#" id="profile-link" class="active">👤</a></li>
      <li><a href="#" id="market-link">🛒</a></li>
      <li><a href="#" id="settings-link">⚙️</a></li>
    </ul>
  </nav>

  <main class="content">
    <div class="bg-wrapper">
      
      <section class="banner">
        <h1>SLU Bazaar</h1>
        <p>The official marketplace of Saint Louis University</p>
      </section>

      <section class="profile-section">
        <div class="profile-card">
          <div class="profile-left">
           <img id="profile-pic" src="../assets/img/Christian_Profile.png" alt="Profile Picture">
            <div class="user-info">
              <h2 id="username">Username</h2>
              <p class="stars">⭐⭐⭐⭐☆</p>
            </div>
          </div>
          <button id="edit-profile-btn">Edit Profile</button>
        </div>

        <div class="tabs">
          <button class="tab active" data-tab="active">Active Listings</button>
          <button class="tab" data-tab="sold">Sold Items</button>
          <button class="tab" data-tab="bids">Bid History</button>
        </div>

        <div class="search-filter">
          <input type="text" id="search" placeholder="Search">
          <button id="filter-btn">⚙️ Filters</button>
        </div>

        <div id="listing-container" class="listing-grid">

          <div class="listing-card">
           <img src="../assets/img/YellowBoots.png" alt="Yellow Boots">

            <h3>Yellow Boots</h3>
            <p>₱1000</p>
            <span>⏰ 04:00:59</span>
          </div>

          <div class="listing-card">
            <img src="../assets/img/BlueJacket.png" alt="Blue Jacket">
            <h3>Blue Jacket</h3>
            <p>₱750</p>
            <span>⏰ 02:15:30</span>
          </div>
        </div>
      </section>

    </div> 
  </main>

  <div class="modal" id="edit-modal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <label for="edit-name">Name:</label>
      <input type="text" id="edit-name">

      <label for="edit-image">Profile Image:</label>
      <input type="file" id="edit-image">

      <div class="modal-actions">
        <button id="save-profile">Save</button>
        <button id="cancel-profile">Cancel</button>
      </div>
    </div>
  </div>
</body>
</html>
