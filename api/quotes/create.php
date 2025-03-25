<?php
require_once('../../models/Quote.php');

$quote = new Quote($db);
$data = $GLOBALS['data'];

if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $result = $quote->create();

    if ($result) {
        http_response_code(201);
        echo json_encode($result); // should include: id, quote, author_id, category_id
    } else {
        // Specific error messages expected:
        if (!$quote->author_exists($quote->author_id)) {
            echo json_encode(['message' => 'author_id Not Found']);
        } elseif (!$quote->category_exists($quote->category_id)) {
            echo json_encode(['message' => 'category_id Not Found']);
        } else {
            echo json_encode(['message' => 'Quote Not Created']);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
