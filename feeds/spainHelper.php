<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';

header('Content-type: application/json; charset=utf-8');

///////////////// PACHUBE ///////////////////

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://api.pachube.com/v2/feeds.json?key=AY2xnknMXVwpcpnrrOJz9aCuL1bkleqj6r2orGgyBtA&tag=sensor%3Atype%3Dradiation&lat=40.45123&lon=-3.72621&distance=1000&per_page=1000&order=created_at&status=live");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

// grab URL and pass it to the browser
$output = curl_exec($ch); 
$output = str_replace("http://www.pachube.com/users/albertonaranjo", "albertonaranjo", $output);
$file = new fFile(DOC_ROOT . '/feeds/spainStatic.json');
$file->write($output);

// close cURL resource, and free up system resources
curl_close($ch);

echo $output;

?>


