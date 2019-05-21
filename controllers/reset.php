<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Reset extends CI_Controller
  {   
    public function index($form_data=array(), $message=null, $error=false) {
      // Boot the user if token is not valid.
      if ($message=='Changes successfully saved!'){
          //delete the token from the database
          $this->db->where('token', $token = $this->input->get('token'));
          $this->db->delete('pwdreset');
      }
      else
          $this->check_token();
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Remove the underscore from the title.
      $data['title'] = str_replace("_", " ", $data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();
      // Set data parameters.
      $data['form_data'] = $form_data;
      $data['message'] = $message;
      $data['error'] = $error;
      $data['token'] = $this->input->get('token');
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view(''.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form() {
      $this->check_token();
      $this->load->model('password_reset_model');
      $form_data = $this->password_reset_model->get_data();
      // If the form is incomplete, show an error.
      if (!$form_data['complete']) {
        $message = 'Missing required fields.';
        $this->index($form_data, $message, true);
      }
      // If the user is changing their password and it doesn't match up, show an error.
      elseif (!$form_data['pass_match']) {
        $message = 'Your new password does not match your new password confirmation.';
        $this->index(null, $message);          
      }
      // Otherwise, update the account record.
      else {
        $this->db->where('token', $token = $this->input->get('token'));
        $count_check = $this->db->count_all_results('pwdreset');
        if ($count_check == 1) {
            //get the row with the token
            $this->db->where('token', $token = $this->input->get('token'));
            $query = $this->db->get('pwdreset');
            foreach ($query->result() as $result){
                $user_id = $result->user_id;
                $this->db->where('user_id', $user_id);
                $this->db->update('users', array('password' => $form_data['new_password']));
            }
        }
      $this->index($form_data, 'Changes successfully saved!');
    }
    }
        
    private function check_token() {
        //get token
        $token = $this->input->get('token');
        //verify token
        $this->db->where('token',$token);
        $count_check = $this->db->count_all_results('pwdreset');
        if ($count_check == 1){
            $query = $this->db->get('pwdreset');
            foreach ($query->result() as $result){
                $tstamp = $result->tstamp; 
                $current_tstamp = $this->input->server('REQUEST_TIME');
                
                $delta = 86400;
                if ($current_tstamp - $tstamp < $delta){
                    $this->db->where('token', $token = $this->input->get('token'));
                    $this->db->delete('pwdreset');
                    return null;
                }
                    
                else
                    show_404(); //instead of 404, we should come up with a better way to alert the user
            }
        }   
        else
            show_404(); //instead of 404, we should come up with a better way to alert the user
    }
  }

?>