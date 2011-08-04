<?php
include '../inc/init.php';
include '../inc/flourishDB.php';
header('Content-type: application/json; charset=utf-8');

$readings = fRecordSet::build('Reading', array('is_verified=' => 1, 'reading_date>' => array(new fDate('-2 days'))), NULL, 100, 0);

echo '{"results":[';
$datastreams = "";		
$jsonData = "";
foreach ($readings as $reading) {
	$user = $reading->createUser();
	$unit = $reading->createUnit();
	$datastreams.= '{"title": "'.$user->getFirstName().' '.$user->getLastName().'",'.
			'"title_jp": "",'.
			'"description": "Equipment used: '.rtrim(fHTML::encode($reading->getEquipment())).'",'.
			'"source": "rdtn.org",'.
			'"creator": "rdtn.org",'.
			'"feed": "http://www.rdtn.org/feeds/readings/'.$reading->getReadingId().'.json",'.
			'"location": {"lon":'.$reading->getLng().', "lat":'.$reading->getLat().', "name": ""},'.
			'"id":'.$reading->getReadingId().','.
			'"datastreams": [';
	$success = false;
	//foreach($stationdatas as $stationdata){	
		//$sa = $stationdata->getSa();	
		//if($sa!=-888 && $sa!=-999){
			$datastreams.= '{"at": "'.$reading->getReadingDate().'",'.
					'"max_value": "'.$reading->getReadingValue().'",'.
					'"min_value": "'.$reading->getReadingValue().'",'.
					'"current_value": "'.$reading->getReadingValue().'",'.
					'"id": "'.$reading->getReadingId().'",'.
					'"unit": {"type": "' . $unit->getUnitType() . '","label": "' . $unit->getUnitLabel() . '","symbol": "' . $unit->getUnitSymbol() . '"}}';
			$success=true;
		//	break;
		//}
				
	//}
	if($success){
		//close and append
		$datastreams.= ']},';
		$jsonData.=$datastreams;
	}
	$datastreams="";
}

echo rtrim($jsonData, ',');
echo '], "itemsPerPage": '.$readings->count().', "startIndex": 0, "totalResults": '.$readings->count(TRUE).'}';

?>


