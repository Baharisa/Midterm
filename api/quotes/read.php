<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Instantiate Quote model
$quote = new Quote($db);

// Get all quotes
$result = $quote->read_all();
$num = $result->rowCount();

if ($num > 0) {
    $quotes_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quotes_arr[] = [
            'id' => $id,
            'quote' => $quote,
            'author' => $author_name,
            'category' => $category_name
        ];
    }

    echo json_encode($quotes_arr); // <- return just array, not { "records": [...] }
} else {
    echo json_encode([]);
}
