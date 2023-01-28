<?php
class Auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // if admin auth is correct return 1 else return 0
    public function authAdmin($username, $password)
    {
        $condition = array(
            "username" => $username,
            "password" => $password
        );
        
       $userCount = $this->db->where($condition)->get("users")->num_rows();

       return $userCount;
    }
}