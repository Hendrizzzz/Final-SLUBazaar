<?php
// This is your main admin layout file
// It's the "shell" that all admin pages use.
// The Controller renders this file and passes in $pageTitle and $content (the path to the partial view)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLU Bazaar | <?php echo htmlspecialchars($pageTitle ?? 'Admin'); ?></title>

    <!-- Paths are now absolute from the public root, which is correct -->
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin/admin-layout.css">
    <link rel="stylesheet" href="/assets/css/admin/admin-overview.css">
    <link rel="stylesheet" href="/assets/css/admin/admin-users.css">
    <link rel="stylesheet" href="/assets/css/admin/admin-listings.css">

    <!-- Defer loading JS for faster page load -->
    <script src="/assets/js/admin/admin-users.js" defer></script>
    <script src="/assets/js/admin/admin-listings.js" defer></script>
    <script src="/assets/js/admin/admin-overview.js" defer></script>

</head>
<body>
<div class="bg-overlay"></div>

<div class="title-tagline-box">
    <div class="title-tagline">
        <img src="/assets/images/SLU Logo.png" alt="slu_logo" onerror="this.src='https://placehold.co/128x128/ffffff/003366?text=SLU'; this.onerror=null;">
        <div>
            <h1>SLU Bazaar</h1>
            <p>Admin Hub</p>
        </div>
    </div>

    <div class="dashboard">
        <!-- Navigation now uses proper links, not JS tabs -->
        <ul id="tabs">
            <li class="<?php echo ($pageTitle === 'Overview') ? 'active-tab' : ''; ?>">
                <a href="/admin/overview">Overview</a>
            </li>
            <li class="<?php echo ($pageTitle === 'Users') ? 'active-tab' : ''; ?>">
                <a href="/admin/users">Users</a>
            </li>
            <li class="<?php echo ($pageTitle === 'Listings') ? 'active-tab' : ''; ?>">
                <a href="/admin/listings">Listings</a>
            </li>
            <!-- Add links for Analytics and Reports here -->
        </ul>

        <div class="tab-contents">
            <?php
            // This is the magic part!
            // It includes the specific partial view (overview, users, or listings)
            if (isset($content) && file_exists($content)) {
                require $content;
            } else {
                echo "<p>Error: View file not found.</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>