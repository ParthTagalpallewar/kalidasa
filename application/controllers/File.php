<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File extends CI_Controller
{
    // show list of available categories
    public function index()
    {
        $files = $this->File_model->getFiles();

        $this->load->view('files', array("files" => $files));

    }

    public function view_file($id)
    {

        $file = $this->File_model->getFile($id);

        $this->load->view('update_file', array("file" => $file));

    }

    public function delete($id)
    {
        $this->File_model->delete($id);
        $this->session->set_flashdata('error', "File Deleted Successfully");
        redirect('file');
    }

    public function view($id)
    {
        $file_details = $this->File_model->get_file_details($id);

        $this->load->view("view_file", array("file" => $file_details));
    }

    public function update($id)
    {
       
        $fileExt1 = pathinfo($_FILES["file_xml"]["name"], PATHINFO_EXTENSION);

       
        $xml_file_name = time().".". $fileExt1;

       
        $config_xml = array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/documents", //path for upload
            'file_name' => $xml_file_name,
            'allowed_types' => "txt|pdf|csv", //restrict extension
            'max_size' => '300000',
            'max_width' => '30000',
            'max_height' => '30000',
        );

        $this->load->library('upload', $config_xml);
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() == false) {
            if(trim($this->input->post('name')) == ""){
                $this->session->set_flashdata('error', "Name can not be empty");
            }
           redirect("file/update/$id");
        }else{
            
            $this->upload->initialize($config_xml);
    
    
            if (!$this->upload->do_upload('file_xml')) {
                $error = $this->upload->display_errors();
    
                $this->session->set_flashdata('error', $error);
    
                redirect("file/update/$id");
    
            } else {
    
                $name = $this->input->post('name');
                $image = $img_file_name;
                $file = $xml_file_name;
                $description = $this->input->post('description');
                    
                $this->File_model->update($id, $name, $description, $xml_file_name);
    
                $this->session->set_flashdata('success', "Successfully Added data");
                redirect("file");
                
            }
                
            
        }

    }
       
    public function add()
    {
        
        $data['categories'] = $this->File_model->fetch_categories();
        $data['authors'] = $this->File_model->fetch_authors();
        $this->load->view("add_file", $data);
    }
    
    public function fetch_sub_categories(){
        if($this->input->post('category_id'))
        {
            echo $this->File_model->fetch_sub_categories($this->input->post('category_id'));
        }

        
    }
    
    public function fetch_sub_to_sub_categories(){
        if($this->input->post('sub_category_id'))
        {
            echo $this->File_model->fetch_sub_to_sub_categories($this->input->post('sub_category_id'));
        }

        
    }


    public function add_file()
    {

        $name = $this->input->post('name');
        $author = $this->input->post('author');
        $description = $this->input->post('description');
        $cat_id = $this->input->post('category');
        $sub_cat_id = $this->input->post('subcategory');
        $subtosub_cat_id = $this->input->post('subtosubcategory');

        if (trim($name) == "") {
            $this->session->set_flashdata('error', "Name can not be empty");
            redirect("file/add");
        }
        else if (trim($cat_id == "")) {
            $this->session->set_flashdata('error', "Category must be selected");
            redirect("file/add");
        }
        else{
            $data = array();
            $errorUploadType = $statusMsg = '';

            if (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) {
                $filesCount = count($_FILES['files']['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    $file_extension = pathinfo($_FILES["files"]["name"][$i], PATHINFO_EXTENSION);
                    $filename = time() + $i . "." . $file_extension;

                    $_FILES['file']['name'] = $filename;
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                    // File upload configuration
                    $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . "/kalidasa/uploads/documents";
                    $config['allowed_types'] = 'csv|txt|pdf';

                    // Load and initialize upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    // Upload file to server
                    if ($this->upload->do_upload('file')) {
                        // Uploaded file data
                        $fileData = $this->upload->data();
                        
                        $uploadData[$i]['name'] = $name;
                        $uploadData[$i]['description'] = $description;
                        
                        if($author == ""){
                            $author = null;
                        }
                        if($sub_cat_id == ""){
                            $sub_cat_id = null;
                        }
                        if($sub_to_sub_cat_id == ""){
                            $subtosub_cat_id = null;
                        }
                        $uploadData[$i]['author_id'] = $author;
                        $uploadData[$i]['cat_id'] = $cat_id;
                        $uploadData[$i]['sub_cat_id'] = $sub_cat_id;
                        $uploadData[$i]['sub_to_sub_cat_id'] = $subtosub_cat_id;
                        $uploadData[$i]['file_url'] = $filename;

                    } else {
                        $errorUploadType .= $_FILES['file']['name'] . ' | ';
                    }
                }

                $errorUploadType = !empty($errorUploadType) ? '<br/>File Type Error: ' . trim($errorUploadType, ' | ') : '';
                if (!empty($uploadData)) {
                    // Insert files data into the database
                    $insert = $this->File_model->insert($uploadData);

                    // Upload status message
                    $statusMsg = $insert ? 'Files uploaded successfully!' . $errorUploadType : 'Some problem occurred, please try again.';
                    if($insert){
                        $this->session->set_flashdata('success', $statusMsg);
                        redirect("file");
                    }else{
                        $this->session->set_flashdata('error', $statusMsg);
                        redirect("file/add");
                    }
                } else {
                    $statusMsg = "Sorry, there was an error uploading your file." . $errorUploadType;
                    $this->session->set_flashdata('error', $statusMsg);
                    redirect("file/add");

                }
            } else {
                $statusMsg = 'Please select image files to upload.';
                $this->session->set_flashdata('error', $statusMsg);
                redirect("file/add");

            }

        }
 
    }
}