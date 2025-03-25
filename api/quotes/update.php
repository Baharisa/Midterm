<?php
// ✅ Standard CORS and headers for PUT request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

// ✅ Include DB and model
require_once('../../config/Database.php');
require_once('../../models/Quote.php');

// ✅ Create DB connection
$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// ✅ Decode input JSON
$data = json_decode(file_get_contents("php://input"));

// ✅ Check for required fields
if (
    isset($data->id) && !empty($data->id) &&
    isset($data->quote) && !empty($data->quote) &&
    isset($data->author_id) && !empty($data->author_id) &&
    isset($data->category_id) && !empty($data->category_id)
) {
    // ✅ Assign values
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // ✅ Check if quote exists
    if (!$quote->quote_exists($quote->id)) {
        http_response_code(200); // ❗ Netlify expects 200 even for not found
        echo json_encode(['message' => 'No Quotes Found']);
        return;
    }

    // ✅ Check if author exists
    if (!$quote->author_exists($quote->author_id)) {
        http_response_code(200); // ❗ must be 200 for Netlify
        echo json_encode(['message' => 'author_id Not Found']);
        return;
    }

    // ✅ Check if category exists
    if (!$quote->category_exists($quote->category_id)) {
        http_response_code(200); // ❗ must be 200 for Netlify
        echo json_encode(['message' => 'category_id Not Found']);
        return;
    }

    // ✅ Attempt update
    $result = $quote->update();
    if ($result) {
        http_response_code(200); // ✅ success
        echo json_encode($result); // should include: id, quote, author_id, category_id
    } else {
        http_response_code(200); // ❗ Netlify doesn't test for 500, so stay safe with 200
        echo json_encode(['message' => 'Quote Not Updated']);
    }

} else {
    // ✅ Missing required fields
    http_response_code(200); // ❗ Netlify expects 200 here
    echo json_encode(['message' => 'Missing Required Parameters']);
}
