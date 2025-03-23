<?php
// ✅ CORS and Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// ✅ Include database and model (correct path from categories/)
include_once('../../config/Database.php');
include_once('../../models/Category.php');

// ✅ Initialize DB connection
$database = new Database();
$db = $database->getConnection();

// ✅ Initialize Category model
$category = new Category($db);

// ✅ Handle GET Request
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $result = $category->read_single($_GET['id']);

        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['message' => 'category_id Not Found']);
        }
    } else {
        $stmt = $category->read();
        $categories = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row;
        }

        if (count($categories)) {
            echo json_encode(['records' => $categories], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['message' => 'No Categories Found']);
        }
    }
} else {
    echo json_encode(['message' => 'Method Not Allowed']);
}
