<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../../config/Database.php';
require_once '../../models/Author.php';

// DB Connection
$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// Handle optional id param
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $author->read_single($id);

    if ($result) {
        echo json_encode([
            'id' => $result['id'],
            'author' => $result['author']
        ]);
    } else {
        echo json_encode(['message' => 'author_id Not Found']);
    }

} else {
    // No id param — return all authors
    $stmt = $author->read_all();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $authors_arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $authors_arr[] = [
                'id' => $row['id'],
                'author' => $row['author']
            ];
        }

        echo json_encode($authors_arr);
    } else {
        echo json_encode([]);
    }
}
