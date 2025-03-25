<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// ✅ Read JSON input from body
$data = json_decode(file_get_contents("php://input"));

// ✅ Validate ID
if (!empty($data->id)) {
    $quote->id = $data->id;

    // ✅ Try to delete
    if ($quote->delete()) {
        http_response_code(200); // Success
        echo json_encode(['id' => $quote->id]);
    } else {
        http_response_code(404); // Not found
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    http_response_code(400); // Missing parameter
    echo json_encode(['message' => 'Missing Required Parameters']);
}
