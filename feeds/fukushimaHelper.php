<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';
$ch = curl_init();
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://fleep.com/earthquake/data/fukushima01_radiation.csv");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// grab URL contents
$output = curl_exec($ch);
$lines = preg_split('/[\n\r]+/', $output);
$headerLine = array_shift($lines);

$lastInserts = fRecordSet::buildFromSQL(
    'Fleepdata',
    "SELECT * FROM  `fleepdatas` 
WHERE  `station_id` IN (101,102,103,104,105,106,107,108)
ORDER BY  `reading_date` DESC 
LIMIT 1"
); 
$lastDate = $lastInserts[0]->getReadingDate();

print_r($lastInserts);

print_r($lastDate);



foreach($lines as $line){
	$columns = explode(",",$line);
	$date = str_replace(".", "-", $columns[0]);
	$time = $columns[1];
	$datetime = $date." ".$time.":00";
	$timestamp = new fTimestamp($datetime);
	print_r($timestamp);
	if($lastDate->gte($timestamp)){
		echo "skipping row <br />";
		continue;
	}
	echo "successfull Row \n\r";
	if($columns[2]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(101);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[2]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[3]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(102);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[3]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[4]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(103);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[4]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[5]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(104);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[5]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[6]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(105);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[6]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[7]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(106);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[7]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[8]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(107);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[8]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
	if($columns[9]!=""){
		try {
		    $reading = new Fleepdata();
		    $reading->setReadingDate($datetime);
		    $reading->setStationId(108);
		    $reading->setUnitId(3);
		    $reading->setReadingValue($columns[9]);
		    $reading->store();		 
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
	}
}

?>
