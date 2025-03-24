<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Author.php');

// DB connection
$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Read input JSON
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->author)) {
    $author->author = $data->author;

    $result = $author->create();

    if ($result) {
        echo json_encode($result); // Returns id and author as JSON
    } else {
        echo json_encode(['message' => 'Author Not Created']);
    }

} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
