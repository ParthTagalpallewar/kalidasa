<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Author extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $authors = $this->Author_model->getAuthors();

        $this->load->view('authors', array("authors" => $authors));

    }

    public function view_author($id)
    {

        $author = $this->Author_model->getAuthor($id);

        $this->load->view('update_author', array("author" => $author));

    }

    public function delete($id)
    {
        $this->Author_model->delete($id);
        $this->session->set_flashdata('error', "Author Deleted Successfully");
        redirect('author');
    }

    public function update($id)
    {
        $name = $this->input->post('name');

        if ($name == null) {

            $this->session->set_flashdata('error', "Name Should not be Empty");

            redirect("author/author/id");

        } else {

            $this->Author_model->update($id, $name);

            $this->session->set_flashdata('success', "Successfully Updated Data");
            redirect("author");

        }
    }

    public function add()
    {
        $this->load->view("add_author");
    }

    public function add_author()
    {

        $name = $this->input->post('name');

        if ($name == null) {

            $this->session->set_flashdata('error', "Name Should Not be Empty");
            redirect("author/add");

        } else {
            $this->Author_model->add($name);

            $this->session->set_flashdata('success', "Successfully Added data");
            redirect("author");

        }

    }
}