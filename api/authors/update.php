<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Author.php');

// DB connection
$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Parse PUT input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->id) && !empty($data->author)) {
    $author->id = $data->id;
    $author->author = $data->author;

    $result = $author->update();

    if ($result) {
        echo json_encode($result); // returns { "id": X, "author": "Updated Name" }
    } else {
        echo json_encode(['message' => 'No Authors Found']);
    }

} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
