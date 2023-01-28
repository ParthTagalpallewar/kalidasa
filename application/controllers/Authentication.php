<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller
{

    public function index()
    {

        //check if admin is already login
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }else
        {
            $this->load->view('sign_in');
        }
    }

    public function login()
    {
        
        $this->form_validation->set_rules('username', 'User Name', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // result will be 1 is user exist else 0
            $result = $this->Auth_model->authAdmin($username, $password);
            
            if ($result == 1) {
                $this->session->set_flashdata('success', 'Login Successful');
                $this->session->set_userdata('user_id', '1');
                redirect('dashboard');
                    
                
            } else {
                $this->session->set_flashdata('error', "Wrong User or Password");
                redirect('authentication');
            }
        }
    }

    public function logout(){
        $this->session->unset_userdata('user_id');
        $this->session->set_flashdata('success', 'Logout Successfully');

        redirect('authentication');
    }

}