<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
  class Node extends CI_Controller
  {
    public function index($tour_id=-1, $node_id=-1, $form_data=array(), $error=null) {
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
      // Get existing files.
      list($result, $img_index) = $this->get_existing_files($node_id);
      // Set additional data parameters.
      $data['form_data'] = $form_data;
      $data['error'] = $error;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['tour_id'] = $tour_id;
      $data['node_id'] = $node_id;
      $data['existing_files'] = $result;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    public function send_form($tour_id=-1, $node_id=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Verify that the specified tour ID is valid.
      $this->verify_tour($tour_id);
      // Verify that the specified node ID is valid.
      $this->verify_node($tour_id, $node_id);
      // Load our image library.
      $this->load->library('image_lib');
      // Define the directory to upload files to.
      $cwd = getcwd().'/';
      $user_id = $this->session->userdata('user_id');
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      $media_dir_uri = $tour_uri.'n'.$node_id.'/media/';
      $thumb_dir_uri = $media_dir_uri.'thumbs/';
      // Create the needed directories if we don't already have them.
      if (!file_exists($cwd.$thumb_dir_uri))
        mkdir($cwd.$thumb_dir_uri, 0755, true);
      // Take stock of the files we already have.
      list($result, $img_index) = $this->get_existing_files($node_id);
      // If we have new files, upload them.
      if (!empty($_FILES)) {
        $seq_num = -1;
        $tempfile = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $filesize = $_FILES['file']['size'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        // Determine the sequence number.
        if (array_key_exists('seq_num', $_POST))
          $seq_num = $_POST['seq_num'];
        elseif (in_array($extension, array('jpg', 'jpeg', 'gif', 'png', 'bmp')))
          $seq_num = $img_index++;
        elseif ($extension == 'mp3')
          $seq_num = 0;
        // Set the server file basename.
        $basename = uniqid().'.'.$extension;
        // Do the uploading procedure!
        if ($seq_num !== -1 && $filesize > 0) {
          $media_file_uri = $media_dir_uri.$basename;
          $thumb_file_uri = $thumb_dir_uri.$basename;
          if (move_uploaded_file($tempfile, $cwd.$media_file_uri)) {
            // Get the size of the file.
            $filesize = filesize($cwd.$media_file_uri);
            // Set or create a thumbnail for the file.
            if ($extension == 'mp3')
              $thumb_file_uri = 'media/img/mp3thumb.png';
            else
              $this->create_thumb($cwd.$media_file_uri, $cwd.$thumb_file_uri);
            //Resize the image if necessary
            $this->resize_image($cwd.$media_file_uri);
            // If we have files with the same sequence number,
            // delete them. (This should only be audio files.)
            $this->load->model('delete_model');
            $this->db->where('node_id', $node_id);
            $this->db->where('seq_num', $seq_num);
            $seq_query = $this->db->get('slides');
            if ($seq_query->num_rows() > 0) {
              foreach ($seq_query->result() as $row) {
                $this->delete_model->delete_slide($row->slide_id);
              }
            }
            // Create the database entry.
            $data = array(
              'node_id' => $node_id,
              'seq_num' => $seq_num,
              'media_name' => $basename,
              'media_size' => $filesize,
              'media_uri' => $media_file_uri,
              'thumb_uri' => $thumb_file_uri
            );
            // Insert the entry into the slides table.
            $this->db->insert('slides', $data);
            // Update the manifest file.
            $this->load->model('build_model');
            $this->build_model->build_node($node_id, $tour_uri);
            $this->build_model->build_dl($user_id, $tour_id);
            $this->build_model->manifesto($user_id, $tour_id);
          }
        }
        // If the user used the fallback form, refresh the page.
        if ($this->input->post('unsupported')) {
          $this->index($tour_id, $node_id, array(), '');
        }
      }
      // Otherwise, echo what we do have.
      else {                                                           
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($result);
      }
    }
    private function get_existing_files($node_id) {
      $result = array();
      $img_index = 1;
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('slides');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $key => $row) {
          $obj['name'] = $row->media_name;
          $obj['size'] = $row->media_size;
          $obj['thmb'] = $row->thumb_uri;
          $obj['seqn'] = $row->seq_num;
          $result[] = $obj;
          if (pathinfo($obj['name'], PATHINFO_EXTENSION) != "mp3")
            $img_index++;
        }
        // Sort the result by sequence number.
        if (!function_exists('seqSort')){
            function seqSort($a, $b) { return $a['seqn'] - $b['seqn']; } //define the function if it has not been defined
        }
        usort($result, 'seqSort'); //without the if statement, this will not work on the second upload
      }
      return array($result, $img_index);
    }
    //create thumbnail
    private function create_thumb($media_file, $thumb_file) {
      $this->resize_image2($media_file, $thumb_file, 100, 100);
      $this->crop_image($thumb_file, $thumb_file, 100, 100);
    }
    private function resize_image2($source, $new, $width, $height) {
      // Get the width and height of the source image.
      list($w, $h) = getimagesize($source);
      // Determine the master axis.
      $master_dim = ($w > $h) ? 'height' : 'width';
      // Set our configuration array.
      $resize_config = array(
        'image_library' => 'GD2',
        'source_image' => $source,
        'new_image' => $new,
        'quality' => 100,
        'width' => $width,
        'height' => $height,
        'create_thumb' => false,
        'maintain_ratio' => true,
        'master_dim' => $master_dim
      );
      // Initialize, resize, and clean up.
      $this->image_lib->initialize($resize_config);
      $this->image_lib->resize();
      $this->image_lib->clear();
      unset($resize_config);
    }   
    private function crop_image($source, $new, $width, $height) {
      // Get the width and height of the source image.
      list($w, $h) = getimagesize($source);
      // Determine the x- and y-axis for cropping.
      $x_axis = 0;
      $y_axis = 0;
      if ($w > $width)
        $x_axis = (($w - $width) / 2);
      elseif ($h > $height)
        $y_axis = (($h - $height) / 2);
      // Set our configuration array.
      $crop_config = array(
        'image_library' => 'GD2',
        'source_image' => $source,
        'new_image' => $new,
        'quality' => 100,
        'width' => $width,
        'height' => $height,
        'create_thumb' => false,
        'maintain_ratio' => false,
        'x_axis' => $x_axis,
        'y_axis' => $y_axis
      );
      // Initialize, crop, and clean up.
      $this->image_lib->initialize($crop_config);
      $this->image_lib->crop();
      $this->image_lib->clear();
      unset($crop_config);
    }
    //resize image if it's oversize to reduce load time
    private function resize_image($source) {
      // Get the width and height of the source image.
      list($w, $h) = getimagesize($source);
      // Determine the master axis.
      $master_dim = ($w > $h) ? 'height' : 'width';
      // Set our configuration array.
        /*
        max width = 1920
        max height = 1080
        if width>1920:
            new width = 1920
            new height = 1920/new width*height
            width = new width
            height = new height
        if height>1080
            new height = 1080
            new width = 1080/new height*width
            width = new width
            height = new height
        */
      define ("max_w", 1920);
      define ("max_h", 1080);
      if ($w > max_w){
          $new_w = max_w;
          $new_h = max_w/$new_w*$h;
          $w = $new_w; 
          $h = $new_h;
      }
        
      if ($h > max_h){
          $new_h = max_h;
          $new_w = max_h/$new_h*$w;
          $w = $new_w; 
          $h = $new_h;
      }
            
      $resize_config = array(
        'image_library' => 'gd2',
        'source_image' => $source,
        'new_image' => $source,
        'quality' => 90,
        'width' => $w,
        'height' => $h,
        'create_thumb' => false,
        'maintain_ratio' => false,
        'master_dim' => $master_dim
      );
      // Initialize, resize, and clean up.
      $this->image_lib->initialize($resize_config);
      $this->image_lib->resize();
      $this->image_lib->clear();
      unset($resize_config);
    }  
    //insert function to compress images and audio files TO DO
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