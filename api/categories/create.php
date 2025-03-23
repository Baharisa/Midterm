<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

include_once('../../config/Database.php');
include_once('../../models/Category.php');

// DB & Model
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

// Read POST input
$data = json_decode(file_get_contents("php://input"));

// Validate
if (!empty($data->category)) {
    $category->category = $data->category;

    if ($category->create()) {
        echo json_encode([
            'id' => $category->id,
            'category' => $category->category
        ]);
    } else {
        echo json_encode(['message' => 'Category Not Created']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
