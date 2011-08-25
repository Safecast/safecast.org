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

$tmpl->place('header');
?>
			<div class="content">
				<div class="relativeWrap">
					<div class="fullScreenWidth">
						<div id="fusion_canvas" style="position: relative;"></div>
						<div id="info_under" style="position:absolute; top: 0px; right: 0px; height:6em; width:200em; background-color:gray; opacity:0.85;">
							<span id="info_size" style="position:absolute; z-index:-1; visibility: hidden">__2011-07-09T18:35:29.000+0900__</span>
						</div>
						<div id="info" style="position:absolute; top: 0px; right: 0px; height:6em; width:200em;" onclick="go_back();"></div>
						<div id="search" style="position: absolute; top: 4.5em; right:0;">
							<input type="text" size="10" id="addr" value="Go to..." onkeydown="if (event.keyCode == 13) { center_map(); return false;}" onfocus="this.select()"; onclick="this.select();" style="background: #0bb;" />
						</div>
						<div id="legend" style="position:absolute; top: 6em; right: 0px;">
							<img src="images/fusionLegend.png" height="120px" width="80px" alt="legend" />
						</div>
					</div>
				</div>
				<script type="text/javascript">
				<!--
					document.getElementById('info_under').style.width  = document.getElementById('info_size').clientWidth + 'px'; 
					document.getElementById('info_under').style.height = document.getElementById('info_size').clientHeight * 4.5 + 'px'; 
					document.getElementById('info').style.width            = document.getElementById('info_size').clientWidth + 'px'; 
					document.getElementById('info').style.height             = document.getElementById('info_size').clientHeight * 4.5 + 'px'; 
				//--> 
				</script>

<?php $tmpl->place('footer') ?>
