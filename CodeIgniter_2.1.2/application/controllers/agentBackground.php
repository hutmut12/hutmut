<?php
class agentBackground extends CI_Controller {

    
	public function __construct()
	{
		parent::__construct();
		$this->load->model('agentBackground_model');
	}

	public function index()
	{
                //IMPORT
                $this->load->helper('url');
                $this->load->helper(array('form', 'url'));
                $this->load->library('session');
                
                                
                $data['title'] = 'Agent Background and Experience';
                
                //FETCH POST DATA
                $postData = $this->input->post(NULL, TRUE);
                
                $this->load->helper('email');


                //CHECKING FOR SESSION VALIDATION
                if($this->session->userdata('email') == NULL OR $this->session->userdata('email') == FALSE){
                   redirect('logout', 'refresh'); // 'login/index' should be automatically called
                   return;
                }
                
                

                //Form Validation START
                $this->load->library('form_validation');

                //SETTING FORM VALIDATION RULES
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name ', 'trim|required|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|is_unique[agent.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirm_password]|min_length[6]|max_length[50]|xss_clean');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[50]|xss_clean');
                
                
                //DEFINING MESSAGE FOR EACH VALIDATION RULE
                $this->form_validation->set_message('required', '%s is required field');
                //----
                
                //LOADING HEADER
                $this->load->view('templates/header', $data);
		
                
                //CHECK IF VALIDATION IS SUCCESS
                if ($this->form_validation->run() == FALSE)
		{
                    //IF FALSE, GO BACK SHOW ERRORS    
                    $this->load->view('agentProfile/agentBackground', $data);
		
                    
                }else{
                    
                    //IF EVERYTHING SUCCESSFUL, SAVE DATA, CREATE SESSION
                    $data['joinUs'] = $this->agentBackground_model->get_joinUs();
                    
                    //DESTROY PREVIOUS SESSION
                    $this->session->sess_destroy();

                    //SETTING NEW SESSION
                    $newdata = array(
                                //'username'  => $this->db->escape($postData['first_name'])." ".$this->db->escape($postData['last_name']),
                                'email'     => $this->db->escape($postData['email']),
                                'logged_in' => TRUE
                    );

                    $this->session->set_userdata($newdata);

                    
                    //SENDING EMAIL 
                    sendEmail("doNotReply_welcome@hutmut.com", "hrspandya@gmail.com", "Hutmut", "Welcome to Hutmut",$this->load->view('pages/about', $data, true));
                    
                    
                    $this->load->view('agentProfile/agentBackground', $data);
                    
		}
                
                
                //LOADING FOOTER
		$this->load->view('templates/footer');
	}

	
}