<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

require_once('../../config/Database.php');
require_once('../../models/Category.php');

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->category)) {
    $category->id = $data->id;
    $category->category = $data->category;

    $result = $category->update();

    if ($result) {
        echo json_encode($result); // updated row
    } else {
        echo json_encode(['message' => 'No Categories Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
