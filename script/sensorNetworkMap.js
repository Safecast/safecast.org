/**
 *  実行
 *  ========================================================
 */
$(document).ready(function()
{
  $('#sensor_network_canvas').visualizeGmCounter(
  {
    pachubeAPI:
    [
            'feeds/pachubeStatic.json'
    ],
    xmlFeed:
    [
    	//'feeds/crowdmapStatic.xml'
    ]
    //'feeds/pachube.php',

    // 'http://api.pachube.com/v2/feeds.json?key=AY2xnknMXVwpcpnrrOJz9aCuL1bkleqj6r2orGgyBtA&tag=sensor:type=radiation&lat=38.27&lon=140.81&distance=4000'
    // visualizer: new GmcVisualizer()
  });
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
(function($){

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
        var aData = this.aData;
        for(var i=0; i<config.pachubeAPI.length; i++)
        {
        	$.getJSON(config.pachubeAPI[i],function(oJson) {
				    aData = aData.concat(oJson.results);
              
		              if(i == config.pachubeAPI.length)
		              {
		                if (config.visualizer.draw)
		                {
		                  config.visualizer.draw(target, aData);
		                }
		                else
		                {
		                  throw new Error('visualiser draw()');
		                };
		              };
				  });
          
        };
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
				success: function(xml) {
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
				  },
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
          	this.oGMap = new google.maps.Map(elTarget.get(0), {
          		zoomControl: true,
				panControl: false,
				scaleControl: true,
				mapTypeControl: false,
				streetViewControl: false,
	            zoom: 6,
	            center: new google.maps.LatLng(38.2, 139.21),
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
	          });
	        globalMap = this.oGMap;
	        var style = [{ featureType: 'all', elementType: 'all', stylers: [ { saturation: -69 } ]}];
			var styledMapType = new google.maps.StyledMapType(style, { map: globalMap, name: 'Styled Map' });
			globalMap.mapTypes.set('map-style', styledMapType);
			globalMap.setMapTypeId('map-style');
	        
	        
	        var fukushima = new google.maps.LatLng(37.425525, 141.029434);

		    // draw evacation area
		    var evac_area = new google.maps.Circle({
		        map: globalMap,
		        center: fukushima,
		        radius: 20000,
		        fillOpacity: 0,
		        strokeColor: '#999999',
		        strokeOpacity: 0.8,
		        strokeWeight: 3
		    });
		    var evac2_area = new google.maps.Circle({
		        map: globalMap,
		        center: fukushima,
		        radius: 30000,
		        fillOpacity: 0,
		        strokeColor: '#999999',
		        strokeOpacity: 0.5,
		        strokeWeight: 3
		    });
		
		    //if (options_obj.show_label) {
		        var marker = new google.maps.Marker({
		            position: fukushima,
		            animation: google.maps.Animation.DROP,
		            map: globalMap,
		            title: "Fukushima Daiichi Nuclear Power Plant"
		        });
		
		        marker.contentString = 'Fukushima Daiichi Nuclear Power Plant';
			
		        google.maps.event.addListener(marker, 'click', function () {
			        gm_infowindow.content = marker.contentString;
		            gm_infowindow.open(globalMap, marker);
		        });
		    //}

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
	            scaleControl: false,
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

        for(var i=0; i < aData.length; i++)
        {
          var oJson = aData[i];
          if (!oJson || !oJson.datastreams || oJson.datastreams.length==0 ||!oJson.datastreams[0].current_value || oJson.datastreams[0].current_value == -8888 || oJson.datastreams[0].current_value == -999)
          {
          	if(oJson.creator.indexOf('germany')!=-1){
          		if(!oJson || !oJson.datastreams || oJson.datastreams.length==0 ||!oJson.datastreams[1].current_value || oJson.datastreams[1].current_value == -8888 || oJson.datastreams[1].current_value == -999){
          			continue;
          		}
          	}else{
          		continue;
          	}
            
          };
  			if(oJson.title.indexOf('Safecast')==-1 && oJson.title.indexOf('Omotesando')==-1){
    	          continue;
    	 	}
          
          /**
           *  地図上にOverlayを置く時に使用する緯度経度
           */
          var oGLatLng = new google.maps.LatLng(oJson.location.lat, oJson.location.lon, false);
          /**
           *  !!!: 要修正
           *  マーカーをクリックしなくても状況がある程度一覧出来る様にcircleを用意
           */
          /*
var oCircle = new google.maps.Circle({
            map: oGMap,
            center: oGLatLng,
            radius: 30000,
            fillOpacity: 0.5,
            fillColor: '#ff0000',
            strokeColor: '#ff0000',
            strokeOpacity: 1,
            strokeWeight: 1,
          });
*/
          /**
           *  Markerを地図上に追加
           */
           var image;
           var shadow;
           if (oJson.creator.indexOf('reactors') != -1) {
           
              image = new google.maps.MarkerImage('images/map_POI.png',
    			  // This marker is 20 pixels wide by 32 pixels tall.
    			  new google.maps.Size(15, 15),
    			  // The origin for this image is 0,0.
    			  new google.maps.Point(0,0),
    			  // The anchor for this image is the base of the flagpole at 0,32.
    			  new google.maps.Point(7, 15));
           } else {
           
              image_name = '/images/blue_pin.png';
              if (oJson.creator.indexOf('mext')!=-1) {
    	          //pachube marker 
    	          image_name = '/images/black_pin.png';
              } else if(oJson.creator.indexOf('rdtn.org')!=-1) {
    	          image_name = '/images/purple_pin.png';
              } else if(oJson.creator.indexOf('government')!=-1) {
    	          image_name = '/images/yellow_pin.png';
              } else if(oJson.creator.indexOf('fleep')!=-1 || oJson.creator.indexOf('fukushima_fleep')!=-1 || oJson.creator.indexOf('fukushima_daiichi')!=-1) {
    	          image_name = '/images/dark_orange2_pin.png';
              } else if(oJson.creator.indexOf('greenpeace')!=-1) {
    	          image_name = '/images/greenish_grey_pin.png';
              } else if(oJson.creator.indexOf('epa')!=-1) {
    	          image_name = '/images/purple2_pin.png';
              } else if(oJson.creator.indexOf('probe')!=-1) {
    	          image_name = '/images/purple_pin.png';
    	      } else if(oJson.creator.indexOf('albertonaranjo')!=-1) {
    	          image_name = '/images/darkblue_pin.png';
    	      } 
    	      if(oJson.title.indexOf('Safecast')!=-1){
    	          image_name = '/images/purple_pin.png';
    	      }else if(oJson.title.indexOf('Omotesando')!=-1){
    	          image_name = '/images/purple_pin.png';
    	      }
              image = new google.maps.MarkerImage(image_name,
    			  // This marker is 20 pixels wide by 32 pixels tall.
    			  new google.maps.Size(20, 32),
    			  // The origin for this image is 0,0.
    			  new google.maps.Point(0,0),
    			  // The anchor for this image is the base of the flagpole at 0,32.
    			  new google.maps.Point(16, 32));
    
    		  shadow = new google.maps.MarkerImage('/images/shadow.png',
    			  // The shadow image is larger in the horizontal dimension
    			  // while the position and offset are the same as for the main image.
    			  new google.maps.Size(37, 32),
    			  new google.maps.Point(0,0),
    			  new google.maps.Point(16, 32));
    			  
           }
          var oGMarker = new google.maps.Marker(
          {
            position: oGLatLng,
            map: oGMap,
            title: oJson.title,
			shadow: shadow,
			icon: image
          });
           
          
/*
          var image = new google.maps.MarkerImage(image_name,
			  // This marker is 20 pixels wide by 32 pixels tall.
			  new google.maps.Size(20, 32),
			  // The origin for this image is 0,0.
			  new google.maps.Point(0,0),
			  // The anchor for this image is the base of the flagpole at 0,32.
			  new google.maps.Point(16, 32));

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
*/
          oGMarker.nIndex = i;
          oGMarker.setMap(oGMap);
          /**
           *  Markerにイベントを登録
           */
          var fnCreateHtmlFromJson = this.createHtmlFromJson;
          var aGInfoWindows = this.aGInfoWindows;
          if(oJson.creator.indexOf('probe')!=-1) {
          	google.maps.event.addListener(oGMarker, 'click', function(oPoint)
	          {
	          	flickr = aData[this.nIndex];
	            $.fancybox({
	            	'href': flickr.datastreams[0].current_value,
	            	'title': flickr.title+'  - &#181;Sv/h <br/>'+flickr.datastreams[0].at,
	            	'titlePosition': 'inside',
	            	'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic', 
					'scrolling' : 'no'
				});
	          });
          }else{
          	google.maps.event.addListener(oGMarker, 'click', function(oPoint)
	          {
	            $(aGInfoWindows).each(function(j, oGWindow)
	            {
	              oGWindow.setMap(null);
	            });
	            var sNewString = fnCreateHtmlFromJson(aData[this.nIndex]);
	            var oGInfoWindow = new google.maps.InfoWindow(
	            {
	              content: sNewString,
	              maxWidth: 400
	            });
	            aGInfoWindows.push(oGInfoWindow);
	            oGInfoWindow.open(oGMap, this);
	            $('.mapAnnotation').parent().parent().css('overflow-y','visible') 
	            $('.mapAnnotation').parent().css('overflow-y','visible') 
	          });
          }
          
          
                   
        };
        
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
        };
        
        
      },
      /**
       *  
       */
      createHtmlFromJson: function(oJson)
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

        oJson.feed.match(/\/(\d+)\.json$/);
        if(oJson.creator.indexOf('mext')!=-1 || oJson.creator.indexOf('fukushima_fleep')!=-1 || oJson.creator.indexOf('fukushima_daiichi')!=-1){
        	var elTitle 		= $('<h2 class="title"><span style="font-size:10pt;font-weight:bold">' + oJson.title + '</span><span> / ' + oJson.title_jp + '</span>');
        }else{
        	var elTitle 		= $('<h2 class="title"><span style="font-size:10pt;font-weight:bold">' + oJson.title + '</span>');
        }
        var elDescription  	= $('<p>').html(oJson.description);
        
        if(oJson.creator.indexOf('reactors') == -1){
        	if(oJson.creator.indexOf('germany')!=-1){
        		var elCurrentValue  = $('<p>').html('Current reading: ' + oJson.datastreams[1].current_value + ' ' + oJson.datastreams[1].unit.symbol + ' (' + oJson.datastreams[1].unit.label +')');
        	}else{
		        var elCurrentValue  = $('<p>').html('Current reading: ' + oJson.datastreams[0].current_value + ' ' + oJson.datastreams[0].unit.symbol + ' (' + oJson.datastreams[0].unit.label +')');
		       }
	        
	        if(oJson.title.indexOf('Safecast')!=-1 && oJson.datastreams.length > 1){
    	         var elAltValue  = $('<p>').html('Current reading: ' + oJson.datastreams[1].current_value + ' ' + oJson.datastreams[1].unit.symbol + ' (' + oJson.datastreams[1].unit.label +')');
    	     }
    	     if(oJson.title.indexOf('Omotesando')!=-1 && oJson.datastreams.length > 1){
    	         var elAltValue  = $('<p>').html('Current reading: ' + oJson.datastreams[1].current_value + ' ' + oJson.datastreams[1].unit.symbol + ' (' + oJson.datastreams[1].unit.label +')');
    	     }
    	     
	        if(oJson.datastreams[0].unit.symbol=="nGy/h"){
	//        var elCurrentValue  = $('<p style="padding-top:5px;">').html('Current reading: ' + oJson.datastreams[0].current_value).append(" nGy/h");
	        	var elApproxValue   = $('<p>').html('Approximate value: ' + oJson.datastreams[0].current_value/1000).append(" microsieverts per hour");
	        }else{
	        	var elApproxValue   = $('<p>')
	        }
	        //var elRange      	= $('<span class="max"> / '+oJson.datastreams[0].max_value+'</span>');
	        var elAt         	= $('<p>')
										.html('Time of reading: ' + oJson.datastreams[0].at
										.replace(/^/,'計測日: ')
										.replace(/-/g, '/')
										.replace('T', '計測時間: ')
										.replace(/\.\d+\+.+?$/, ''));
	        var elWrap 			= $('<div class="mapAnnotation" style="min-height:130px;">');
	        var elGraph        	= $('<img src="http://www.pachube.com/feeds/21187/datastreams/0/history.png" height="80" width="400" />');
	        //elCurrentValue.append(elRange);
	        
	        elWrap.append(elTitle);
	        elWrap.append('<hr>');
	        elWrap.append(elCurrentValue);
	         if(oJson.title.indexOf('Safecast')!=-1){
	         		elWrap.append(elAltValue);
	         }
	         if(oJson.title.indexOf('Omotesando')!=-1){
	         		elWrap.append(elAltValue);
	         }
	         
	        elWrap.append(elApproxValue);
	        elWrap.append(elAt);
		} else {
			var elWrap = $('<div class="mapAnnotation" style="min-height:30px;min-width:330px;">');
	        elWrap.append(elTitle);
		}

        if(oJson.creator.indexOf('pachube')!=-1 || oJson.creator.indexOf('albertonaranjo')!=-1 ){
        
            elWrap.append('<hr>');
			elWrap.append('<span>Historical Graphs '+oJson.datastreams[0].unit.symbol+': </span><a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[0].id+'\',2,\''+escape(oJson.datastreams[0].unit.symbol)+'\')">Last 24 hours</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[0].id+'\',3,\''+escape(oJson.datastreams[0].unit.symbol)+'\')">Last 4 days</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[0].id+'\',4,\''+escape(oJson.datastreams[0].unit.symbol)+'\')">Last 3 months</a>' );
			
			
			if(oJson.title.indexOf('Safecast')!=-1 && oJson.datastreams.length > 1){
				elWrap.append('<br /><span>Historical Graphs '+oJson.datastreams[1].unit.symbol+': </span><a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',2,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 24 hours</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',3,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 4 days</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',4,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 3 months</a>' );
			}
			if(oJson.title.indexOf('Omotesando')!=-1 && oJson.datastreams.length > 1){
				elWrap.append('<br /><span>Historical Graphs '+oJson.datastreams[1].unit.symbol+': </span><a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',2,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 24 hours</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',3,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 4 days</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',4,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 3 months</a>' );
			}
			        	
     	}else if(oJson.creator.indexOf('germany')!=-1){
        
            elWrap.append('<hr>');
			elWrap.append('<span>Historical Graphs '+oJson.datastreams[1].unit.symbol+': </span><a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',2,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 24 hours</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',3,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 4 days</a> | <a class="historicalLink" href="javascript:;" onClick="showPachubeHistory(\''+oJson.id +'\', \''+oJson.datastreams[1].id+'\',4,\''+escape(oJson.datastreams[1].unit.symbol)+'\')">Last 3 months</a>' );
			
			        	
     	} else if(oJson.creator.indexOf('mext')!=-1 || oJson.creator.indexOf('fleep')!=-1 || oJson.creator.indexOf('fukushima_fleep')!=-1 || oJson.creator.indexOf('fukushima_daiichi')!=-1){
     	
     		elWrap.append('<hr>');
			elWrap.append('<span>Historical Graphs: </span><a class="historicalLink" href="javascript:;" onClick="showMextHistory(\''+oJson.creator +'\',\''+oJson.id +'\',2,\''+oJson.datastreams[0].unit.symbol+'\')">Last 24 hours</a> | <a class="historicalLink" href="javascript:;" onClick="showMextHistory(\''+oJson.creator +'\',\''+oJson.id +'\',3,\''+oJson.datastreams[0].unit.symbol+'\')">Last 4 days</a> | <a class="historicalLink" href="javascript:;" onClick="showMextHistory(\''+oJson.creator +'\',\''+oJson.id +'\',4,\''+oJson.datastreams[0].unit.symbol+'\')">Last 3 months</a>' );
     	}

        if(oJson.creator.indexOf('rdtn.org')!=-1 || oJson.creator.indexOf('government')!=-1 || oJson.creator.indexOf('reactors')!=-1 || oJson.creator.indexOf('epa')!=-1){
        	 elWrap.append(elDescription);
        }


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
    
    parser.getXmlData();
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