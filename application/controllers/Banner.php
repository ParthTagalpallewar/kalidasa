<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $banners = $this->Banner_model->getBanners();

        $this->load->view('banners', array("banners" => $banners));

    }

    public function view_banner($id)
    {

        $banner = $this->Banner_model->getBanner($id);

        $this->load->view('update_banner', array("banner" => $banner));

    }

    public function delete($id)
    {
        $this->Banner_model->delete($id);
        $this->session->set_flashdata('error', "Banner Deleted Successfully");
        redirect('banner');
    }

    

    public function add(){
        $this->load->view("add_banner");
    }

    public function add_banner(){
        $fileExt = pathinfo($_FILES["banner"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/banner", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );
        
        $this->load->library('upload', $config_img);

        if (!$this->upload->do_upload('banner')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $error);

            redirect("banner/add");

        } else {
           

            $this->Banner_model->add($img_file_name);

            $this->session->set_flashdata('success', "Successfully Added data");
            redirect("banner");

        }

    }
   
    public function update($id){
        $fileExt = pathinfo($_FILES["banner"]["name"], PATHINFO_EXTENSION);
        $img_file_name = time() . "." . $fileExt;

        $config_img = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/banner", //path for upload
            'file_name' => $img_file_name,
            'allowed_types' => "jpg|png|webp|jpeg", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );
        
        $this->load->library('upload', $config_img);

        if (!$this->upload->do_upload('banner')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $error);

            redirect("banner/banner/$id");

        } else {
           

            $this->Banner_model->update($id, $img_file_name);

            $this->session->set_flashdata('success', "Successfully Updated data");
            redirect("banner");

        }

    }

   
       
}