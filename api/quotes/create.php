<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// ✅ Use proper JSON decoding
$data = json_decode(file_get_contents("php://input"));

// ✅ Validate required fields
if (
    isset($data->quote) && !empty($data->quote) &&
    isset($data->author_id) && !empty($data->author_id) &&
    isset($data->category_id) && !empty($data->category_id)
) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // ✅ Check if author exists
    if (!$quote->author_exists($quote->author_id)) {
        http_response_code(200);
        echo json_encode(['message' => 'author_id Not Found']);
        return;
    }

    // ✅ Check if category exists
    if (!$quote->category_exists($quote->category_id)) {
        http_response_code(404);
        echo json_encode(['message' => 'category_id Not Found']);
        return;
    }

    // ✅ Create quote
    $result = $quote->create();
    if ($result) {
        http_response_code(201); // Created
        echo json_encode($result); // Must include id, quote, author_id, category_id
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
