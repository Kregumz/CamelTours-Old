<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Update extends CI_Controller
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

    public function send_form() {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the current user is an admin
      $this->verify_admin();
      // Grab all of the data.
      $user_id = $this->session->userdata('user_id');
      //grab all the nodes from the nodes table
      $query = $this->db->get('tours');
      //update all the nodes in each tour
      foreach ($query->result() as $tour) {
        //declare the $tour_uri
        $user_id = $tour->user_id;
        $tour_id = $tour->tour_id;
        //load build_model
        $this->load->model('build_model');
        //build all tour pages
        $this->build_model->build_tour($user_id, $tour_id);
        //get the tour_uri
        $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
        //get all the nodes in the tour
        $this->db->where('tour_id', $tour_id);
        $query2 = $this->db->get('nodes');
        //call build model for the each node
        foreach ($query2->result() as $node){
            $node_id = $node->node_id;
            $this->build_model->build_node($node_id, $tour_uri);
        }
      }
      $this->index('Updated successfully!');
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
