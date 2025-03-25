<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../models/Quote.php');

$quote = new Quote($db); // $db comes from index.php
$data = $GLOBALS['data']; // Parsed JSON input from index.php

// Validate required fields
if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $result = $quote->create();

    if ($result) {
        http_response_code(201); // Created
        echo json_encode($result); // Return: id, quote, author_id, category_id
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'author_id or category_id Not Found']);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
