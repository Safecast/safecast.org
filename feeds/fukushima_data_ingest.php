<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));

include MY_ROOT.'/inc/pdoDB.php';
$output = file_get_contents("fukushima03.csv");
$lines = preg_split('/[\n\r]+/', $output);
$station_names = explode(",",$lines[0]);
$lat_long = explode(",",$lines[1]);

// insert into DB


$line_num = 0;
foreach ($lines as $line) {
    if ($line_num++ < 2) {
        continue;
    }
	$columns = explode(",",$line);
	$date = str_replace(".", "-", $columns[0]);
	$datetime = $date." 00:00:00";

	$col_num = 0;
	$station_id = 900;
	foreach ($columns as $column) {
        $latlong_split = explode("|", $lat_long[$col_num++]);
        print_r(count($latlong_split[0]) . '\n\r');
        if (count($latlong_split) == 2) {
            if ($column > 0) {
                $sql = "INSERT INTO fukushimadatas (station_id, reading_date, reading_value, unit_id) VALUES (".$station_id.",'".$datetime."','".$column."',3)";
                print_r($sql.'\n\r');
               	$result = $db->query($sql);
            }           	
           	$station_id++;
        }
	}
}

$db = null;
?>
