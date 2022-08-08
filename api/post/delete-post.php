<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Heasders: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

$post->id = $data->id;

//Check if post is DELETED
$result = $post->delete_post();
if ($result === true) {
    http_response_code(200);
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'message' => 'Post Deleted Successful!'
    ));
} else if ($result === false) {
    http_response_code(500);
    echo json_encode(
        array(
            'status' => false,
            'message' => "Error deleting record: " . $db->error
        )
    );
} else {
    http_response_code(400);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => "No record found!"
        )
    );
}
