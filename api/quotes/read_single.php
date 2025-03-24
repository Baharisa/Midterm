// read_single.php
<?php
include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

$id = $_GET['id'] ?? null;
if ($id && is_numeric($id)) {
    $data = $quote->read_single($id);
    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(["message" => "No Quotes Found"]);
    }
} else {
    echo json_encode(["message" => "Missing Required Parameters"]);
}
