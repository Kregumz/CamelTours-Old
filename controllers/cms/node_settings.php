<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Node_Settings extends CI_Controller
  {
    public function index($tour_id=-1, $node_id=-1, $form_data=array(), $message=null, $error=false) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Verify that the specified node ID is valid.
      $this->verify_node($tour_id, $node_id);
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Remove the underscore from the title.
      $data['title'] = str_replace("_", " ", $data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/cms/'.$page.'.php'))
        show_404();
      // Get the node record.
      $data['tour_id'] = $tour_id;
      $data['node_id'] = $node_id;
      $data['node_name'] = '';
      $data['node_location'] = '';
      //add node lat/long parameters here
      $data['node_lat'] = '';
      $data['node_long'] = '';
      
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() == 1) {
        $row = $query->row();
        $data['node_name'] = $row->node_name;
        $data['node_location'] = $row->node_location;
        //add node lat/long parameters here
        $data['node_lat'] = $row->node_lat;
        $data['node_long'] = $row->node_long;
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
    public function send_form($tour_id=-1, $node_id=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Verify that the specified node ID is valid.
      $this->verify_node($tour_id, $node_id);
      // Grab all of the data.
      $user_id = $this->session->userdata('user_id');
      $this->load->model('node_form_model');
      $form_data = $this->node_form_model->get_data();
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      // Get our node row.
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() == 1) {
        $this_row = $query->row();
        // If we're deleting the node, delete
        // all associated records and files
        // and update the rest of the tour.
        if ($this->input->post('delete')) {
          $this->load->model('delete_model');
          $this->delete_model->delete_node($user_id, $tour_id, $node_id); //if adding lat long this line does not need to be changed/altered
          $this->load->model('build_model');
          $this->build_model->build_tour($user_id, $tour_id);
          $this->build_model->build_dl($user_id, $tour_id);
          $this->build_model->manifesto($user_id, $tour_id);
          redirect('cms/home/'.$tour_id);
        }
        // If the form is incomplete, show an error.
        elseif (!$form_data['complete']) {
          $message = 'Missing required fields.';
          $this->index($tour_id, $node_id, $form_data, $message, true); //if adding lat long this line HAS to be changed/altered to maybe $this->index($tour_id, $node_id, $node_lat, $node_long, $form_data, $message, true);
        }
        // Otherwise, update the node record.
        else {
          $this->load->model('build_model');
          if ($this_row->node_name !== $form_data['node_name']) {
            $this->db->where('node_id', $this_row->node_id);
            $count_check = $this->db->count_all_results('nodes');
            if ($count_check == 1) {
              $this->db->where('node_id', $this_row->node_id);
              $this->db->update('nodes', array('node_name' => $form_data['node_name']));
              $this->build_model->build_node($this_row->node_id, $tour_uri);
              $this->build_model->build_tour($user_id, $tour_id);
            }
          }
          if ($this_row->node_location !== $form_data['node_location']) {
            $this->db->where('node_id', $this_row->node_id);
            $count_check = $this->db->count_all_results('nodes');
            if ($count_check == 1) {
              $this->db->where('node_id', $this_row->node_id);
              $this->db->update('nodes', array('node_location' => $form_data['node_location']));
              $this->build_model->build_node($this_row->node_id, $tour_uri);
              $this->build_model->build_tour($user_id, $tour_id);
            }
          }
          //if adding lat long need to add two if statements checking lat long they can be viewed below in the block comments
          if ($this_row->node_lat !== $form_data['node_lat']) {
            $this->db->where('node_id', $this_row->node_id);
            $count_check = $this->db->count_all_results('nodes');
            if ($count_check == 1) {
              $this->db->where('node_id', $this_row->node_id);
              $this->db->update('nodes', array('node_lat' => $form_data['node_lat']));
              $this->build_model->build_node($this_row->node_id, $tour_uri);
              $this->build_model->build_tour($user_id, $tour_id);
            }
          }
          if ($this_row->node_long !== $form_data['node_long']) {
            $this->db->where('node_id', $this_row->node_id);
            $count_check = $this->db->count_all_results('nodes');
            if ($count_check == 1) {
              $this->db->where('node_id', $this_row->node_id);
              $this->db->update('nodes', array('node_long' => $form_data['node_long']));
              $this->build_model->build_node($this_row->node_id, $tour_uri);
              $this->build_model->build_tour($user_id, $tour_id);
            }
          }
          $this->index($tour_id, $node_id, $form_data, 'Changes saved successfully!');
        }
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
    private function verify_node($tour_id, $node_id) {
      $this->db->where('tour_id', $tour_id);
      $this->db->where('node_id', $node_id);
      if (!$this->db->get('nodes')->num_rows() == 1)
        redirect('cms/home');
    }
  }

?>