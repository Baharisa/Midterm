<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once('../../config/Database.php');
require_once('../../models/Category.php');

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

// Get ID from query string
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id && is_numeric($id)) {
    $result = $category->read_single($id);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode([
            'id' => $row['id'],
            'category' => $row['category']
        ]);
    } else {
        echo json_encode(['message' => 'category_id Not Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
