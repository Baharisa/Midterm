<?php

class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all authors
    public function read_all() {
        $query = "SELECT id, author FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single author by ID
    public function read_single($id) {
        $query = "SELECT id, author FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    // Create new author
    public function create() {
        $query = "INSERT INTO {$this->table} (author) VALUES (:author) RETURNING id, author";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // Update existing author
    public function update() {
        $query = "UPDATE {$this->table} SET author = :author WHERE id = :id RETURNING id, author";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // Delete author with FK check
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
            // Foreign key violation: author is used in quotes
            if ($e->getCode() === '23503') {
                http_response_code(409); // Conflict
                echo json_encode(['message' => 'Cannot delete author: author_id is in use']);
                exit;
            }

            // General DB error
            http_response_code(500);
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
}
