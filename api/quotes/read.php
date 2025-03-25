<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// Check for filters
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Validate author_id exists (if provided)
if (!empty($author_id) && !$quote->author_exists($author_id)) {
    http_response_code(404);
    echo json_encode(['message' => 'author_id Not Found']);
    exit;
}

// Validate category_id exists (if provided)
if (!empty($category_id) && !$quote->category_exists($category_id)) {
    http_response_code(404);
    echo json_encode(['message' => 'category_id Not Found']);
    exit;
}

// Read quotes
if ($author_id || $category_id) {
    $result = $quote->read_filtered($author_id, $category_id);
} else {
    $result = $quote->read_all();
}

// Fetch results
$quotes_arr = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $quotes_arr[] = [
        'id' => $row['id'],
        'quote' => $row['quote'],
        'author' => $row['author'],
        'category' => $row['category']
    ];
}

// No quotes found
if (count($quotes_arr) === 0) {
    echo json_encode(['message' => 'No Quotes Found']);
    exit;
}

// Output result
echo json_encode($quotes_arr);
