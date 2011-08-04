<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';$ch = curl_init();

function resolveAddress($address) {
    $key = "ABQIAAAAnrUlX7POdnZwsqXNLLWGTRTCAOrLU5NdacupICpa1yq2UotN1RRe8_BJKQHYDsBYx0hL5Q16LynxQw";
     
    //If you want an extended data set, change the output to "xml" instead of csv
    $url = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=csv&key=".$key;
    //Set up a CURL request, telling it not to spit back headers, and to throw out a user agent.
    $ch = curl_init();
     
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER,0); //Change this to a 1 to return headers
    //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     
    $data = curl_exec($ch);
    curl_close($ch);
     
    return $data;
}

// set URL and other appropriate options
//curl_setopt($ch, CURLOPT_URL, "http://opendata.socrata.com/views/3d4g-9muv/rows.csv?accessType=DOWNLOAD");
curl_setopt($ch, CURLOPT_URL, "http://opendata.socrata.com/views/w9fb-tgv6/rows.csv?accessType=DOWNLOAD");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL contents
$output = curl_exec($ch);
$lines = preg_split('/[\n\r]+/', $output);
$headerLine = array_shift($lines);
$datastreams = '{"results":[';
$jsonData = '';
foreach($lines as $line){
	$columns = explode(",",$line);

    $googleLocation = explode(",", resolveAddress($columns[1].' '.$columns[0]));
    $isotopes = array('Ba-140','Co-60','Cs-134','Cs-136','Cs-137','I-131','I-132','I-133','Te-129','Te-129m','Te-132');
	$datastreams.= '{"title": "'.$columns[1].', '.$columns[0].'",'.
			'"title_jp": "",'.
			'"description": "Sample Type: '.$columns[4].'",'.
			'"creator": "epa",'.
			'"feed": "http://opendata.socrata.com/views/3d4g-9muv/rows.csv",'.
			'"location": {"lon":"'.$googleLocation[3].'", "lat":"'.$googleLocation[2].'", "name": "'.$columns[1].', '.$columns[0].'"},'.
			'"city":"'.$columns[1].'",'.
			'"state":"'.$columns[0].'",'.
			'"id":"",'.
			'"datastreams": [';
	$success = false;
	$comma = '';
    for ($i = 6; $i < 17; $i++) {
        if ($columns[$i] != 'ND' && $columns[$i] != '') {
            $datastreams.=$comma;
        	$datastreams.= '{"at": "'.$columns[3].'",'.
        			'"max_value": "'.$columns[$i].'",'.
        			'"min_value": "'.$columns[$i].'",'.
        			'"current_value": "'.$columns[$i].'",'.
        			'"isotope": "'.$isotopes[$i-6].'",'.
        			'"id": "",'.
        			'"unit": {"type": "basicSI","label": "'.$columns[5].'","symbol": "'.$columns[5].'"}}';
            $comma = ',';
        }
    }
	$success=true;

	if($success){
		//close and append
		$datastreams.= ']},';
		$jsonData.=$datastreams;
	}

	$datastreams="";
}

$output = rtrim($jsonData, ',');
$output.= '], "itemsPerPage": '.count($lines).', "startIndex": 0, "totalResults": '.count($lines).'}';

$file = new fFile(DOC_ROOT . '/feeds/epaStatic.json');
$file->write($output);
echo $output;
?>
