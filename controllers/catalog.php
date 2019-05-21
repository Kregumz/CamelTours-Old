<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Catalog extends CI_Controller
  {
    public function index() {
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();

        $data['tours']=[];

        $this->db->where('tour_public', 1);
        $query = $this->db->get('tours');


        foreach ($query->result() as $row){
            $link = ''.base_url().'ct/u'.$row->user_id.'/t'.$row->tour_id.'/';
            $this->db->where('tour_id', $row->tour_id);
            $query1 = $this->db->get('nodes');
            $node_id = $query1->first_row()->node_id;
            $this->db->where('node_id', $node_id);
            $query2 = $this->db->order_by('seq_num','desc')->get('slides');
            $img_url = $query2->first_row()->media_uri;
            $data['tours'][] = array($link, $row->tour_name, $row->tour_location, $row->tour_lat, $row->tour_long, $row->tour_id, $img_url);
        }

      //<!--Load our templates and the view for this page.-->
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view($page, $data);
      $this->load->view('templates/footer', $data);
    }
  }

?>
