<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

//Instantiate DB $ Connect
$database = new Database();
$db = $database->connection();

//Instantiate blog post object
$post = new Post($db);

//Blog post query
$result = $post->read();

//Check if any post
if ($result[0] > 0) {
    $posts_arr = array();
    $posts_arr['data'] = array();
    while ($row = mysqli_fetch_assoc($result[1])) {
        extract($row);
        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            // 'category_name' => $category_name,
        );
        //Push to data
        array_push($posts_arr['data'], $post_item);
    }
    //Turn to JSON and output
    echo json_encode(($posts_arr));
} else {
    //No post
    echo json_encode(
        array('message' => 'No post found!')
    );
}
