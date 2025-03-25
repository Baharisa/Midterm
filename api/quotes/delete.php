<?php 
// ✅ Allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

// ✅ Load DB and Quote model
require_once('../../config/Database.php');
require_once('../../models/Quote.php');

// ✅ Connect to DB
$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// ✅ Decode JSON input
$data = json_decode(file_get_contents("php://input"));

// ✅ Validate ID
if (!empty($data->id)) {
    $quote->id = $data->id;

    // ✅ Attempt delete
    if ($quote->delete()) {
        http_response_code(200); // ✅ success
        echo json_encode(['id' => $quote->id]);
    } else {
        http_response_code(200); // ❗ Netlify expects 200 even if not found
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(200); // ❗ Netlify expects 200 here too
    echo json_encode(['message' => 'Missing Required Parameters']);
}
