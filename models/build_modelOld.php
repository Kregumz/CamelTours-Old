<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Build_Model extends CI_Model
  {
    // Static CSS files.
    private $css = array(
      'media/css/swiper.css',
      'media/css/normalize.css',
      'media/css/app.css'
    );
    // Static icon files.
    private $ico  = array(
      'media/img/CamelToursIcon.ico'
    );
    // Static JS files.
    private $js = array(
      'media/js/vendor/jquery.js',
      'media/js/swiper.min.js',
      'media/js/app.js'
    );
    public function manifesto($user_id, $tour_id) {
      // Define our paths and files.
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/'; 
      $manifest_file = getcwd().'/'.$tour_uri.'dl/cache.manifest';
      // Get the node files that belong to this tour.
      $nodes = array();
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row)
          $nodes[$row->node_id] = $tour_uri.'n'.$row->node_id.'/';
      }
      // Get the media that belongs to this tour.
      $media = array();
      foreach ($nodes as $id => $node) {
        $this->db->where('node_id', $id);
        $query = $this->db->get('slides');
        if ($query->num_rows() > 0) {
          foreach ($query->result() as $row)
            $media[] = $row->media_uri;
        }
      }
      // Define the header and footer of our manifest file.
      $header = array("CACHE MANIFEST\n", "# ".time()."\n\n");
      $footer = array("\nNETWORK:\n", "*\n");
      // Define the array of other tour files to add.
      $misc = array(
        $tour_uri.'dl/',	
        $tour_uri	//putting the web page to the manifesto for caching, e.g http://cameltours.org/ct/u16/t10
      );
      // Merge our arrays together.
      $static = array_merge($this->css, $this->ico, $this->js);
      $specific = array_merge($misc, array_values($nodes), $media);
      $files = array_merge($static, $specific);
      // Add the base url and line breaks to each line.
      foreach ($files as &$line)
        $line = base_url().$line."\n";
      // Record how many files there are.
      $total_files = count($files) + 1;
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 1) {
        $this->db->where('tour_id', $tour_id);
        $this->db->update('tours', array('total_files' => $total_files));
      }
      // Add the header and the footer to the contents.
      $contents = array_merge($header, $files, $footer);
      // Create the manifest file.
      file_put_contents($manifest_file, $contents);
    }
    public function build_dl($user_id, $tour_id) {
      // Define our paths and files.
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      $download_file = '/'.$tour_uri.'dl/index.html';
      // Get the number of files.
      $total_files = 0;
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 1) {
        $row = $query->row();
        $total_files = $row->total_files;
      }
      $h   = array();
      $h[] = '<!DOCTYPE html>';
      $h[] = '<html manifest="'.base_url().'ct/u'.$user_id.'/t'.$tour_id.'/dl/cache.manifest">';
      $h[] = '  <head>';
      $h[] = '    <meta charset="utf-8">';
      $h[] = '    <meta name="viewport" content="initial-scale=1">';
      $h[] = '    <title>CamelTours</title>';
      foreach ($this->ico as $ico) {
        $h[] = '    <link rel="shortcut icon" href="'.base_url().$ico.'"/>';
      }
      foreach ($this->css as $css) {
        $h[] = '    <link rel="stylesheet" href="'.base_url().$css.'">';
      }
      $h[] = '  </head>';
      $h[] = '  <body>';
      $h[] = '    <div class="outer-centerer">';
      $h[] = '      <div class="middle-centerer">';
      $h[] = '        <div class="inner-centerer panel">';
      $h[] = '          <h4 id="main" class="loading-ellipsis">Please wait while the content of the tour downloads';
      $h[] = '            <span>.</span>';
      $h[] = '            <span>.</span>';
      $h[] = '            <span>.</span>';
      $h[] = '          </h4>';
      $h[] = '          <h5 id="sub"></h5><br>';
      $h[] = '          <div class="progress radius" id="loadbox">';
      $h[] = '            <span id="no-js" style="color: #333333;"><b> &nbsp; Uh oh! To view tours, you need to enable JavaScript.</b></span>';
      $h[] = '            <span class="meter" id="loadbar" style="width: 0%"></span>';
      $h[] = '          </div>';
      $h[] = '          <h4 id="help" style="display: none;"><a href="'.base_url().'help" target="_blank">Click here for additional help.</a></h4>';
      $h[] = '          <br>';
      $h[] = '          <p><i>Note:</i> <span id="note">If your browser prompts you to increase or use additional storage, select "Yes."</span></p>';
      $h[] = '        </div>';
      $h[] = '      </div>';
      $h[] = '    </div>';
      $h[] = '    <script>';
      $h[] = '      window.onload = function() {';
      $h[] = '        document.getElementById("no-js").style.display = "none"';
      $h[] = '      }';
      $h[] = '    </script>';
      $h[] = '    <script> var totalFiles = '.$total_files.'; </script>';
      $h[] = '    <script src="'.base_url().'media/js/cacher.js"></script>';
      $h[] = '  </body>';
      $h[] = '</html>';
      // Create the HTML string to write to file.
      $html = implode("\n", $h);
      // Write the string!
      file_put_contents($download_file, $html);
    }
    
    public function build_tour($user_id, $tour_id) {
      // Define our tour paths and files.
      $tour_uri = 'ct/u'.$user_id.'/t'.$tour_id.'/';
      $tour_file = getcwd().'/'.$tour_uri.'index.html';
      // Get the tour title.
      $title = '';
      //declare variables
      //$t_lat = array();
      //$t_long = array();
      
      $this->db->where('tour_id', $tour_id); //go to the location of the database with the tour id matching our tour id
      $query = $this->db->get('tours'); //get the tour infor
      if ($query->num_rows() == 1) {
        $title = $query->row()->tour_name;
	$t_lat = $query->row()->tour_lat;
	$t_long = $query->row()->tour_long;
	}

      /*$this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 3)
        $t_lat = $query->row()->tour_lat;
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() == 4)
        $t_long = $query->row()->tour_long;
      */
      // Grab all of the nodes.
      $nodes = array();
      $nodes_data_all = array();
      //variables for node_lat/node_long
      //$n_lats = array();
      //$n_longs = array();
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row){
          $nodes[$row->node_name] = $row->node_id; //ORIGINAL
          }
          
          //JUST ADDED
          //$n_lats[$row->node_name] = $row->node_lat;
          //$n_longs[$row->node_name] = $row->node_long;
          //$nodes[$row->node_name] = array("id"=>$row->node_id, "lat"=>$row->node_lat, "long"=>$row->node_long);
 
          //grab all node lat/long's
          //$n_lat[$row->node_lat] = $row->node_id;
          //$n_long[$row->node_long] = $row->node_id;
        ksort($nodes);
        
        //NEW CODES
        foreach ($query->result() as $row){
         $nodes_data_all[$row->node_name] = array($row->node_name, $row->node_lat, $row->node_long, $row->node_id, $row->tour_id, $row->node_location);
        }
      }
      $h   = array();
      $h[] = '<!DOCTYPE html>';
      $h[] = '  <head>';
      $h[] = '    <meta charset="utf-8">';
      $h[] = '    <meta name="viewport" content="initial-scale=1">';
      $h[] = '    <title>CamelTours</title>';
      foreach ($this->ico as $ico) {
        $h[] = '    <link rel="shortcut icon" href="'.base_url().$ico.'"/>';
      }
      foreach ($this->css as $css) {
        $h[] = '    <link rel="stylesheet" href="'.base_url().$css.'">';
      }
      $h[] = '  </head>';
      $h[] = '  <body>';
      $h[] = '    <div class="outer-centerer">';
      $h[] = '      <div class="middle-centerer">';
      $h[] = '        <div class="inner-centerer text-center">';
      $h[] = '          <h2>Welcome to '.$title.'!</h2>';
    //conditionals here to handle lat long == null
	if ($t_lat != null and $t_long != null) {
      
      //$h[] = '          <div id="map" style="width:100%; height:400px"></div>';
      
      
      //if the tour is Virginia's mural walk, include a static map		
      		if($tour_id == 10 && $user_id == 16){
      			$h[] = '<div id="mapHolder"><img src="http://cameltours.org/ct/u16/t10/map/map.PNG" alt="Static Map"></div>';
      			}		
      		else {
      		
      		
      $h[] = '          <div id="mapHolder"></div>';
      
      			}
      
      
      $h[] = '		<script>';
      /*
      var mapDiv = document.createElement("div");
      mapDiv.setAttribute("id","map");
      mapDiv.setAttribute("style","width:100%; height:400px");
      var mapHolder = document.getElementById("mapHolder");
      mapHolder.appendChild(mapDiv);
      
      */
      /*
      $h[] = '				var mapDiv = document.createElement("div");';
      $h[] = '				mapDiv.setAttribute("id","map");';
      $h[] = '				mapDiv.setAttribute("style","width:100%; height:400px");';
      $h[] = '				var mapHolder = document.getElementById("mapHolder");';
      $h[] = '				mapHolder.appendChild(mapDiv);';
      */
      
      $h[] = '		    function myMap() {';
      $h[] = '		    		var i = 1';
      $h[] = '				var mapHolder = document.getElementById("mapHolder");';
      
      //if this is Virginia's walking tour, remove the static map
      if($tour_id == 10 && $user_id == 16){
      $h[] = '				mapHolder.removeChild(mapHolder.childNodes[0]);';
      }
      
      
      $h[] = '				mapHolder.style.width ="100%"';
      $h[] = '				mapHolder.style.height ="400px"';
      $h[] = '				var mapDiv = document.createElement("div");';
      $h[] = '				mapDiv.setAttribute("id","map");';
      $h[] = '				mapDiv.style.width ="100%"';
      $h[] = '				mapDiv.style.height ="400px"';
      $h[] = '				mapHolder.appendChild(mapDiv);';
      
      
      $h[] = '				var mapOptions = {';
      $h[] = '		                	center: new google.maps.LatLng('.$t_lat.','.$t_long.'),'; 
      $h[] = '			        	zoom: 17';
      $h[] = '	             		};';
      $h[] = '				var map = new google.maps.Map(document.getElementById("map"), mapOptions);';

      //MAP INFO
       				foreach ($nodes_data_all as $node_data) {
      $h[] = '				var label1 = "'.substr($node_data[0],0,2).'"';
      $h[] = '				var label2 = (isNaN(parseInt(label1))) ? "" : label1;';
      $h[] = '				var marker = new google.maps.Marker({';
      $h[] = '          			position: new google.maps.LatLng('.$node_data[1].','.$node_data[2].'),label:label2,';
      $h[] = '					title:"'.$node_data[0].'"';
      $h[] = '				});';
      
      $h[] = '				marker.info = new google.maps.InfoWindow({';
      $h[] = ' 				content: "<a href=\"'.base_url().$tour_uri.'n'.$node_data[3].'\">'.$node_data[0].'</a>"';
      $h[] = '  			});';
      //event listener
      $h[] = '				marker.addListener("click", function() {';
      $h[] = '    			this.info.open(map, this);';
      $h[] = '  			});';
      //send info to map
      $h[] = '          		marker.setMap(map);'; 
      $h[] = '          		i++'; 
      
      //put each node's lat and long in a marker and place on the map
      				}
	$h[] = '			}';
	$h[] = '		</script>';
	$h[] = '		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZRfXTzzroSaw75H3RmUSAuO14haEZtHQ&callback=myMap"></script>';
	}
   
    //places stops on the tour
      if (count($nodes) > 0) {
        $h[] = '          <div class="node-list">';
        $h[] = '            <br><h4>Stops on this tour:</h4>';
        $h[] = '            <ul>';
        foreach ($nodes as $node_name => $node_id) {
          $h[] = '              <li><a href="'.base_url().$tour_uri.'n'.$node_id.'/'.'">'.$node_name.'</a></li>';
        }
        /*JUST ADDED
        
        foreach ($n_lats as $node_name => $node_lat) {
          $h[] = '              <li><p>The lat of '.$node_name.' is '.$node_lat.'</p></li>';
        }
        
        foreach ($n_longs as $node_name => $node_long) {
          $h[] = '              <li><p>The long of '.$node_name.' is '.$node_long.'</p></li>';
        }
	PAY ATTENTION TO THE JUST ADDED LINES        */
        
        
        $h[] = '            </ul>';
        $h[] = '          </div>';
      }
      else {
        $h[] = '          <br><p>No nodes found for this tour.</p>';        
      }
      $h[] = '          <br><br><h2><a href="http://cameltours.org/catalog">Click for Catalog</a></h2>';
      $h[] = '          <br><br><h2><a href="'.base_url().$tour_uri.'dl/">Download Tour for Offline Use</a></h2>';
      $h[] = '        </div>';
      $h[] = '      </div>';
      $h[] = '    </div>';
      foreach ($this->js as $js) {
        $h[] = '    <script src="'.base_url().$js.'"></script>';
      }
      $h[] = '  </body>';
      $h[] = '</html>';
      // Create the HTML string to write to file.
      $html = implode("\n", $h);
      // Write the string!
      file_put_contents($tour_file, $html);
    }
    /*
    get_map_contents($tour_id, 
    //<!--load libraries for google maps api-->
      $this->load->library('googlemaps');
      $h[] = '$config['center'] = '.$tour_lat.', '.$tour_long.'; //these need to be entered as a string
      $h[] = '$config['zoom'] = 'auto';
       //<!--initialize map with config-->
      $this->googlemaps->initialize($config);
      $h[] = '<div id="googleMap" style="width:100%;height:400px"></div>';
    */
    public function build_node($node_id, $tour_uri) {
      $images = array();
      $audio_url = null;
      // Get the node title.
      $title = '';
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() == 1)
        $title = $query->row()->node_name;
      // Get the slides and the media.
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('slides');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          if ($row->seq_num == 0)
            $audio_url = base_url().$row->media_uri;
          else {
            $images[$row->seq_num] = array(
              'url' => base_url().$row->media_uri,
              'caption' => $row->caption
            );
          }
        }
        ksort($images);
      }
      $h   = array();
      $h[] = '<!DOCTYPE html>';
      $h[] = '  <head>';
      $h[] = '    <meta charset="utf-8">';
      $h[] = '    <meta name="viewport" content="initial-scale=1">';
      $h[] = '    <title>CamelTours</title>';
      foreach ($this->ico as $ico) {
        $h[] = '    <link rel="shortcut icon" href="'.base_url().$ico.'"/>';
      }
      foreach ($this->css as $css) {
        $h[] = '    <link rel="stylesheet" href="'.base_url().$css.'">';
      }
      $h[] = '  </head>';
      $h[] = '  <body>';
      $h[] = '    <div id="loader" class="outer-centerer">';
      $h[] = '      <div class="middle-centerer">';
      $h[] = '        <div class="inner-centerer text-center">';
      $h[] = '          <h2>Please wait while your content loads...</h2>';
      $h[] = '        </div>';
      $h[] = '      </div>';
      $h[] = '    </div>';
      $h[] = '    <div class="swiper-container" style="visibility: hidden;">';
      if ($audio_url) {
        $h[] = '      <div class="audiobar">';
        $h[] = '        <audio id="node-audio" controls>';
        $h[] = '          <source src="'.$audio_url.'" type="audio/mpeg">';
        $h[] = '          Your browser does not support the audio tag.';
        $h[] = '        </audio>';
        $h[] = '      </div>';
      }
      $h[] = '      <div class="pagination"></div>';
      $h[] = '      <div class="swiper-wrapper">';
      $h[] = '        <div class="swiper-slide">';
      $h[] = '          <div class="inner">';
      $h[] = '            <div class="swiper-intro">';
      $h[] = '              <div class="intro-content">';
      $h[] = '                <br><br>';
      $h[] = '                <h3>'.$title.'</h3>';
      $h[] = '                <div class="callout-button">';
      if ($audio_url) {
        $h[] = '                  <h3><a id="node-control" class="button-border" href="#">Begin Audio</a></h3>';
      }
      elseif (count($images) > 0) {
        $h[] = '                  <h3><a id="node-control" class="button-border" href="#">Swipe! &#9755;</a></h3>';
      }
      else {
        $h[] = '                  <h3>No media found for this node.</h3>';
      }
      $h[] = '                </div>';
      $h[] = '                <a href="'.base_url().$tour_uri.'">Tour Home</a>';
      $h[] = '              </div>';
      $h[] = '            </div>';
      $h[] = '          </div>';
      $h[] = '        </div>';
      foreach ($images as $seq_num => $img) {
        $h[] = '        <div class="swiper-slide">';
        $h[] = '          <div class="inner">';
        $h[] = '            <img src="'.$img['url'].'">';
        $h[] = '            <div class="caption"> '.$img['caption'].' </div>';
        $h[] = '          </div>';
        $h[] = '        </div>';
      }
      $h[] = '      </div>';
      $h[] = '    </div>';
      foreach ($this->js as $js) {
        $h[] = '    <script src="'.base_url().$js.'"></script>';
      }
      $h[] = '  </body>';
      $h[] = '</html>';
      // Define the node index file.
      $node_file = getcwd().'/'.$tour_uri.'/n'.$node_id.'/index.html';
      // Create the HTML string to write to file.
      $html = implode("\n", $h);
      // Write the string!
      file_put_contents($node_file, $html);
    }
  }

?>