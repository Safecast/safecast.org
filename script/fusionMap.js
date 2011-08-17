var map			= null;
var zoom_level		= 7;
var layer_count		= 1;
var fusion_layer	= null;
var fusion_listener	= null;
var tables		= new Array("1290203", "1289664", "1289840");
var table		= "1290203";

$(document).ready(function()
{
	initialize_map();
});


function initialize_map()
{
	map = new google.maps.Map(
		document.getElementById('fusion_canvas'),
		{ center: new google.maps.LatLng(36.94111143010772, 140.60302734375),
			zoom: zoom_level,
			zoomControl: true,
			panControl: false,
			scaleControl: true,
			mapTypeControl: false,
			scaleControlOptions: { position: google.maps.ControlPosition.TOP_CENTER },
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
	var style = [{ featureType: 'all', elementType: 'all', stylers: [ { saturation: -69 } ]}];
	var styledMapType = new google.maps.StyledMapType(style, { map: map, name: 'Styled Map' });
	map.mapTypes.set('map-style', styledMapType);
	map.setMapTypeId('map-style');

	var NPS = new google.maps.LatLng(37.425252, 141.033247);
	var area_30km = new google.maps.Circle( { map: map, center: NPS, fillColor: '#ff0000', fillOpacity: 0.2, strokeColor: '#ff0000', strokeOpacity: 0.8, strokeWeight: 1, radius: 30000 });
	var area_20km = new google.maps.Circle( { map: map, center: NPS, fillColor: '#ff0000', fillOpacity: 0.3, strokeColor: '#ff0000', strokeOpacity: 0.8, strokeWeight: 1, radius: 20000 });

	change_map();
}

function change_map()
{
	update_info(null);	// grey-out the div initially
	zoom = map.getZoom();	// update current zoom
	document.getElementById('info').innerHTML = "<b>zoom: " + zoom + "</b>";
	
	if (fusion_layer)
	{
		fusion_layer.setMap(null);
		fusion_layer = null;
	}
	if (zoom < 9)
	{
		table = tables[0];
	}
	else if ((zoom >= 9) && (zoom <= 12))
	{
		table = tables[1];
	}
	else if (zoom > 12)
	{
		table = tables[2];
	}
	else
	{
		alert("we shouldn't be here");
	}

	fusion_layer = new google.maps.FusionTablesLayer({ query: {select: 'lat_lon', from: table, where: ""} });
	fusion_layer.setOptions({ suppressInfoWindows : true});
	fusion_listener = google.maps.event.addListener(fusion_layer, 'click', function(e) { update_info(e);});
	google.maps.event.addListener(map, 'zoom_changed', function() { if (zoom != map.getZoom()) { change_map(); }; });

	fusion_layer.setMap(map);
}

function update_info(e)
{
	var info_div = document.getElementById('info');
	if (e)
	{
		info_div.innerHTML = e.infoWindowHtml;
		//var DRE = e.infoWindowHtml.match(/([.0-9]+) μSv\/h/)[1];
		var DRE = parseFloat(e.row.DRE.value);
		if (DRE <= 0.2) 
			{document.getElementById("info_under").style.backgroundColor='#99ff99';}
		else if (DRE >0.2 && DRE <= 0.5)
			{document.getElementById("info_under").style.backgroundColor='#ffff99';}
		else if (DRE >0.5 && DRE <= 1.0)
			{document.getElementById("info_under").style.backgroundColor='#ff99ff';}
		else if (DRE >1.0 && DRE <= 5.0)
			{document.getElementById("info_under").style.backgroundColor='#9999ff';}
		else if (DRE >5.0 && DRE <= 10.0)
			{document.getElementById("info_under").style.backgroundColor='#ff6666';}
		else
			{document.getElementById("info_under").style.backgroundColor='red';}
	}
	else
	{
		document.getElementById("info_under").style.backgroundColor='gray';
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
