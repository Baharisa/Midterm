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

// ✅ Includes (relative path)
include_once('../../config/Database.php');
include_once('../../models/Author.php');

// ✅ Initialize DB and model
$database = new Database();
$db = $database->getConnection();
$author = new Author($db);

// ✅ Handle GET logic
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $result = $author->read_single($_GET['id']);

        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['message' => 'author_id Not Found']);
        }
    } else {
        $stmt = $author->read();
        $authors = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $authors[] = $row;
        }

        if (count($authors)) {
            echo json_encode(['records' => $authors], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['message' => 'No Authors Found']);
        }
    }
} else {
    echo json_encode(['message' => 'Method Not Allowed']);
}
