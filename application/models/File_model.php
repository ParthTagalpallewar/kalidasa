<?php
class File_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFile($id){
        return $this->db->where("id", $id)->get('file')->result()[0];
    }

    public function get_file_details($id){
        
        $file = $this->db->where('id', $id)->get('file')->result()[0];
    
        $this->db->select('file.*, category.name As cat_name');
        $this->db->from('file');
        $this->db->join('category', "file.cat_id=category.id");
        
        if($file->sub_cat_id != null){
            $this->db->select('sub_category.name As sub_cat_name');
            $this->db->join('sub_category', "file.sub_cat_id=sub_category.id");
        }
        
        if($file->sub_to_sub_cat_id != null){
            $this->db->select('sub_to_sub_category.name As subtosub_cat_name');
            $this->db->join('sub_to_sub_category', "file.sub_to_sub_cat_id=sub_to_sub_category.id");
        }
        
        if($file->author_id != null){
            $this->db->select('author.name As author_name');
            $this->db->join('author', "file.author_id=author.id and file.id=$id");
        }
        
        $result = $this->db->get()->result();
        
        return $result[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("file");
    }

    public function getFiles(){
        return $this->db->get('file')->result_array();
    }

    public function update($id, $name, $description, $file_url){

        $this->db->set('id', $id);

        if($name != null){
            $this->db->set('name', $name);
        }
       
        if($file_url != null){
            $this->db->set('file_url', $file_url);
        }
        if($description != null){
            $this->db->set('description', $description);
        }
        
        $this->db->where('id', $id);
        $this->db->update('file');
        
    }

    public function add($name, $description, $cat_id, $sub_cat_id, $subtosub_cat_id, $author_id, $file_url){
        $data = array(
            'name' => $name,
            'description' => $description, 
            'author_id' => $author_id,
            'cat_id' => $cat_id,
            'sub_cat_id' => $sub_cat_id, 
            'file_url' => $file_url  
                    
        );
        
        if($subtosub_cat_id == 0 || $subtosub_cat_id == null){
            $data['sub_to_sub_cat_id'] = null;
        }

        $this->db->insert("file", $data);
    }
    
    public function fetch_categories(){
        return $this->db->get('category')->result();
    }
    
    public function fetch_authors(){
        return $this->db->get('author')->result();
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
    
    public function fetch_sub_to_sub_categories($sub_category_id){
        $this->db->where('sub_cat_id', $sub_category_id);
        $query = $this->db->get('sub_to_sub_category');
        $output = '<option value="">Select Sub-To-Sub-Category</option>';
        foreach ($query->result() as $row) {
            $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return $output;

    }
    
    public function insert($data = array()){ 
        $insert = $this->db->insert_batch('file',$data); 
        return $insert?true:false; 
    } 
}