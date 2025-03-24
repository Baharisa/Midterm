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
    case 'PUT':
    case 'DELETE':
        $input = file_get_contents("php://input");
        if ($input) {
            $data = json_decode($input);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Make input data globally available
                $GLOBALS['data'] = $data;

                // Route to the proper script
                $file = strtolower($method) . '.php';
                if (file_exists($file)) {
                    require $file;
                } else {
                    echo json_encode(['message' => 'Invalid Method Handler']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid JSON']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'No input received']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
