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
        echo json_encode([
            'id' => $result['id'],
            'quote' => $result['quote'],
            'author_id' => $result['author_id'],
            'category_id' => $result['category_id']
        ]);
    } else {
        echo json_encode(['message' => 'author_id Not Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
