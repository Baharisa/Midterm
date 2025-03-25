<?php
require_once('../../models/Quote.php');

$quote = new Quote($db);
$data = $GLOBALS['data'];

if (!empty($data->id)) {
    $quote->id = $data->id;

    if ($quote->delete()) {
        echo json_encode(['id' => $quote->id]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
