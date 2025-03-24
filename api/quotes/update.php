<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

// Use the already-included DB connection from index.php
require_once('../../models/Quote.php');

$quote = new Quote($db); // $db is passed from index.php
$data = $GLOBALS['data']; // Parsed once in index.php using: $GLOBALS['data'] = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $result = $quote->update();

    if ($result) {
        http_response_code(200);
        echo json_encode($result); // ✅ return updated quote (id, quote, author_id, category_id)
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
