<?php
include './inc/init.php';
include './inc/flourishDB.php';


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
$tmpl->set('lang', $lang);

$tmpl->set('pageName', 'drives');
$action = fRequest::get('action',"string?" );
$driveId = fRequest::get('driveId',"integer" );


$existingLatitude = "";
$existingLongitude = "";
$existingDescription = "";
$existingDrivers = "";
$existingZoom = "13";

	$tmpl->set('title', 'List of Drives');
	$tmpl->place('header');
	$drives = fRecordSet::build('Drive');
	
	echo '<script language="Javascript">var currentDrive='.$driveId.';</script><div class="content">
				<div class="relativeWrap">
					<div class="crudWrap">
						<div id="submitFormBody" class="form">
				<table>
					<tr>
						<th>Drive Description</th>
						<th>Participants</th>';
	$odd = true;				
	foreach ($drives as $drive) {
		if($odd){
			echo '<tr class="crudOddRow">';
		}else{
			echo '<tr class="crudEvenRow">';
		}
		$odd = !$odd;
    	echo '<td><a href="/drive/'.$drive->getDriveId().'" target="_top">'.$drive->getRouteDescription().'</a></td><td>'.$drive->getDrivers().'</td></tr>';
	}
		echo '</table>';

	
	
	echo '</div>
		</div>
	</div>
</div>';

	$tmpl->place('footer')
?>
