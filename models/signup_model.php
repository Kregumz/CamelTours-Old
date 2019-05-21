<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Signup_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
      $form_data = array(
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'password' => MD5($this->input->post('password')),
        'confirm_password' => MD5($this->input->post('confirm_password')),
        'essay' => $this->input->post('essay'),
        'sum' => $this->input->post('sum')
       );
       // Check whether we are missing any required parameters.
       $form_data['complete'] = array_search('', $form_data) == false;
       // Fill in our hidden number parameters.
       $form_data['num1'] = $this->input->post('num1');
       $form_data['num2'] = $this->input->post('num2');
       // Check whether the user answered the security question correctly.
       $form_data['correct'] = (intval($form_data['num1']) + intval($form_data['num2'])) == intval($form_data['sum']);
       // Check whether the user's password and password confirmation match.
       $form_data['pass_match'] = ($form_data['password'] == $form_data['confirm_password']);
       // Check whether this email address is already in the database.
       $this->db->where('email', $form_data['email']);
       $form_data['already_in_users_db'] = ($this->db->get('users')->num_rows() == 1);
       $this->db->where('email', $form_data['email']);
       $form_data['already_in_requests_db'] = ($this->db->get('requests')->num_rows() == 1);
       // Return the form data.
       return $form_data;
    }
  }

?>