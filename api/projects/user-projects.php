<?php

require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

include_once '../../config/Database.php';
include_once '../../models/Projects.php';


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

//Instantiate project object
$project = new Projects($db);

$all_headers = getallheaders();

$token = isset($all_headers['Authorization']) ? $all_headers['Authorization']  : '';

if (!empty($token)) {
    try {
        $secret_key = '12345';
        $decoded_data = JWT::decode($token, new Key($secret_key, 'HS512'));

        $user_id = $decoded_data->data->id;

        $project->user_id = $user_id;

        // query
        $result = $project->get_user_projects();

        //Check if any post
        if ($result->num_rows > 0) {
            $projects_arr = array();
            $projects_arr['status'] = true;
            $projects_arr['data'] = array();
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $project_item = array(
                    'id' => $id,
                    'name' => $name,
                    'description' => html_entity_decode($description),
                    'user_id' => $user_id,
                    'created_at' => $created_at,
                    'status' => $status,
                );
                //Push to data
                array_push($projects_arr['data'], $project_item);
            }
            http_response_code(200);
            //Turn to JSON and output
            echo json_encode($projects_arr);
        } else {
            http_response_code(404);
            //No Project    
            echo json_encode(
                array(
                    'status' => false,
                    'message' => 'No Project found!'
                )
            );
        }
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
