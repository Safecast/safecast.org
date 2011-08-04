<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';
include MY_ROOT.'/inc/flourishDB.php';
/*
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "greenpeace.csv");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
*/
// grab URL contents
/* $output = curl_exec($ch); */
$output = file_get_contents("greenpeace.csv");
$lines = preg_split('/[\n\r]+/', $output);
/* $headerLine = array_shift($lines); */

/*
$lastInserts = fRecordSet::buildFromSQL(
    'Fleepdata',
    "SELECT * FROM  `fleepdatas` 
WHERE  `station_id` IN (101,102,103,104,105,106,107,108)
ORDER BY  `reading_date` DESC 
LIMIT 1"
); 
$lastDate = $lastInserts[0]->getReadingDate();

print_r($lastInserts);
*/

/* print_r($lastDate); */



foreach($lines as $line){
	print_r("*************".$line);
	$columns = explode(",",$line);
/* 	$date = str_replace(".", "-", $columns[0]); */
	$date = $columns[1];
	$datetime = $date." 00:00:00";
	$timestamp = new fTimestamp($datetime);
/* 	print_r($timestamp); */
	
//	$latlongs = explode(",",$columns[2]);
	$raw_lat = $columns[2];
/* 	print_r("raw_lat: ".$raw_lat); */
	$raw_lng = $columns[3];
	$lat_pieces = explode(" ",$raw_lat);
/* 	print_r("lat_pieces: ".$lat_pieces); */
	$lng_pieces = explode(" ",$raw_lng);
	$full_lat = $lat_pieces[0] + $lat_pieces[1]/60;
	$full_lng = $lng_pieces[0] + $lng_pieces[1]/60;
	
/* 	print_r("lat: ".$full_lat); */
/*
	if($lastDate->gte($timestamp)){
		echo "skipping row <br />";
		continue;
	}
*/
	echo "successful Row \n\r";
//	if($columns[2]!=""){
		try {
		    $reading = new Greenpeacedata();
		    $reading->setId($columns[0]);
		    $reading->setReadingDate($datetime);
		    $reading->setLatitude($full_lat);
		    $reading->setLongitude($full_lng);
		    $reading->setCps($columns[4]);
		    $reading->setMicrosieverts($columns[5]);
		    $reading->setComments($columns[6]);
		    $reading->store();
		} catch (fExpectedException $e) {
		    //echo $e->printMessage();
		}
//	}

}

?>