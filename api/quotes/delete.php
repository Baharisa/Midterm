<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../models/Quote.php');

$quote = new Quote($db);
$data = $GLOBALS['data'];

if (!empty($data->id)) {
    $quote->id = $data->id;

    if ($quote->delete()) {
        http_response_code(200); // ✅ Required
        echo json_encode(['id' => $quote->id]); // ✅ Must return id on success
    } else {
        http_response_code(404); // ✅ Not found
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(400); // ✅ Missing ID
    echo json_encode(['message' => 'Missing Required Parameters']);
}
