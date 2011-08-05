<?php
include './inc/init.php';
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
//$tmpl->add('js',  'script/maps.js');
$tmpl->add('js',  'script/jquery.xml2json.pack.js');
$tmpl->add('js',  'script/pachube.js');
$tmpl->add('js', 'http://widgets.twimg.com/j/2/widget.js');
$tmpl->add('js', '/script/fancybox/jquery.fancybox-1.3.4.pack.js');
$tmpl->add('js', '/script/fancybox/jquery.mousewheel-3.0.4.pack.js');
$tmpl->add('js', '/script/flot/jquery.flot.js');
$tmpl->add('js', '/script/flot/jquery.flot.axislabel.js');
$tmpl->add('css', '/script/fancybox/jquery.fancybox-1.3.4.css');
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

$tmpl->place('header');
?>
			<div class="content">
				<div class="mapSearchRow">
					<input id="address" class="mapSearch" type="textbox" placeholder="Enter location"><input type="button" id="addressSubmit" class="searchButton" onclick="codeAddress()" value=" "> 
				</div> 
				<div class="relativeWrap">
					<div class="twoColumnWidth">
						<div id="map_canvas"></div>
						<!--br/>
						<div id="latestReadings">
							<div class="sectionHeadLeft">Latest Readings</div>
						</div-->
						<br/>
						<div class="rightColumnLegend">
                          <a href="/submit?lang=<?php echo $lang; ?>"><img src="images/submitReading_<?php echo $lang ?>.png" alt="Submit A Reading" width="120" class="submitButtonImage" /></a>
						  <div class="sectionHeadRight"><?php echo $translations->legend; ?></div>
						  <div class="legendpins">
						      <div class="pin"><img src="/images/purple_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->safecastSubmission; ?> </div>
						      <div class="pin"><img src="/images/black_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->mextOfJapan; ?> </div>
						      <div class="pin"><img src="/images/blue_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->pachube; ?> </div>
						      <div class="pin"><img src="/images/red_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->radiationCrowdmap; ?> </div>
						      <div class="pin"><img src="/images/yellow_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->usPublicHealth; ?> </div>
						      <div class="pin"><img src="/images/dark_orange2_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->fukushimaPrefecture; ?> </div>
						      <div class="pin"><img src="/images/greenish_grey_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->greenpeace; ?></div>
						      <div class="pin"><img src="/images/purple2_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->usEpa; ?></div>
						      <div class="pin"><img src="/images/darkblue_pin.png" class="pinImage" height="24" width="15"/> <?php echo $translations->radiactividad; ?></div>
						      <div class="pin"><img src="/images/map_POI.png" class="pinImage" height="12" width="12"/> <span  style="position:relative;top:3px;left:3px;"><?php echo $translations->nuclearPowerPlant; ?> </span></div>
						      
    						  <div style="padding:4px;" class="disclaimer"><?php echo $translations->legendBlurb; ?> </div>
						  </div>
				        </div>
<!--
				        <div class="chart">
				        	<div class="sectionHeadRight">Symptoms of airborne radiation within 1 day of exposure.</div>
				        	<div></div><img src="../images/placeholder_graphic.png" alt="placeholder_graphic" width="" height="" /></div>
				        </div>
-->
						
					
				</div>
			</div>
<script>
	$("#address").keyup(function(event){
	  if(event.keyCode == 13){
	    $("#addressSubmit").click();
	  }
	});
</script>
<?php $tmpl->place('footer') ?>
