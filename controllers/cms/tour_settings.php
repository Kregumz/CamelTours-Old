<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Tour_Settings extends CI_Controller
  {
    public function index($tour_id=-1, $form_data=array(), $message=null, $error=false) {
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
      // Get the tour record.
      $data['tour_id'] = $tour_id;
      $data['tour_name'] = '';
      $data['tour_location'] = '';
      //add the following lines for tour lat and tour long
      $data['tour_lat'] = '';
      $data['tour_long'] = '';
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 1) {
        $row = $query->row();
        $data['tour_name'] = $row->tour_name;
        $data['tour_location'] = $row->tour_location;
        //tour lat and tour long
        $data['tour_lat'] = $row->tour_lat;
        $data['tour_long'] = $row->tour_long;
      }
      // Set additional data parameters.
      $data['form_data'] = $form_data;
      $data['message'] = $message;
      $data['error'] = $error;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form($tour_id=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Grab all of the data.
      $user_id = $this->session->userdata('user_id');
      $this->load->model('tour_form_model');
      $form_data = $this->tour_form_model->get_data();
      // Get our tour row.
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 1) {
        $this_row = $query->row();
        // If we're deleting the tour, delete
        // all associated records and files.
        if ($this->input->post('delete')) {
          $this->load->model('delete_model');
          $this->delete_model->delete_tour($user_id, $tour_id);
          redirect('cms/home');
        }
        // If the form is incomplete, show an error.
        elseif (!$form_data['complete']) {
          $message = 'Missing required fields.';
          $this->index($tour_id, $form_data, $message, true); //do not think I need to change this line if adding tour lat and tour long
        }
        // Otherwise, update the tour record.
        else {
          $this->load->model('build_model');
          //check tour name
          if ($this_row->tour_name !== $form_data['tour_name']) {
            $this->db->where('tour_id', $this_row->tour_id);
            $count_check = $this->db->count_all_results('tours');
            if ($count_check == 1) {
              $this->db->where('tour_id', $this_row->tour_id);
              $this->db->update('tours', array('tour_name' => $form_data['tour_name']));
              $this->build_model->build_tour($user_id, $this_row->tour_id);
            }
          }
          //check tour location
          if ($this_row->tour_location !== $form_data['tour_location']) {
            $this->db->where('tour_id', $this_row->tour_id);
            $count_check = $this->db->count_all_results('tours');
            if ($count_check == 1) {
              $this->db->where('tour_id', $this_row->tour_id);
              $this->db->update('tours', array('tour_location' => $form_data['tour_location']));
              $this->build_model->build_tour($user_id, $this_row->tour_id);
            }
          }
          //if adding tour lat and tour long add two more if statements as seen below
          
          //check tour lat
          if ($this_row->tour_lat !== $form_data['tour_lat']) {
            $this->db->where('tour_id', $this_row->tour_id);
            $count_check = $this->db->count_all_results('tours');
            if ($count_check == 1) {
              $this->db->where('tour_id', $this_row->tour_id);
              $this->db->update('tours', array('tour_lat' => $form_data['tour_lat']));
              $this->build_model->build_tour($user_id, $this_row->tour_id);
            }
          }
          //check tour long
          if ($this_row->tour_long !== $form_data['tour_long']) {
            $this->db->where('tour_id', $this_row->tour_id);
            $count_check = $this->db->count_all_results('tours');
            if ($count_check == 1) {
              $this->db->where('tour_id', $this_row->tour_id);
              $this->db->update('tours', array('tour_long' => $form_data['tour_long']));
              $this->build_model->build_tour($user_id, $this_row->tour_id);
            }
          }
          $this->index($tour_id, $form_data, 'Changes saved successfully!');//DO NOT CHANGE THIS LINE IF ADDING tour lat and tour long because this info will be in the form_data
        }
      }
    }
    public function download_zip($tour_id=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Grab all of the data.
      $user_id = $this->session->userdata('user_id');
      // Create the zip archive for download.
      $this->load->library('zip');
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      $this->zip->read_dir(getcwd().'/'.$tour_uri, false);
      $this->zip->download('t'.$tour_id.'.zip');
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