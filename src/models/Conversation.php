<?php
// New file: src/models/Conversation.php

namespace Models;

use Core\Database;

class Conversation
{
    protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Finds all conversations for a specific user.
     * It intelligently joins to get the other user's name and the item title.
     * @param int $userId The ID of the logged-in user.
     * @return array A list of conversations.
     */
    public function findConversationsByUserId(int $userId): array
    {
        $sql = "SELECT 
                    c.conversation_id,
                    i.title AS item_title,
                    -- Use a CASE statement to determine the other user's ID
                    CASE 
                        WHEN c.buyer_id = :user_id THEN c.seller_id
                        ELSE c.buyer_id 
                    END AS other_user_id,
                    -- And another CASE statement to get the other user's name
                    CASE 
                        WHEN c.buyer_id = :user_id THEN u_seller.username
                        ELSE u_buyer.username 
                    END AS other_username
                FROM conversation c
                -- Join with the item table to get the title
                JOIN item i ON c.item_id = i.item_id
                -- Join with the user table TWICE: once for the buyer, once for the seller
                JOIN user u_buyer ON c.buyer_id = u_buyer.user_id
                JOIN user u_seller ON c.seller_id = u_seller.user_id
                WHERE c.buyer_id = :user_id OR c.seller_id = :user_id
                ORDER BY c.conversation_id DESC"; // You would normally order by last message timestamp

        $statement = $this->db->query($sql, ['user_id' => $userId]);
        return $statement->fetchAll();
    }
}