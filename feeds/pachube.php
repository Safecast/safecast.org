<?php
include '../inc/init.php';

header('Content-type: application/json; charset=utf-8');

///////////////// PACHUBE ///////////////////

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://api.pachube.com/v2/feeds.json?key=AY2xnknMXVwpcpnrrOJz9aCuL1bkleqj6r2orGgyBtA&tag=sensor%3Atype%3Dradiation&lat=38.27&lon=140.81&distance=4000&per_page=1000");
curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

// grab URL and pass it to the browser
$output = curl_exec($ch); 

// close cURL resource, and free up system resources
curl_close($ch);

?>


