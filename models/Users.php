<?php
class Users
{
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;


    private $conn;
    private $users_table;


    public function __construct($db)
    {
        $this->conn = $db;
        $this->users_table = 'users';
        $this->projects_table = 'projects';
    }


    public function create_user()
    {
        $sql = 'SELECT * FROM ' . $this->users_table . ' WHERE email = ?';

        $query = $this->conn->prepare($sql);
        $query->bind_param('s', $this->email);
        $query->execute();
        $query->store_result();

        if ($query->num_rows) {
            return 0;
        }

        $user_query = 'INSERT INTO ' . $this->users_table . ' SET name=?, email=?, password=?';
        $user_obj = $this->conn->prepare($user_query);
        $user_obj->bind_param('sss', $this->name, $this->email, $this->password);
        if ($user_obj->execute()) {
            return 1;
        }
        return 2;
    }

    public function check_is_user_login()
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $query = $this->conn->prepare($sql);
        $query->bind_param('s', $this->email);
        if ($query->execute()) {
            $data = $query->get_result();
            return $data->fetch_assoc();
        }

        return array();
    }
}
