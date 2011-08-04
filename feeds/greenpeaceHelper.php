<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';
//$stations = fRecordSet::build('Station', NULL, NULL, 100, 100);
$stationdatas = fRecordSet::buildFromSQL(
    'Greenpeacedata',
    "SELECT * FROM greenpeacedatas WHERE reading_date > ADDDATE(NOW(), INTERVAL -2 DAY)"
);

$output = '{"results":[';
$datastreams = "";		
$jsonData = "";
foreach ($stationdatas as $stationdata) {
	$datastreams.= '{"title": "Greenpeace Reading",'.
			'"title_jp": "Greenpeace Reading",'.
			'"description": "",'.
			'"creator": "greenpeace",'.
			'"feed": "",'.
			'"location": {"lon":'.$stationdata->getLongitude().', "lat":'.$stationdata->getLatitude().', "name": "'.$stationdata->getComments().'"},'.
			'"id":"",'.
			'"datastreams": [';
	$success = false;
	$datastreams.= '{"at": "'.$stationdata->getReadingDate().'",'.
			'"max_value": "'.$stationdata->getMicrosieverts().'",'.
			'"min_value": "'.$stationdata->getMicrosieverts().'",'.
			'"current_value": "'.$stationdata->getMicrosieverts().'",'.
			'"id": "'.$stationdata->getId().'",'.
			'"unit": {"type": "basicSI","label": "microsieverts per hour","symbol": "μSv/h"}}';
	$success=true;
				
	if($success){
		//close and append
		$datastreams.= ']},';
		$jsonData.=$datastreams;
	}
	$datastreams="";
}

$output.= rtrim($jsonData, ',');
$output.= '], "itemsPerPage": '.$stationdatas->count().', "startIndex": 0, "totalResults": '.$stationdatas->count(TRUE).'}';

$file = new fFile(DOC_ROOT . '/feeds/greenpeaceStatic.json');
$file->write($output);
echo $output;
?>


