<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/Database.php';
require_once '../../models/Quote.php';

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

// Handle each HTTP method
switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        $author_id = $_GET['author_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;

        if ($id) {
            $data = $quote->read_single($id);
            echo $data ? json_encode($data) : json_encode(['message' => 'No Quotes Found']);
        } elseif ($author_id || $category_id) {
            $stmt = $quote->read_filtered($author_id, $category_id);
            $quotes_arr = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quotes_arr[] = [
                    'id' => $row['id'],
                    'quote' => $row['quote'],
                    'author' => $row['author'],
                    'category' => $row['category']
                ];
            }

            echo count($quotes_arr) > 0
                ? json_encode($quotes_arr)
                : json_encode(['message' => 'No Quotes Found']);
        } else {
            $stmt = $quote->read_all();
            $quotes_arr = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quotes_arr[] = [
                    'id' => $row['id'],
                    'quote' => $row['quote'],
                    'author' => $row['author'],
                    'category' => $row['category']
                ];
            }

            echo json_encode($quotes_arr);
        }
        break;

    case 'POST':
        $input = file_get_contents("php://input");
        $data = json_decode($input);
        if (json_last_error() === JSON_ERROR_NONE && $data) {
            $GLOBALS['data'] = $data;
            require 'create.php';
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON or No input received']);
        }
        break;

    case 'PUT':
        $input = file_get_contents("php://input");
        $data = json_decode($input);
        if (json_last_error() === JSON_ERROR_NONE && $data) {
            $GLOBALS['data'] = $data;
            require 'update.php';
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON or No input received']);
        }
        break;

    case 'DELETE':
        $input = file_get_contents("php://input");
        $data = json_decode($input);
        if (json_last_error() === JSON_ERROR_NONE && $data) {
            $GLOBALS['data'] = $data;
            require 'delete.php';
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON or No input received']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
