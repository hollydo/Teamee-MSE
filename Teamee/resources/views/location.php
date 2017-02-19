<?php
$pageName='Store Locations';
$logoImg='image/teameeLogo.png';
$logoID='logo';
include('header.php');
?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
    <title>Find Nearest Teamee Location</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

  </head>

  <body>
      <input id="pac-input" class="controls" placeholder="Enter a location">
      <span><input type="button" id="mapButton" value="  GO  " onclick="nearest()"></span>

    <div id="map"></div>
    <script>

        // Locations of each centers with their coordinates
        var loc= [
            {"name":"Westminster, CA","lat":33.7513, "lang":-117.9940},
            {"name":"Fullerton, CA","lat":33.8704,"lang":-117.9243},
        ];

        var Currentlat,CurrentLoc;
        var Outtermap,sautocomplete;
        var prevmarker;
      function initAutocomplete() {

        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 33.8025, lng: -117.9931}, //Set map to center on the United States
          zoom: 11, //zoom size just to show the United States
             scrollwheel: true,
            /* Placed Map Type Control in the botom left of the map */
             mapTypeControl: true,
             mapTypeControlOptions: {
             style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
             position: google.maps.ControlPosition.BOTTOM_LEFT
             },
         /* Style the map */
      styles: [
        {
          "featureType": "administrative.neighborhood",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels.text",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi.business",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels.icon",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "labels",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "labels",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.local",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "transit",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "labels.text",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        }
      ]
        });
          Outtermap=map;
          // Placed markers on each center location
          for(var i=0;i<loc.length;i++){
              var marker= new google.maps.Marker({
                  position: {lat:loc[i].lat, lng:loc[i].lang},
                  map: map
              });
              marker.addListener('click',function(){
				  getMarkerPosition(this.position);
              });

          }
        //When user clicks on a marker, it shows that center's infowindow with each center's unique contents
          function getMarkerPosition(s){

              var pos = {
                    lat: s.lat(),
                    lng: s.lng()
                    };
              var link;
              var centerAddress;
              var centerImg;
              var centerPhone;
              for(var i=0;i<loc.length;i++){
                  if((s.lat() == loc[i].lat) && (s.lng() == loc[i].lang)){
                      if(loc[i].name == "Westminster, CA" ){
                       link="#";
                       centerImg="<br><img src='image/123.png'><br>";
                       centerAddress="<br>1234 Main St <br> Westminster, CA 98765";
                       centerPhone="<br>(123) 456-7890";
                      }
                      else if(loc[i].name == "Fullerton, CA"){
                          link="#";
                          centerImg="<br><img src='image/IMG681519940b.jpg' style='width:180px;'><br>";
                          centerAddress="<br>4321 Main St <br> Fullerton, CA 12345";
                          centerPhone="<br>(987) 654-3210";
                      }

                    var infoWindow = new google.maps.InfoWindow({map: map});
					infoWindow.setPosition(pos);
                    infoWindow.setContent(loc[i].name+ centerImg + centerAddress + centerPhone + "<br><a href="+link+">Click Here to Visit</a>");
					infoWindows.push(infoWindow);
                  }
              }
          }
          var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

           autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
       var searchBox = new google.maps.places.SearchBox(input);
          searchBox.addListener('places_changed',function(){
              var geocoder = new google.maps.Geocoder();
            geocodeAddress(geocoder,map);
          });

          function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('pac-input').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            console.log(results[0].geometry.location.lat());
            console.log(results[0].geometry.location.lng());
            Currentlat=results[0].geometry.location.lat();
              CurrentLoc=results[0].geometry.location.lng();
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      }
      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }
        //below code from by galen(http://stackoverflow.com/questions/4057665/google-maps-api-v3-find-nearest-markers)
        //Find the closest center when user input their location and an infowindow popups open with that center's information
        function nearest(){
            var link;
             var q = new Array(loc.length);
             for(var i=0;i<loc.length;i++){
            var closest=getDistanceFromLatLonInKm(loc[i].lat,loc[i].lang,Currentlat,CurrentLoc);
                 q[i]=closest;
             }
           console.log(q);

            var min=Math.min.apply(null,q);
            for(var i=0;i<q.length;i++){
                if(q[i]==min){
                    var pos = {
                    lat: loc[i].lat,
                    lng: loc[i].lang
                    };
                    if(loc[i].name == "Westminster, CA" ){
                      link="#";
                      centerImg="<br><img src='image/123.png'><br>";
                      centerAddress="<br>1234 Main St <br> Westminster, CA 98765";
                      centerPhone="<br>(123) 456-7890";
                      }
                      else if(loc[i].name == "Fullerton, CA"){
                        link="#";
                        centerImg="<br><img src='image/IMG681519940b.jpg' style='width:180px;'><br>";
                        centerAddress="<br>4321 Main St <br> Fullerton, CA 12345";
                        centerPhone="<br>(987) 654-3210";
                      }
                    var infoWindow = new google.maps.InfoWindow({map: Outtermap});
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(loc[i].name+"\n:"+"This is your nearest store location"+ centerImg + centerAddress + centerPhone + "<br><a href="+link+">Click Here to visit</a>");

                }
            }
         }
        function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
            var R = 6371; // km
            //has a problem with the .toRad() method below.
            var x1 = lat2-lat1;
            var dLat = x1.toRad();
            var x2 = lon2-lon1;
            var dLon = x2.toRad();
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c;
            return d;

        }
        Number.prototype.toRad = function() {
            return this * Math.PI / 180;
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9T5mDnnWXmHM6mhRc44AgCrhxGtzhXAI&callback=initAutocomplete&libraries=places">
    </script>
  </body>
<!-- Footer -->
    <?php
        include('footer.php');
    ?>
<!-- End of footer -->