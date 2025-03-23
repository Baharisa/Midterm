<?php
include_once '../config/Database.php';
include_once '../models/Category.php';
include_once '../header.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

// Get ID from URL
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

$category->readOne();

if ($category->category != null) {
    $category_arr = array(
        "id" => $category->id,
        "category" => $category->category
    );

    http_response_code(200);
    echo json_encode($category_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Category not found."));
}
?>
