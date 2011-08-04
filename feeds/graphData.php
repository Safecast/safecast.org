<?php
include '../inc/init.php';
include '../inc/flourishDB.php';
$stationId = fRequest::get('id',"integer?" );
$which = fRequest::get('which',"string?");

if($which=="mext"){
echo '{ "data" : [';
		
		$readings = fRecordSet::build(
		    'Stationdata',
		    array('station_id=' => $stationId),
		    array('reading_date' => 'asc')
		);
		$varstring = '';
		$i = 0;
		foreach ($readings as $reading) {
			if ($reading->getSa() > 0) {
				if ($i > 0) {
					$varstring .= ',';
				}
				$date = $reading->getReadingDate();
				$timestamp = strtotime($date);
				$milli = $timestamp * 1000;
				$varstring .= '['.$milli.','.$reading->getSa().']';
				$i++;
			}
		}
		echo $varstring;
    	echo '], "label": "nGy/h"}'; 
}else if($which=="fleep"){
	echo '{ "data" : [';
		
		$readings = fRecordSet::build(
		    'Fleepdata',
		    array('station_id=' => $stationId),
		    array('reading_date' => 'asc')
		);
		$varstring = '';
		$i = 0;
		foreach ($readings as $fleepdata) {
			if ($fleepdata->getReadingValue() > 0) {
				if ($i > 0) {
					$varstring .= ',';
				}
				$date = $fleepdata->getReadingDate();
				$timestamp = strtotime($date);
				$milli = $timestamp * 1000;
				$varstring .= '['.$milli.','.$fleepdata->getReadingValue().']';
				$i++;
			}
		}
		echo $varstring;
    	echo '], "label": "&#181;Sv/h"}';
}else if($which=="fukushima_fleep"){
	echo '{ "data" : [';
		
		$readings = fRecordSet::build(
		    'Fukushimadata',
		    array('station_id=' => $stationId),
		    array('reading_date' => 'asc')
		);
		$varstring = '';
		$i = 0;
		foreach ($readings as $fleepdata) {
			if ($fleepdata->getReadingValue() > 0) {
				if ($i > 0) {
					$varstring .= ',';
				}
				$date = $fleepdata->getReadingDate();
				$timestamp = strtotime($date);
				$milli = $timestamp * 1000;
				$varstring .= '['.$milli.','.$fleepdata->getReadingValue().']';
				$i++;
			}
		}
		echo $varstring;
    	echo '], "label": "&#181;Sv/h"}';
}