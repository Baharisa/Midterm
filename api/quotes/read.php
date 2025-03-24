<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Instantiate quote object
$quote = new Quote($db);

// Get query params
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $result = $quote->read_single($id);
    echo json_encode($result);
} elseif ($author_id && $category_id) {
    $results = $quote->read_filtered($author_id, $category_id);
    echo json_encode($results);
} elseif ($author_id) {
    $results = $quote->read_filtered($author_id, null);
    echo json_encode($results);
} elseif ($category_id) {
    $results = $quote->read_filtered(null, $category_id);
    echo json_encode($results);
} else {
    $results = $quote->read_all();
    echo json_encode($results);
}
