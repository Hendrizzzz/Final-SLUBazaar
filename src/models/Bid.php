<?php
// New file: src/models/Bid.php

namespace Models;

use Core\Database;

class Bid
{
    protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Finds all bids made by a specific user, joining with item data.
     * @param int $userId The ID of the bidder.
     * @return array
     */
    public function findBidsByUserId(int $userId): array
    {
        $sql = "SELECT 
                    b.item_id, 
                    b.bid_amount,
                    i.title AS item_name,
                    -- A subquery to find the winning bid amount for completed items
                    (SELECT MAX(bid_amount) FROM bid WHERE item_id = i.item_id) as winning_bid,
                    i.status
                FROM bid b
                JOIN item i ON b.item_id = i.item_id
                WHERE b.bidder_id = :user_id
                GROUP BY b.item_id -- Get only the highest bid per item for this user
                ORDER BY b.bid_timestamp DESC";

        $statement = $this->db->query($sql, ['user_id' => $userId]);
        return $statement->fetchAll();
    }


    /**
     * Finds all bids for a specific item, joining with user data.
     * @param int $itemId
     * @return array
     */
    public function findBidsByItemId(int $itemId): array
    {
        $sql = "SELECT b.bid_amount, b.bid_timestamp, u.username 
                FROM bid b
                JOIN user u ON b.bidder_id = u.user_id
                WHERE b.item_id = :item_id
                ORDER BY b.bid_timestamp DESC";
        $statement = $this->db->query($sql, ['item_id' => $itemId]);
        return $statement->fetchAll();
    }



    /**
     * Inserts a new bid into the database.
     */
    public function placeBid(int $itemId, int $bidderId, float $bidAmount): bool
    {
        try {
            $sql = "INSERT INTO bid (item_id, bidder_id, bid_amount) VALUES (:item_id, :bidder_id, :bid_amount)";
            $this->db->query($sql, [
                'item_id' => $itemId,
                'bidder_id' => $bidderId,
                'bid_amount' => $bidAmount
            ]);
            return true;
        } catch (\PDOException $e) { return false; }
    }
}