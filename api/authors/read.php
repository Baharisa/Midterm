 
<?php
include_once '../config/Database.php';
include_once '../models/Author.php';
include_once '../header.php';

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);

$stmt = $author->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $authors_arr = array();
    $authors_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array(
            "id" => $id,
            "author" => $author
        );

        array_push($authors_arr["records"], $author_item);
    }

    http_response_code(200);
    echo json_encode($authors_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No authors found."));
}
?>
