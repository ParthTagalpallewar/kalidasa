<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subtosubcategory extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $subtosubcategories = $this->SubToSubCategory_model->getSubToSubCategories();

        $this->load->view('subtosubcategories', array("categories" => $subtosubcategories));
    }
 
    public function delete($id)
    {
        $this->SubToSubCategory_model->delete($id);
        $this->session->set_flashdata('error', "Sub-To-Sub-Category Deleted Successfully");
        redirect('subtosubCategory');
    }

    public function view_subtosubcategory($id)
    {

        $subtosubcategory = $this->SubToSubCategory_model->getSubToSubCategory($id);

        $this->load->view('update_subtosubcategory', array("subtosubcategory" => $subtosubcategory));

    }

    public function update($id)
    {

        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() == false) {
            if (trim($this->input->post('name')) == "") {
                $this->session->set_flashdata('error', "Name can not be empty");
            }
            redirect("subtosubcategory/subtosubcategory/$id");
        } else {

            
            $name = $this->input->post('name');
            $description = $this->input->post("description");
            $category = $this->input->post("category");
            $filename = $img_file_name;

            $this->SubToSubCategory_model->update($id, $name, $description, $category);

            $this->session->set_flashdata('success', "Successfully Added data");
            redirect("subtosubcategory");

            
        }
    }
    
    public function add()
    {
        $data['categories'] = $this->SubToSubCategory_model->fetch_categories();
        $this->load->view("add_subtosubcategory", $data);
       
    }
    
    public function fetch_sub_categories()
    {
        if($this->input->post('category_id'))
        {
            echo $this->SubToSubCategory_model->fetch_sub_categories($this->input->post('category_id'));
        }

        
    }


    public function add_subtosubcategory()
    {

        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() == false) {
            if (trim($this->input->post('name')) == "") {
                $this->session->set_flashdata('error', "Name can not be empty");
            }
            redirect("subtosubcategory/add");
        } else {

            $name = $this->input->post('name');
            $description = $this->input->post("description");

            $subcategory = $this->input->post("subcategory");
            $filename = $img_file_name;

            $this->SubToSubCategory_model->add($name, $description, $subcategory);

            $this->session->set_flashdata('success', "Successfully Added data");
            redirect("subtosubcategory");
  
        }
    }

}