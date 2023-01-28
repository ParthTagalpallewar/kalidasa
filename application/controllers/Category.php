<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $categories = $this->Category_model->getCategories();

        $this->load->view('categories', array("categories" => $categories));

    }

    public function view_category($id)
    {

        $category = $this->Category_model->getCategory($id);

        $this->load->view('update_category', array("category" => $category));

    }

    public function delete($id)
    {
        $this->Category_model->delete($id);
        $this->session->set_flashdata('error', "Category Deleted Successfully");
        redirect('category');
    }

   

    public function add(){
        $this->load->view("add_category");
    }

   
    public function add_category(){
        $fileExt = pathinfo($_FILES["category"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/category", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );
        
        $this->load->library('upload', $config_img);

        if (!$this->upload->do_upload('category')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $error);

            redirect("category/add");

        } else {
           

            $name = $this->input->post('name');
            $filename = $img_file_name;

            $this->Category_model->add($name, $filename);

            $this->session->set_flashdata('success', "Successfully Added data");
            redirect("category");


        }

    }
   
    public function update($id){
        $fileExt = pathinfo($_FILES["category"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/category", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );
        
        $this->load->library('upload', $config_img);

        if (!$this->upload->do_upload('category')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $error);

            redirect("category/view_category/$id");

        } else {
           

            $name = $this->input->post('name');
            $filename = $img_file_name;

            $this->Category_model->update($id, $name, $filename);

            $this->session->set_flashdata('success', "Successfully Updated data");
            redirect("category");


        }

    }
    
}