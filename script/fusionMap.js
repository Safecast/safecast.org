var map;
var zoom_level              = 7;
var layer_count             = 1;
var fusion_layers           = new Array(null, null, null, null);
var fusion_listeners    = new Array(null, null, null, null);
var tables                      = new Array("1219714", "1122112", "1122112", "1122112");    //1122112 is empty table FIXME: how do I select a map from the dropdown?
var grid_layer              = null;


$(document).ready(function()
{
  initialize_map();
});


function initialize_map()
{
    map = new google.maps.Map(document.getElementById('fusion_canvas'),
        { center: new google.maps.LatLng(36.94111143010772, 140.60302734375),
        zoom: zoom_level,
        zoomControl: true,
        panControl: false,
        scaleControl: true,
        mapTypeControl: false,
        scaleControlOptions: { position: google.maps.ControlPosition.TOP_CENTER },
        mapTypeId: google.maps.MapTypeId.ROADMAP });

    var style = [{ featureType: 'all', elementType: 'all', stylers: [ { saturation: -69 } ]}];

    var styledMapType = new google.maps.StyledMapType(style, { map: map, name: 'Styled Map' });
    map.mapTypes.set('map-style', styledMapType);
    map.setMapTypeId('map-style');
    changeMap();
}

function changeMap()
{
    update_info(null);          // grey-out the div initially
    zoom = map.getZoom();       // update current zoom
    document.getElementById('info').innerHTML = "<b>zoom: " + zoom + "</b>";
    // create/destroy the grid
    
    // grid_layer = new LatLonGraticule();
    //grid_layer.setMap(map);
    
    for (L = 0; L < layer_count; L++)
    {
        if (fusion_layers[L])
        {
            fusion_layers[L].setMap(null);
            fusion_layers[L] = null;
        }
        var condition = "";
        /*
        if (document.getElementById('min_' + L).value > 0)
        {
            condition += "'DRE' >= " + document.getElementById('min_' + L).value.replace("'", "\\'");
            if (document.getElementById('date_' + L).value !== "")
            {
                condition += " AND 'timestamp_JST' STARTS WITH '" + document.getElementById('date_' + L).value.replace("'", "\\'") + "'";
            }
        }
        else
        {
            if (document.getElementById('date_' + L).value !== "")
            {
                condition += "'timestamp_JST' STARTS WITH '" + document.getElementById('date_' + L).value.replace("'", "\\'") + "'";
            }
        }
*/
        if ((tables[L] != "1122112") && (L == 0)) //automagcally swap tables based on zoom
        {
            tables[L] = (zoom > 12) ? "1219825" : "1219714" ;
        }

        fusion_layers[L] = new google.maps.FusionTablesLayer(
        {
            query: { select: 'lat_lon', from: tables[L], where: condition}
        });

            fusion_layers[L].setOptions({ suppressInfoWindows : true});
            fusion_listeners[L] = google.maps.event.addListener(fusion_layers[L], 'click', function(e) { update_info(e);});
            google.maps.event.addListener(map, 'zoom_changed', function() { if (zoom != map.getZoom()) { changeMap(); }; });

        fusion_layers[L].setMap(map);
    }
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