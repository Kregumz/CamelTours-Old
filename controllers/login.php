<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Login extends CI_Controller
  {
    public function index($user=null, $error=null) {
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();
      // Set additional data parameters.
      $data['user'] = $user;
      $data['error'] = $error;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view($page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function attempt_login() {
      // Load the login model.
      $this->load->model('login_model');
      // Validate whether the user can login.
      $success = $this->login_model->validate();
      // If the login was unsuccessful, show the user an error message.
      if (!$success)
        $this->index($this->input->post('username'), 'Invalid email/password combination.');
      // Otherwise, send them to their CMS home.
      else
        $this->verify_admin();   
    }
      
    private function verify_admin() {
      $this->db->where('user_id', $this->session->userdata('user_id'));
      $query = $this->db->get('users');
      if ($query->num_rows() == 1) {
        $role = $query->row()->role;
        if ($role != "admin")
            redirect('cms/home');
        else
            redirect('admin');
	  }
    }
      
  }

?>