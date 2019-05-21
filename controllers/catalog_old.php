<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Catalog_Old extends CI_Controller
  {
    public function index() {
      // Get the name of the page from the class.
      $data['title'] = get_class($this);
      $page = strtolower($data['title']);
      // Make sure that the view for this page exists!
      if (!file_exists(APPPATH.'/views/'.$page.'.php'))
        show_404();
      
      //<!--load libraries for google maps api-->
      $this->load->library('googlemaps');
      
      //<!--config center to average of highest&lowest lat/long-->
      $config['center'] = '41.3311485, -72.3895955';
      $config['zoom'] = 'auto';
      //<!--initialize map with config-->
      $this->googlemaps->initialize($config);
      
      //<!--config marker-->
      //$marker = array();
      $marker['position'] = '41.284101, -72.684537'; //Fair Street Tour
      $marker['infowindow_content']="Fair St. CamelTour";
      $this->googlemaps->add_marker($marker);
      $marker['position'] = '41.283867, -72.681522'; //Historic Building Guilford Tour
      $marker['infowindow_content']="Historic Downtown Guilford Tour";
      $this->googlemaps->add_marker($marker);
      $marker['position'] = '41.287041, -72.667720'; //Alderbrook Cemetery Tour
      $marker['infowindow_content']="Alderbrook Cemetery Tour";
      $this->googlemaps->add_marker($marker);
      $marker['position'] = '41.353045, -72.094654'; //Hygienic New London
      $marker['infowindow_content']="Streetside Interviews with New London Mural Walk Artists";
      $this->googlemaps->add_marker($marker);
      $marker['position'] = '41.374616, -72.102841'; //office of sus
      $marker['infowindow_content']="Connecticut College Office of Sustainability Tour";
      $this->googlemaps->add_marker($marker);
      $marker['position'] = '41.378430, -72.109132'; // CC Arbo
      //$marker['icon'] = 'https://cdn4.iconfinder.com/data/icons/new-google-logo-2015/400/new-google-favicon-128.png';
      //$marker['icon_scaledSize'] = '40,40';
      //$marker['animation'] = 'BOUNCE';
      $marker['infowindow_content']="Connecticut College Arboretum Tour";
      $this->googlemaps->add_marker($marker);
      //new london
      $marker['position'] = '41.352569, -72.095474'; //133 Bank St. New London, CT
      $marker['infowindow_content']="Historic Waterfront District Heritage Trail";
      $this->googlemaps->add_marker($marker);
   
      //<!--create_map return two things (javascript in js variable and map in html variable) -->
      $data['map'] =  $this->googlemaps->create_map();
      
      
      //<!--Load our templates and the view for this page.-->
      $this->load->view('templates/header', $data);
      $this->load->view('templates/navigation', $data);
      $this->load->view($page, $data);
      $this->load->view('templates/footer', $data);
    }
  }

?>