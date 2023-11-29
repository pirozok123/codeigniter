<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Users extends CI_Controller { 
     
    function __construct() { 
        parent::__construct(); 
         
        // Load form validation ibrary & user model 
        $this->load->library('form_validation'); 
        $this->load->model(array('user','sitemap')); 
         
        // User login status 
        $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
    } 
     
    public function index(){ 
        if($this->isUserLoggedIn){ 
            redirect('users/account'); 
        }else{ 
            redirect('users/login'); 
        } 
    } 
 
    public function account(){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $con = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->user->getRows($con); 
             
            // Pass the user data and load view 
            $this->load->view('elements/header', $data); 
            $this->load->view('users/account', $data); 
            $this->load->view('elements/footer'); 
        }else{ 
            redirect('users/login'); 
        } 
    } 
 
    public function login(){ 
        $data = array(); 
         
        // Get messages from the session 
        if($this->session->userdata('success_msg')){ 
            $data['success_msg'] = $this->session->userdata('success_msg'); 
            $this->session->unset_userdata('success_msg'); 
        } 
        if($this->session->userdata('error_msg')){ 
            $data['error_msg'] = $this->session->userdata('error_msg'); 
            $this->session->unset_userdata('error_msg'); 
        } 
         
        // If login request submitted 
        if($this->input->post('loginSubmit')){ 
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
             
            if($this->form_validation->run() == true){ 
                $con = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'email'=> $this->input->post('email'), 
                        'password' => md5($this->input->post('password')), 
                        'status' => 1 
                    ) 
                ); 
                $checkLogin = $this->user->getRows($con); 
                if($checkLogin){ 
                    $this->session->set_userdata('isUserLoggedIn', TRUE); 
                    $this->session->set_userdata('userId', $checkLogin['id']); 
                    redirect('users/account/'); 
                }else{ 
                    $data['error_msg'] = 'Wrong email or password, please try again.'; 
                } 
            }else{ 
                $data['error_msg'] = 'Please fill all the mandatory fields.'; 
            } 
        } 
         
        // Load view 
        $this->load->view('elements/header', $data); 
        $this->load->view('users/login', $data); 
        $this->load->view('elements/footer'); 
    } 
 
     
    public function logout(){ 
        $this->session->unset_userdata('isUserLoggedIn'); 
        $this->session->unset_userdata('userId'); 
        $this->session->sess_destroy(); 
        redirect('users/login/'); 
    } 
     
     
    // Existing email check during validation 
    public function email_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'email' => $str 
            ) 
        ); 
        $checkEmail = $this->user->getRows($con); 
        if($checkEmail > 0){ 
            $this->form_validation->set_message('email_check', 'The given email already exists.'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    } 

     // Existing email check during validation 
    public function login_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'login' => $str 
            ) 
        ); 
        $checkEmail = $this->user->getRows($con); 
        if($checkEmail > 0){ 
            $this->form_validation->set_message('login_check', 'The given login already exists.'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    } 


    public function userprofile($id = null){ 

        $data = array();

        if($this->input->post('signupSubmit')){

            $this->form_validation->set_rules('project_name', 'Project Name', 'required'); 

            if(!$this->input->post('user_id')){
            $this->form_validation->set_rules('login', 'Login', 'required|callback_login_check');
            $this->form_validation->set_rules('api_key', 'Api_key', 'required');
            }

            else 
            $this->form_validation->set_rules('login', 'Login', 'required'); 

            if($this->form_validation->run() == true){  

              $data['login'] = $this->input->post('login');
              $data['project_name'] = $this->input->post('project_name');
              $data['password'] = md5($this->input->post('password'));

              if($this->input->post('user_id')){
              
              $data['user_id'] = $this->input->post('user_id');
              $this->user->updateRow($data);

              }

              else {
              $data['api_key'] = $this->input->post('api_key');  
              $this->user->insert($data); 
              $insert_id = $this->db->insert_id();
              redirect('/users/userprofile/'.$insert_id);
            }
           }

        }

        if($id != null) {
        
        $con = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'id'=> intval($id), 
                    ) 
        );

        $data['button'] = 'Update user';
        $data['sitemapbutton'] = 'Add sitemap';
        $data['api_edit'] = 'disabled'; 

        $data['userinfo'] = $this->user->getRows($con);

        }

        else {
        $data['button'] = 'Register user';   
        $data['api_edit'] = 'required'; 
        } 

        $con = array( 
            'returnType' => '',
        ); 

        $data['userlist'] = $this->user->getRows($con);

        $con = array( 
            'returnType' => '',
        ); 

        $data['sitemaplist'] = $this->sitemap->getRows($con);

        $this->load->view('elements/header', $data); 
        $this->load->view('users/userprofile', $data); 
        $this->load->view('elements/footer'); 
    }

     public function updatePassword($id){ 

        $data = array();

        if($this->input->post('passwordUpdate')){
            $this->form_validation->set_rules('password', 'password', 'required'); 

            if($this->form_validation->run() == true){  

              $data['password'] = md5($this->input->post('password'));
              $data['user_id'] = $this->input->post('user_id');
              $this->user->updateRow($data);
              redirect('/users/userprofile/'.$id);

              }
        } 

        $this->load->view('elements/header', $data); 
        $this->load->view('users/userprofile', $data); 
        $this->load->view('elements/footer'); 


    }

     public function sitemapprofile($id = null){ 

        $data = array();

        if($this->input->post('sitemapSubmit')){
            $this->form_validation->set_rules('project_name', 'Project Name', 'required'); 
            $this->form_validation->set_rules('sitemap', 'Sitemap Url', 'required'); 
            $this->form_validation->set_rules('page_type', 'Page type', 'required|numeric');
           // $this->form_validation->set_rules('user_id', 'User id', 'required|numeric');

            if($this->form_validation->run() == true){  

              $data['project_name'] = $this->input->post('project_name');
              $data['sitemap'] = $this->input->post('sitemap');
              $data['page_type'] = $this->input->post('page_type');
              $data['user_id'] = $this->input->post('user_id');

              if($this->input->post('sitemap_id')){
             
              $data['sitemap_id'] = $this->input->post('sitemap_id');
              $this->sitemap->updateRow($data);
              redirect('/users/userprofile/'.$this->input->post('user_id'));

              }

              else {
              $this->sitemap->insert($data); 
              $insert_id = $this->db->insert_id();
              redirect('/users/userprofile/'.$insert_id);
            }
           }

        }

        if($id != null) {
        
        $con = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'id'=> intval($id), 
                    ) 
        );

        $data['sitemapbutton'] = 'Update sitemap';

        $data['sitemap'] = $this->sitemap->getRows($con);

        }

        $this->load->view('elements/header', $data); 
        $this->load->view('users/sitemapprofile', $data); 
        $this->load->view('elements/footer'); 
    }


     public function deleteprofile($id){ 
           $id = intval($id);
           $this->user->deleteRow($id);
           redirect('/users/userlist');
       }

     public function deletesitemap($id){ 
           $id = intval($id);
           $this->sitemap->deleteRow($id);
           redirect('/users/userprofile');
       }

    public function sitemaplist(){ 

        $data = array();

        $con = array( 
            'returnType' => '',
        ); 

        $data['sitemaplist'] = $this->sitemap->getRows($con);

        $this->load->view('elements/header', $data); 
        $this->load->view('users/usersitemap', $data); 
        $this->load->view('elements/footer'); 
    }
}