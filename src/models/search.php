<?php 


namespace Models;

use core\Database;

class Search {
    protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function searchItems($query) {
        return $this->db->query("SELECT * FROM items WHERE title LIKE ?", ["%$query%"]);
    }
}


?>