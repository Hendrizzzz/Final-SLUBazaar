<?php

namespace Controllers;

// We need to use the Database class from the Core namespace
use Core\Database;

// Other models (you can use these later when fetching data)
use Models\Bid;
use Models\Item;
use Models\Rating;
use Models\Report;
use Models\User;


class AdminController {

    /**
     * The database connection object.
     * @var Database
     */
    protected Database $db; // This property will hold the connection

    /**
     * The constructor accepts the Database object from the router and saves it.
     *
     * @param Database $db The database connection instance.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Renders the Admin Overview page.
     * This is the method that was missing.
     */
    public function overview(): void
    {
        // 1. Set the page title for the layout's <title> tag
        $pageTitle = 'Overview';

        // 2. Set the path to the partial view file
        $content = __DIR__ . '/../views/admin/admin-overview.php';

        // 3. Prepare the $stats data for the overview view.
        //    (The view file 'admin-overview.php' expects this variable)
        //    Later, you will fetch this data from the database using $this->db
        $stats = [
            'active_listing' => 0, // TODO: Fetch real data
            'closed_listing' => 0, // TODO: Fetch real data
            'new_users' => 0,      // TODO: Fetch real data
            'sold' => 0,           // TODO: Fetch real data
            'reported_listings' => 0, // TODO: Fetch real data
            'reported_users' => 0  // TODO: Fetch real data
        ];

        // 4. Load the main admin layout file.
        //    The layout file will then use the $pageTitle and $content variables.
        require __DIR__ . '/../views/admin/admin-layout.php';
    }

    /**
     * Renders the Admin Users page.
     */
    public function users(): void
    {
        $pageTitle = 'Users';
        $content = __DIR__ . '/../views/admin/admin-users.php';

        // TODO: Add logic here to fetch user data from the database.

        require __DIR__ . '/../views/admin/admin-layout.php';
    }

    /**
     * Renders the Admin Listings page.
     */
    public function listings(): void
    {
        $pageTitle = 'Listings';
        $content = __DIR__ . '/../views/admin/admin-listings.php';

        // TODO: Add logic here to fetch listing data from the database.

        require __DIR__ . '/../views/admin/admin-layout.php';
    }

    /**
     * Renders the Admin Reports page.
     */
    public function reports(): void
    {
        $pageTitle = 'Reports';
        // Note: You haven't created this view file yet, so it might show an error.
        $content = __DIR__ . '/../views/admin/admin-reports.php';

        require __DIR__ . '/../views/admin/admin-layout.php';
    }

    /**
     * Renders the Admin Analytics page.
     */
    public function analytics(): void
    {
        $pageTitle = 'Analytics';
        // Note: You haven't created this view file yet, so it might show an error.
        $content = __DIR__ . '/../views/admin/admin-analytics.php';

        require __DIR__ . '/../views/admin/admin-layout.php';
    }
}