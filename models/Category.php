<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$stmt = $category->read();

$categories_arr = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $categories_arr[] = [
        'id' => $id,
        'category' => $category
    ];
}

echo json_encode($categories_arr);
