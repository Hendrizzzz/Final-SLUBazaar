<?php

namespace Models; 

use Core\Database;

class Message {
     protected $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAllMessages() {
        return $this->db->query("SELECT * FROM messages", [], true);
    }

    public function addMessage($data) {
        return $this->db->query("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)", [$data['name'], $data['email'], $data['message']]);
    }
    public function deleteMessage($id) {
        return $this->db->query("DELETE FROM messages WHERE id = ?", [$id]);
    }

    public function getMessageById($id) {
        return $this->db->query("SELECT * FROM messages WHERE id = ?", [$id], true);
    }
    

}