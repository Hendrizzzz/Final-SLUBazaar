<?php
// Partial view for Overview
// The $stats variable comes from the AdminController
?>
<div id="overview" data-tab-content class="active">
    <h1 id="today">Today</h1>
    <ul class="values">
        <li><div><h1><?php echo $stats['active_listing'] ?? 0; ?></h1><p>Active Listing</p></div></li>
        <li><div><h1><?php echo $stats['closed_listing'] ?? 0; ?></h1><p>Closed Listing</p></div></li>
        <li><div><h1><?php echo $stats['new_users'] ?? 0; ?></h1><p>New Users</p></div></li>
        <li><div><h1><?php echo $stats['sold'] ?? 0; ?></h1><p>Sold</p></div></li>
        <li><div><h1><?php echo $stats['reported_listings'] ?? 0; ?></h1><p>Reported Listings</p></div></li>
        <li><div><h1><?php echo $stats['reported_users'] ?? 0; ?></h1><p>Reported Users</p></div></li>
    </ul>
</div>