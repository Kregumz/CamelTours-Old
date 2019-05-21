<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Request extends CI_Controller
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
      $data['requests'] = array();
        
      $query = $this->db->get('requests');
      foreach ($query->result() as $request){
          $this->db->where('email', $request->email);
          $query2 = $this->db->get('users');
          if ($query2->num_rows() == 0){
              $id = $request->request_id;
              $name = $request->name;
              $email = $request->email;
              $essay = $request->essay;
              $request_data = array($id, $name, $email, $essay);
              $data['requests'][]=$request_data;
          }
      }

      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view(''.$page, $data);
      $this->load->view('templates/footer', $data);
    }
      
    public function add_user() {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the current user is an admin
      $this->verify_admin();
      //get the input
      $id = $_POST['id'];
      $this->db->where('request_id', $id);
      $query = $this->db->get('requests');
      foreach ($query->result() as $request){
        $user_data = array(
          'name' => $request->name,
          'email' => $request->email,
          'password' => $request->password,
        );
        // Insert the entry into the user table.
        $this->db->insert('users', $user_data);
      }
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