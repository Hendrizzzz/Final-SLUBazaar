<?php

use PHPUnit\Framework\TestCase;
use Core\Database;
use Models\User;
use Models\Item;
use Models\Bid;
use Models\ItemImage;

class SLUBazaarTest extends TestCase
{
    private $db;
    private $userModel;
    private $itemModel;
    private $bidModel;
    private $itemImageModel;
    
    protected function setUp(): void
    {
        // Set up database connection for testing
        $config = require __DIR__ . '/../config/database.php';
        $this->db = new Database($config);
        $this->userModel = new User($this->db);
        $this->itemModel = new Item($this->db);
        $this->bidModel = new Bid($this->db);
        $this->itemImageModel = new ItemImage($this->db);
    }
    
    /**
     * Test User Registration
     */
    public function testUserRegistration()
    {
        // Test that a new user can be registered
        $firstName = "Test";
        $username = "testuser" . time(); // Unique username
        $email = "test" . time() . "@example.com"; // Unique email
        $passwordHash = password_hash("password123", PASSWORD_BCRYPT);
        
        $result = $this->userModel->create($firstName, $username, $email, $passwordHash);
        $this->assertTrue($result, "User registration should succeed");
    }
    
    /**
     * Test Market Browsing
     */
    public function testMarketBrowsing()
    {
        // Test that users can browse active auctions
        $activeItems = $this->itemModel->getAllActiveItems();
        $this->assertIsArray($activeItems, "Active items should be returned as an array");
    }
    
    /**
     * Test Search Functionality
     */
    public function testSearchFunctionality()
    {
        // Test that users can search for items
        $query = "test";
        $results = $this->itemModel->search($query);
        
        $this->assertIsArray($results, "Search results should be returned as an array");
    }
    
    /**
     * Test Item Deletion
     */
    public function testItemDeletion()
    {
        // First create a test item to delete
        $sellerId = 1; // Assuming we have a test user with ID 1
        $title = "Test Item for Deletion";
        $description = "Test Description";
        $startingBid = 100.00;
        $auctionEnd = date('Y-m-d H:i:s', strtotime('+7 days'));
        $category = "Electronics";
        
        $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
        
        // Verify the item was created
        $this->assertIsNumeric($itemId, "Item creation should return a numeric ID");
        
        // Test the delete functionality
        $result = $this->itemModel->deleteItembyId($itemId);
        $this->assertTrue($result, "Item deletion should succeed");
        
        // Verify the item no longer exists
        $deletedItem = $this->itemModel->findById($itemId);
        $this->assertFalse($deletedItem, "Deleted item should not be found");
    }
    
    /**
     * Test Bid Placement
     */
    public function testBidPlacement()
    {
        // First create a test item to bid on
        $sellerId = 1; // Assuming we have a test user with ID 1
        $title = "Test Item for Bidding";
        $description = "Test Description";
        $startingBid = 100.00;
        $auctionEnd = date('Y-m-d H:i:s', strtotime('+7 days'));
        $category = "Electronics";
        
        $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
        
        // Verify the item was created
        $this->assertIsNumeric($itemId, "Item creation should return a numeric ID");
        
        // Test placing a bid
        $bidderId = 2; // Assuming we have another test user
        $bidAmount = 150.00;
        
        $result = $this->bidModel->placeBid($itemId, $bidderId, $bidAmount);
        $this->assertTrue($result, "Bid placement should succeed");
        
        // Manually update the item's current bid (this is done in the controller in the real application)
        $updateResult = $this->itemModel->updateCurrentBid($itemId, $bidAmount);
        $this->assertTrue($updateResult, "Item current bid update should succeed");
        
        // Verify the bid was recorded
        $bids = $this->bidModel->findBidsByItemId($itemId);
        $this->assertIsArray($bids, "Bids should be returned as an array");
        $this->assertNotEmpty($bids, "There should be at least one bid");
        
        // Verify the item's current bid was updated
        $updatedItem = $this->itemModel->findById($itemId);
        $this->assertEquals($bidAmount, $updatedItem['current_bid'], "Item's current bid should match the bid amount");
    }
    
    /**
     * Test Image Insertion
     */
    public function testImageInsertion()
    {
        // First create a test item to add images to
        $sellerId = 1; // Assuming we have a test user with ID 1
        $title = "Test Item for Image";
        $description = "Test Description";
        $startingBid = 100.00;
        $auctionEnd = date('Y-m-d H:i:s', strtotime('+7 days'));
        $category = "Electronics";
        
        $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
        
        // Verify the item was created
        $this->assertIsNumeric($itemId, "Item creation should return a numeric ID");
        
        // Test adding an image to the item
        $imageUrl = "/uploads/test_image.jpg";
        $result = $this->itemImageModel->addImage($itemId, $imageUrl);
        $this->assertTrue($result, "Image insertion should succeed");
        
        // Verify the image was added
        $imagesStmt = $this->itemImageModel->getAllImagesByItemId($itemId);
        $images = $imagesStmt->fetchAll();
        $this->assertIsArray($images, "Images should be returned as an array");
        $this->assertNotEmpty($images, "There should be at least one image");
        
        // Verify the image URL matches
        $this->assertEquals($imageUrl, $images[0]['image_url'], "Image URL should match");
    }
}
?>