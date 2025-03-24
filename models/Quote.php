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

    // ✅ READ ALL (for /quotes/ endpoint)
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

    // ✅ READ SINGLE (for /quotes/?id=10)
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

    // ✅ READ FILTERED (for ?author_id=5&category_id=4)
    public function read_filtered($author_id = null, $category_id = null) {
        $conditions = [];
        $params = [];

        if ($author_id !== null) {
            $conditions[] = 'q.author_id = :author_id';
            $params[':author_id'] = $author_id;
        }

        if ($category_id !== null) {
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
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt;
    }

    // ✅ CREATE
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
            return true;
        }

        return false;
    }

    // ✅ UPDATE
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
        return $stmt->rowCount() > 0;
    }

    // ✅ DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
