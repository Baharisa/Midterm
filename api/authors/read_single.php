 
<?php
include_once '../config/Database.php';
include_once '../models/Author.php';
include_once '../header.php';

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);

// Get ID from URL
$author->id = isset($_GET['id']) ? $_GET['id'] : die();

$author->readOne();

if ($author->author != null) {
    $author_arr = array(
        "id" => $author->id,
        "author" => $author->author
    );

    http_response_code(200);
    echo json_encode($author_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Author not found."));
}
?>
