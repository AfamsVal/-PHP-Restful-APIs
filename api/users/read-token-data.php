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
include_once '../../models/Users.php';

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
$user = new Users($db);

$all_headers = getallheaders();

$token = isset($all_headers['Authorization']) ? $all_headers['Authorization']  : '';

if (!empty($token)) {
    try {
        $secret_key = '12345';
        $decoded_data = JWT::decode($token, new Key($secret_key, 'HS512'));

        $user_id = $decoded_data->data->id;

        http_response_code(200);
        //No post
        echo json_encode(
            array(
                'status' => true,
                'message' => 'Token Found', #
                'data' => $decoded_data,
                'user_id' => $user_id
            )
        );
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
} else {
    http_response_code(404);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Token not found!'
        )
    );
}
