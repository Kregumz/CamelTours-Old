<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Login_Model extends CI_Model
  {
    public function validate() {
      // Get the user input.
      $username = $this->security->xss_clean($this->input->post('username'));
      $password = MD5($this->security->xss_clean($this->input->post('password')));
      // Prepare the database query.
      $this->db->where('username', $username);
      $this->db->or_where('email', $username);
      // Execute the database query.
      $query = $this->db->get('users');
      if ($query->num_rows() == 1) {
        $row = $query->row();
        // Check the password.
        if ($row->password == $password) {
          // If everything's A-OK, set the session.
          $data = array(
            'user_id' => $row->user_id,
            'name' => $row->name,
            'username' => $row->username,
            'email' => $row->email,
            'role' => $row->role,
            'validated' => true
          );
          $this->session->set_userdata($data);
          return true;
        }
      }
    // Otherwise, return false.
    return false;
  }
}

?>