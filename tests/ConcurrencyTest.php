<?php

use PHPUnit\Framework\TestCase;
use Core\Database;
use Models\User;
use Models\Item;
use Models\Bid;

class ConcurrencyTest extends TestCase
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
     * Test Race Condition in Bidding
     * This test simulates what might happen if multiple users try to bid simultaneously
     */
    public function testBidRaceCondition()
    {
        // Create a test item
        $sellerId = 1; // Assuming we have a test user
        $title = "Race Condition Test Item";
        $description = "Item to test bidding race conditions";
        $startingBid = 100.00;
        $auctionEnd = date('Y-m-d H:i:s', strtotime('+1 day'));
        $category = "Electronics";
        
        $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
        
        // If item creation failed, skip the test
        if (!$itemId) {
            $this->markTestSkipped('Could not create test item');
            return;
        }
        
        // Get the initial item data
        $initialItem = $this->itemModel->findById($itemId);
        $initialBid = $initialItem['starting_bid'];
        
        // Simulate multiple users trying to bid at the same time
        // In a real scenario, these would be simultaneous requests
        $bids = [
            [2, $initialBid + 10], // User 2 bids 110
            [3, $initialBid + 20], // User 3 bids 120
            [4, $initialBid + 15], // User 4 bids 115
        ];
        
        $successfulBids = 0;
        $bidResults = [];
        
        // Place all bids
        foreach ($bids as $bidData) {
            $bidderId = $bidData[0];
            $bidAmount = $bidData[1];
            
            $result = $this->bidModel->placeBid($itemId, $bidderId, $bidAmount);
            $bidResults[] = [
                'bidder_id' => $bidderId,
                'amount' => $bidAmount,
                'success' => $result
            ];
            
            if ($result) {
                $successfulBids++;
                // Update the item's current bid after each successful bid
                $this->itemModel->updateCurrentBid($itemId, $bidAmount);
            }
        }
        
        // Verify that at least some bids were successful
        $this->assertGreaterThan(0, $successfulBids, "At least one bid should succeed");
        
        // Get the final item data
        $finalItem = $this->itemModel->findById($itemId);
        $finalBid = $finalItem['current_bid'] ?? $finalItem['starting_bid'];
        
        // The final bid should be numeric
        $this->assertIsNumeric($finalBid, "Final bid should be a numeric value");
    }
    
    /**
     * Test Multiple Users Creating Items Simultaneously
     */
    public function testMultipleItemCreation()
    {
        // Test that multiple users can create items at the same time without conflicts
        $sellerId = 1; // Assuming we have a test user
        
        $items = [
            ["Item 1", "Description 1", 100.00, "+1 day", "Electronics"],
            ["Item 2", "Description 2", 200.00, "+2 days", "Books"],
            ["Item 3", "Description 3", 150.00, "+3 days", "Clothing"],
        ];
        
        $createdItems = [];
        
        foreach ($items as $itemData) {
            $title = $itemData[0];
            $description = $itemData[1];
            $startingBid = $itemData[2];
            $duration = $itemData[3];
            $category = $itemData[4];
            
            $auctionEnd = date('Y-m-d H:i:s', strtotime($duration));
            
            $itemId = $this->itemModel->create($sellerId, $title, $description, $startingBid, $auctionEnd, $category);
            
            if ($itemId) {
                $createdItems[] = $itemId;
            }
        }
        
        // Verify that all items were created
        $this->assertCount(count($items), $createdItems, "All items should be created successfully");
    }
    
    /**
     * Test Database Connection Handling Under Load
     */
    public function testDatabaseConnectionHandling()
    {
        // Test that the database can handle multiple simultaneous operations
        // This is more of a conceptual test since we can't easily simulate load in PHPUnit
        
        // Perform multiple database operations
        $operations = [
            'getAllActiveItems',
            'search',
            'findActiveBySellerId',
        ];
        
        $results = [];
        
        foreach ($operations as $operation) {
            try {
                switch ($operation) {
                    case 'getAllActiveItems':
                        $result = $this->itemModel->getAllActiveItems();
                        $results[] = is_array($result);
                        break;
                    case 'search':
                        $result = $this->itemModel->search("test");
                        $results[] = is_array($result);
                        break;
                    case 'findActiveBySellerId':
                        $result = $this->itemModel->findActiveBySellerId(1);
                        $results[] = is_array($result);
                        break;
                }
            } catch (Exception $e) {
                $results[] = false;
            }
        }
        
        // All operations should succeed
        $successfulOperations = array_filter($results);
        $this->assertCount(count($operations), $successfulOperations, "All database operations should succeed");
    }
}
?>