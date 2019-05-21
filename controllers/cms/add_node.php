<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Add_Node extends CI_Controller
  {
    public function index($tour_id=-1, $form_data=array(), $error=null) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Remove the underscore from the title.
      $data['title'] = str_replace("_", " ", $data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/cms/'.$page.'.php'))
        show_404();
      // Set additional data parameters.
      $data['tour_id'] = $tour_id;
      $data['form_data'] = $form_data;
      $data['error'] = $error;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form() {
      $this->load->model('node_form_model');
      $form_data = $this->node_form_model->get_data();
      // If the form is incomplete, show an error.
      if (!$form_data['complete']) {
        $error = 'Missing required fields.';
        $this->index($form_data['tour_id'], $form_data, $error); //if add lat/long FOR THE TOUR ITSELF and it is a necessary field this line HAS TO CHANGE replacement can be $this->index($form_data['tour_lat'], $form_data['tour_long'], $form_data['tour_id'] $form_data, $error);
        //
      }
      // Otherwise, add the new node.
      else {
        // Create the database entry.
        //if adding lat/long to the new node entry it would look like the block quotes below 
        $data = array(
          'tour_id' => $form_data['tour_id'],
          'node_name' => $form_data['node_name'],
          'node_location' => $form_data['node_location'],
          'node_lat' => $form_data['node_lat'],
          'node_long' => $form_data['node_long']
        );
        // Insert the entry into the nodes table.
        $this->db->insert('nodes', $data);
        // Create the necessary files.
        $user_id = $this->session->userdata('user_id');
        $tour_id = $form_data['tour_id'];
        $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
        $node_id = $this->db->insert_id();
        $node_uri = $tour_uri.'n'.$node_id.'/';
        if (!file_exists(getcwd().'/'.$node_uri))
          mkdir(getcwd().'/'.$node_uri, 0755, true);
        $this->load->library('ciqrcode');
        $params = array(
          'data' => base_url().$node_uri,
          'savename' => getcwd().'/'.$node_uri.'qr.png',
          'level' => 'H',
          'size' => 15
        );
        $this->ciqrcode->generate($params);
        $this->load->model('build_model');
        $this->build_model->build_node($node_id, $tour_uri);
        $this->build_model->build_tour($user_id, $tour_id);
        $this->build_model->build_dl($user_id, $tour_id);
        $this->build_model->manifesto($user_id, $tour_id);
        // Redirect the user to the upload page.
        redirect('cms/node/'.$tour_id.'/'.$node_id);
      }
    }
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
    private function verify_tour($tour_id) {
      $this->db->where('user_id', $this->session->userdata('user_id'));
      $this->db->where('tour_id', $tour_id);
      if (!$this->db->get('tours')->num_rows() == 1)
        redirect('cms/home');
    }
  }

?>