<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));

include MY_ROOT.'/inc/pdoDB.php';
//print_r("HOUIHUHNJKLREINKFDEW");
$output = file_get_contents("fukushima_daiichi.csv");
//print_r("output: ");
$lines = preg_split('/[\n\r]+/', $output);


$line_num = 0;
$date = '';
foreach ($lines as $line) {
    if ($line_num++ < 3) {
        continue;
    }
    // Day	Time	Meas. Location	γ線	Radiation level	Wind Dir.	Wind Speed
	$columns = explode(",",$line);
	if ($columns[0] != '') {
    	//$date = str_replace(".", "-", $columns[0]);
    	$date = $columns[0];
    }
    // example time: 午前11時30分
	// AM 午前
	// PM 午後
	$time = $columns[1];
	$time = str_replace("時", ":", $time);
	if (strstr($time,"午前")) {
	    $time = str_replace("分", ":00 AM", $time);
        $time = str_replace("午前", "", $time);
	} else {
	    $time = str_replace("分", ":00 PM", $time);
        $time = str_replace("午後", "", $time);
	}
	$datetime = $date." ".$time;
	
	$location = $columns[2];
	$radiation_amount = $columns[4];
	$radiation_unit = trim(preg_replace('/[0-9\.]/i','',$radiation_amount));
	$radiation_value = trim(preg_replace('/[^0-9\.]/i','',$radiation_amount));
//	print_r("rad_unit: ".$radiation_value);

	$col_num = 0;

    $unit_id = 3; // μSv/h
    if ($radiation_unit == "nGy/h") {
        $unit_id = 7;
    }
    $station_id = -1;
    if ($location == 'MP-1' || $location == 'MP-1付近') {
        $station_id = 800;
    } else if ($location == 'MP-2' || $location == 'MP-2付近') {
        $station_id = 801;
    } else if ($location == 'MP-3' || $location == 'MP-3付近') {
        $station_id = 802;
    } else if ($location == 'MP-4' || $location == 'MP-4付近') {
        $station_id = 803;
    } else if ($location == 'MP-5' || $location == 'MP-5付近') {
        $station_id = 804;
    } else if ($location == 'MP-6' || $location == 'MP-6付近') {
        $station_id = 805;
    } else if ($location == 'MP-7' || $location == 'MP-7付近') {
        $station_id = 806;
    } else if ($location == 'MP-8' || $location == 'MP-8付近') {
        $station_id = 807;
    } else if ($location == '西門') {
        $station_id = 808;
    } else if ($location == '正門') {
        $station_id = 809;
    } else if ($location == '管理棟') {
        $station_id = 810;
    }
    if ($station_id > 0 && $radiation_value > 0 && $radiation_unit != '' && $datetime != NULL && $datetime != '') {
        $sql = "INSERT INTO fukushimadatas (station_id, reading_date, reading_value, unit_id) VALUES (".$station_id.",STR_TO_DATE('".$datetime."','%Y/%m/%e %h:%i:%s %p'),'".$radiation_value."',".$unit_id.")";
        print_r($sql.'\n\r');
       	$result = $db->query($sql);
    }
}
$db = null;
?>
