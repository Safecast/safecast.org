<?php
include '../inc/init.php';
include '../inc/flourishDB.php';







//method generator:

$latStart = 43.3098;
$latStop = 33.267;
$latIncrement = 0.1407;
$lngStart = 130.41;
$lngStop = 141.9236;
$lngIncrement = 0.1758;
$lngStep = 1;
$latStep = 1;
$latI = 1;
$lngI = 1;
/*
$latMethod = 'function findMyLatGrid($lat){
';


while($latStart > $latStop){
	$latStep = $latStart-$latIncrement;
	$latMethod.='else if($lat<='.$latStart.' && $lat>'.$latStep.'){
		return '.$latI.';
	}';
	$latI++;
	$latStart-=$latIncrement;
}
$latMethod.="
}";

$lngMethod = 'function findMyLngGrid($lng){
';
while($lngStart < $lngStop){
	$lngStep = $lngStart+$lngIncrement;
	$lngMethod.='else if($lng>='.$lngStart.' && $lng<'.$lngStep.'){
		return '.$lngI.';
	}';
	$lngI++;
	$lngStart+=$lngIncrement;
}
$lngMethod.="
}";


echo $latMethod."

".$lngMethod;
*/


//populate grid assignments

$pool = fRecordSet::buildFromSQL(
    'Drivingdata',
    "SELECT * FROM `drivingdatas` WHERE `zoom_7_grid` = '-1' LIMIT 10000"
);

foreach($pool as $data){
	$latGrid = findMyLatGrid($data->getLatitude());
	$lngGrid = findMyLngGrid($data->getLongitude());
	$data->setZoom7Grid($latGrid."_".$lngGrid);
	$data->store();
	echo $data->getReadingId();
}



/*
$tippingPoint = 35;
$jsonOutput = "[";

while($latStart > $latStop){
	$lngStep = $lngStart;
	$lngI = 1;
	while($lngStep<$lngStop){
		$grid = $latI."_".$lngI;
		$sql = "SELECT * FROM `drivingdatas` WHERE `zoom_7_grid` = '".$grid."' LIMIT 5";
		$sqlCount = "SELECT count(*) FROM `drivingdatas` WHERE `zoom_7_grid` = '".$grid."'";
		$pool = fRecordSet::buildFromSQL(
		    'Drivingdata',
		    $sql,
		    $sqlCount,
		    5,
		    1
		);
		
		$hotOrNot = 0;
		$top = $latStart;
		$bottom = $latStart+$latIncrement;
		$left = $lngStep;
		$right = $lngStep + $lngIncrement;
		if(count($pool)>0){
			$totalDataPoints = $pool->count(true);
			$hotDataPoints = 0;
			$data = $pool[0];
			if($data->getAltReadingValue()>$tippingPoint){
				$hotOrNot = 1;
				$sql = "SELECT * FROM `drivingdatas` WHERE `zoom_7_grid` = '".$grid."' AND `alt_reading_value` > ".$tippingPoint."  LIMIT 5";
				$sqlCount = "SELECT count(*) FROM `drivingdatas` WHERE `zoom_7_grid` = '".$grid."' AND `alt_reading_value` > ".$tippingPoint;
				$hotPool = fRecordSet::buildFromSQL(
				    'Drivingdata',
				    $sql,
				    $sqlCount,
				    5,
				    1
				);
				$hotDataPoints = $hotPool->count(true);
			}else{
				
			}
			
			$jsonOutput.='{"topLeft":{"lat":'.$top.', "lng":'.$left.'},"bottomRight":{"lat":'.$bottom.', "lng":'.$right.'},"contaminated_air":'.$hotOrNot.', "gridId":"'.$grid.'", "totalPoints":'.$totalDataPoints.', "hotPoints":'.$hotDataPoints.'},';
			
		}else{
			//if grid is not over the ocean...   need to figure that one out.
			$jsonOutput.='{"topLeft":{"lat":'.$top.', "lng":'.$left.'},"bottomRight":{"lat":'.$bottom.', "lng":'.$right.'},"contaminated_air":-1, "gridId":"'.$grid.'", "totalPoints":0, "hotPoints":0},';
		}
		
		$lngI++;
		$lngStep+=$lngIncrement;
	}
	$latStart-=$latIncrement;
	$latI++;
}

echo rtrim($jsonOutput, ',');
echo "]";
*/

function findMyLatGrid($lat){
	if($lat<=43.3098 && $lat>43.1691){
		return 1;
	}else if($lat<=43.1691 && $lat>43.0284){
		return 2;
	}else if($lat<=43.0284 && $lat>42.8877){
		return 3;
	}else if($lat<=42.8877 && $lat>42.747){
		return 4;
	}else if($lat<=42.747 && $lat>42.6063){
		return 5;
	}else if($lat<=42.6063 && $lat>42.4656){
		return 6;
	}else if($lat<=42.4656 && $lat>42.3249){
		return 7;
	}else if($lat<=42.3249 && $lat>42.1842){
		return 8;
	}else if($lat<=42.1842 && $lat>42.0435){
		return 9;
	}else if($lat<=42.0435 && $lat>41.9028){
		return 10;
	}else if($lat<=41.9028 && $lat>41.7621){
		return 11;
	}else if($lat<=41.7621 && $lat>41.6214){
		return 12;
	}else if($lat<=41.6214 && $lat>41.4807){
		return 13;
	}else if($lat<=41.4807 && $lat>41.34){
		return 14;
	}else if($lat<=41.34 && $lat>41.1993){
		return 15;
	}else if($lat<=41.1993 && $lat>41.0586){
		return 16;
	}else if($lat<=41.0586 && $lat>40.9179){
		return 17;
	}else if($lat<=40.9179 && $lat>40.7772){
		return 18;
	}else if($lat<=40.7772 && $lat>40.6365){
		return 19;
	}else if($lat<=40.6365 && $lat>40.4958){
		return 20;
	}else if($lat<=40.4958 && $lat>40.3551){
		return 21;
	}else if($lat<=40.3551 && $lat>40.2144){
		return 22;
	}else if($lat<=40.2144 && $lat>40.0737){
		return 23;
	}else if($lat<=40.0737 && $lat>39.933){
		return 24;
	}else if($lat<=39.933 && $lat>39.7923){
		return 25;
	}else if($lat<=39.7923 && $lat>39.6516){
		return 26;
	}else if($lat<=39.6516 && $lat>39.5109){
		return 27;
	}else if($lat<=39.5109 && $lat>39.3702){
		return 28;
	}else if($lat<=39.3702 && $lat>39.2295){
		return 29;
	}else if($lat<=39.2295 && $lat>39.0888){
		return 30;
	}else if($lat<=39.0888 && $lat>38.9481){
		return 31;
	}else if($lat<=38.9481 && $lat>38.8074){
		return 32;
	}else if($lat<=38.8074 && $lat>38.6667){
		return 33;
	}else if($lat<=38.6667 && $lat>38.526){
		return 34;
	}else if($lat<=38.526 && $lat>38.3853){
		return 35;
	}else if($lat<=38.3853 && $lat>38.2446){
		return 36;
	}else if($lat<=38.2446 && $lat>38.1039){
		return 37;
	}else if($lat<=38.1039 && $lat>37.9632){
		return 38;
	}else if($lat<=37.9632 && $lat>37.8225){
		return 39;
	}else if($lat<=37.8225 && $lat>37.6818){
		return 40;
	}else if($lat<=37.6818 && $lat>37.5411){
		return 41;
	}else if($lat<=37.5411 && $lat>37.4004){
		return 42;
	}else if($lat<=37.4004 && $lat>37.2597){
		return 43;
	}else if($lat<=37.2597 && $lat>37.119){
		return 44;
	}else if($lat<=37.119 && $lat>36.9783){
		return 45;
	}else if($lat<=36.9783 && $lat>36.8376){
		return 46;
	}else if($lat<=36.8376 && $lat>36.6969){
		return 47;
	}else if($lat<=36.6969 && $lat>36.5562){
		return 48;
	}else if($lat<=36.5562 && $lat>36.4155){
		return 49;
	}else if($lat<=36.4155 && $lat>36.2748){
		return 50;
	}else if($lat<=36.2748 && $lat>36.1341){
		return 51;
	}else if($lat<=36.1341 && $lat>35.9934){
		return 52;
	}else if($lat<=35.9934 && $lat>35.8527){
		return 53;
	}else if($lat<=35.8527 && $lat>35.712){
		return 54;
	}else if($lat<=35.712 && $lat>35.5713){
		return 55;
	}else if($lat<=35.5713 && $lat>35.4306){
		return 56;
	}else if($lat<=35.4306 && $lat>35.2899){
		return 57;
	}else if($lat<=35.2899 && $lat>35.1492){
		return 58;
	}else if($lat<=35.1492 && $lat>35.0085){
		return 59;
	}else if($lat<=35.0085 && $lat>34.8678){
		return 60;
	}else if($lat<=34.8678 && $lat>34.7271){
		return 61;
	}else if($lat<=34.7271 && $lat>34.5864){
		return 62;
	}else if($lat<=34.5864 && $lat>34.4457){
		return 63;
	}else if($lat<=34.4457 && $lat>34.305){
		return 64;
	}else if($lat<=34.305 && $lat>34.1643){
		return 65;
	}else if($lat<=34.1643 && $lat>34.0236){
		return 66;
	}else if($lat<=34.0236 && $lat>33.8829){
		return 67;
	}else if($lat<=33.8829 && $lat>33.7422){
		return 68;
	}else if($lat<=33.7422 && $lat>33.6015){
		return 69;
	}else if($lat<=33.6015 && $lat>33.4608){
		return 70;
	}else if($lat<=33.4608 && $lat>33.3201){
		return 71;
	}else if($lat<=33.3201 && $lat>33.1794){
		return 72;
	}
}

function findMyLngGrid($lng){
	if($lng>=130.41 && $lng<130.5858){
		return 1;
	}else if($lng>=130.5858 && $lng<130.7616){
		return 2;
	}else if($lng>=130.7616 && $lng<130.9374){
		return 3;
	}else if($lng>=130.9374 && $lng<131.1132){
		return 4;
	}else if($lng>=131.1132 && $lng<131.289){
		return 5;
	}else if($lng>=131.289 && $lng<131.4648){
		return 6;
	}else if($lng>=131.4648 && $lng<131.6406){
		return 7;
	}else if($lng>=131.6406 && $lng<131.8164){
		return 8;
	}else if($lng>=131.8164 && $lng<131.9922){
		return 9;
	}else if($lng>=131.9922 && $lng<132.168){
		return 10;
	}else if($lng>=132.168 && $lng<132.3438){
		return 11;
	}else if($lng>=132.3438 && $lng<132.5196){
		return 12;
	}else if($lng>=132.5196 && $lng<132.6954){
		return 13;
	}else if($lng>=132.6954 && $lng<132.8712){
		return 14;
	}else if($lng>=132.8712 && $lng<133.047){
		return 15;
	}else if($lng>=133.047 && $lng<133.2228){
		return 16;
	}else if($lng>=133.2228 && $lng<133.3986){
		return 17;
	}else if($lng>=133.3986 && $lng<133.5744){
		return 18;
	}else if($lng>=133.5744 && $lng<133.7502){
		return 19;
	}else if($lng>=133.7502 && $lng<133.926){
		return 20;
	}else if($lng>=133.926 && $lng<134.1018){
		return 21;
	}else if($lng>=134.1018 && $lng<134.2776){
		return 22;
	}else if($lng>=134.2776 && $lng<134.4534){
		return 23;
	}else if($lng>=134.4534 && $lng<134.6292){
		return 24;
	}else if($lng>=134.6292 && $lng<134.805){
		return 25;
	}else if($lng>=134.805 && $lng<134.9808){
		return 26;
	}else if($lng>=134.9808 && $lng<135.1566){
		return 27;
	}else if($lng>=135.1566 && $lng<135.3324){
		return 28;
	}else if($lng>=135.3324 && $lng<135.5082){
		return 29;
	}else if($lng>=135.5082 && $lng<135.684){
		return 30;
	}else if($lng>=135.684 && $lng<135.8598){
		return 31;
	}else if($lng>=135.8598 && $lng<136.0356){
		return 32;
	}else if($lng>=136.0356 && $lng<136.2114){
		return 33;
	}else if($lng>=136.2114 && $lng<136.3872){
		return 34;
	}else if($lng>=136.3872 && $lng<136.563){
		return 35;
	}else if($lng>=136.563 && $lng<136.7388){
		return 36;
	}else if($lng>=136.7388 && $lng<136.9146){
		return 37;
	}else if($lng>=136.9146 && $lng<137.0904){
		return 38;
	}else if($lng>=137.0904 && $lng<137.2662){
		return 39;
	}else if($lng>=137.2662 && $lng<137.442){
		return 40;
	}else if($lng>=137.442 && $lng<137.6178){
		return 41;
	}else if($lng>=137.6178 && $lng<137.7936){
		return 42;
	}else if($lng>=137.7936 && $lng<137.9694){
		return 43;
	}else if($lng>=137.9694 && $lng<138.1452){
		return 44;
	}else if($lng>=138.1452 && $lng<138.321){
		return 45;
	}else if($lng>=138.321 && $lng<138.4968){
		return 46;
	}else if($lng>=138.4968 && $lng<138.6726){
		return 47;
	}else if($lng>=138.6726 && $lng<138.8484){
		return 48;
	}else if($lng>=138.8484 && $lng<139.0242){
		return 49;
	}else if($lng>=139.0242 && $lng<139.2){
		return 50;
	}else if($lng>=139.2 && $lng<139.3758){
		return 51;
	}else if($lng>=139.3758 && $lng<139.5516){
		return 52;
	}else if($lng>=139.5516 && $lng<139.7274){
		return 53;
	}else if($lng>=139.7274 && $lng<139.9032){
		return 54;
	}else if($lng>=139.9032 && $lng<140.079){
		return 55;
	}else if($lng>=140.079 && $lng<140.2548){
		return 56;
	}else if($lng>=140.2548 && $lng<140.4306){
		return 57;
	}else if($lng>=140.4306 && $lng<140.6064){
		return 58;
	}else if($lng>=140.6064 && $lng<140.7822){
		return 59;
	}else if($lng>=140.7822 && $lng<140.958){
		return 60;
	}else if($lng>=140.958 && $lng<141.1338){
		return 61;
	}else if($lng>=141.1338 && $lng<141.3096){
		return 62;
	}else if($lng>=141.3096 && $lng<141.4854){
		return 63;
	}else if($lng>=141.4854 && $lng<141.6612){
		return 64;
	}else if($lng>=141.6612 && $lng<141.837){
		return 65;
	}else if($lng>=141.837 && $lng<142.0128){
		return 66;
	}
}


