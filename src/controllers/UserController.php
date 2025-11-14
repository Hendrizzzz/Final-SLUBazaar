<?php 

namespace Controllers;

// remove or add as necessary
use Core\Database;
use Models\Bid;
use Models\Conversation;
use Models\Item;
use Models\ItemImage;
use Models\Message;
use Models\Rating;
use Models\User;

class UserController {

    protected Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function market(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit();
        }

        // --- Fetch data for ALL tabs ---
        $itemModel = new Item($this->db);
        $bidModel = new Bid($this->db);
        $userId = $_SESSION['user_id'];

        $liveAuctions = $itemModel->getAllActiveItems(); 
        $myBids = $bidModel->findBidsByUserId($userId); // Data for the "My Bids" tab
        // $myWatchlist = ... We will add this later

        require __DIR__ . '/../views/user/user-market.php';
    }





    /**
     * Displays a single auction item page (the "view item" page).
     */
    public function auction(): void
    {
        if (!isset($_SESSION['user_id'])) { header('Location: /'); exit(); }

        $itemId = $_GET['id'] ?? null;
        if (!$itemId) { http_response_code(400); echo "Error: Item ID is required."; exit(); }

        $itemModel = new Item($this->db);
        $userModel = new User($this->db);
        $bidModel = new Bid($this->db);
        $itemImageModel = new ItemImage($this->db);

        $item = $itemModel->findById((int)$itemId);
        if (!$item) { http_response_code(404); echo "Error: Item not found."; exit(); }

        $seller = $userModel->findById($item['seller_id']);

        $bidHistory = $bidModel->findBidsByItemId((int)$itemId);
        
        // Get images for this item (ensure it's always an array)
        $itemImagesStmt = $itemImageModel->getAllImagesByItemId((int)$itemId);
        $itemImages = [];
        if ($itemImagesStmt && is_object($itemImagesStmt)) {
            $itemImages = $itemImagesStmt->fetchAll();
        }
        
        $successMessage = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        require __DIR__ . '/../views/user/user-auction.php';
    }

    /**
     * Displays the user's messages and conversation list.
     */
    public function messages(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $conversationModel = new Conversation($this->db);
        
        $conversations = $conversationModel->findConversationsByUserId($userId);

        require __DIR__ . '/../views/user/user-messages.php';
    }




    /**
     * Displays the user's profile page with their listings and bid history.
     */
    public function profile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // --- Instantiate all necessary models ---
        $userModel = new User($this->db);
        $itemModel = new Item($this->db);
        $bidModel = new Bid($this->db);

       
        $user = $userModel->findById($userId);
        if (!$user) { header('Location: /market'); exit(); }

        $activeListings = $itemModel->findActiveBySellerId($userId);
        $soldItems = $itemModel->findSoldBySellerId($userId);
        $bidHistory = $bidModel->findBidsByUserId($userId);

        require __DIR__ . '/../views/user/user-profile.php';
    }





    /**
     * Shows the "New Listing" form and checks for a flash error message.
     */
    public function showCreateItemForm(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit();
        }

        $errorMessage = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        
        require __DIR__ . '/../views/user/user-create-item.php';
    }

    /**
     * Handles the POST request from the "New Listing" form.
     */
    public function handleCreateItem(): void
    {
        if (!isset($_SESSION['user_id'])) { exit(); }

        $sellerId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $itemModel = new Item($this->db);

        // --- DUPLICATE CHECK ---
        if ($itemModel->findActiveDuplicate($sellerId, $title)) {
            $_SESSION['flash_error'] = "You already have an active auction with this exact title.";
            header('Location: /items/create');
            exit();
        }
        
        // If the check passes, create the item
        $description = $_POST['description'] ?? '';
        $photos = $_FILES['photos'] ?? null;
        $startingBid = $_POST['starting_bid'] ?? 0;
        $durationDays = $_POST['duration'] ?? 1;
        $auctionEnd = (new \DateTime())->add(new \DateInterval("P{$durationDays}D"))->format('Y-m-d H:i:s');
        $category = $_POST['category'] ?? '';
        
        $newItemId = $itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);

       if ($newItemId) {
    // Process uploaded images
    $photos = $_FILES['photos'] ?? null;
    
    if ($photos && is_array($photos['name']) && count($photos['name']) > 0) {
        $itemImageModel = new ItemImage($this->db);
        
        // Create uploads directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Process each uploaded photo
        for ($i = 0; $i < count($photos['name']); $i++) {
            if ($photos['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '_' . basename($photos['name'][$i]);
                $targetPath = $uploadDir . $fileName;
                
                // Move uploaded file to destination
                if (move_uploaded_file($photos['tmp_name'][$i], $targetPath)) {
                    // Save image path to database
                    $itemImageModel->addImage($newItemId, '/uploads/' . $fileName);
                }
            }
        }
    }
    
    $_SESSION['flash_success'] = "Your new listing has been successfully created!";
    header('Location: /item/view?id=' . $newItemId);
    exit();
}
    }

    /**
     * Handles the POST request from the "Place Bid" form.
     */
    public function handlePlaceBid(): void
    {
        if (!isset($_SESSION['user_id'])) { exit(); }

        $itemId = $_POST['item_id'] ?? null;
        $bidAmount = $_POST['bid_amount'] ?? 0;
        $bidderId = $_SESSION['user_id'];
        
        // Always redirect back to the item page, even on basic errors
        $redirectUrl = '/item/view?id=' . $itemId;

        if (!$itemId || $bidAmount <= 0) {
            header('Location: ' . $redirectUrl);
            exit();
        }

        $itemModel = new Item($this->db);
        $bidModel = new Bid($this->db);
        $item = $itemModel->findById((int)$itemId);

        // --- SERVER-SIDE VALIDATION ---
        
        if ($item['seller_id'] == $bidderId) {
            $_SESSION['flash_error'] = "You cannot bid on your own item.";
            header('Location: ' . $redirectUrl);
            exit();
        }
        
        if (new \DateTime() > new \DateTime($item['auction_end'])) {
            $_SESSION['flash_error'] = "This auction has ended.";
            header('Location: ' . $redirectUrl);
            exit();
        }

        $currentBid = $item['current_bid'] ?? $item['starting_bid'];
        if ($bidAmount <= $currentBid) {
            $_SESSION['flash_error'] = "Your bid must be higher than the current bid.";
            header('Location: ' . $redirectUrl);
            exit();
        }

        // --- Place the Bid ---
        $bidSuccess = $bidModel->placeBid((int)$itemId, $bidderId, (float)$bidAmount);
        
        if ($bidSuccess) {
            $itemModel->updateCurrentBid((int)$itemId, (float)$bidAmount);
            $_SESSION['flash_success'] = "Your bid of ₱" . number_format($bidAmount, 2) . " was placed successfully!";
        } else {
            $_SESSION['flash_error'] = "There was an error placing your bid.";
        }

        header('Location: ' . $redirectUrl);
        exit();
    }

 /**
 * Handles the search functionality for the market page
 */
public function search() {
    $query = $_GET['query'] ?? '';
    
    if (empty($query)) {
        // Redirect to market page if no query
        header('Location: /market');
        exit();
    }
    
    $itemModel = new Item($this->db);
    $liveAuctions = $itemModel->search($query); // Changed variable name to match view
    
    require __DIR__ . '/../views/user/user-market.php';
}

/**
 * Handles the Edit Item form submission.
 */
public function handleEditItem(): void
{
    if (!isset($_SESSION['user_id'])) { 
        header('Location: /');
        exit(); 
    }
    
    $itemId = $_POST['item_id'] ?? null;
    
    // First, check if the user owns this item
    $itemModel = new Item($this->db);
    $item = $itemModel->findById((int)$itemId);
    
    if (!$item || $item['seller_id'] != $_SESSION['user_id']) {
        $_SESSION['flash_error'] = "You don't have permission to edit this item.";
        header('Location: /listings/active');
        exit();
    }
    
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $startingBid = $_POST['starting_bid'] ?? 0;
    $durationDays = $_POST['duration'] ?? 1;
    $auctionEnd = (new \DateTime())->add(new \DateInterval("P{$durationDays}D"))->format('Y-m-d H:i:s');
    $category = $_POST['category'] ?? '';
    
    $itemModel->update($itemId, $title, $description, $startingBid, $auctionEnd, $category);
    
    $_SESSION['flash_success'] = "Your item has been successfully updated!";
    header('Location: /listings/active');
    exit();
}

/**
 * Deletes an item.
 */
public function deleteItem(): void
{
    if (!isset($_SESSION['user_id'])) { 
        header('Location: /');
        exit(); 
    }
    
    $itemId = $_POST['item_id'] ?? null;
    
    // First, check if the user owns this item
    $itemModel = new Item($this->db);
    $item = $itemModel->findById((int)$itemId);
    
    if (!$item || $item['seller_id'] != $_SESSION['user_id']) {
        $_SESSION['flash_error'] = "You don't have permission to delete this item.";
        header('Location: /listings/active');
        exit();
    }
    
    $itemModel->deleteItembyId((int)$itemId);
    
    $_SESSION['flash_success'] = "Your item has been successfully deleted!";
    header('Location: /listings/active');
    exit();
}

/**
 * Handles the active listings functionality.
 */
public function activeListings(): void
{
    if (!isset($_SESSION['user_id'])) { 
        header('Location: /');
        exit(); 
    }
    
    $userId = $_SESSION['user_id'];
    $itemModel = new Item($this->db);
    $activeListings = $itemModel->findActiveBySellerId($userId);
    
    require __DIR__ . '/../views/user/user-active-listings.php';
}

/**
 * Handles the update item functionality.
 */


/**
 * Handles the edit item functionality.
 */
public function showEditItemForm(): void
{
    if (!isset($_SESSION['user_id'])) { 
        header('Location: /');
        exit(); 
    }
    
    $itemId = $_GET['id'] ?? null;
    if (!$itemId) { 
        header('Location: /listings/active');
        exit(); 
    }
    
    $itemModel = new Item($this->db);
    $item = $itemModel->findById((int)$itemId);
    
    // Ensure the user owns this item
    if (!$item || $item['seller_id'] != $_SESSION['user_id']) {
        header('Location: /listings/active');
        exit();
    }
    
    require __DIR__ . '/../views/user/user-edit-item.php';
}
}