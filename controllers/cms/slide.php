<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
  class Slide extends CI_Controller
  {
    public function index($tour_id=-1, $node_id=-1, $seq_num=-1, $form_data=array(), $message=null, $error=false) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Verify that the specified node ID is valid.
      $this->verify_node($tour_id, $node_id);
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/cms/'.$page.'.php'))
        show_404();
      // Check what type of slide they are accessing.
      $is_audio_slide = (intval($seq_num) === 0);
      $audio_not_found = false;
      // Prepare the slide database query.
      $this->db->where('node_id', $node_id);
      $this->db->where('seq_num', $seq_num);
      // Execute the slide database query.
      $slide_query = $this->db->get('slides');
      if ($slide_query->num_rows() == 1)
        $row = $slide_query->row();
      elseif ($is_audio_slide)
        $audio_not_found = true;
      else
        show_404();      
      // Query the DB to count the total slides for this node.
      $this->db->where('node_id', $node_id);
      $total_slides = $this->db->count_all_results('slides');
      // Also query to see if we have an audio file at all.
      $this->db->where('node_id', $node_id);
      $this->db->where('seq_num', '0');
      $audio_row_num = $this->db->count_all_results('slides');
      $total_slides_minus_audio = $total_slides - $audio_row_num;
      // Set additional data parameters.
      $data['form_data'] = $form_data;
      $data['message'] = $message;
      $data['error'] = $error;
      $data['tour_id'] = $tour_id;
      $data['node_id'] = $node_id;
      $data['seq_num'] = $seq_num;
      $data['node_url'] = base_url().'cms/slide/'.$tour_id.'/'.$node_id.'/';
      $data['total_slides'] = $total_slides;
      $data['total_slides_minus_audio'] = $total_slides_minus_audio;
      if (!$audio_not_found) {
        $data['media_uri'] = $row->media_uri;
        $data['thumb_uri'] = $row->thumb_uri;
        $data['caption'] = $row->caption;
      }
      $data['is_audio_slide'] = $is_audio_slide;
      $data['audio_not_found'] = $audio_not_found;
      $data['upload_url'] = base_url().'cms/node/'.$tour_id.'/'.$node_id;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form($tour_id=-1, $node_id=-1, $seq_num=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Verify that the specified node ID is valid.
      $this->verify_node($tour_id, $node_id);
      // Grab all of the data.
      $this->load->model('slide_form_model');
      $form_data = $this->slide_form_model->get_data();
      // Get the user ID and tour URI for later.
      $user_id = $this->session->userdata('user_id');
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      // Set the slide number for redirecting.
      $redirect_slide = $form_data['seq_num'];
      // Take stock of the current slide ordering.
      $order = array(); //maybe this needs to have a fixed size
      $order[0] = -1;	//order[0] is the audio file, slide_id is not -1 but set it to -1
      $this->db->where('node_id', $node_id);
      $total_query = $this->db->get('slides');
      foreach ($total_query->result() as $row)
        $order[$row->seq_num] = $row->slide_id; //associate the slide with its order
      ksort($order); //why is this sorted?
      // Get our specific single row.
      $this->db->where('node_id', $node_id);
      $this->db->where('seq_num', $seq_num);
      $single_query = $this->db->get('slides');
      if ($single_query->num_rows() == 1) {
        $this_row = $single_query->row();
        
        // Delete Action
        if ($this->input->post('delete')) {
          // Shuffle the slide to be deleted to the end
          // of the ordering if it's not the audio slide.
          if ($this_row->seq_num > 0) {
            $o = $this_row->seq_num;
            $last = count($order) - 1;
            $this->reorder($o, $last, $order);
          }
          // Delete the slide from the database.
          $this->db->where('slide_id', $this_row->slide_id); //find slide_id, check that it exists
          $count_check = $this->db->count_all_results('slides'); //doing the select to make sure there is only one selected
          if ($count_check == 1) {
            // Delete the media files.
            $cwd = getcwd(); //current working directory is obtained
            unlink($cwd.'/'.$this_row->media_uri);//take our current working directory and remove the media by unlinking it from the database
            if ($this_row->seq_num > 0)  //since there is only a thumb_uri for the photos, and each photo has a sequence number >0 
              unlink($cwd.'/'.$this_row->thumb_uri); //unlink thumb_uri from that slot
            // Delete the database entry.
            $this->db->delete('slides', array('slide_id' => $this_row->slide_id));
            // Update the node and manifest files.
            $this->load->model('build_model');
            $this->build_model->build_node($node_id, $tour_uri);
            $this->build_model->build_dl($user_id, $tour_id);
            $this->build_model->manifesto($user_id, $tour_id);
            // Set the redirect slide.
            if ($this_row->seq_num > 0) {
              $redirect_slide = $this_row->seq_num;
              if ($this_row->seq_num == $last)
                $redirect_slide--;
            }
            else
              $redirect_slide = 0;
          }
        }
        // If the form is incomplete, show an error.
        elseif (!$form_data['complete']) {
          $message = 'Missing required fields.';
          $this->index($tour_id, $node_id, $seq_num, $form_data, $message, true);
        }
        else {
          // Reorder Action
          if ($this_row->seq_num !== $form_data['seq_num']) {
            $o = $this_row->seq_num;
            $n = $form_data['seq_num'];
            //if ($n > 0 && $n < count($order)) //why is this not an and? &&
            if ($n > 0 || $n < count($order)) //why is this not an and? &&
              $this->reorder($o, $n, $order);
          }
          // Caption Action
          if ($this_row->caption !== $form_data['caption']) {
            $this->db->where('slide_id', $this_row->slide_id);
            $count_check = $this->db->count_all_results('slides');
            if ($count_check == 1) {
              $this->db->where('slide_id', $this_row->slide_id);
              $this->db->update('slides', array('caption' => $form_data['caption']));
            }
          }
          // Update the node file.
          $this->load->model('build_model');
          $this->build_model->build_node($node_id, $tour_uri);
        }
      }
      $this->index($tour_id, $node_id, $redirect_slide, $form_data, 'Changes saved successfully!');
    }
    //reorder the slide
    private function reorder($o, $n, $order) {	//$n is the last spot
      $new_order = $this->swapify($o, $n, $order);
      for ($i = 0; $i < count($new_order); $i++) {
        $this->db->where('slide_id', $new_order[$i]); //what does this line do? query into the database to get what exactly? THIS is the error we're getting
        $count_check = $this->db->count_all_results('slides'); //what is the purpose for count_all_results? where is this function? How can we find it?
        if ($count_check == 1) { //i think this is where the error is
          $this->db->where('slide_id', $new_order[$i]);
          $this->db->update('slides', array('seq_num' => $i));
        }
      }
    }
    //julia swap function takes in $o, $n, $order but calls order $a, returns $a (the array of order) at the end to be the $new_order
    private function swapify($o, $n, $a) {
      if ($o > $n) {	//this will never happen, bc n is the last element of the array
        for ($i = $o; $i > $n; $i--) {
          $t = $a[$i];
          $a[$i] = $a[$i-1];
          $a[$i-1] = $t;
        }
      }
      elseif ($o < $n) { //if $o is less than $n
        for ($i = $o; $i < $n; $i++) { //set i = o and increment i until it = n
          $t = $a[$i]; //index into the array at i
          $a[$i] = $a[$i+1];
          $a[$i+1] = $t; //last error
        }
      }
      return $a; //if n = 11
    }
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
    private function verify_tour($tour_id) {
      $this->db->where('user_id', $this->session->userdata('user_id'));
      $this->db->where('tour_id', $tour_id);
      if (!$this->db->get('tours')->num_rows() == 1)
        redirect('cms/home');
    }
    private function verify_node($tour_id, $node_id) {
      $this->db->where('tour_id', $tour_id);
      $this->db->where('node_id', $node_id);
      if (!$this->db->get('nodes')->num_rows() == 1)
        redirect('cms/home');
    }
  }

?>