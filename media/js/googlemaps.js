      /*
      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };
      */

        /*
        var infoWindow = new google.maps.InfoWindow; //initiates marker for info

          // Change this depending on the name of your PHP or XML file
          downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker'); //array of everything that has a tag <marker>
            Array.prototype.forEach.call(markers, function(markerElem) { //for data type array add a new method called forEach element in the array perform the fuction, first parameter will replace the second 
              var name = markerElem.getAttribute('node_name');
              var address = markerElem.getAttribute('node_location');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('node_lat')),
                  parseFloat(markerElem.getAttribute('node_long')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong'); //in strong tag will be name
              strong.textContent = name //should this have a semicolon after it
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br')); //line break

              var text = document.createElement('text');
              text.textContent = address //should this have a semicolon after it
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({ //create new pin or marker obj
                map: map,
                position: point, //made up of node_lat and node_long
                label: icon.label //instead of making it label R or B we will change color based on 
              });
              marker.addListener('click', function() { //add event listener so if the pin is clicked...
                infoWindow.setContent(infowincontent); //offer info
                infoWindow.open(map, marker);
                //if this is clicked we will concentrate on this tour by zooming in to level 15, show all stops of that tour, and make other tours invisible
              });
            });
          });
        }
        
        function initMap() { //creates map
        var map = new google.maps.Map(document.getElementById('map'), {
        //pass in center, which is tour_lat and tour_long
          center: new google.maps.LatLng(41.3557, -72.0995), //how do we get this to integrate?
          zoom: 15 //zoom is set to street level
        });

      function downloadUrl(url, callback) { //pass in XML in the URL
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest; //initiate XMLHTTP request

        request.onreadystatechange = function() { //REQUEST has five states as documented in google doc
          if (request.readyState == 4) { //state 4 means XML HTTP request has concluded
            request.onreadystatechange = doNothing;
            callback(request, request.status); //request is the XML HTTP request
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
   */
    async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaMAC8kPZ9TPt7gtps6gb18QHnfCpVvbk&callback=initMap" //load js file from this link
   
  