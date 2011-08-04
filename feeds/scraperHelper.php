<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';
header('Content-type: application/json; charset=utf-8');

//$readings = fRecordSet::build('GovernmentReading', NULL, NULL, 100, 100);
$readings = fRecordSet::buildFromSQL(
    'GovernmentReading',
    "select r1.* from government_readings r1 where r1.reading_date > ADDDATE(NOW(), INTERVAL -2 DAY) AND r1.reading_date = (select max(reading_date) from government_readings r2 where r2.city_id = r1.city_id)"
);

$output = '{"results":[';
$datastreams = "";		
$jsonData = "";
foreach ($readings as $reading) {
	$govCity = $reading->createGovernmentLocation();
	$govFeed = $govCity->createGovernmentFeed();

	$datastreams.= '{"title": "'.$govCity->getCityName().'",'.
			'"title_jp": "'.$govCity->getCityName().'",'.
			'"description": "'. $govFeed->getDisplayName() .'",'.
			'"creator": "government",'.
			'"feed": "'. $govFeed->getWebpage() .'",'.
			'"location": {"lon":'.$govCity->getLongitude().', "lat":'.$govCity->getLatitude().', "name": "'.$govCity->getCityName().'"},'.
			'"id":"",'.
			'"datastreams": [';
			
	$success = false;

	$datastreams.= '{"at": "'.$reading->getReadingDate().'",'.
			'"max_value": "'.$reading->getReadingValue().'",'.
			'"min_value": "'.$reading->getReadingValue().'",'.
			'"current_value": "'.$reading->getReadingValue().'",'.
			'"id": "'.$reading->getReadingId().'",'.
			'"unit": {"type": "basicSI","label": "clicks per minute","symbol": "CPM"}}';
	$success=true;

	if($success){
		//close and append
		$datastreams.= ']},';
		$jsonData.=$datastreams;
	}
	$datastreams="";
	
}

$output.= rtrim($jsonData, ',');
$output.= '], "itemsPerPage": '.$readings->count().', "startIndex": 0, "totalResults": '.$readings->count(TRUE).'}';

$file = new fFile(DOC_ROOT . '/feeds/scrapedStatic.json');
$file->write($output);
echo $output;
?>


