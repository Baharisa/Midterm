<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Author.php');

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Read POST input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->author)) {
    $author->author = $data->author;

    if ($author->create()) {
        echo json_encode([
            'id' => $author->id,
            'author' => $author->author
        ]);
    } else {
        echo json_encode(['message' => 'Author Not Created']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
