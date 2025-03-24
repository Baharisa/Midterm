<?php
require_once '../../config/Database.php';
require_once '../../models/Category.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$result = $category->read_all();

if ($result && $result->rowCount() > 0) {
    $categories_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $categories_arr[] = [
            'id' => $row['id'],
            'category' => $row['category']
        ];
    }

    echo json_encode($categories_arr);
} else {
    echo json_encode(['message' => 'category_id Not Found']);
}
