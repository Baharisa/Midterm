<?php

class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔍 Read all categories
    public function read() {
        $query = "SELECT id, category FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 🔍 Read single category by ID
    public function read_single($id) {
        $query = "SELECT id, category FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return null;
    }


    public function create() {
        $query = "INSERT INTO " . $this->table . " (category) VALUES (:category) RETURNING id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
    
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
    
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);
    
        $stmt->execute();
    
        return $stmt->rowCount() > 0;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
    
        $stmt->execute();
    
        return $stmt->rowCount() > 0;
    }
    
    
}
