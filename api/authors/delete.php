<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Author.php');

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $author->id = $data->id;

    if ($author->delete()) {
        echo json_encode(['id' => $author->id]);
    } else {
        echo json_encode(['message' => 'No Authors Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
