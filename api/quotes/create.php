 
<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// Read input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (
    !empty($data->quote) &&
    !empty($data->author_id) &&
    !empty($data->category_id)
) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if ($quote->create()) {
        echo json_encode([
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
