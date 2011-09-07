<?php
include './inc/init.php';
include './inc/flourishDB.php';
include './inc/pdoDB.php';

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


	$sql = 'SELECT * FROM drives ORDER BY drive_id DESC';
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);	

	$tmpl->set('title', 'List of Drives');
	$tmpl->place('header');
//	$drives = fRecordSet::build('Drive');
	
	echo '<script language="Javascript">var currentDrive='.$driveId.';</script>
	<script type="text/javascript" language="javascript" src="/script/jquery.dataTables.js"></script>
<div class="content">

				<div class="relativeWrap">
				<div class="sectionHeadRight">Safecasting Trips</div>
				<div class="wideBoxContent">
				<p>Below is the full list of each individual Safecasting trip that has contributed radiation readings to our database.  Clicking on an individual trip will display a map with all the data from that Safecasting trip.
				You may search the list by typing a location or point of interest into the filter box: (Example: Fukushima or Iwaki)</p></div>
					<div class="crudWrap driveWrap">
						<div id="submitFormBody" class="form">
				<table id="driveTable">
				<thead>
					<tr>
						<th><br></th>
						<th><br></th>
						<th><br></th>
					</tr>
				</thead>
				<tbody>';
	$odd = true;				
	while ($data = $result->fetch()) {
		if($odd){
			echo '<tr class="crudOddRow driveRow" onMouseOver="style.backgroundColor=\'#5EA6C0\'" onMouseOut="style.backgroundColor=\'#d7d7d7\'" onClick="document.location.href=\'/drive/'.$data->drive_id.'\'; return false;">';
		}else{
			echo '<tr class="crudEvenRow driveRow" onMouseOver="style.backgroundColor=\'#5EA6C0\'" onMouseOut="style.backgroundColor=\'#c7c7c7\'" onClick="document.location.href=\'/drive/'.$data->drive_id.'\'; return false;">';
		}
		$odd = !$odd;
		$date = date("Y-m-d", strtotime($data->drive_date));
		echo '<td class="driveColumn dateColumn">'.$date.'</td><td class="driveColumn"><span class="description">'.$data->route_description.'</span><br><h3>'.$data->locations.'</h3><span class="drivers">Safecasters: '.$data->drivers.'</span></td><td class="driveColumn idColumn">'.$data->drive_id.'</td></tr>';
	}
	echo '</tbody>
		</table>';
	echo '<span id="last" />';
	
	echo '</div>
		</div>
	</div>
</div>';

	echo '<script type="text/javascript">
		$(document).ready(function(){
			$("#driveTable").dataTable({
				"bLengthChange":false,
				"bPaginate": false,
				"bInfo": false,
				"bSort": false,
				"oLanguage": {
					"sSearch": "Filter list by: "
				}
			});
			$(".driveNum").each(function(i,v) {
				v = $(v);
				var txt = v.html();
				v.html("<a href=\"/drive/" + txt + "\" target=\"_top\">" + txt + "</a>");	
			});
		} );

	</script>';
	$tmpl->place('footer')
?>
