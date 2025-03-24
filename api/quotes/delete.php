
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

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