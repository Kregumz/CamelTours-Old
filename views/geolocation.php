    <!-- Page Body-->
    <br>
    <div class="row">
      <div class="small-11 medium-11 large-11 columns small-centered medium-centered large-centered">
        <div class="row">
        	<h3>Find CamelTours Destinations Near You!</h3>
        	<p>Enable geolocation if your browser prompts you to.</p>
        </div>
          
        <div id="mapHolder"></div>  
        <div id="output"></div>

        <script>
            var x = document.getElementById("output");
            //Called by button click; retrieves coordinates
            function getLocation(){
                if (navigator.geolocation)
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                else
                    x.innerHTML = "Geolocation is not supported by this browser.";
            }

            function showError(error){
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        x.innerHTML="User denied the request for Geolocation."
                        break;
                    case error.POSITION_UNAVAILABLE:
                        x.innerHTML="Location information is unavailable."
                        break;
                    case error.TIMEOUT:
                        x.innerHTML="The request to get user location timed out."
                        break;
                    case error.UNKNOWN_ERROR:
                        x.innerHTML="An unknown error occurred."
                        break;
                }
            }

            function showPosition(position){
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                //create buttons to add community
                addCommunity(lat, lon);
                initMap(lat, lon);
            }

            function addCommunity(lat,lon){
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        var output = document.getElementById("output");
                        output.innerHTML = xmlhttp.responseText;
                        output.style.marginBottom ="30px";
                    }

                };
                <?php 
                    echo 'var url = "'.base_url().'geolocation/send_form?lat="+lat.toString()+"&long="+lon.toString();';
                ?>
                xmlhttp.open("GET",url,true);
                xmlhttp.send();
            } 
            
            function initMap(lat,lon, zoom){
                var mapHolder = document.getElementById("mapHolder");
                mapHolder.style.height ="310px";
                mapHolder.style.marginBottom ="30px";
                
                var myLatlng = new google.maps.LatLng(lat, lon);
                var directionsService = new google.maps.DirectionsService();
                var directionsDisplay = new google.maps.DirectionsRenderer();
                var mapOptions = {
                    zoom: 15,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: true, //allows you to select map type eg. map or satellite
                    navigationControlOptions:{style: google.maps.NavigationControlStyle.SMALL},
                }
                var map = new google.maps.Map(document.getElementById('mapHolder'), mapOptions);
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('panel'));
                var infoWindow = new google.maps.InfoWindow;
                 // marker
                var marker = new google.maps.Marker({ position: myLatlng, map: map, title: 'marker'});

                 // information window
                var infowindow = new google.maps.InfoWindow({
                  content: "You are here"
                  });

                  // Eventlistener for the information window
                infowindow.open(map,marker);

                <?php 
                    echo 'var page = "'.base_url().'geolocation/map?";';
                ?>
                downloadUrl(page, lat, lon, function(data){
                        var xml = data.responseXML;
                        var markers = xml.documentElement.getElementsByTagName('marker');
                        console.log(xml);
                        /* test code
                        console.log(markers[0]);
                        console.log(markers[1]);
                        console.log(markers[2]);
                        */
                        Array.prototype.forEach.call(markers, function(markerElem) {
                            var id = markerElem.getAttribute('id');
                            var name = markerElem.getAttribute('node_name');

                            var point = new google.maps.LatLng(
                                parseFloat(markerElem.getAttribute('node_lat')),
                                parseFloat(markerElem.getAttribute('node_long')));

                            marker = new google.maps.Marker({
                                position: point
                            });

                            infowincontent = document.createElement('div');
                            var strong = document.createElement('strong');

                            infowincontent.appendChild(strong);
                            infowincontent.appendChild(document.createElement('br'));
                            
                            var link = document.createElement('A');
                            link.setAttribute("href", markerElem.getAttribute('link'));
                            
                            var text = document.createElement('text');
                            text.textContent = name;
                            link.appendChild(text);
                            strong.appendChild(link);
                            
                            marker.infowindow = new google.maps.InfoWindow({content: infowincontent});
                            marker.infowindow.open(map, marker);

                            marker.addListener('click', function() {
                                this.infowindow.open(map, this);
                            });
                            marker.setMap(map);
                        });
                    
                    var point = new google.maps.LatLng(
                                parseFloat(markers[0].getAttribute('node_lat')),
                                parseFloat(markers[0].getAttribute('node_long')));
                    
                    var request = {
                        origin: myLatlng,
                        destination: point,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING};
                    
                    directionsService.route(request, function (response, status) {
                       if (status == google.maps.DirectionsStatus.OK) {
                         directionsDisplay.setDirections(response);
                       }
                    });
                });
            };

            function downloadUrl(url, lat, lon, callback) {
                var request = window.ActiveXObject ?
                    new ActiveXObject('Microsoft.XMLHTTP') :
                    new XMLHttpRequest;

                request.onreadystatechange = function() {
                  if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                  }
                };

                url += "lat=" + lat.toString();
                url += "&long=" + lon.toString();

                request.open('GET', url, true);
                request.send(null);
            }

            function doNothing() {}
      </script>      
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0McuBn_IhaHzTNE3zSHYC8CtfRsADLvk"></script>
        <div  style="text-align: center;"><button onclick="getLocation()">Find Destinations Near Me</button>
        </div>
       
      </div>
   </div>

	