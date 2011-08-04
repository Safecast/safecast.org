var gm_map;
var gm_map_labels = {};
var gm_circles = {};
var gm_value_labels = {};
var gm_source_labels = {};
var gm_map_options = [];
var gm_init_count = 0;
var gm_js_loaded = false;
var gm_maps_loaded = false;
var gm_registered_feeds = [];
var gm_feedcount = 0;
var gm_threshold = 0.081;
var gm_infowindow = new google.maps.InfoWindow();

function gm_init(obj) {
    if (obj != undefined) {
        gm_map_options.push(obj);
        //alert("init map " + obj.map_name);
    }
}
function gm_load_feeds(gm_load_feeds_options) {
    gm_registered_feeds = gm_registered_feeds.concat(gm_load_feeds_options.json);
        //alert("load feeds " + gm_load_feeds_options);
}

function create_maps() {
    gm_maps_loaded = true;
    gm_final_load();
}


function gm_final_load() {
    for (var i = 0; i < gm_map_options.length; i++) {
           var map = create_geigermap(gm_map_options[i]);
            var map_name = gm_map_options[i].map_name;
        }

        for (var i = 0; i < gm_registered_feeds.length; i++) {
            //alert("loading feed " + gm_registered_feeds[i]);
            $.getJSON(gm_registered_feeds[i], gm_process_grid_json);
        }
}



function create_geigermap(options_obj) {

    var latlng = new google.maps.LatLng(36.597889, 139.669678);
    if (options_obj != undefined && options_obj.lat != undefined && options_obj.lng != undefined) {
        latlng = new google.maps.LatLng(options_obj.lat, options_obj.lng);
    }
    var map_div = "grid_map_canvas";
    if (options_obj != undefined && options_obj.div != undefined) {
        map_div = options_obj.div;
    }
    var map_div_obj = document.getElementById("grid_map_canvas");
    var map = new google.maps.Map(map_div_obj, {
        zoom: ((options_obj != undefined) ? ((options_obj.zoom != undefined) ? options_obj.zoom : 7) : 7),
        center: latlng,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });

    gm_map = map;

    var fukushima = new google.maps.LatLng(37.425525, 141.029434);

    // draw evacation area
    var evac_area = new google.maps.Circle({
        map: map,
        center: fukushima,
        radius: 20000,
        fillOpacity: 0,
        strokeColor: '#ffffff',
        strokeOpacity: 0.8,
        strokeWeight: 3
    });
    var evac2_area = new google.maps.Circle({
        map: map,
        center: fukushima,
        radius: 30000,
        fillOpacity: 0,
        strokeColor: '#ffffff',
        strokeOpacity: 0.5,
        strokeWeight: 3
    });

    if (options_obj.show_label) {
        var marker = new google.maps.Marker({
            position: fukushima,
            animation: google.maps.Animation.DROP,
            map: map,
            title: "Fukushima Daiichi Nuclear Power Plant"
        });

        marker.contentString = 'Fukushima Daiichi Nuclear Power Plant';
	
        google.maps.event.addListener(marker, 'click', function () {
	        gm_infowindow.content = marker.contentString;
            gm_infowindow.open(map, marker);
        });
    }
	return map;
}

function gm_process_grid_json(oJson) {
	//alert("gm_process_grid_json " + oJson);
    for (var i = 0; i < oJson.length; i++) {
        //alert("new square " + oJson[i].gridId);
        var curr_square = oJson[i];
        // draw the square

        var rectangle = new google.maps.Rectangle();
        var topLeft = new google.maps.LatLng(parseFloat(curr_square.topLeft.lat), parseFloat(curr_square.topLeft.lng));
        var bottomRight = new google.maps.LatLng(curr_square.bottomRight.lat, curr_square.bottomRight.lng);
        var bounds = new google.maps.LatLngBounds(topLeft, bottomRight);
        var color = (curr_square.contaminated_air == "1") ? "#000000" : ((curr_square.contaminated_air == "-1") ? "#ffffff":"#39f139");
        var opacity = (curr_square.contaminated_air == "1") ? 0.4 : 0.7;
        
        var rectOptions = {
      	strokeWeight: 0.2,
      	strokeColor: color,
      	fillColor: color,
      	fillOpacity: opacity,
      	map: gm_map,
      	bounds: bounds
    	};
    	rectangle.setOptions(rectOptions);
    }
}