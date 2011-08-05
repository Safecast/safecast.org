<?php
include './inc/init.php';
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
$tmpl->add('js',  'script/drive.js');
$tmpl->set('title', 'Safecast');
$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('formallyKnownAs', $translations->formallyKnownAs);
$tmpl->set('aboutCaps', $translations->about);
$tmpl->set('maps', $translations->maps);
$tmpl->set('wiki', $translations->wiki);
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
$tmpl->set('pageName', 'drives');
$tmpl->set('lang', $lang);


$tmpl->place('header');
$driveId = fRequest::get('id',"integer" );
?>

<div class="content">
	<div class="relativeWrap">
		<div class="twoColumnWidth">
			<div id="map_canvas"></div>
			<div id="mapInfo">
			<h2 class="title"><span style="font-size:10pt;font-weight:bold"></span></h2>
			<br /><br />Latitude:<br /> Longitude:<br /><hr>
			Reading value:<br />Derived dose rate:<br />Time of reading: <br /><br />
			
			</div>
		</div>
	</div>
</div>

<div class="hiddenDiv">
	<div id="mapLegend">
		<hr>
		<img src="images/7_black.png" /> >= 1050 CPM<br />
		<img src="images/6_darkRed.png" /> >= 680 CPM<br />
		<img src="images/5_red.png" /> >= 420 CPM<br />
		<img src="images/4_darkOrange.png" /> >= 350 CPM<br />
		<img src="images/3_orange.png" /> >= 280 CPM<br />
		<img src="images/2_yellow.png" /> >= 175 CPM<br />
		<img src="images/2_lightGreen.png" /> >= 105 CPM<br />
		<img src="images/1_green.png" /> >= 70 CPM<br />
		<img src="images/0_midgreen.png" /> >= 35 CPM<br />
		<img src="images/white.png" /> <  35 CPM 
	</div>
	<div id="downloadLinks">
		<hr>
		<h2 class="title"><span style="font-size:10pt;font-weight:bold">Download data</span></h2>
		<a id="kmlLink">KML</a>
		<a id="csvLink">CSV</a>
	</div>
</div>

<?php echo '<script type="text/javascript">var which='.$driveId.';</script>'; ?>
<?php $tmpl->place('footer') ?>
