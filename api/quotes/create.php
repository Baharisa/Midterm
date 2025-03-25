<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../models/Quote.php');

$quote = new Quote($db);
$data = $GLOBALS['data'];

if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Validate existence of author and category first
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

    $result = $quote->create();

    if ($result) {
        http_response_code(201);
        echo json_encode($result);  // ✅ Must include id, quote, author_id, category_id
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
