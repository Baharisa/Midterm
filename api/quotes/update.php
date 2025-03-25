<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../models/Quote.php');

$quote = new Quote($db);
$data = $GLOBALS['data'];

// Validate required fields
if (
    !empty($data->id) &&
    !empty($data->quote) &&
    !empty($data->author_id) &&
    !empty($data->category_id)
) {
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Check if author and category exist first
    if (!$quote->author_exists($quote->author_id)) {
        http_response_code(404);
        echo json_encode(['message' => 'author_id Not Found']);
        exit();
    }

    if (!$quote->category_exists($quote->category_id)) {
        http_response_code(404);
        echo json_encode(['message' => 'category_id Not Found']);
        exit();
    }

    $result = $quote->update();

    if ($result) {
        http_response_code(200);
        echo json_encode($result); // ✅ return with id, quote, author_id, category_id
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
