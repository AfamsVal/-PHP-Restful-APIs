<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(503);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Access Denied!'
        )
    );
    exit();
}

//Instantiate DB $ Connect
$database = new Database();
$db = $database->connection();

//Instantiate blog post object
$post = new Post($db);

$post->id = isset($_GET['id']) ? intval($_GET['id']) : die();

//Blog post query
$result = $post->read_single();

//Check if any post
if ($result[0] > 0) {
    $row = mysqli_fetch_assoc($result[1]);
    extract($row);
    $post_item = array(
        'id' => $id,
        'title' => $title,
        'body' => html_entity_decode($body),
        'author' => $author,
        'category_id' => $category_id,
        // 'category_name' => $category_name,
    );
    http_response_code(200);
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'data' => $post_item
    ));
} else {
    http_response_code(404);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'No post found!' . $db->error
        )
    );
}
