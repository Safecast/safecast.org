<?php
include '../inc/pdoDB.php';
include ('cScrape.php');
$scrape = new Scrape();

$url = 'http://public.health.oregon.gov/Preparedness/CurrentHazards/Pages/AirMonitoring.aspx';

$scrape->fetch($url);

$data = $scrape->removeNewlines($scrape->result);

$data = $scrape->fetchBetween('<table style="width:500px" class=ms-rteTable-1 summary=""><tbody>','</tbody></table>',$data,true);
$rows = $scrape->fetchAllBetween('<tr','</tr>',$data,true);

$aprildata = $scrape->fetchBetween('<table style="width:67.29%;height:73px" class=ms-rteTable-1 summary=""><tbody>','</tbody></table>',$data,true);
$aprilrows = $scrape->fetchAllBetween('<tr','</tr>',$aprildata,true);

$totalrows = array_merge($rows, $aprilrows);

$i = 0;
$records[] = NULL;
foreach ($totalrows as $id => $row){
    $i++;
    if ($i==1) {
        continue;
    }

    $record = array();
    
    $cells = $scrape->fetchAllBetween('<td','</td>',$row,true);

    $record['Date'] = strip_tags($cells[0]);
    $record['Portland'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[1]));
    $record['Corvallis'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[2]));
    $record['Eureka'] = preg_replace("/[^.0-9\s]/", "", strip_tags($cells[3]));

    $records[] = $record;
}
/* if ($records[0]) { */
   // print_r($records);

    // insert into DB
    
    foreach ($records as $id => $record) {
        $result = $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (1, '".$record['Portland']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)");
        $result = $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (2, '".$record['Corvallis']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)");
        $result = $db->query("INSERT INTO government_readings (city_id, reading_value, reading_system, reading_date, radiation_type, is_verified) VALUES (3, '".$record['Eureka']."', '2', '".date('Y-m-d 00:00:00', strtotime($record['Date']))."', 'beta', 1)");
    }
    
    $db=null;
    /*
} else {
	print_r("nothing to insert\n");
}
*/
?>
