<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include '../inc/init.php';
include '../inc/pdoDB.php';

// ##################################################################
// functions for writing out content to each format
// ##################################################################

function populateJSON($data){
	global $jsonOutput;
	$jsonOutput.= '{"lon":'.$data->longitude.
					', "lat":'.$data->latitude.
					', "name": "",'.
					'"current_value": "'.$data->reading_value.'",'.
					'"cpm_value": "'.$data->alt_reading_value.'",'.
					'"id": "'.$data->reading_id.'",'.
					'"at": "'.$data->reading_date.'",'.
					'"label": "'.$data->unit_symbol.'"},';	
}

function populateCSV($data){
	global $csvOutput, $driveId;
	$csvOutput.= $driveId.','.$data->reading_date.','.$data->alt_reading_value.','.$data->latitude.','.$data->longitude.','.$data->gps_altitude.','.$data->gps_precision.','.$data->gps_quality_indicator.'
';
}

function populateKML($data){
	global $kmlOutput, $driveDesc, $driveId, $driveName;
	$current_value = $data->reading_value;
	$cpm_value = $data->alt_reading_value;
	$id = $data->reading_id;
	$date = $data->reading_date;
	$label = $data->unit_symbol;
	$desc = $driveDesc;
	$name = $driveName;
	$altitude = $data->gps_altitude;
	$gps_precision = $data->gps_precision;
	$gps_quality = $data->gps_quality_indicator;
	$latitude = $data->latitude;
	$longitude = $data->longitude;
	
	if($current_value >= 3.0) {
		$color = '#grey';
	}
	elseif($current_value >= 1.8) {
		$color = '#darkRed';
	}
	elseif($current_value >= 1.2) {
		$color = '#red';
	}
	elseif($current_value >= 1.0) {
		$color = '#darkOrange';
	}
	elseif($current_value >= 0.8) {
		$color = '#orange';
	}
	elseif($current_value >= 0.5) {
		$color = '#yellow';
	}
	elseif($current_value >= 0.3) {
		$color = '#lightGreen';
	}
	elseif($current_value >= 0.2) {
		$color = '#green';
	}
	elseif($current_value >= 0.1) {
		$color = '#midgreen';
	}
	else {
		$color = '#white';
	}

	$kmlOutput.= '<Placemark>'.
			'<name>'.$name.'</name>'.
			'<description>
			
<![CDATA[<html xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:msxsl="urn:schemas-microsoft-com:xslt">

<head>

<META http-equiv="Content-Type" content="text/html">

</head>

<body style="margin:0px 0px 0px 0px;overflow:auto;background:#FFFFFF;">

<table style="font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:300;border-collapse:collapse;padding:3px 3px 3px 3px">

<tr style="text-align:center;font-weight:bold;background:#9CBCE2">

<td>SAFECAST</td>
</tr>

<table style="font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:300;border-spacing:0px; padding:3px 3px 3px 3px">

<tr>

<td>Name</td>
<td>'.$name.'</td>
</tr>
<tr>
<td>Current Value</td>
<td>'.$current_value.'</td>
</tr>
<tr>
<td>CPM Value</td>
<td>'.$cpm_value.'</td>
</tr>
<tr>
<td>ID</td>
<td>'.$id.'<td>
</tr>
<tr>
<td>Date</td>
<td>'.$date.'</td>
</tr>
<tr>
<td>Label</td>
<td>'.$label.'</td>
</tr>
</table>
</table>
</body>
</html>]]>
			</description>'.
			'<styleUrl>'.$color.'</styleUrl>'.
			'<Point>
<altitudeMode>clampToGround</altitudeMode>
<coordinates>'.$longitude.','.$latitude.'</coordinates>'.
			'</Point>'.
			'</Placemark>';
}


// ##################################################################
// init
// ##################################################################
$driveId = 6;

//$driveId = $_GET['id'];
$driveId = fRequest::get("id","integer?");
if(!$driveId){
	$driveId=6;
}
$done = false;
$iterated = 0;
$index = 0;
$limit = 1000;
$driveDesc = "";
$driveName = "";

$jsonCount = 0;


// ##################################################################
// Prep starts of outputs
// ##################################################################

$jsonOutput = "";

$kmlOutput = '<?xml version="1.0" encoding="UTF-8"?>'.
		'<kml xmlns="http://www.opengis.net/kml/2.2">'.
		'<Document>
<Style id="grey">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/grey.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="darkRed">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/darkRed.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="red">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/red.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="darkOrange">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/darkOrange.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="orange">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/orange.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="yellow">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/yellow.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="lightGreen">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/lightGreen.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="green">
	<IconStyle>
		<scale>0.5</scale>	
		<Icon>
			<href>http://www.safecast.org/kml/green.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="midgreen">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/midgreen.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>
<Style id="white">
	<IconStyle>
		<scale>0.5</scale>
		<Icon>
			<href>http://www.safecast.org/kml/white.png</href>
		</Icon>
        </IconStyle>
	<LabelStyle>
		<color>00000000</color>
		<scale>0</scale>
	</LabelStyle>
	<PolyStyle>
		<color>ff000000</color>
		<outline>0</outline>
	</PolyStyle>
</Style>';

$csvOutput = '# drive id, datetime, CPM, latitude, longitude, altitude, gps precision, gps quality
';





// ##################################################################
// database connet and drive info
// ##################################################################
  

$sql = "SELECT * FROM drives WHERE `drive_id` = ".$driveId;
$result = $db->query($sql);
$result->setFetchMode(PDO::FETCH_OBJ);
echo "drive_id: ".$driveId;
print_r($result);
echo "count: ".$result->rowCount();
while ($drive = $result->fetch()) {
	echo "foundDrive: ";
	print_r($drive);
    $jsonOutput.= '{"title": "'.$drive->route_description.'",'.
			'"title_jp": "",'.
			'"description": "'.$drive->drivers.'",'.
			'"locations": "'.$drive->locations.'",'.
			'"creator": "safecast drive",'.
			'"mapLat": '.$drive->latitude.','.
			'"mapLng": '.$drive->longitude.','.
			'"mapZoom": '.$drive->zoom.','.
			'"dataPoints":[';
	$driveDesc = $drive->drivers;
	$driveName = $drive->route_description;
}


/* close the result set */
$result->closeCursor();


// ##################################################################
// main iterative loop
// ##################################################################


// get the count of total records for this drive
$sqlCount = "SELECT count(*) FROM `drivingdatas` WHERE `drive_id` = '".$driveId."'";
$result = $db->query($sqlCount);
$result->setFetchMode(PDO::FETCH_ASSOC);

while ($count = $result->fetch()) {
    $totalRecords = $count["count(*)"];
}
/* close the result set */
$result->closeCursor();

while(!$done){

	$sql = "SELECT d.*,u.unit_symbol FROM `drivingdatas` as d, `units` as u WHERE d.unit_id=u.unit_id AND d.drive_id = '".$driveId."' LIMIT ".$index.", ".$limit;
	/*
		$sqlCount = "SELECT count(*) FROM `drivingdatas` WHERE `drive_id` = '".$driveId."'";
		$drivingdatas = fRecordSet::buildFromSQL(
		    'Drivingdata',
		    $sql,
		    $sqlCount,
		    $limit,
		    $index
		);
	*/
	
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);

	while ($data = $result->fetch()) {
		$jsonCount++;
		populateJSON($data);
		populateKML($data);
		populateCSV($data);
	}
	$index+=$limit;
	if($index>=$totalRecords){
		$done = true;
	}


}
		


// ##################################################################
// close/finish of formats and write files
// ##################################################################

$kmlOutput.= '</Document>'.
	'</kml>';
$jsonOutput = rtrim($jsonOutput, ',');
$jsonOutput.= '], "itemsPerPage": '.$totalRecords.', "startIndex": 0, "totalResults": '.$totalRecords.'}';

//dont write files unless there were rows returned

if($jsonCount > 0 ) {
	echo DOC_ROOT.'/feeds/driveCache/drive'.$driveId.'.json';
	//write JSON
	try{
		$file = new fFile(DOC_ROOT . '/feeds/driveCache/drive'.$driveId.'.json');
		$file->write($jsonOutput);
	}catch (fExpectedException $e) {
		$new_file = fFile::create(DOC_ROOT . '/feeds/driveCache/drive'.$driveId.'.json', $jsonOutput);
	}catch (fEnvironmentException $e) {
		
	}
	
	//write KML
	try{
		$file = new fFile(DOC_ROOT .'/feeds/driveCache/drive'.$driveId.'.kml');
		$file->write($kmlOutput);
	}catch (fExpectedException $e) {
		$new_file = fFile::create(DOC_ROOT . '/feeds/driveCache/drive'.$driveId.'.kml', $kmlOutput);
	}catch (fEnvironmentException $e) {
		
	}
	
	//write CSV
	try{
		$file = new fFile(DOC_ROOT . '/feeds/driveCache/drive'.$driveId.'.csv');
		$file->write($csvOutput);
	}catch (fExpectedException $e) {
		$new_file = fFile::create(DOC_ROOT . '/feeds/driveCache/drive'.$driveId.'.csv', $csvOutput);
	}catch (fEnvironmentException $e) {
		
	}


}
$db = null;
//echo $output;

?>
