<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Heasders: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

$data = json_decode(file_get_contents('php://input'));

$post->id = htmlspecialchars(strip_tags($data->id));
$post->title = htmlspecialchars(strip_tags($data->title));
$post->body = htmlspecialchars(strip_tags($data->body));


//Check if post is updated
if ($post->update_post()) {
    http_response_code(200);
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'message' => 'Post updated successful!'
    ));
} else {
    http_response_code(500);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Update failed!' . $db->error
        )
    );
}
