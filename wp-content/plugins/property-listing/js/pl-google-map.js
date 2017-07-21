function initMap() {
    lat = 0;
    long = 0;
    if (typeof my_coordinates !== 'undefined' && my_coordinates.lat && my_coordinates.long) {
        lat = my_coordinates.lat;
        long = my_coordinates.long;
    }
    var mapProp = {
      center: new google.maps.LatLng(lat, long),
      zoom: 12,
      mapTypeId: google.maps.MapTypeId.ROADMAP
      };
    var map = new google.maps.Map(document.getElementById("pl-map"), mapProp);

          var marker = new google.maps.Marker({
          position: new google.maps.LatLng(lat, long),
          map: map
        });


}

function initMaps() {
        var uluru = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('pl-map'), {
          zoom: 4,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
