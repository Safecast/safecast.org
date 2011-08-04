<?php
include './inc/init.php';
include './inc/flourishDB.php';

$users = fRecordSet::build('User');
$submissions = fRecordSet::build('Reading');
$totalSubmissions = $submissions->count();
$totalSubmissions-= 610;
$japanSubmissions = fRecordSet::buildFromSQL(
    'Reading',
    "SELECT * FROM `readings` WHERE `lat` > 33.267 AND `lat` < 43.3098 AND `lng` > 130.41 AND `lng` < 141.9236"
);






$tmpl->set('title', 'Stats');
$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('formallyKnownAs', $translations->formallyKnownAs);
$tmpl->set('maps', $translations->maps);
$tmpl->set('blog', $translations->blog);
$tmpl->set('forums', $translations->forums);
$tmpl->set('submitAReading', $translations->submitAReading);
$tmpl->set('languageSelect', $translations->languageSelect);
$tmpl->set('contact', $translations->contact);
$tmpl->set('follow', $translations->follow);
$tmpl->set('termsPolicy', $translations->termsPolicy);
$tmpl->set('pageName', 'stats');
$tmpl->place('header');
?>

<div class="content">
	<div class="relativeWrap">
		<div class="twoColumnWidth">
			<div id="map_canvas">
			<h1><b><?php echo $users->count(); ?></b> Total Users</h1>
			<h1><b><?php echo $totalSubmissions; ?></b> Total Submissions</h1>
			<h1><b><?php echo $japanSubmissions->count(); ?></b> Submissions in Japan</h1></div>
			<div class="rightColumnLegend">
	        </div>
		</div>
	</div>
<?php $tmpl->place('footer') ?>