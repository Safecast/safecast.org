<?php
include './inc/init.php';
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
$tmpl->add('js',  'script/fusionMap.js');
$tmpl->set('title', 'Safecast');
$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('formallyKnownAs', $translations->formallyKnownAs);
$tmpl->set('aboutCaps', $translations->about);
$tmpl->set('maps', $translations->maps);
$tmpl->set('blog', $translations->blog);
$tmpl->set('japan', $translations->japan);
$tmpl->set('forums', $translations->forums);
$tmpl->set('submitAReading', $translations->submitAReading);
$tmpl->set('languageSelect', $translations->languageSelect);
$tmpl->set('contact', $translations->contact);
$tmpl->set('follow', $translations->follow);
$tmpl->set('termsPolicy', $translations->termsPolicy);
$tmpl->set('conceivedPart1', $translations->conceivedPart1);
$tmpl->set('conceivedPart2', $translations->conceivedPart2);
$tmpl->set('pageName', 'index');
$tmpl->set('lang', $lang);

$lat = fRequest::get('lat',"float?" );
$lon = fRequest::get('lon',"float?" );

$tmpl->place('header');

?>
<div class="content">
	<div class="relativeWrap">
		<div id="safecast_map" style="position:relative;" class="fullScreenWidth">

				<div class="sectionHeadRight">Safecast Full Dataset Visualization</div>
				<div class="wideBoxContent">
				<p>Below is a map plotting all the data accumulated from our Safecasting trips.  Each dot represents many readings from the gieger counters. As you zoom in the dots will become more granular.
				You may focus the map on a specific location by typing the location or point of interest into the "Go to..." box: (Example: Fukushima or Iwaki)
				<div id="dateUpdated"></div></p></div>
			<div id="search" >
			Change map location: 
				<input type="text" size="20" id="addr" value="Go to..." onkeydown="if (event.keyCode == 13) { center_map(); return false;}" onfocus="this.select()"; onclick="this.select();" style=" margin-bottom: 10px;" />
			</div>
			<div id="info_box" >
					<div id="info_under" > 
						<span id="info_size" >__2011-07-09T18:35:29.000+0900__</span>
					</div>
					<div id="info"  onclick="go_back();" ></div>
					<!--<div id="legend" style="padding-top: 5px; width: 265px;">
						<img src="images/fusionLegend.png" height="120px" width="80px" alt="legend" />
					</div>-->
					<div id="legend" >
						<div id="legendHeader" style="font-weight: bold;">&#xB5Sv/h </div> 	
						<img src="images/dots/dot-green.jpg" /> < 0.2 <br> 
						<img src="images/dots/dot-yellow.jpg" /> < 0.5 <br> 
						<img src="images/dots/dot-pink.jpg" /> < 1.0 <br> 
						<img src="images/dots/dot-purple.jpg" /> < 5.0 <br> 
						<img src="images/dots/dot-red.jpg" /> < 10.0 <br> 
						<img src="images/dots/map.jpg" /> >= 10.0 <br> 
					</div>	
			</div>
			<div id="fusion_canvas" style="position: relative;"></div>
			<div id="logo" style="position:absolute; bottom: 10px; right: 0px;">
				<a href="http://maps.safecast.org/" target="_blank"><img src="images/SafeCast_logo.png" height="74px" width="64px" alt="logo" /></a>
			</div>
			<script type="text/javascript">
			<!--
				//document.getElementById('info').style.width = document.getElementById('info_under').style.width = document.getElementById('info_size').clientWidth + 'px'; 
			//-->		
				var mLat = <?php echo $lat; ?>;
				var mLon = <?php echo $lon; ?>;
			</script>
		</div>

<?php $tmpl->place('footer') ?>
