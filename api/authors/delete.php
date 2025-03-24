<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Author.php');

// DB connection
$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Parse DELETE input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->id)) {
    $author->id = $data->id;

    try {
        if ($author->delete()) {
            echo json_encode(['id' => $author->id]);
        } else {
            echo json_encode(['message' => 'No Authors Found']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() === '23503') { // FK constraint violation
            echo json_encode(['message' => 'Cannot delete author: author_id is in use']);
        } else {
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
        }
    }

} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
