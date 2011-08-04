<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';

$output = file_get_contents("daiichi-mp-May-j.csv");
$lines = preg_split('/[\n\r]+/', $output);
$headerLine = array_shift($lines); //waste the header
$headerLine = array_shift($lines); //there are two lines of header
$lastInserts = fRecordSet::buildFromSQL(
    'Fukushimadata',
    "SELECT * FROM  `fukushimadatas` 
WHERE  `station_id` IN (800,801,802,803,804,805,806,807)
ORDER BY  `reading_date` DESC 
LIMIT 1"
); 
$lastDate = $lastInserts[0]->getReadingDate();
print_r($lastInserts);

print_r($lastDate);


foreach($lines as $line){
	$columns = explode(",",$line);
	$date = str_replace("/", "-", $columns[0]);
	$time = $columns[1];
	$datetime = $date." ".$time.":00";
	$timestamp = new fTimestamp($datetime);
	print_r($timestamp);
	if($lastDate->gte($timestamp)){
		echo "skipping row <br />";
		continue;
	}
	echo "successfull Row \n\r";
	if($columns[2]!="" && $columns[2]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(800);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[2]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[3]!="" && $columns[3]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(801);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[3]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[4]!="" && $columns[4]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(802);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[4]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[5]!="" && $columns[5]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(803);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[5]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[6]!="" && $columns[6]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(804);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[6]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[7]!="" && $columns[7]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(805);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[7]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[8]!="" && $columns[8]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(806);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[8]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[9]!="" && $columns[9]!="N/A"){
		try {
		    $reading = new Fukushimadata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(807);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[9]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}

}

?>