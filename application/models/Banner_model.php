<?php
class Banner_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBanner($id){
        return $this->db->where("id", $id)->get('banner')->result()[0];
    }

    public function delete($id){
        $this->db->where('id', $id)->delete("banner");
    }

    public function getBanners(){
        return $this->db->get('banner')->result_array();
    }

   

    public function update($id, $image){

        $this->db->set('id', $id);

        if($image != null){
            $this->db->set('image', $image);
        }
        
        $this->db->where('id', $id);
        $this->db->update('banner');
        
    }

    public function add($image){
        $data = array(
            'image' => $image
        );

        $this->db->insert("banner", $data);
    }

  
}