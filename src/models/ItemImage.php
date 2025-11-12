<?php

namespace Models; 

use Core\Database;
use Exception;

class ItemImage {
    
     protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAllImagesByItemId($itemId) {
        $statement = $this->db->query("SELECT * FROM item_image WHERE item_id = ?", [$itemId]);
        return $statement;
    }

    public function addImage($itemId, $imageUrl) {
        try {
            $statement = $this->db->query("INSERT INTO item_image (item_id, image_url) VALUES (?, ?)", [$itemId, $imageUrl]);
            return true;
        } catch (Exception $e) {
            error_log("Error saving image to database: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteImage($imageId) {
        return $this->db->query("DELETE FROM item_image WHERE image_id = ?", [$imageId]);
    }

    /**
     * For additional methods only 
     */
    public function deleteImagesByItemId($itemId) {
        return $this->db->query("DELETE FROM item_image WHERE item_id = ?", [$itemId]); 
    }
    
}