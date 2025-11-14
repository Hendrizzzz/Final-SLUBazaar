<?php
// Partial view for Listings
?>
<div id="listings" data-tab-content class="active">
    <!-- This now uses the 'users-panel' class, which is what your CSS targets -->
    <div class="users-panel">
        <div class="table-queries">
            <ul id="listings-queries">
                <li class="active-query" data-query="all">All listings</li>
                <li data-query="reported">Reported listings</li>
                <li data-query="sold">Sold listings</li>
                <li data-query="deleted">Deleted listings</li>
            </ul>
        </div>
        <div class="table-container">
            <div class="table-header">
                <h2 id="listing-table-title">All listings</h2>
                <div class="search-bar">
                    <input type="text" id="listing-search-input" placeholder="Search Listings">
                </div>
                <button id="delete-listing-btn">
                    <img src="/assets/images/trash-alt-svgrepo-com.svg" alt="delete_icon">
                </button>
            </div>

            <!-- This is the <table>, which matches your JS and CSS -->
            <table class="table-content">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-listings"></th>
                    <th>Listing ID</th>
                    <th>Date Posted</th>
                    <th>Item Name</th>
                    <th>Seller Name</th>
                    <th>Price (PHP)</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody id="listing-table-body">
                <!-- JS will populate this -->
                </tbody>
            </table>
            <div class="table-footer">
                <div class="pagination" id="listing-pagination"></div>
            </div>
        </div>
    </div>
</div>