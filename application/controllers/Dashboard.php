<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()
    {  
        //check if admin is login or not
        if (!$this->session->userdata('user_id')) {
            redirect('authentication');
        }else{
            $category_counts = $this->Category_model->getRecordsCount();
            
            $this->load->view('dashboard', array("counts" => $category_counts));
        }

        
       
    }

    
    public function logout(){
        $this->session->unset_userdata('user_id');

        redirect('authentication');
    }
}