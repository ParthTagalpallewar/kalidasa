<?php
class Author_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAuthor($id){
        return $this->db->where("id", $id)->get('author')->result()[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("author");
    }

    public function getAuthors(){
        return $this->db->get('author')->result_array();
    }

    public function update($id, $name){

        $this->db->set('id', $id);

        if($name != null){
            $this->db->set('name', $name);
        }
       
        
        $this->db->where('id', $id);
        $this->db->update('author');
        
    }

    public function add($name){
        $data = array(
            'name' => $name,
        );

        $this->db->insert("author", $data);
    }
}