$(document).ready(function()
{
	initialize_map();
});


//
// fusionMap.js
// Originally coded 2011-07 by Kalin KOZHUHAROV <kalin@thinrope.net> for http://thinrope.net/
//

var map			= null;
var zoom_level		= 7;
var layer_count		= 1;
var fusion_layer	= null;
var fusion_listener	= null;
var tables_A		= new Array("1355535", "1355910", "1355515", "1355439");
var tables_B		= new Array("1403955", "1403954", "1403953", "1403581");
var tables		= tables_B;
var tables_updated	= "2011-09-04";
var table		= tables[0];
var geocoder		= null;
var params		= {h: '100%', w: '100%'};

function initialize_map()
{
	var lat = null;
	if(!location.search.match(/\?lat=([0-9]+.[0-9]+)/)) {
		lat = 36.94111143010772;
		lon = 140.60302734375;
	} else {
		lat = location.search.match(/\?lat=([0-9]+.[0-9]+)/)[1];
		lon = location.search.match(/\&lon=([0-9]+.[0-9]+)/)[1];
		zoom_level = 10;
	}
	map = new google.maps.Map(
		document.getElementById('fusion_canvas'),
		{ center: new google.maps.LatLng(lat, lon ),
			zoom: zoom_level,
			zoomControl: true,
			panControl: false,
			scaleControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			scaleControlOptions: { position: google.maps.ControlPosition.TOP_CENTER },
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
	geocoder = new google.maps.Geocoder();
	var style = [{ featureType: 'all', elementType: 'all', stylers: [ { saturation: -69 } ]}];
	var styledMapType = new google.maps.StyledMapType(style, { map: map, name: 'Styled Map' });
	map.mapTypes.set('map-style', styledMapType);
	map.setMapTypeId('map-style');

	var NPS = new google.maps.LatLng(37.425252, 141.033247);
	var area_30km = new google.maps.Circle( { map: map, center: NPS, fillColor: '#ff0000', fillOpacity: 0.2, strokeColor: '#ff0000', strokeOpacity: 0.8, strokeWeight: 1, radius: 30000 });
	var area_20km = new google.maps.Circle( { map: map, center: NPS, fillColor: '#ff0000', fillOpacity: 0.3, strokeColor: '#ff0000', strokeOpacity: 0.8, strokeWeight: 1, radius: 20000 });

	window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) { params[key] = value; });
	document.getElementById('safecast_map').style.height = params['h'];
	document.getElementById('safecast_map').style.width = params['w'];

	change_map();
}

function change_map()
{
	update_info(null);	// grey-out the div initially
	zoom = map.getZoom();	// update current zoom
	document.getElementById('info').innerHTML = '<p ><b>zoom: ' + zoom +
		'</b><br /><br />Please click on any marker to see its reading.</p>';
	document.getElementById('dateUpdated').innerHTML = 'Dataset last updated: <b>' + tables_updated + '</b><br />'; 
	if (fusion_layer)
	{
		fusion_layer.setMap(null);
		fusion_layer = null;
	}
	if (zoom < 6)
	{
		table = tables[0];
	}
	else if (zoom <= 9)
	{
		table = tables[1];
	}
	else if (zoom <= 12)
	{
		table = tables[2];
	}
	else if (zoom > 12)
	{
		table = tables[3];
	}
	else
	{
		alert("we shouldn't be here");
	}

	fusion_layer = new google.maps.FusionTablesLayer({ query: {select: 'lat_lon', from: table, where: ""} });
	fusion_layer.setOptions({ suppressInfoWindows : true});
	fusion_listener = google.maps.event.addListener(fusion_layer, 'click', function(e) { update_info(e);});
	google.maps.event.addListenerOnce(map, 'zoom_changed', function() { if (zoom != map.getZoom()) { change_map(); }; });

	fusion_layer.setMap(map);
}

function update_info(e)
{
	document.getElementById("addr").value = "Go to...";
	var info_div = document.getElementById('info');
	if (e)
	{
		info_div.innerHTML = e.infoWindowHtml;
		//var DRE = e.infoWindowHtml.match(/([.0-9]+) μSv\/h/)[1];
		var DRE = parseFloat(e.row.DRE.value);
		if (DRE <= 0.2) 
			{document.getElementById("info").style.backgroundColor='#99ff99';}
		else if (DRE <= 0.5)
			{document.getElementById("info").style.backgroundColor='#ffff99';}
		else if (DRE <= 1.0)
			{document.getElementById("info").style.backgroundColor='#ff99ff';}
		else if (DRE <= 5.0)
			{document.getElementById("info").style.backgroundColor='#9999ff';}
		else if (DRE <= 10.0)
			{document.getElementById("info").style.backgroundColor='#ff6666';}
		else
			{document.getElementById("info").style.backgroundColor='red';}
	}
	else
	{
		document.getElementById("info").style.backgroundColor='white';
		info_div.innerHTML = '';
	}
}

function go_back()
{
	var info_div = document.getElementById('info');
	var ll = info_div.innerHTML.toString().match(/\(([\+\-\.0-9]+ [\+\-\.0-9]+)\)/)[1].split(/ /);
	var coord = new google.maps.LatLng(ll[0], ll[1]);
	map.setCenter(coord);
	return true;
}

function center_map()
{
	var addr = document.getElementById("addr").value;
	go_to(addr);
}

function go_to(place)
{
	geocoder.geocode( {'address': place, 'region': "jp"}, function (results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
				//var marker = new google.maps.Marker( { map: map, position: results[0].geometry.location } );
				//map.setCenter(results[0].geometry.location);
				//map.setZoom(10);
				map.fitBounds(results[0].geometry.viewport);
			 }
			else
				{ alert ("Cannot find " + addr + "! Status: " + status); }
		});
}
