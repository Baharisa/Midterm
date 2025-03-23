 
<?php
include_once '../config/Database.php';
include_once '../models/Quote.php';
include_once '../header.php';

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// Get ID from URL
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

$quote->readOne();

if ($quote->quote != null) {
    $quote_arr = array(
        "id" => $quote->id,
        "quote" => $quote->quote,
        "author_id" => $quote->author_id,
        "category_id" => $quote->category_id
    );

    http_response_code(200);
    echo json_encode($quote_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No quote found."));
}
?>
