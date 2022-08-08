<?php
// error_reporting(1);
// ini_set('display_errors', 1);
class Database
{
    private $host = 'localhost';
    private $db_name = 'myblog';
    private $user = 'root';
    private $passwd = '';
    private $connect;

    // - DB Connect
    // public function connect()
    // {
    //     $this->conn = new mysqli($this->host, $this->user, $this->passwd, $this->db_name);
    //     $this->conn->set_charset("utf8mb4");
    //     if ($this->conn->connect_errno) {
    //         die('connection failed');
    //     }
    // }

    public function __construct()
    {
        $this->connect = new mysqli($this->host, $this->user, $this->passwd, $this->db_name);
        $this->connect->set_charset("utf8mb4");
        if ($this->connect->connect_errno) {
            die('connection failed' . $this->connect->connect_error);
        } else {
            // echo 'connection success';
        }
    }

    public function connection()
    {
        return $this->connect;
    }
}
