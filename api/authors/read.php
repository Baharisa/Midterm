<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);
$stmt = $author->read();

$authors_arr = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $authors_arr[] = [
        'id' => $id,
        'author' => $author
    ];
}

echo json_encode($authors_arr);
