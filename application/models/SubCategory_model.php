<?php
class SubCategory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSubCategory($id){
        
        return $this->db->where("id", $id)->get('sub_category')->result()[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("sub_category");
    }

    public function getSubCategories(){
        $query = "SELECT sub_category.*, category.name As cat_name
            FROM sub_category
            JOIN category ON sub_category.cat_id=category.id";
       
        $query1 = $this->db->query($query);
        return $query1->result_array(); 
    }

    public function update($id, $name, $description, $image){

        $this->db->set('id', $id);

        if($name != null){
            $this->db->set('name', $name);
        }
        if($image != null){
            $this->db->set('image', $image);
        }
        if($description != null){
            $this->db->set('description', $description);
        }
   
        $this->db->where('id', $id);
        $this->db->update('sub_category');
        
    }

    public function add($name, $description, $image, $category){
        $data = array(
            'name' => $name,
            'description' => $description,
            'image' => $image, 
            'cat_id' => $category
        );

        $this->db->insert("sub_category", $data);
    }

    
}