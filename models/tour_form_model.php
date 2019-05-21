<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Tour_Form_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
       $form_data = array(
        'tour_name' => $this->input->post('tour_name'),
        'tour_location' => $this->input->post('tour_location'),
        'tour_lat' => $this->input->post('tour_lat'),
        'tour_long' => $this->input->post('tour_long')
       );
       
       // Check whether we are missing any required parameters.
       $form_data['complete'] = array_search('', $form_data) == false;
       // Return the form data.
       return $form_data;
    }
  }

?>