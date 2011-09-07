<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include '../inc/init.php';
include '../inc/pdoDB.php';

// ##################################################################
// functions for writing out content to each format
// ##################################################################

function populatePointBased($data){
	$seivert = $data->cpm_avg / 350;
	return '{"lon":'.$data->lat_avg.
					', "lat":'.$data->lon_avg.
					', "name": "",'.
					'"current_value": "'.$seivert.'",'.
					'"cpm_value": "'.$data->cpm_avg.'",'.
					'"id": "0",'.
					'"at": "'.$data->timestamp_max.'",'.
					'"label": "CPM"},';	
}

function populateGridBased($data){
	return '{"topLeft":{"lat":'.$data->lat_max.', "lng":'.$data->lon_min.'},"bottomRight":{"lat":'.$data->lat_min.', "lng":'.$data->lon_max.'},"contaminated_air":'.($data->cpm_max/350).', "gridId":"0", "totalPoints":'.$data->points.', "hotPoints":'.$data->points.'},';
}


// ##################################################################
// init
// ##################################################################
$driveId = 6;

//$driveId = $_GET['id'];
$driveId = fRequest::get("id","integer?");
if(!$driveId){
	$driveId=6;
}
$done = false;
$iterated = 0;
$index = 0;
$limit = 1000;
$driveDesc = "";
$driveName = "";

$jsonCount = 0;

// ##################################################################
// Prep starts of outputs
// ##################################################################

$gridOutput = "[";
$pointOutput = "[";
// ##################################################################
// main iterative loop
// ##################################################################


// get the count of total records for this drive
$sqlCount = "SELECT count(*) FROM `gridify_out`";
$result = $db->query($sqlCount);
$result->setFetchMode(PDO::FETCH_ASSOC);

while ($count = $result->fetch()) {
    $totalRecords = $count["count(*)"];
}
/* close the result set */
$result->closeCursor();

while(!$done){

	$sql = "SELECT * FROM `gridify_out` LIMIT ".$index.", ".$limit;
	
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);

	while ($data = $result->fetch()) {
		$jsonCount++;
		$gridOutput.=populateGridBased($data);
	}
	$index+=$limit;
	if($index>=$totalRecords){
		$done = true;
	}
	$result->closeCursor();
}

$gridOutput = rtrim($gridOutput, ',');
$gridOutput.= ']';

try{
	$file = new fFile(MY_ROOT . '/feeds/gridFusion.json');
	$file->write($gridOutput);
}catch (fExpectedException $e) {
	$new_file = fFile::create(MY_ROOT . '/feeds/gridFusion.json', $gridOutput);
}catch (fEnvironmentException $e) {
	print_r($e);	
}
$db=null;
echo $gridOutput;
