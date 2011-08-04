<?php
include '../inc/init.php';
include '../inc/flourishDB.php';
header('Content-type: application/json; charset=utf-8');


$stations = fRecordSet::buildFromSQL(
    'GovernmentReading',
    "SELECT r1.city_id, r1.reading_value, r1.reading_date FROM government_readings r1 WHERE r1.reading_date = (SELECT max(reading_date) FROM government_readings r2 WHERE r2.city_id = r1.city_id) AND r1.reading_date > ADDDATE(NOW(), INTERVAL -2 DAY)"
);

echo '{"results":[';
$datastreams = "";		
$jsonData = "";
foreach ($stations as $station) {
	$stationdatas = $station->buildStationdatas();
	
	$datastreams.= '{"title": "'.$station->getStationName2En().'",'.
			'"title_jp": "'.$station->getStationName2Jp().'",'.
			'"description": "",'.
			'"creator": "uncorked",'.
			'"feed": "http://www.rdtn.org/feeds/station/'.$station->getStationId().'.json",'.
			'"location": {"lon":'.$station->getLongitude().', "lat":'.$station->getLatitude().', "name": "'.$station->getPrefectureNameEn().'"},'.
			'"id":'.$station->getStationId().','.
			'"datastreams": [';
	$success = false;
	foreach($stationdatas as $stationdata){	
		$sa = $stationdata->getSa();	
		if($sa!=-888 && $sa!=-999){
			$datastreams.= '{"at": "'.$stationdata->getDatetimeString().'",'.
					'"max_value": "'.$stationdata->getRa().'",'.
					'"min_value": "'.$stationdata->getRa().'",'.
					'"current_value": "'.$stationdata->getSa().'",'.
					'"id": "'.$stationdata->getReadingId().'",'.
					'"unit": {"type": "basicSI","label": "n","symbol": "Gy/h"}}';
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

echo rtrim($jsonData, ',');
echo '], "itemsPerPage": '.$stations->count().', "startIndex": 0, "totalResults": '.$stations->count(TRUE).'}';

?>


