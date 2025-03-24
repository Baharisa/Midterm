
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../../config/Database.php';
require_once '../../models/Author.php';

$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id && is_numeric($id)) {
    $result = $author->read_single($id);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(['message' => 'author_id Not Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
