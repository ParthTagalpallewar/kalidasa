<?php
class SubToSubCategory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSubToSubCategory($id){
        
        return $this->db->where("id", $id)->get('sub_to_sub_category')->result()[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("sub_to_sub_category");
    }

    public function getSubToSubCategories(){
        $query = "SELECT sub_to_sub_category.*, category.name As cat_name, sub_category.name As sub_cat_name
            FROM sub_to_sub_category
            JOIN category ON sub_to_sub_category.cat_id=category.id
            JOIN sub_category ON sub_to_sub_category.sub_cat_id=sub_category.id";
       
        $query1 = $this->db->query($query);
        return $query1->result_array(); 
    }

    public function update($id, $name, $description){

        $this->db->set('id', $id);

        if($name != null){
            $this->db->set('name', $name);
        }
       
        if($description != null){
            $this->db->set('description', $description);
        }
       
        
        $this->db->where('id', $id);
        $this->db->update('sub_to_sub_category');
        
    }
    
    public function fetch_categories(){
        return $this->db->get('category')->result();
    }

    public function fetch_sub_categories($category_id){
        $this->db->where('cat_id', $category_id);
        $query = $this->db->get('sub_category');
        $output = '<option value="">Select Sub-Category</option>';
        foreach ($query->result() as $row) {
            $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return $output;

    }


    public function add($name, $description, $subcategory){

        $subcategorymodel = $this->SubCategory_model->getSubcategory($subcategory);

        $data = array(
            'name' => $name,
            'description' => $description,
          
            'cat_id' => $subcategorymodel->cat_id, 
            'sub_cat_id' => $subcategory
        );

        $this->db->insert("sub_to_sub_category", $data);
    }

    
}