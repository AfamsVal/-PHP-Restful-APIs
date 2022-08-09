<?php
class Projects
{
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;


    private $conn;
    private $projects_table;


    public function __construct($db)
    {
        $this->conn = $db;
        $this->projects_table = 'projects';
    }


    public function create_projects()
    {
        $query = 'INSERT INTO ' . $this->projects_table . ' SET user_id =?, name=?, description=?, status=?';
        $user_obj = $this->conn->prepare($query);
        $user_obj->bind_param('isss', $this->user_id, $this->name, $this->description, $this->status);
        if ($user_obj->execute()) {
            return true;
        }
        return false;
    }

    //Get All Projects
    public function get_all_projects()
    {
        $sql = 'SELECT
         * FROM ' . $this->projects_table . '
         ORDER BY 
         created_at DESC';
        $project_obj = $this->conn->prepare($sql);
        $project_obj->execute();
        return $project_obj->get_result();
    }

    //Get User Projects
    public function get_user_projects()
    {
        $sql = 'SELECT
         * FROM ' . $this->projects_table . ' WHERE user_id = ?
         ORDER BY 
         created_at DESC';
        $query = $this->conn->prepare($sql);
        $query->bind_param('i', $this->user_id);
        $query->execute();
        return $query->get_result();
    }
}
