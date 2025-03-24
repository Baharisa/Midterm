<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->getConnection();

$quote = new Quote($db);
$result = $quote->read_all();

$num = $result->rowCount();

if ($num > 0) {
    $quotes_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quotes_arr[] = [
            'id' => $id,
            'quote' => $quote,
            'author' => $author,        // match alias in SQL
            'category' => $category     // match alias in SQL
        ];
    }

    echo json_encode($quotes_arr);
} else {
    echo json_encode([]);
}
