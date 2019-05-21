<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Add_Tour extends CI_Controller
  {
    public function index($form_data=array(), $error=null) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Remove the underscore from the title.
      $data['title'] = str_replace("_", " ", $data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/cms/'.$page.'.php'))
        show_404();
      // Set additional data parameters.
      $data['form_data'] = $form_data;
      $data['error'] = $error;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form() {
      $this->load->model('tour_form_model');
      $form_data = $this->tour_form_model->get_data();
      // If the form is incomplete, show an error.
      if (!$form_data['complete']) {
        $error = 'Missing required fields.';
        $this->index($form_data, $error);
      }
      // Otherwise, add the new tour.
      else {
        // Create the database entry.
        //to add tour lat and tour long to this array we would need to replace lines 36 to 40 with the following code
        $data = array(
          'user_id' => $this->session->userdata('user_id'),
          'tour_name' => $form_data['tour_name'],
          'tour_location' => $form_data['tour_location'],
          'tour_lat' => $form_data['tour_lat'],
          'tour_long' => $form_data['tour_long']
        );
        // Insert the entry into the tours table.
        $this->db->insert('tours', $data);
        // Create the necessary files.
        $user_id = $this->session->userdata('user_id');
        $tour_id = $this->db->insert_id();
        $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
        if (!file_exists(getcwd().'/'.$tour_uri.'dl/'))
          mkdir(getcwd().'/'.$tour_uri.'dl/', 0755, true);
        
        $this->load->library('ciqrcode');
        $params = array(
          'data' => base_url().$tour_uri,
          'savename' => getcwd().'/'.$tour_uri.'qr.png',
          'level' => 'H',
          'size' => 15
        );
        
        $this->ciqrcode->generate($params);
        
        $this->load->model('build_model');
        $this->build_model->build_tour($user_id, $tour_id);
        $this->build_model->build_dl($user_id, $tour_id);
        $this->build_model->manifesto($user_id, $tour_id);
        // Redirect the user to their CMS home.
        redirect('cms/home/'.$tour_id);
      }
    }
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
  }

?>