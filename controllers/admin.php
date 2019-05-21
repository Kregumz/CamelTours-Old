<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Admin extends CI_Controller
  {
    public function index($message=null, $error=false) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the current user is an admin
      $this->verify_admin();
      // Get the name of the page from the class.
        
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'views/'.$page.'.php'))
        show_404();
      // Set data parameters.
      $data['message'] = $message;
      $data['error'] = $error;

      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view(''.$page, $data);
      $this->load->view('templates/footer', $data);
    }
      
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
    private function verify_admin() {
      $this->db->where('user_id', $this->session->userdata('user_id'));
      $query = $this->db->get('users');
      if ($query->num_rows() == 1) {
        $role = $query->row()->role;
        if ($role != "admin")
            redirect('login');
	  }
    }
  }

?>