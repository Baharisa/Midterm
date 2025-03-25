<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ ALL
    public function read_all() {
        $query = "SELECT q.id, q.quote, a.author AS author, c.category AS category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  ORDER BY q.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // READ SINGLE BY ID
    public function read_single($id) {
        $query = "SELECT q.id, q.quote, a.author AS author, c.category AS category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ FILTERED (BY AUTHOR/CATEGORY)
    public function read_filtered($author_id = null, $category_id = null) {
        $conditions = [];
        $params = [];

        if (!empty($author_id)) {
            $conditions[] = 'q.author_id = :author_id';
            $params[':author_id'] = $author_id;
        }

        if (!empty($category_id)) {
            $conditions[] = 'q.category_id = :category_id';
            $params[':category_id'] = $category_id;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $query = "SELECT q.id, q.quote, a.author AS author, c.category AS category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  $where
                  ORDER BY q.id ASC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        return $stmt;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id)
                  VALUES (:quote, :author_id, :category_id)
                  RETURNING id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return [
                'id' => $this->id,
                'quote' => $this->quote,
                'author_id' => $this->author_id,
                'category_id' => $this->category_id
            ];
        }

        return false;
    }

    // ✅ FIXED: UPDATE with row count check
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET quote = :quote, author_id = :author_id, category_id = :category_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return [
                'id' => $this->id,
                'quote' => $this->quote,
                'author_id' => $this->author_id,
                'category_id' => $this->category_id
            ];
        }

        return false;
    }

    // DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // ✅ Check if author exists
    public function author_exists($author_id) {
        $query = "SELECT id FROM authors WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $author_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // ✅ Check if category exists
    public function category_exists($category_id) {
        $query = "SELECT id FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
