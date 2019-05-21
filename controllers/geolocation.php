<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Geolocation extends CI_Controller
  {
    public function index() {
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();
      
      //<!--Load our templates and the view for this page.-->
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view($page, $data);
      $this->load->view('templates/footer', $data);
    }
      
    public function send_form(){
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');

        $this->db->where('node_lat !=','null');
        $cmd = "SQRT((node_long-".$long.")*(node_long-".$long.")+(node_lat-".$lat.")*(node_lat-".$lat."))";
        $this->db->order_by($cmd, "ASC");
        $this->db->limit(5);
        $query=$this->db->get('nodes');
        $i = 0;
        
        foreach ($query->result() as $node){
            $node_lat = $node->node_lat;
            $node_long = $node->node_long;
            $tour_id = $node->tour_id;
            $dist = $this->latLonToMiles($lat, $long, $node_lat, $node_long);
            
            $this->db->where('tour_id', $tour_id);
            $query2 = $this->db->get('tours');

            foreach ($query2->result() as $row){
                $link = 'ct/u'.$row->user_id.'/t'.$row->tour_id.'/n'.$node->node_id; 
                $link2 = 'ct/u'.$row->user_id.'/t'.$row->tour_id; 
            }
            
            echo '<br><a href="'.$link.'">'.$node->node_name.'</a> in the tour <a href="'.$link2.'">'.$row->tour_name.'</a> distance: '.$dist.' (miles). Open in <a href="https://www.google.com/maps/search/?api=1&query='.$node_lat.','.$node_long.'">Google Maps</a>';
            if ($i == 0){
                echo '  (Direction shows on map)';
            }
            echo '<br>';
            $i++;
        }
    }
    
    public function map(){
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
        
        $this->db->where('node_lat !=','null');
        $cmd = "SQRT((node_long-".$long.")*(node_long-".$long.")+(node_lat-".$lat.")*(node_lat-".$lat."))";
        $this->db->order_by($cmd, "ASC");
        $this->db->limit(5);
        $query=$this->db->get('nodes');
        
        $dom = new DOMDocument("1.0","UTF-8");
        $nodeElement = $dom->createElement("markers");
        $nodeElement = $dom->appendChild($nodeElement);

        header("Content-type: text/xml");
        
        foreach ($query->result() as $node){
            $newnode = $dom->createElement("marker");
            $newnode = $nodeElement->appendChild($newnode);
            $newnode->setAttribute('id', $node->node_id);
            $newnode->setAttribute('node_lat', $node->node_lat);
            $newnode->setAttribute('node_long', $node->node_long);
            $newnode->setAttribute('node_name', $node->node_name);
            
            $this->db->where('tour_id', $node->tour_id);
            $query2 = $this->db->get('tours');

            foreach ($query2->result() as $row){
                $link = 'ct/u'.$row->user_id.'/t'.$row->tour_id.'/n'.$node->node_id;  
            }
            $newnode->setAttribute('link', $link);
        }
        
        echo $dom->saveXML();
    }
    
    private function latLonToMiles($lat1, $lon1, $lat2, $lon2){  //haversine formula
      $R = 3961;  // radius of the Earth in miles
      $dlon = ($lon2 - $lon1)*M_PI/180;
      $dlat = ($lat2 - $lat1)*M_PI/180;
      $lat1 *= M_PI/180;
      $lat2 *= M_PI/180;
      $a = pow(sin($dlat/2),2) + cos($lat1) * cos($lat2) * pow(sin($dlon/2),2) ;
      $c = 2 * atan2( sqrt($a), sqrt(1-$a) ) ;
      $d = number_format($R * $c, 2);
      return $d;
    }
  }

?>