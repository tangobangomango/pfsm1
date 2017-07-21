/*
 * Used to create Google Map of property location.
 * Assumes a use of ACF with values 'town' and 'province' and country of 'Italy'
 * Allows input in form 'town province'
 * Input comes from property-listings.php custom post type in functions pl_enqueue_google_maps() and pl_register_maps_script()
 * Function name initMap() needs to match callback in script in pl_enqueue_google_maps()
 * Code here relies heavily on https://stackoverflow.com/questions/15925980/using-address-instead-of-longitude-and-latitude-with-google-maps-api
 * Good resource here for custom markers available http://kml4earth.appspot.com/index.html
 */




function initMap() {
  var address = "San Ginesio, MC, Italy";
  var label = "Property for Sale Marche";

 

    if (typeof my_location !== 'undefined') {
        
        address = my_location.address;
        label = my_location.label;
    } 

    

  var geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var myOptions = {
    zoom: 10,
    center: latlng,
    mapTypeControl: true,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    },
    navigationControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  

  var map = new google.maps.Map(document.getElementById("pl-map"), myOptions);
  if (geocoder) {
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);

          var infowindow = new google.maps.InfoWindow({
            content: '<b>' + label + '</b>',
            size: new google.maps.Size(150, 50)
          });

          var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
  
          var marker = new google.maps.Marker({
            position: results[0].geometry.location,
            map: map,
            title: label,
            icon: 'http://test.edkatzman.com/wp-content/uploads/2017/07/Avventura-home-map-pin.png'
          });
          google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
          });

        } else {
          alert("No results found");
        }
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
}