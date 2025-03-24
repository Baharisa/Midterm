<?php

class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read_all() {
        $query = "SELECT id, author FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single($id) {
        $query = "SELECT id, author FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO {$this->table} (author) VALUES (:author) RETURNING id, author";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Returns id + author
        }

        return false;
    }

    public function update() {
        $query = "UPDATE {$this->table} SET author = :author WHERE id = :id RETURNING id, author";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Returns updated row
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
    
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == '23503') { // Foreign key violation
                echo json_encode([
                    'message' => 'Cannot delete author: author_id is in use'
                ]);
                exit();
            } else {
                echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
                exit();
            }
        }
    }
    
}
