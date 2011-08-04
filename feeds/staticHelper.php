<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';
//$stations = fRecordSet::build('Station', NULL, NULL, 100, 100);
$stations = fRecordSet::buildFromSQL(
    'Station',
    "SELECT * FROM stations"
);

$output = '{"results":[';
$datastreams = "";		
$jsonData = "";
foreach ($stations as $station) {
	//$stationdatas = $station->buildStationdatas();
	$stationdatas = fRecordSet::buildFromSQL('Stationdata',
    array("SELECT * FROM `stationdatas` WHERE `station_id`=%i ORDER BY `reading_date` DESC LIMIT 0 , 6",$station->getStationId()));
	//$stationdatas->sort('getDatetimeString','desc');
	$datastreams.= '{"title": "'.$station->getStationName2En().'",'.
			'"title_jp": "'.$station->getStationName2Jp().'",'.
			'"description": "",'.
			'"creator": "mext",'.
			'"feed": "http://www.rdtn.org/feeds/station/'.$station->getStationId().'.json",'.
			'"location": {"lon":'.$station->getLongitude().', "lat":'.$station->getLatitude().', "name": "'.$station->getPrefectureNameEn().'"},'.
			'"id":'.$station->getStationId().','.
			'"datastreams": [';
	$success = false;
	foreach($stationdatas as $stationdata){	
		$sa = $stationdata->getSa();	
		if($sa!=-888 && $sa!=-999){
			$datastreams.= '{"at": "'.$stationdata->getReadingDate().'",'.
					'"max_value": "'.$stationdata->getRa().'",'.
					'"min_value": "'.$stationdata->getRa().'",'.
					'"current_value": "'.$stationdata->getSa().'",'.
					'"id": "'.$stationdata->getReadingId().'",'.
					'"unit": {"type": "basicSI","label": "nano Gray per hour","symbol": "nGy/h"}}';
			$success=true;
			break;
		}
				
	}
	if($success){
		//close and append
		$datastreams.= ']},';
		$jsonData.=$datastreams;
	}
	$datastreams="";
}

$output.= rtrim($jsonData, ',');
$output.= '], "itemsPerPage": '.$stations->count().', "startIndex": 0, "totalResults": '.$stations->count(TRUE).'}';

$file = new fFile(DOC_ROOT . '/feeds/jrodStatic.json');
$file->write($output);
echo $output;
?>


