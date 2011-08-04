<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';

header('Content-type: application/json; charset=utf-8');

///////////////// PACHUBE ///////////////////

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://api.pachube.com/v2/feeds.json?key=AY2xnknMXVwpcpnrrOJz9aCuL1bkleqj6r2orGgyBtA&tag=sensor%3Atype%3Dradiation&lat=38.27&lon=140.81&distance=2000&per_page=1000&order=created_at");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

// grab URL and pass it to the browser
$output = curl_exec($ch); 

$json = json_decode($output);
if ($json->results && $json->totalResults > 0) {
    $file = new fFile(DOC_ROOT . '/feeds/pachubeStatic.json');
    $file->write($output);
    echo $output;
} else {
    echo "fail!";
}

// close cURL resource, and free up system resources
curl_close($ch);

?>


