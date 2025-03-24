// read.php
<?php
require_once '../../config/Database.php';
require_once '../../models/Author.php';

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

$result = $author->read_all();

if ($result->rowCount() > 0) {
    $authors_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $authors_arr[] = [
            'id' => $row['id'],
            'author' => $row['author']
        ];
    }

    echo json_encode($authors_arr);
} else {
    echo json_encode(['message' => 'author_id Not Found']);
}