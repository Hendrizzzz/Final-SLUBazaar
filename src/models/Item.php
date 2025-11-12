<?php

namespace Models;

use Core\Database;

class Item
{
    protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Fetches all items that are currently active for auction.
     * @return array
     */
    public function getAllActiveItems(): array
    {
        // Fetches items where the auction has not ended and status is 'Active'
        $sql = "SELECT * FROM item WHERE status = 'Active' AND auction_end > NOW() ORDER BY auction_end ASC";
        
        $statement = $this->db->query($sql);
        
        return $statement->fetchAll();
    }


    /**
     * Creates a new item listing in the database.
     * @return int|false The ID of the new item, or false on failure.
     */
    public function create(int $sellerId, string $title, string $description, float $startingBid, string $auctionEnd)
    {
        try {
            $sql = "INSERT INTO item (seller_id, title, description, starting_bid, auction_end, category) 
                    VALUES (:seller_id, :title, :description, :starting_bid, :auction_end, 'Default')"; // Added a default category

            $this->db->query($sql, [
                'seller_id' => $sellerId,
                'title' => $title,
                'description' => $description,
                'starting_bid' => $startingBid,
                'auction_end' => $auctionEnd
            ]);
            
            // Return the ID of the row we just inserted
            return $this->db->connection->lastInsertId();

        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Finds a single item by its unique ID.
     * This is the method that was missing.
     *
     * @param int $itemId The ID of the item to find.
     * @return array|false The item's data as an associative array, or false if not found.
     */
    public function findById(int $itemId)
    {
        $sql = "SELECT * FROM item WHERE item_id = :item_id";
        
        // Execute the query, binding the item_id to prevent SQL injection
        $statement = $this->db->query($sql, ['item_id' => $itemId]);
        
        // fetch() gets a single row. If no row is found, it returns false automatically.
        return $statement->fetch();
    }




    /**
     * Checks if a seller already has an active auction with the same title.
     * This is used to prevent duplicate listings.
     * We trim whitespace and compare in lowercase to make the check more robust.
     *
     * @param int $sellerId The ID of the seller.
     * @param string $title The title of the item.
     * @return bool True if a duplicate exists, false otherwise.
     */
    public function findActiveDuplicate(int $sellerId, string $title): bool
    {
        $sql = "SELECT COUNT(*) FROM item WHERE seller_id = :seller_id AND LOWER(TRIM(title)) = LOWER(TRIM(:title)) AND status = 'Active'";
        
        $statement = $this->db->query($sql, [
            'seller_id' => $sellerId,
            'title' => $title
        ]);
        
        // fetchColumn() gets the value of the first column (the COUNT).
        // If the count is greater than 0, a duplicate exists.
        return $statement->fetchColumn() > 0;
    }





    /**
     * Updates the current_bid field in the item table.
     */
    public function updateCurrentBid(int $itemId, float $newBidAmount): bool
    {
        try {
            $sql = "UPDATE item SET current_bid = :new_bid_amount WHERE item_id = :item_id";
            $this->db->query($sql, [
                'new_bid_amount' => $newBidAmount,
                'item_id' => $itemId
            ]);
            return true;
        } catch (\PDOException $e) { return false; }
    }



    /**
     * Finds all active items listed by a specific seller.
     * @param int $sellerId
     * @return array
     */
    public function findActiveBySellerId(int $sellerId): array
    {
        $sql = "SELECT * FROM item WHERE seller_id = :seller_id AND status = 'Active' ORDER BY auction_end ASC";
        $statement = $this->db->query($sql, ['seller_id' => $sellerId]);
        return $statement->fetchAll();
    }

    
    /**
     * Finds all completed items sold by a specific seller, along with the winning price.
     * @param int $sellerId
     * @return array
     */
    public function findSoldBySellerId(int $sellerId): array
    {
        $sql = "SELECT i.item_id, i.title,
                       (SELECT MAX(b.bid_amount) FROM bid b WHERE b.item_id = i.item_id) AS winning_price
                FROM item i
                WHERE i.seller_id = :seller_id AND i.status = 'Completed'
                ORDER BY i.auction_end DESC";
        $statement = $this->db->query($sql, ['seller_id' => $sellerId]);
        return $statement->fetchAll();
    }
}