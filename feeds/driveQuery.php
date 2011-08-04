<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));

include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';

header('Content-type: application/json; charset=utf-8');

$driveId = fRequest::get("id","integer?");
if(!$driveId){
	$driveId=3;
}
$drive = new Drive($driveId);
$drivingdatas = fRecordSet::build(
    'Drivingdata',
    array('drive_id=' => $driveId)
);

$jsonOutput = '{"title": "'.$drive->getRouteDescription().'",'.
			'"title_jp": "",'.
			'"description": "'.$drive->getDrivers().'",'.
			'"creator": "safecast drive",'.
			'"mapLat": '.$drive->getLatitude().','.
			'"mapLng": '.$drive->getLongitude().','.
			'"mapZoom": '.$drive->getZoom().','.
			'"dataPoints":[';



foreach ($drivingdatas as $data) {
	$unit = $data->createUnit();

	$current_value = $data->getReadingValue();
	$cpm_value = $data->getAltReadingValue();
	$id = $data->getReadingId();
	$date = $data->getReadingDate();
	$label = $unit->getUnitSymbol();
	$desc = $drive->getDrivers();
	$name = $drive->getRouteDescription();
	
	
	$jsonOutput.= '{"lon":'.$data->getLongitude().
				', "lat":'.$data->getLatitude().
				', "name": "",'.
				'"current_value": "'.$current_value.'",'.
				'"cpm_value": "'.$cpm_value.'",'.
				'"id": "'.$id.'",'.
				'"at": "'.$date.'",'.
				'"label": "'.$label.'"},';

	
}

$jsonOutput = rtrim($jsonOutput, ',');
$jsonOutput.= '], "itemsPerPage": '.$drivingdatas->count().', "startIndex": 0, "totalResults": '.$drivingdatas->count(TRUE).'}';


//$file = new fFile(DOC_ROOT . '/feeds/driveStatic.json');
//$file->write($output);
echo $jsonOutput;

?>