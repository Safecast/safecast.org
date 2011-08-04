window.addEventListener?window.addEventListener("load",initialize,false):window.attachEvent("onload",initialize);

var geocoder;
var map;
var cities = [
  ['Soma', 37.795, 140.86, 4],
  ['Sendai', 38.27, 140.81, 1],
  ['Natori', 38.170, 140.835, 3],
  ['MinamiSoma', 37.64, 140.9, 2],
];


function setMarkers(map, locations) {
  // Add markers to the map
 
  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.
 
  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.
  var image = new google.maps.MarkerImage('../images/black_pin.png',
      // This marker is 20 pixels wide by 32 pixels tall.
      new google.maps.Size(20, 32),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is the base of the flagpole at 0,32.
      new google.maps.Point(0, 32));
  var shadow = new google.maps.MarkerImage('../images/shadow.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(37, 32),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 32));
      // Shapes define the clickable region of the icon.
      // The type defines an HTML &lt;area&gt; element 'poly' which
      // traces out a polygon as a series of X,Y points. The final
      // coordinate closes the poly by connecting to the first
      // coordinate.
  var shape = {
      coord: [1, 1, 1, 20, 18, 20, 18 , 1],
      type: 'poly'
  };
  for (var i = 0; i < locations.length; i++) {
    var beach = locations[i];
    var myLatLng = new google.maps.LatLng(cities[1], cities[2]);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        shadow: shadow,
        icon: image,
        //shape: shape,
        title: cities[0],
        zIndex: cities[3]
    });
    attachSecretMessage(marker, cities[0]);
  }
} 

function attachSecretMessage(marker, message) {
  var infowindow = new google.maps.InfoWindow(
      { content: message
      });
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });
}
/*
function initialize() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(38.100, 140.844);
	var myOptions = {
		  zoom: 8,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    var georssLayer = new google.maps.KmlLayer('http://rdtn.org/feeds/georss_sample.xml');
    georssLayer.setMap(map);
}
function initialize() {
  var myLatlng = new google.maps.LatLng(38.100, 140.844);
  var myOptions = {
    zoom: 6,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
 
  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
 
  var georssLayer = new google.maps.KmlLayer('http://rdtn.org/feeds/georss_sample.xml');
  georssLayer.setMap(map);
}
*/

function initialize() {
  var myLatlng = new google.maps.LatLng(38.100, 140.844);
  var myOptions = {
    zoom: 4,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
 
  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
 
  var georssLayer = new google.maps.KmlLayer('http://www.rdtn.org/feeds/georss_sample.xml');
  georssLayer.setMap(map);
}

function codeAddress() {
	var address = document.getElementById("address").value;
	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		var marker = new google.maps.Marker({
			map: map, 
			position: results[0].geometry.location
		});
	  } else {
		alert("Geocode was not successful for the following reason: " + status);
	  }
	});
}
