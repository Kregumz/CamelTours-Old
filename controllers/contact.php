<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Contact extends CI_Controller
  {
    public function index($form_data=array(), $error=null) {
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();
      // Set additional data parameters.
      $data['form_data'] = $form_data;
      $data['num1'] = rand(1, 10);
      $data['num2'] = rand(1, 10);
      $data['error'] = $error;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view($page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form() {
      $this->load->model('contact_model');
      $form_data = $this->contact_model->get_data();
      // If the form is incomplete, show an error.
      if (!$form_data['complete']) {
        $error = 'Missing required fields.';
        $this->index($form_data, $error);
      }
      // If the user did not answer the security question correctly, show an error.
      else if (!$form_data['correct']) {
        $error = 'Incorrect answer to security question.';
        $this->index($form_data, $error);
      }
      // Otherwise, send the form and thank the user.
      else {
        $crlf = "\r\n";
        $body = 'A contact form has been submitted on CamelTours.org.'.$crlf.$crlf
              . 'Name: '.$form_data['name'].$crlf
              . 'Email: '.$form_data['email'].$crlf.$crlf
              . 'Message: '.$form_data['message'].$crlf.$crlf
              . 'Thank you for your attention to this matter.';
        $this->load->library('email');
        $this->email->from('admin@cameltours.org', 'CamelTours Admin');
        $this->email->to('cameltours@conncoll.edu');
        $this->email->subject('Contact Form Submission');
        $this->email->message($body);
        $this->email->send();
        
        redirect('thanks');
      }
    }
  }

?>