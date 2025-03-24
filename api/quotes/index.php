<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        $author_id = $_GET['author_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;

        if ($id) {
            $data = $quote->read_single($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "No Quotes Found"]);
            }
        } elseif ($author_id || $category_id) {
            $data = $quote->read_filtered($author_id, $category_id);
            echo json_encode($data);
        } else {
            $stmt = $quote->read_all();
            $num = $stmt->rowCount();
            $quotes_arr = [];

            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $quotes_arr[] = [
                        'id' => $id,
                        'quote' => $quote,
                        'author' => $author,
                        'category' => $category
                    ];
                }
                echo json_encode($quotes_arr);
            } else {
                echo json_encode([]);
            }
        }
        break;

    case 'POST':
        require 'create.php';
        break;

    case 'PUT':
        require 'update.php';
        break;

    case 'DELETE':
        require 'delete.php';
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}