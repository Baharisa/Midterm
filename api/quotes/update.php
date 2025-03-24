// update.php
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $result = $quote->update();
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
