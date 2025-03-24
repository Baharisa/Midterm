
<?php
include_once('../../config/Database.php');
include_once('../../models/Quote.php');

$database = new Database();
$db = $database->getConnection();
$quote = new Quote($db);

$result = $quote->read_all();
$quotes_arr = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $quotes_arr[] = [
        'id' => $row['id'],
        'quote' => $row['quote'],
        'author' => $row['author'],
        'category' => $row['category']
    ];
}

echo json_encode($quotes_arr);