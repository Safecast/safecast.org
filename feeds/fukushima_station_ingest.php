<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));

include MY_ROOT.'/inc/pdoDB.php';
$output = file_get_contents("fukushima_stations.csv");
$lines = preg_split('/[\n\r]+/', $output);
$station_names = explode(",",$lines[0]);
$lat_long = explode(",",$lines[1]);


for ($i = 0; $i < count($station_names); $i++) {
    
    $latlong_split = explode("|", $lat_long[$i]);
    
    if (count($latlong_split) == 2) {
        $record = array();
        
        $record['lat'] = $latlong_split[0];
        $record['lng'] = $latlong_split[1];
        
        $station_split = explode("-", $station_names[$i]);
        $record['name_jp'] = trim($station_split[0]);
        $record['name_en'] = trim($station_split[1]);
    
        $records[] = $record;
    }
}

print_r($records);


$i = 0;
foreach ($records as $id => $record) {
    $new_id = 900 + $i++;
    $sql = "INSERT INTO stations (station_id, site_name_jp, site_name_en, prefecture_name_jp, prefecture_name_en, station_name_jp, station_name_en, latitude, longitude, source) VALUES (".$new_id.",'".$record['name_jp']."','".$record['name_en']."','福島県','Fukushima','".$record['name_jp']."','".$record['name_en']."',".$record['lat'].",".$record['lng'].",'fukushima_fleep')";
    print_r($sql.'\n\n');
   	$result = $db->query($sql); 
}

$db = null;
?>
