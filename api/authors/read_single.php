<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Get ID from query param
$author_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$author_id) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
}

// Call the model method
$result = $author->read_single($author_id);

// Check if author exists
if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['message' => 'author_id Not Found']);
}
