<?php
include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();

$quote = new Quote($db);

// Collect GET parameters
$id = $_GET['id'] ?? null;
$author_id = $_GET['author_id'] ?? null;
$category_id = $_GET['category_id'] ?? null;
$random = isset($_GET['random']) && $_GET['random'] === 'true';

// Logic: Which method to call based on parameters
if ($id) {
    // Get single quote by ID
    $result = $quote->read_single($id);
    if ($result) {
        echo json_encode($result, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    // Get filtered or all quotes
    $result = $quote->read_filtered($author_id, $category_id, $random);

    if (count($result)) {
        echo json_encode(['records' => $result], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
