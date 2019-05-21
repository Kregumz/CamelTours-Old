<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Home extends CI_Controller
  {
    public function index($active_tour=-1) {
      // Boot the user if they are not logged in.
      $this->check_session();
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/cms/'.$page.'.php'))
        show_404();
      // Gets the users tours.
      $tours = array();
      $this->db->where('user_id', $this->session->userdata('user_id'));
      $tour_query = $this->db->get('tours');
      if ($tour_query->num_rows() > 0) {
        foreach ($tour_query->result() as $tour_row) {
          // Gets the nodes of each tour.
          $nodes = array();
          $this->db->where('tour_id', $tour_row->tour_id);
          $node_query = $this->db->get('nodes');
          if ($node_query->num_rows() > 0) {
            foreach ($node_query->result() as $node_row) {
              // Gets a cover slide and caption for each node.
              $caption = '';
              $node_name = $node_row->node_name;
              $cover = base_url().'media/img/thumb.png';
              $this->db->where('node_id', $node_row->node_id);
              $this->db->where('seq_num', '1');
              $cover_query = $this->db->get('slides');
              if ($cover_query->num_rows() == 1)
                $cover = base_url().$cover_query->row()->thumb_uri;
              else
                $caption = $node_name;
              $nodes[$node_row->node_id] = array (
                'caption' => $caption,
                'cover' => $cover,
                'name' => $node_name
              );
            }
            ksort($nodes);
          }
          $tours[$tour_row->tour_id] = array(
            'name' => $tour_row->tour_name,
            'nodes' => $nodes
          );
        }
        ksort($tours);
      }
      // Set additional data parameters.
      $data['tours'] = $tours;
      $data['active_tour'] = $active_tour;
      // Load our templates and the view for this page.
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view('cms/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }
    private function check_session() {
      if (!$this->session->userdata('validated'))
        redirect('login');
    }
  }

?>