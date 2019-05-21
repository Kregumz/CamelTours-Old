<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Contact_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
      $form_data = array(
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'message' => $this->input->post('message'),
        'sum' => $this->input->post('sum')
      );
      // Check whether we are missing any required parameters.
      $form_data['complete'] = array_search('', $form_data) == false;
      // Fill in our hidden number parameters.
      $form_data['num1'] = $this->input->post('num1');
      $form_data['num2'] = $this->input->post('num2');
      // Check whether the user answered the security question correctly.
      $form_data['correct'] = (intval($form_data['num1']) + intval($form_data['num2'])) == intval($form_data['sum']);
      // Return the form data.
      return $form_data;
    }
  }

?>