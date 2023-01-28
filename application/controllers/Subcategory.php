<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcategory extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $subcategories = $this->SubCategory_model->getSubCategories();

        
        
         $this->load->view('subcategories', array("categories" => $subcategories));

    }

    public function add(){
        $categories = $this->Category_model->getCategories();
        $this->load->view("add_subcategory", array("categories" => $categories));
    }

  
    public function add_subcategory(){
        $fileExt = pathinfo($_FILES["subcategory"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/sub_category", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );
        
        $this->load->library('upload', $config_img);
        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
        if ($this->form_validation->run() == false) {
            if(trim($this->input->post('name')) == ""){
                $this->session->set_flashdata('error', "Name can not be empty");
            }
           redirect("subcategory/add");
        } else {

            if (!$this->upload->do_upload('subcategory')) {
                $error = $this->upload->display_errors();

                $this->session->set_flashdata('error', $error);

                redirect("subcategory/add");

            } else {
                $name = $this->input->post('name');
                $description = $this->input->post("description");
                $category = $this->input->post("category");
                $filename = $img_file_name;

                $this->SubCategory_model->add($name, $description, $filename, $category);

                $this->session->set_flashdata('success', "Successfully Added data");
                redirect("subcategory");

            }
        }

    }

    public function delete($id)
    {
        $this->SubCategory_model->delete($id);
        $this->session->set_flashdata('error', "Category Deleted Successfully");
        redirect('subcategory');
    }

    
    public function view_subcategory($id)
    {

        $category = $this->SubCategory_model->getSubCategory($id);

        $this->load->view('update_subcategory', array("category" => $category));

    }

    public function update($id)
    {
        $fileExt = pathinfo($_FILES["subcategory"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/sub_category", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );


        $this->load->library('upload', $config_img);

        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() == false) {
            if (trim($this->input->post('name')) == "") {
                $this->session->set_flashdata('error', "Name can not be empty");
            }
            redirect("subcategory/subcategory/$id");
        } else {

            if (!$this->upload->do_upload('subcategory')) {
                $error = $this->upload->display_errors();

                $this->session->set_flashdata('error', $error);

                redirect("subcategory/subcategory/$id");


            } else {
                $name = $this->input->post('name');
                $description = $this->input->post("description");
                $filename = $img_file_name;

                $this->SubCategory_model->update($id ,$name, $description, $filename);

                $this->session->set_flashdata('success', "Successfully Updated edata");
                redirect("subcategory");

            }
        }
    }

    
}