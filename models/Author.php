<?php

class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔍 Read all authors
    public function read() {
        $query = "SELECT id, author FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 🔍 Read single author by ID
    public function read_single($id) {
        $query = "SELECT id, author FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return null;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (author) VALUES (:author) RETURNING id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
    
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
    
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET author = :author WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
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
