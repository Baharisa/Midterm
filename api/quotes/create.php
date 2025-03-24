<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../models/Quote.php');

$quote = new Quote($db);  // $db already set in index.php
$data = $GLOBALS['data']; // from index.php

// Validate required fields
if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $result = $quote->create();

    if ($result) {
        http_response_code(201); // Created
        echo json_encode($result);  // should include: id, quote, author_id, category_id
    } else {
        http_response_code(500); // Server error
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
