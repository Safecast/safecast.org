/**
 *  実行
 *  ========================================================
 */

var calibrationConstant = 334; 
 
$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});

 
$(document).ready(function()
{
	//which = $.getUrlVar("id");
  $('#map_canvas').visualizeGmCounter(
  {
    pachubeAPI:
    [
            '/feeds/driveCache/drive' + which + '.json'
    ]
    //'feeds/pachube.php',

    // 'http://api.pachube.com/v2/feeds.json?key=AY2xnknMXVwpcpnrrOJz9aCuL1bkleqj6r2orGgyBtA&tag=sensor:type=radiation&lat=38.27&lon=140.81&distance=4000'
    // visualizer: new GmcVisualizer()
  });
  $("#mapInfo").html($("#mapInfo").html() + $("#mapLegend").html() + $("#downloadLinks").html());
  $("#kmlLink").attr("href","/feeds/driveCache/drive" + which + ".kml");
  $("#csvLink").attr("href","/feeds/driveCache/drive" + which + ".csv");
});

/**
 *  
 *  plugin for jQuery - visualizeGmCounter
 *  
 *  @summary
 *    <www.pachube.com> に登録されているガイガーカウンターのデータを取得し、可視化しています。
 *    このソースコードは、おおまかに分けて☟4つのセクションに分かれています。
 *      + config
 *      + Pachubeからのデータ取得部分実装
 *      + Visualize部分実装
 *      + 実行
 *  
 *  @todo
 *    + 今回のケースに合った、良いVisualize方法を探す
 *    
 */
(function($)
{
  

  $.fn.visualizeGmCounter = function(config)
  {
    /**
     *  $(elem).visualizeGmCounter() の設定
     *  ========================================================
     *
     *    {Array}    pachubeAPI
     *      + 取得したいPachube APIのURI (json) を配列で格納します。
     *
     *    {Object}  visualizer
     *      + Pachubeから取得したデータを使ってVisualizeするClassのインスタンスです。
     *      + 何も指定しない場合は、このコード内の Class GmcVisualizer が使用されます。
     *      + Pachubeからのデータ取得が終わると、 取得した全Jsonの配列を引数としてvisualizerインスタンスの draw メソッドが呼び出されます。
     *      + Forkする際には、このGmcVisualizerを編集したり、 別のvisualizerを渡したりすると便利（かもしれません）。
     *  
     */
    config = $.extend(
    {
      pachubeAPI:[],
      xmlFeed:[],
      visualizer: undefined
    }, config);

    var target = this;



    /**
     *  Class GmcDataParser
     *  ========================================================
     *  
     *    @use
     *      + Pachubeからデータを取得して、visualizerに渡します。
     *      
     */
    var GmcDataParser = function(){};
    GmcDataParser.prototype =
    {
      /**
       *  {Array}  aData
       *    + Pachubeから取得したデータ全てを配列で格納します。
       *    + 中に入るデータは, www.pachube.com/feeds/{Feed ID}.json で得られる形式のJSONです。
       *    + このデータは、Visualizerの draw() の引数に渡されます。
       */
      aData: [],
      xmlData: [],

      /**
       *  {Function}  getData
       *    + visualizeGmCounter定義冒頭の config.pachubeAPI 全てのURIからデータを取得します。
       *    + 全てのURIからデータを貰った時点で, config.visualizer.draw に this.data を渡して実行します。
       */
      getData: function()
      {

        $.ajax({
		    type: "GET",
		    url: config.pachubeAPI[0],
		    contentType: "application/json; charset=utf-8",
		    dataType: "json",
		    success: function(json) {
		        //alert("success");
		        if (config.visualizer.draw)
		                {
		                  config.visualizer.draw(target, json);
		                }
		                else
		                {
		                  throw new Error('visualiser draw()');
		                };
		    },
		    error: function (xhr, textStatus, errorThrown) {
		        alert("error "+textStatus+" - "+ errorThrown);
		    }
		});
	
      }, 
      
      getXmlData: function()
      {
        var xmlData = this.xmlData;
        for(var i=0; i<config.xmlFeed.length; i++)
        {
        	$.ajax({
        		url:config.xmlFeed[i],
				crossDomain:true, 
				dataType:"xml",
				success: function(xml)
				{
				  var jsonConverted = $.xml2json(xml);
				  if(jsonConverted && jsonConverted.channel && jsonConverted.channel.item){
					  xmlData = xmlData.concat(jsonConverted.channel.item);
				  }
				  if(i == config.xmlFeed.length)
				  {
				    if (config.visualizer.drawXml)
				    {
				      config.visualizer.drawXml(target, xmlData);
				    }
				    else
				    {
				      throw new Error('visualiser draw()');
				    };
				  };
				}
			});
          
        };
      }
      
      
    };



    /**
     *  Class GmcVisualizer
     *  ========================================================
     *  
     *    @use
     *      + Pachubeからのデータ取得が終わると、 draw メソッドが呼び出されます。
     *      + JSON形式のデータのみ受け付けます。
     *    
     */
    var GmcVisualizer = function(){};
    GmcVisualizer.prototype = 
    {
      /**
       *  {Object}  oGMap    google.maps.Mapへの参照
       *  {Array}    aGInfoWindows  google.maps.InfoWindow の配列
       */
      oGMap: undefined,
      aGInfoWindows: new Array(),
      /**
       *  {Function}  draw
       *    + Pachubeからのデータ取得が終わった後、このmethodが呼ばれます。
       *    
       *    @params {Array}  data
       *      config.pachubeAPI で指定したPachube APIから取得した全てのJSONがこの配列に入っています。
       *    
       *    @params {Array}  data
       *      config.pachubeAPI で指定したPachube APIから取得した全てのJSONがこの配列に入っています。
       */
      draw: function(elTarget, aData)
      {
        try
        {
          /**
           *  Google Mapの初期化
           */
          if(!this.oGMap){
          	var mapStyles =  
					 [
					  {
					    featureType: "transit.line",
					    elementType: "all",
					    stylers: [
					      { lightness: 62 }
					    ]
					  },{
					    featureType: "all",
					    elementType: "labels",
					    stylers: [
					      { saturation: -73 },
					      { lightness: 22 }
					    ]
					  },{
					    featureType: "road",
					    elementType: "all",
					    stylers: [
					      { saturation: -47 },
					      { lightness: 38 }
					    ]
					  }
					];
			var styledMapOptions = {
			     name: "Safecast"
			  	};
					
          	this.oGMap = new google.maps.Map(elTarget.get(0), {
	            zoom: aData.mapZoom,
	            center: new google.maps.LatLng(aData.mapLat, aData.mapLng),
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: true,
			panControl: false,
			scaleControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			    scrollwheel: true
	          });
			globalMap = this.oGMap;
	        var style = [{ featureType: 'all', elementType: 'all', stylers: [ { saturation: -69 } ]}];
			var styledMapType = new google.maps.StyledMapType(style, { map: globalMap, name: 'Styled Map' });
			globalMap.mapTypes.set('map-style', styledMapType);
			globalMap.setMapTypeId('map-style');
			
	        var fukushima = new google.maps.LatLng(37.425525, 141.029434);

    // draw evacation area
    var evac_area = new google.maps.Circle({
        map: this.oGMap,
        center: fukushima,
        radius: 20000,
        fillOpacity: 0,
        strokeColor: '#999999',
        strokeOpacity: 0.8,
        strokeWeight: 3
    });
    var evac2_area = new google.maps.Circle({
        map: this.oGMap,
        center: fukushima,
        radius: 30000,
        fillOpacity: 0,
        strokeColor: '#999999',
        strokeOpacity: 0.5,
        strokeWeight: 3
    });
    var marker = new google.maps.Marker({
            position: fukushima,
            animation: google.maps.Animation.DROP,
            map: this.oGMap,
            title: "Fukushima Daiichi Nuclear Power Plant"
        });

        marker.contentString = 'Fukushima Daiichi Nuclear Power Plant';
	
        google.maps.event.addListener(marker, 'click', function () {
	        gm_infowindow.content = marker.contentString;
            gm_infowindow.open(this.oGMap, marker);
        });


	        	  	$(".sectionHeadRight").html(aData.title);
	  	$(".driveLocations").html(aData.locations);
	  	$(".drivers").html(aData.description);
          } 
          
          /**
           *  PachubeのデータをGmap上に配置
           */
          this.visualize(aData);
        }
        catch(e)
        {
          throw new Error(e);
        };
      },
      
      drawXml: function(elTarget, xmlData)
      {
        try
        {
         if(!this.oGMap){
         	
         	this.oGMap = new google.maps.Map(elTarget.get(0), {
	            zoom: 6,
	            center: new google.maps.LatLng(38.27, 140.81),
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: true,
			panControl: false,
			scaleControl: true,
			mapTypeControl: false,
			streetViewControl: false,
		    scrollwheel: true
	          });
          } 

          this.visualizeXml(xmlData);
        }
        catch(e)
        {
          throw new Error(e);
        };
      },
      /**
       *  {Function}  visualize
       *    
       *    @params {Array}  aData
       *      config.pachubeAPI で指定したPachube APIから取得した全てのJSONがこの配列に入っています。
       */
      visualize: function(aData)
      {
        var elBalloon = this.elBalloon;
        var oGMap = this.oGMap;

        for(var i=0; i < aData.dataPoints.length; i++)
        {
          var oJson = aData.dataPoints[i];
          var oGLatLng = new google.maps.LatLng(oJson.lat, oJson.lon, false);
          
          
          
	  
	   if(oJson.cpm_value/calibrationConstant >= 3.0){
          	var image = new google.maps.MarkerImage('images/Border/grey.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 1.8){
          	var image = new google.maps.MarkerImage('images/Border/darkRed.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 1.2){
          	var image = new google.maps.MarkerImage('images/Border/red.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 1.0){
          	var image = new google.maps.MarkerImage('images/Border/darkOrange.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 0.8){
          	var image = new google.maps.MarkerImage('images/Border/orange.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 0.5){
          	var image = new google.maps.MarkerImage('images/Border/yellow.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 0.3){
          	var image = new google.maps.MarkerImage('images/Border/lightGreen.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 0.2){
          	var image = new google.maps.MarkerImage('images/Border/green.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else if(oJson.cpm_value/calibrationConstant >= 0.1){
          	var image = new google.maps.MarkerImage('images/Border/midgreen.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }else {
          	var image = new google.maps.MarkerImage('images/Border/white.png',
    			  new google.maps.Size(11, 11),new google.maps.Point(0,0),new google.maps.Point(6, 6));
          }
	  
          var radiationLevel = oJson.cpm_value;	  
	  radiationLevel = radiationLevel + " CPM";
          var oGMarker = new google.maps.Marker(
          {
            position: oGLatLng,
            map: oGMap,
            //title: aData.title,
	    title: radiationLevel,
	    icon: image
          });
           
          oGMarker.nIndex = i;
          oGMarker.setMap(oGMap);

          var fnCreateHtmlFromJson = this.createHtmlFromJson;
          var aGInfoWindows = this.aGInfoWindows;
	  
          
          google.maps.event.addListener(oGMarker, 'mouseover', function(oPoint)
	          {
	            $(aGInfoWindows).each(function(j, oGWindow)
	            {
	              oGWindow.setMap(null);
	            });
	            var sNewString = fnCreateHtmlFromJson(aData.title, aData.description, aData.dataPoints[this.nIndex]);
	            $("#mapInfo").html(sNewString+$("#mapLegend").html() + $("#downloadLinks").html());
		    $("#kmlLink").attr("href","/feeds/driveCache/drive" + which + ".kml");
		    $("#csvLink").attr("href","/feeds/driveCache/drive" + which + ".csv");
		    /*
		    var oGInfoWindow = new google.maps.InfoWindow(
	            {
	              content: sNewString,
	              maxWidth: 400
	            });*/
		    
	            /*aGInfoWindows.push(oGInfoWindow);
	            oGInfoWindow.open(oGMap, this);*/
	            $('.mapAnnotation').parent().parent().css('overflow-y','visible') 
	            $('.mapAnnotation').parent().css('overflow-y','visible') 
	          });
	  
	  
	  
        }
          
          
                   
        
      },
      
      visualizeXml: function(xmlData)
      {
        var elBalloon = this.elBalloon;
        var oGMap = this.oGMap;

        for(var i=0; i < xmlData.length; i++)
        {
          var oJson = xmlData[i];
          if (!oJson || !oJson.description ||!oJson.point || !oJson.title || !oJson.pubDate)
          {
            continue;
          };
          
          /**
           *  地図上にOverlayを置く時に使用する緯度経度
           */
          var lngLatArr = oJson.point.split(" ");
		  if(lngLatArr.length == 2){
			    if(isNaN(lngLatArr[0]) || isNaN(lngLatArr[1])){
			        //bad lat-lng pair
			        continue;
			    }else{
			        var oGLatLng = new google.maps.LatLng(lngLatArr[0], lngLatArr[1], false);
			    }
          }else{
          	//bad lat-lng pair
          	continue;
          }
          
          /**
           *  Markerを地図上に追加
           */
          var image;
          if(oJson.link && oJson.link.indexOf('crowdmap')!=-1){
	          //crowdMap marker 
	          image = new google.maps.MarkerImage('/images/red_pin.png',
				  // This marker is 20 pixels wide by 32 pixels tall.
				  new google.maps.Size(20, 32),
				  // The origin for this image is 0,0.
				  new google.maps.Point(0,0),
				  // The anchor for this image is the base of the flagpole at 0,32.
				  new google.maps.Point(16, 32));
          }else {
          	//rdtn marker 
	          image = new google.maps.MarkerImage('/images/purple_pin.png',
				  // This marker is 20 pixels wide by 32 pixels tall.
				  new google.maps.Size(20, 32),
				  // The origin for this image is 0,0.
				  new google.maps.Point(0,0),
				  // The anchor for this image is the base of the flagpole at 0,32.
				  new google.maps.Point(16, 32));
          }

		  var shadow = new google.maps.MarkerImage('/images/shadow.png',
			  // The shadow image is larger in the horizontal dimension
			  // while the position and offset are the same as for the main image.
			  new google.maps.Size(37, 32),
			  new google.maps.Point(0,0),
			  new google.maps.Point(16, 32));
			  
          var oGMarker = new google.maps.Marker(
          {
            position: oGLatLng,
            map: oGMap,
            title: oJson.title,
			shadow: shadow,
			icon: image
          });
          oGMarker.nIndex = i;
          oGMarker.setMap(oGMap);
          /**
           *  Markerにイベントを登録
           */
          var fnCreateHtmlFromXml = this.createHtmlFromXml;
          var aGInfoWindows = this.aGInfoWindows;
          google.maps.event.addListener(oGMarker, 'click', function(oPoint)
          {
            $(aGInfoWindows).each(function(j, oGWindow)
            {
              oGWindow.setMap(null);
            });
            var sNewString = fnCreateHtmlFromXml(xmlData[this.nIndex]);
            var oGInfoWindow = new google.maps.InfoWindow(
            {
              content: sNewString
            });
            aGInfoWindows.push(oGInfoWindow);
            oGInfoWindow.open(oGMap, this);
          });
          google.maps.event.addListener(oGMarker, "mouseover", function (e) 
          { 
          	log("Mouse Over"); 
          });
     	  google.maps.event.addListener(oGMarker, "mouseout", function (e) 
     	  { 
     	  	log("Mouse Out"); 
     	  });
        };
        
        
      },
      /**
       *  
       */
      createHtmlFromJson: function(title, description, oJson)
      {
        var elBalloon = $('<div id="balloon">');
        var sNewString = new String();
        // -------------------------------------
        /**
         *  for debugging...
         */
         //console.log(oJson)
         //console.log("[title] "+ oJson.title);
         //console.log("[creator] "+ oJson.creator);
         //console.log("[description] "+ oJson.description);
         //console.log("[at] "+ oJson.datastreams[0].at);
         //console.log("[current_value] "+ oJson.datastreams[0].current_value);
         //console.log("[max_value] "+ oJson.datastreams[0].max_value);
         //console.log("[min_value] "+ oJson.datastreams[0].min_value);
         //console.log("------------");
        // -------------------------------------

	var mDate = new Date(oJson.at.substr(0, 10));
	//console.log("[Date]" + oJson.at);
	var month = mDate.getMonth()+1 < 10 ? "0" + (mDate.getMonth()+1) : (mDate.getMonth()+1);
	var elCurrentValue  = $('<p>').html("<span style=\"font-weight: bold;\">" + oJson.cpm_value + ' CPM</span> <br /><span style=\"font-weight: bold;\">'+ (oJson.cpm_value/calibrationConstant).toFixed(3) + ' ' + oJson.label + '</span> derived dose');
	    
	        //var elRange      	= $('<span class="max"> / '+oJson.datastreams[0].max_value+'</span>');
	var elAt         	= $('<p>').html("<span style=\"font-weight: bold;\">" + mDate.getFullYear() + "/" + month + "/" + mDate.getDate() + '</span>');
										//.replace(/^/,'計測日: ')
										//.replace(/-/g, '/')
										//.replace('T', '計測時間: ')
										//.replace(/\.\d+\+.+?$/, ''));
		
	var mNum 		= ((oJson.cpm_value/calibrationConstant)*365*24)/1000.0;
	var elAnnual 		= $('<p>').html("<div id=\"_doseNum\"> Annualized dose: " + mNum.toFixed(3) + " mSv</div>");
	/*if(mNum >= 10.0) {
		var elAnnual 	= $('<p>').html("<div id=\"doseSquare\" style=\"background-color: black;\"></div><div id=\"doseNum\"> Annual dose: " + mNum.toFixed(3) + " mSv</div>");
		var elNote 	= $('<p>').html("<span id=\"radNote\" > Exceeds Japan's acceptable radiation standard of 20 mSv/year.</span>");
	} else {
		var elAnnual 	= $('<p>').html("<div id=\"doseSquare\" style=\"background-color: green;\"></div><div id=\"doseNum\"> Annual dose: " + mNum.toFixed(3) + " mSv</div>");
		var elNote	= $('<p>').html(" Below Japan's acceptable radiation standard of 20 mSv/year."); 
	}*/
        var elLat		= $('<p>').html("Latitude: " + oJson.lat.toFixed(4));
	var elLon		= $('<p>').html("Longitude: " + oJson.lon.toFixed(4));


	var elWrap 		= $('<div class="mapAnnotation" style="min-height:130px;">');
	        
	elWrap.append(elCurrentValue);
	elWrap.append(elAt);
	elWrap.append('<hr>');
	elWrap.append(elAnnual);
	//elWrap.append(elNote);
	elWrap.append('<hr>');
	elWrap.append(elLat);
	elWrap.append(elLon);



	elBalloon.append(elWrap);
        sNewString = elBalloon.html();
        elBalloon = null;
        return sNewString;
      },
      
      createHtmlFromXml: function(oJson)
      {
        var elBalloon = $('<div id="balloon">');
        var sNewString = new String();
        // -------------------------------------
        /**
         *  for debugging...
         */
         //console.log(oJson)
         //console.log("[title] "+ oJson.title);
         //console.log("[creator] "+ oJson.creator);
         //console.log("[description] "+ oJson.description);
         //console.log("[at] "+ oJson.datastreams[0].at);
         //console.log("[current_value] "+ oJson.datastreams[0].current_value);
         //console.log("[max_value] "+ oJson.datastreams[0].max_value);
         //console.log("[min_value] "+ oJson.datastreams[0].min_value);
         //console.log("------------");
        // -------------------------------------


        var elTitle 		= $('<h2 class="title"><span style="font-size:10pt;font-weight:bold">' + oJson.title + '</span>');
        var elCurrentValue  = $('<p>').html(oJson.description);
        var elAt         	= $('<p>').html(oJson.pubDate);
        var elWrap 			= $('<div class="mapAnnotation" style="min-height:130px;">');        
        elWrap.append(elTitle);
        elWrap.append('<hr>');
        elWrap.append(elCurrentValue);
        elWrap.append(elAt);

		elBalloon.append(elWrap);
        sNewString = elBalloon.html();
        elBalloon = null;
        return sNewString;
      }
    };




    /**
     *  実行
     *  ========================================================
     */
    if ( !config.visualizer )
    {
      config.visualizer = new GmcVisualizer();
    };
    
    
    
    
    
    var parser = new GmcDataParser();
    parser.getData();
    
    //parser.getXmlData();
  }
})(jQuery);


function showPachubeHistory(id, stream, resolution, unit){
	$.fancybox({
			//'orig'			: $(this),
			'padding'		: '10px',
			'href'			: 'http://www.pachube.com/feeds/'+id+'/datastreams/'+stream+'/history.png?r='+resolution+'&b=true&g=true&c=FD0100&t=Historical%20Data&l='+unit,
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic'
		});
	return false;
}

function showMextHistory(source, id, resolution, unit){
	$.fancybox({
			'content' : '<div id="historicalWrapper" style="width:950px;height:480px"><div id="historicalGraph" style="width:940px;height:470px"></div></div>',
			'width' : 950,
			'height' : 480,
			'autoScale'		: false,
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic', 
			'scrolling' : 'no'
		});
		
	//populate
	var datasource = "/feeds/graphData.php?which="+source+"&id="+id;
	$.getJSON(datasource,function(oJson) {
	
					switch(resolution){
					case 2:
						//last 24
						var startDate = new Date();
				    	startDate.setMinutes(startDate.getMinutes() - (60*24));
				        $.plot($("#historicalGraph"), [oJson], {
				            xaxis: {
				                mode: "time",
				                min: startDate.getTime(),
				                max: (new Date()).getTime()
				            },
							yaxis: { 
					        	axisLabel: unit,
					            axisLabelUseCanvas: false,
					            axisLabelFontSizePixels: 16,
					            axisLabelFontFamily: 'Helvetica'
					    	},
					    	colors: ["#fd0100"], 
					    	series: {
				                   lines: { show: true },
				                   points: { show: true }
				            },
				            grid: { hoverable: true, clickable: false }, 
				            legend: {
							    show: false
							} 
				        });
				        //$.fancybox.resize();
						break;
					case 3:
						//last 4 days
						var startDate = new Date();
				    	startDate.setDate(startDate.getDate() - 4);
				        $.plot($("#historicalGraph"), [oJson], {
				            xaxis: {
				                mode: "time",
				                min: startDate.getTime(),
				                max: (new Date()).getTime()
				            },
							yaxis: { 
					        	axisLabel: unit,
					            axisLabelUseCanvas: false,
					            axisLabelFontSizePixels: 16,
					            axisLabelFontFamily: 'Helvetica'
					    	},
					    	colors: ["#fd0100"], 
					    	series: {
				                   lines: { show: true },
				                   points: { show: true }
				            },
				            grid: { hoverable: true, clickable: false }, 
				            legend: {
							    show: false
							}  
				        });
				        //$.fancybox.resize();
						break;
					case 4:
						//last 3 months (all)
						$.plot($("#historicalGraph"), [oJson], { 
							xaxis: {
					        	mode: "time" 
					    	},
							yaxis: { 
					        	axisLabel: unit,
					            axisLabelUseCanvas: false,
					            axisLabelFontSizePixels: 16,
					            axisLabelFontFamily: 'Helvetica'
					    	},
					    	colors: ["#fd0100"], 
					    	series: {
				                   lines: { show: true },
				                   points: { show: true }
				            },
				            grid: { hoverable: true, clickable: false }, 
				            legend: {
							    show: false
							}  
				
				        });
				        //$.fancybox.resize();
						break;
					}
					bindTooltip();
			});	
	
	return false;
}
var previousPoint = null;
function bindTooltip(){
    $("#historicalGraph").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
 

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                    
                    showTooltip(item.pageX, item.pageY,
                                y + " " +item.series.label);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
        
    });
}
function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y - 5,
            left: x + 10,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80, 
            zIndex:2000
        }).appendTo("body").fadeIn(200);
    }
