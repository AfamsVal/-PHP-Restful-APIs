<?php
require '../../vendor/autoload.php';
require '../../utils/core.php';

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

$data = json_decode(file_get_contents('php://input'));

if (empty(trim($data->email)) || empty(trim($data->password))) {
    http_response_code(200);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'All data needed'
        )
    );
    exit();
}

$user->email = htmlspecialchars(strip_tags($data->email));
$user->password = htmlspecialchars(strip_tags($data->password));


//Check if post created
$user_data = $user->check_is_user_login();

if (!empty($user_data)) {
    $name = $user_data['name'];
    $email = $user_data['email'];
    $password = $user_data['password'];

    if (password_verify($data->password, $password)) {
        $data = array(
            "id" => $user_data['id'],
            "name" => $user_data['name'],
            "email" => $user_data['email']
        );

        $jwt = generateToken($data);

        http_response_code(200);
        //No post
        echo json_encode(
            array(
                'status' => true,
                'token' => $jwt,
                'message' => 'User logged in successfully'
            )
        );
    } else {
        http_response_code(404);
        //No post
        echo json_encode(
            array(
                'status' => false,
                'message' => 'Invalid Credentials'
            )
        );
    }
} else {
    http_response_code(404);
    //No post
    echo json_encode(
        array(
            'status' => false,
            'message' => 'Invalid Credentials'
        )
    );
}
