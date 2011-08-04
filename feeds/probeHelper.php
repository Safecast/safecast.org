<?php
define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
include MY_ROOT.'/inc/init.php';

//header('Content-type: application/json; charset=utf-8');

///////////////// PACHUBE ///////////////////

//flickr API key :: f6eab4827c53ef50b1ea152d39f17e6e
//flickr API secret :: 3b0e7d356cea6990

//curl 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=f6eab4827c53ef50b1ea152d39f17e6e&user_id=61942515@N05&extras=tags,geo,url_m,date_taken&per_page=200&format=json'
//curl 
/*  
{
"id":"5642075867", 
"owner":"61942515@N05", 
"secret":"3d24fcd62f", 
"server":"5029", 
"farm":6, 
"title":"Safecast Probe 0008", 
"ispublic":1, 
"isfriend":0, 
"isfamily":0, 
"latitude":35.7675, 
"longitude":139.762166, 
"accuracy":"16", 
"place_id":"dMEsUIybCZn5FeaUzQ", 
"woeid":"28529538", 
"geo_is_family":0, 
"geo_is_friend":0, 
"geo_is_contact":0, 
"geo_is_public":1, 
"url_m":"http:\/\/farm6.static.flickr.com\/5029\/5642075867_3d24fcd62f.jpg", 
"height_m":"500", 
"width_m":"374", 
"datetaken":"2011-04-20 19:19:40", 
"datetakengranularity":"0"}
*/

// create a new cURL resource

$flickrFeeds = array("http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=f6eab4827c53ef50b1ea152d39f17e6e&user_id=61747902@N04&extras=tags,geo,url_m,date_taken&per_page=200&format=json",
                     "http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=f6eab4827c53ef50b1ea152d39f17e6e&user_id=61942515@N05&extras=tags,geo,url_m,date_taken&per_page=200&format=json");
/*

"http://api.flickr.com/services/feeds/geo/?id=61747902@N04&lang=en-us&format=json",
					"http://api.flickr.com/services/feeds/geo/?id=61942515@N05&lang=en-us&format=json");
*/

$newFeed = '{"results":[';					
$total = 0;
foreach($flickrFeeds as $feed){
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $feed);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	$data = curl_exec($ch); // execute curl session
	
	$data = str_replace( 'jsonFlickrApi(', '', $data );
	$data = substr( $data, 0, strlen( $data ) - 1 ); //strip out last paren
	
	$object = json_decode( $data ); // stdClass object
	$items = $object->photos->photo;
	
	foreach ($items as $item) {
	    $newFeed.='{"title": "'.$item->title.'","title_jp": "","description": "'.$item->tags.'","creator": "probe","feed": "http://api.flickr.com/services/rest","location": {"lon":'.$item->longitude.', "lat":'.$item->latitude.', "name": ""},"id":"'.$item->owner.'","datastreams": [{"at": "'.$item->datetaken.'","max_value": "0","min_value": "0","current_value": "'.$item->url_m.'","id": "1","unit": {"type": "photo","label": "photo","symbol": "photo"}}]},';
	}
	curl_close($ch);
	$total += count($items);
}

					
$newFeed = substr( $newFeed, 0, strlen( $newFeed ) - 1 ); //strip out last comma

$newFeed.='], "itemsPerPage": '.$total.', "startIndex": 0, "totalResults": '.$total.'}';

try{
	$file = new fFile(DOC_ROOT . '/feeds/probe002.json');
}catch(fExpectedException $e){
	$file = fFilesystem::createObject(DOC_ROOT . '/feeds/probe002.json');

}

$file->write($newFeed);

// close cURL resource, and free up system resources

print_r($newFeed);

?>