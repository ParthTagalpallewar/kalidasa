<?php
class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCategory($id){
        return $this->db->where("id", $id)->get('category')->result()[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("category");
    }

    public function getCategories(){
        return $this->db->get('category')->result_array();
    }

    public function getRecordsCount(){
        $data = array( 
            "category" => $this->getCategoriesCount(),
            "sub_category" => $this->getSubCategoriesCount(),
            "sub_to_sub_category" => $this->getSubToSubCategoriesCount(),
            "files" => $this->getFilesCount()
        );

        return $data;
    }

    public function update($id, $name, $image){

        $this->db->set('id', $id);

        if($name != null){
            $this->db->set('name', $name);
        }
        if($image != null){
            $this->db->set('image', $image);
        }
        
        $this->db->where('id', $id);
        $this->db->update('category');
        
    }

    public function add($name, $image){
        $data = array(
            'name' => $name,
            'image' => $image
        );

        $this->db->insert("category", $data);
    }

    public function getCategoriesCount(){
        return $this->db->get('category')->num_rows();
    }
    
    public function getSubCategoriesCount(){
        return $this->db->get('sub_category')->num_rows();
    }
    
    public function getSubToSubCategoriesCount(){
        return $this->db->get('sub_to_sub_category')->num_rows();
    }
    public function getFilesCount(){
        return $this->db->get('file')->num_rows();
    }
}