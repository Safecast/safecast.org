<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';


//header('Content-type: application/json; charset=utf-8');

///////////////// PACHUBE ///////////////////

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://radiation.crowdmap.com/feed/");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

// grab URL and pass it to the browser
$output = curl_exec($ch); 
try{
	$file = new fFile(DOC_ROOT . '/feeds/crowdmapStatic.xml');
}catch(fExpectedException $e){
	$file = fFilesystem::createObject(DOC_ROOT . '/feeds/crowdmapStatic.xml');

}

$file->write($output);

// close cURL resource, and free up system resources
curl_close($ch);

echo $output;

?>


