<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

// Reuse the $db from index.php
require_once('../../models/Quote.php');

$quote = new Quote($db); // $db is already created in index.php
$data = $GLOBALS['data']; // Parsed from index.php using json_decode

if (!empty($data->id)) {
    $quote->id = $data->id;

    if ($quote->delete()) {
        http_response_code(200);
        echo json_encode(['id' => $quote->id]);  // ✅ Matches Netlify requirement
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
