<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Account_Settings extends CI_Controller
  {
    public function index($form_data=array(), $message=null, $error=false) {
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
      // Set parameters for the account data.
      $data['name'] = $this->session->userdata('name');
      $data['email'] = $this->session->userdata('email');
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
    public function send_form() {
      $this->load->model('account_form_model');
      $form_data = $this->account_form_model->get_data();
      // If the form is incomplete, show an error.
      if (!$form_data['complete']) {
        $message = 'Missing required fields.';
        $this->index($form_data, $message, true);
      }
      // If the user entered their password incorrectly, show an error.
      elseif (!$form_data['curr_pass_correct']) {
        $message = 'Your current password was entered incorrectly.';
        $this->index($form_data, $message, true);
      }
      // If the user's new email address already exists, show an error.
      elseif ($form_data['email_already_exists']) {
        $message = 'Someone else has that email address.';
        $this->index($form_data, $message, true);
      }
      // If the user is changing their password and it doesn't match up, show an error.
      elseif ($form_data['changing_pass'] && !$form_data['pass_match']) {
        $message = 'Your new password does not match your new password confirmation.';
        $this->index($form_data, $message, true);          
      }
      // Otherwise, update the account record.
      else {
        $this->db->where('user_id', $this->session->userdata['user_id']);
        $count_check = $this->db->count_all_results('users');
        if ($count_check == 1) {
          $this->db->where('user_id', $this->session->userdata['user_id']);
          $this->db->update('users', array('name' => $form_data['name'], 'email' => $form_data['email']));
          $this->session->set_userdata('name', $form_data['name']);
          $this->session->set_userdata('email', $form_data['email']);
          if ($form_data['changing_pass']) {
            $this->db->where('user_id', $this->session->userdata['user_id']);
            $this->db->update('users', array('password' => $form_data['new_password']));
          }
        }
      }
      $this->index($form_data, 'Changes successfully saved!');
    }
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
  }

?>