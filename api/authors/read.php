<?php
include_once '../../config/Database.php';
include_once '../../models/Author.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

$result = $author->read();
$num = $result->rowCount();

if ($num > 0) {
    $authors_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $authors_arr[] = [
            'id' => $id,
            'author' => $author
        ];
    }

    echo json_encode($authors_arr);
} else {
    echo json_encode([]);
}
