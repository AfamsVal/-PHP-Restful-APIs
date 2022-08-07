<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Heasders: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

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
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'message' => 'Post Deleted Successful!'
    ));
} else if ($result === false) {
    echo json_encode(
        array(
            'status' => false,
            'message' => "Error deleting record: " . $db->error
        )
    );
} else {
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => "No record found!"
        )
    );
}
