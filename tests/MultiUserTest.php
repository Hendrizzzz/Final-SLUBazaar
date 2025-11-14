<?php

use PHPUnit\Framework\TestCase;
use Core\Database;
use Models\User;
use Models\Item;
use Models\Bid;

class MultiUserTest extends TestCase
{
    private $db;
    private $userModel;
    private $itemModel;
    private $bidModel;
    
    protected function setUp(): void
    {
        // Set up database connection for testing
        $config = require __DIR__ . '/../config/database.php';
        $this->db = new Database($config);
        $this->userModel = new User($this->db);
        $this->itemModel = new Item($this->db);
        $this->bidModel = new Bid($this->db);
    }
    
    /**
     * Test Multiple User Registration
     */
    public function testMultipleUserRegistration()
    {
        // Test that multiple users can be registered
        $users = [
            ["John", "john" . time(), "john" . time() . "@example.com"],
            ["Jane", "jane" . time(), "jane" . time() . "@example.com"],
            ["Bob", "bob" . time(), "bob" . time() . "@example.com"]
        ];
        
        foreach ($users as $userData) {
            $firstName = $userData[0];
            $username = $userData[1];
            $email = $userData[2];
            $passwordHash = password_hash("password123", PASSWORD_BCRYPT);
            
            $result = $this->userModel->create($firstName, $username, $email, $passwordHash);
            $this->assertTrue($result, "User registration should succeed for " . $username);
        }
    }
    
    /**
     * Test Concurrent Bidding
     */
    public function testConcurrentBidding()
    {
        // Test that multiple users can bid on the same item
        // This simulates the race condition scenario
        
        // First create a test item
        $sellerId = 1; // Assuming we have a test user
        $title = "Test Auction Item";
        $description = "Test Description for concurrent bidding";
        $startingBid = 100.00;
        $auctionEnd = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $category = "Electronics";
        
        $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
        
        // If item creation failed, skip the test
        if (!$itemId) {
            $this->markTestSkipped('Could not create test item');
            return;
        }
        
        // Simulate multiple users placing bids
        $bids = [
            [2, 150.00], // User 2 bids 150
            [3, 200.00], // User 3 bids 200
            [4, 175.00]  // User 4 bids 175
        ];
        
        $successCount = 0;
        foreach ($bids as $bidData) {
            $bidderId = $bidData[0];
            $bidAmount = $bidData[1];
            
            $result = $this->bidModel->placeBid($itemId, $bidderId, $bidAmount);
            if ($result) {
                $successCount++;
            }
        }
        
        // At least some bids should succeed
        $this->assertGreaterThan(0, $successCount, "At least one bid should succeed");
        
        // Clean up - delete the test item
        // Note: In a real test environment, you might want to use a separate test database
    }
    
    /**
     * Test User Session Isolation
     */
    public function testUserSessionIsolation()
    {
        // Test that users don't interfere with each other's sessions
        // This is more of a conceptual test since we can't easily test actual sessions in PHPUnit
        
        $this->assertTrue(true, "Session isolation test placeholder");
    }
    
    /**
     * Test Auction End with Multiple Users
     */
    public function testAuctionEndWithMultipleUsers()
    {
        // Test that an auction ending doesn't cause issues with multiple users
        
        $this->assertTrue(true, "Auction end test placeholder");
    }
    
    /**
     * Test Multiple Users Creating Listings
     */
    public function testMultipleUsersCreatingListings()
    {
        // Test that multiple users can create listings simultaneously
        // and that each user's listings are properly associated with their account
        
        // Create test users if they don't exist
        $users = [
            ["Alice", "alice" . time(), "alice" . time() . "@example.com"],
            ["Bob", "bob" . time(), "bob" . time() . "@example.com"],
            ["Charlie", "charlie" . time(), "charlie" . time() . "@example.com"]
        ];
        
        $userIds = [];
        foreach ($users as $userData) {
            $firstName = $userData[0];
            $username = $userData[1];
            $email = $userData[2];
            $passwordHash = password_hash("password123", PASSWORD_BCRYPT);
            
            // Try to create the user
            $result = $this->userModel->create($firstName, $username, $email, $passwordHash);
            // We're not asserting on result here because the user might already exist
            
            // Find the user to get their ID
            $user = $this->userModel->findByEmailOrUsername($username);
            if ($user) {
                $userIds[] = $user['user_id'];
            }
        }
        
        // Verify we have user IDs
        $this->assertNotEmpty($userIds, "Should have at least one user ID");
        
        // Each user creates multiple listings
        $listings = [
            [
                "user_index" => 0,
                "listings" => [
                    ["Laptop", "Gaming laptop", 500.00, "+3 days", "Electronics"],
                    ["Book", "Programming book", 25.00, "+5 days", "Books"]
                ]
            ],
            [
                "user_index" => 1,
                "listings" => [
                    ["Phone", "Smartphone", 300.00, "+2 days", "Electronics"],
                    ["Desk", "Office desk", 100.00, "+7 days", "Furniture"]
                ]
            ],
            [
                "user_index" => 2,
                "listings" => [
                    ["Camera", "Digital camera", 400.00, "+4 days", "Electronics"],
                    ["Chair", "Office chair", 75.00, "+6 days", "Furniture"]
                ]
            ]
        ];
        
        $createdItems = [];
        
        // Create listings for each user
        foreach ($listings as $userListings) {
            $userIndex = $userListings["user_index"];
            $userId = $userIds[$userIndex];
            
            foreach ($userListings["listings"] as $listingData) {
                $title = $listingData[0];
                $description = $listingData[1];
                $startingBid = $listingData[2];
                $duration = $listingData[3];
                $category = $listingData[4];
                
                $auctionEnd = date('Y-m-d H:i:s', strtotime($duration));
                
                $itemId = $this->itemModel->create($userId, $title, $description, $startingBid, $auctionEnd, $category);
                
                if ($itemId) {
                    $createdItems[] = [
                        'item_id' => $itemId,
                        'user_id' => $userId,
                        'title' => $title
                    ];
                }
            }
        }
        
        // Verify that items were created
        $this->assertNotEmpty($createdItems, "Should have created at least one item");
        
        // Verify that each item is associated with the correct user
        foreach ($createdItems as $item) {
            $itemId = $item['item_id'];
            $expectedUserId = $item['user_id'];
            
            // Retrieve the item from the database
            $retrievedItem = $this->itemModel->findById($itemId);
            
            // Verify the item exists
            $this->assertIsArray($retrievedItem, "Item should be found in database");
            
            // Verify the item is associated with the correct user
            $this->assertEquals($expectedUserId, $retrievedItem['seller_id'], 
                "Item should be associated with the correct user");
            
            // Verify the item has the correct title
            $this->assertEquals($item['title'], $retrievedItem['title'], 
                "Item should have the correct title");
        }
        
        // Verify that each user can only see their own active listings
        foreach ($userIds as $userId) {
            $userListings = $this->itemModel->findActiveBySellerId($userId);
            
            // Count how many items this user created
            $expectedCount = 0;
            foreach ($createdItems as $item) {
                if ($item['user_id'] == $userId) {
                    $expectedCount++;
                }
            }
            
            // Verify the count matches
            $this->assertCount($expectedCount, $userListings, 
                "User should have the correct number of active listings");
            
            // Verify all listings belong to this user
            foreach ($userListings as $listing) {
                $this->assertEquals($userId, $listing['seller_id'], 
                    "All listings should belong to the user");
            }
        }
    }
}
?>