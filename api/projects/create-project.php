<?php
require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Heasders: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Projects.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
$project = new Projects($db);

$data = json_decode(file_get_contents('php://input'));

$headers = getallheaders();
$token = isset($all_headers['Authorization']) ? $all_headers['Authorization']  : '';

if (empty(trim($token))) {
    http_response_code(500);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Invalid User'
        )
    );
    exit();
}

if (empty(trim($data->name)) || empty(trim($data->description)) || empty(trim($data->status))) {
    http_response_code(200);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Please fill all feild'
        )
    );
    exit();
}

try {
    $secret_key = '12345';
    $decoded_data = JWT::decode($token, new Key($secret_key, 'HS512'));

    $project->user_id = $decoded_data->data->id;
    $project->name = htmlspecialchars(strip_tags($data->name));
    $project->description = htmlspecialchars(strip_tags($data->description));
    $project->status = htmlspecialchars(strip_tags($data->status));
} catch (Exception $ex) {
    http_response_code(500);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => $ex->getMessage()
        )
    );
    exit();
}


//Check if project is created

if ($project->create_projects()) {
    http_response_code(200);
    //No post
    echo json_encode(
        array(
            'status' => true,
            'message' => 'Project created successfully'
        )
    );
} else {
    http_response_code(500);
    //Turn to JSON and output
    echo json_encode(array(
        'status' => true,
        'message' => 'Unabel to create project' . $db->error
    ));
}
