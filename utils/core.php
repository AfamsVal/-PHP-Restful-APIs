<?php

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = '12345XXX';

function generateToken($data)
{
    $iat = time();

    $payload_info = array(
        "iss" => 'localhost',
        "iat" => $iat,
        "nbf" => $iat + 10, //10 sec
        "exp" => $iat + 60 * 60 * 24 * 10, //10days
        "aud" => 'myusers',
        "data" => $data
    );

    return JWT::encode($payload_info,  $GLOBALS['secret_key'], 'HS512');
}


function decodeToken($token)
{
    return JWT::decode($token, new Key($GLOBALS['secret_key'], 'HS512'));
}
