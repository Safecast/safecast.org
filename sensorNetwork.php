<?php
include './inc/init.php';
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
$tmpl->add('js', '/script/fancybox/jquery.fancybox-1.3.4.pack.js');
$tmpl->add('js',  'script/sensorNetworkMap.js');
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

$lat = fRequest::get('lat',"float?" );
$lon = fRequest::get('lon',"float?" );

$tmpl->place('header');

?>
<div class="content">
	<div class="relativeWrap">
		<div id="safecast_map" style="position:relative;" class="fullScreenWidth">

				<div class="sectionHeadRight"><?php echo $translations->sensorNetworkTitle; ?></div>
				<div class="wideBoxContent">
				<p><?php echo $translations->sensorNetworkBody1; ?></p>
				<p>These sensors are being deployed in partnership with KEIO University's <a href="http://scanningtheearth.org/">Scanning The Earth</a> project</p>
				</div>
			<div id="sensor_network_canvas" style="position: relative;"></div>
			<div id="logo" style="position:absolute; bottom: 10px; right: 0px;">
				<a href="http://maps.safecast.org/" target="_blank"><img src="images/SafeCast_logo.png" height="74px" width="64px" alt="logo" /></a>
			</div>
		</div>

<?php $tmpl->place('footer') ?>
