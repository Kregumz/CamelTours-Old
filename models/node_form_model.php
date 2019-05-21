<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Node_Form_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
       $form_data = array(
        'tour_id' => $this->input->post('tour_id'),
        'node_name' => $this->input->post('node_name'),
        'node_location' => $this->input->post('node_location'),
        'node_lat' => $this->input->post('node_lat'),
        'node_long' => $this->input->post('node_long')
       );
       // Check whether we are missing any required parameters.
       $form_data['complete'] = array_search('', $form_data) == false;
       // Return the form data.
       return $form_data;
    }
  }
?>