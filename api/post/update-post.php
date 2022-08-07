<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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
$post->title = $data->title;
$post->body = $data->body;


//Check if post is updated
if ($post->update_post()) {
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'message' => 'Post updated successful!'
    ));
} else {
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Update failed!' . $db->error
        )
    );
}
