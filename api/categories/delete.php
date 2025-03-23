<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Category.php');

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $category->id = $data->id;

    if ($category->delete()) {
        echo json_encode(['id' => $category->id]);
    } else {
        echo json_encode(['message' => 'No Categories Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
