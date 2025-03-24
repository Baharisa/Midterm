<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all categories
    public function read_all() {
        $query = "SELECT id, category FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single category
    public function read_single($id) {
        $query = "SELECT id, category FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    // Create a category
    public function create() {
        $query = "INSERT INTO {$this->table} (category) VALUES (:category) RETURNING id, category";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // return new row
        }

        return false;
    }

    // Update a category
    public function update() {
        $query = "UPDATE {$this->table} SET category = :category WHERE id = :id RETURNING id, category";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // return updated row
        }

        return false;
    }

    // Delete a category
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
