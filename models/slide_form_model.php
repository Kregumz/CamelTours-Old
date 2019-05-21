<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Slide_Form_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
      $form_data = array(
        'caption' => $this->input->post('caption'),
        'seq_num' => $this->input->post('seqn')
       );
       // Check whether we are missing any required parameters.
       $form_data['complete'] = $form_data['seq_num'] != false;
       // Return the form data.
       return $form_data;
    }
  }

?>