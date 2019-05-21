<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Account_Form_Model extends CI_Model
  {
    public function get_data() {
      // Get the user input.
      $form_data = array(
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'password' => MD5($this->input->post('password'))
       );
       // Check whether we are missing any required parameters.
       $form_data['complete'] = array_search('', $form_data) == false;
       // Check whether the user's current password is correct.
       $this->db->where('user_id', $this->session->userdata['user_id']);
       $this->db->where('password', $form_data['password']);
       $form_data['curr_pass_correct'] = ($this->db->count_all_results('users') == 1);
       // Check whether this email address, if changed, is already in the database.
       $form_data['email_already_exists'] = false;
       if ($form_data['email'] != $this->session->userdata['email']) {
         $this->db->where('email', $form_data['email']);
         $form_data['email_already_exists'] = ($this->db->get('users')->num_rows() == 1);
       }
       // Check whether the user is changing their password.
       $form_data['changing_pass'] = false;
       if ($this->input->post('new_password'))
         $form_data['changing_pass'] = true;
       // Fill in our change password input.
       $form_data['new_password'] = MD5($this->input->post('new_password'));
       $form_data['confirm_new_password'] = MD5($this->input->post('confirm_new_password'));
       // Check whether the user's new password and new password confirmation match.
       $form_data['pass_match'] = ($form_data['new_password'] == $form_data['confirm_new_password']);
       // Return the form data.
       return $form_data;
    }
  }

?>