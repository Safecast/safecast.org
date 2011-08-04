<?php
include '../inc/pdoDB.php';
include ('cScrape.php');
$scrape = new Scrape();

$url = 'http://www.doh.wa.gov/Topics/japan/monitor-history.htm';

$scrape->fetch($url);

$data = $scrape->removeNewlines($scrape->result);

$data = $scrape->fetchBetween('<table border="0" cellpadding="4">','</table>',$data,true);
$rows = $scrape->fetchAllBetween('<tr','</tr>',$data,true);
$i = 0;
foreach ($rows as $id => $row){
    $i++;
    if ($i<3) {
        continue;
    }

    $record = array();
    
//    $cells = $scrape->fetchAllBetween('<font face="Arial" size="2">','</font>',$row,true);
    $cells = $scrape->fetchAllBetween('<font face=','</font></td>',$row,true);

    $record['Date'] = strip_tags($cells[0]);
    $record['Richland'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[1]));
    $record['Seattle'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[2]));
    $record['Spokane'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[3]));
    $record['Tumwater'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[4]));

    $records[] = $record;
}

print_r($records);

// insert into DB

foreach ($records as $id => $record) {
	if (is_numeric($record['Richland'])) {
    	$db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (4, '".$record['Richland']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)"); 
    } 
	if (is_numeric($record['Seattle'])) {
        $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (5, '".$record['Seattle']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)"); //  47.45  122.30 
    }
	if (is_numeric($record['Spokane'])) {
        $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (6, '".$record['Spokane']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)");  //  47.63  117.53
    }
	if (is_numeric($record['Tumwater'])) {
        $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (7, '".$record['Tumwater']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)");  // 
    }
}

$db=null;
?>
