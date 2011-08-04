<?php

define('MY_ROOT', realpath(dirname(__FILE__) . '/../'));
//include db
include MY_ROOT.'/inc/pdoDB.php';

$output = file_get_contents("scottTest2.log");
$lines = preg_split('/[\n\r]+/', $output);
//$headerLine = array_shift($lines); //waste the header
//$headerLine = array_shift($lines); //there are two lines of header
$driveId = 122;
/*
//for now these are all raw drive imports so assume all are valid in full sheet.
$lastInserts = fRecordSet::buildFromSQL(
    'Fukushimadata',
    "SELECT * FROM  `fukushimadatas` 
WHERE  `drive_id` IN (808,809,810)
ORDER BY  `reading_date` DESC 
LIMIT 1"
); 
$lastDate = $lastInserts[0]->getReadingDate();
print_r($lastInserts);

print_r($lastDate);
*/

//sample line:
//2011-4-23	21:52:38	55	3538.4580	N	13943.2420	E	1	09	0.9	41.0	Canmore GT-730FL-S	Air


//prepare the db insert statements for re-use:
$oldSql = "INSERT INTO drivingdatas (drive_id, reading_date, reading_value, unit_id, alt_reading_value, alt_unit_id, latitude, longitude, gps_quality_indicator, satellite_num, gps_precision, gps_altitude, gps_device_name, measurement_type) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
$oldFormatInsert = $db->prepare($oldSql);

$newSql = "INSERT INTO drivingdatas (drive_id, reading_date, reading_value, unit_id, alt_reading_value, alt_unit_id, latitude, longitude, rolling_count, total_count, gps_quality_indicator, gps_precision, gps_altitude) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
$newFormatInsert = $db->prepare($newSql);

$prevLat = 0;
$prevLng = 0;
$row = 0;
$wasNewFormat = false;
foreach($lines as $line){
		$row++;
		//check for # comment
		if(strpos($line,'#')!==false){
			//found comment line so skip and continue to next line
			echo "found comment line ".$row." \n";
			continue;
		}
		$isNewformat = strpos($line,'$');
		if($isNewformat!==false && $isNewformat==0){
			$wasNewFormat = true;
			//found a dollar sign and it was the first character of the line
			$endOfLine = strpos($line,'*');
			$rawLine = substr($line, 1, $endOfLine-1);
			$columns = explode(",",$rawLine);
			if(count($columns)<15){
				echo "incomplete line ".$row." \n";
				continue;
			}
			//confirm both radiation sensor and GPS have valid signals
			if($columns[6]=='V' || $columns[12]=='V'){
				//data from one or both sensors is void so skip it
				echo "Void line ".$row." \n";
				continue;
			}
			$datetime = $columns[2];
			//$timestamp = new DateTime($columns[2]);
			$CPM = (integer)$columns[3];
			$sieverts = round($CPM/350, 3);
			$rollingCount = (integer)$columns[4];
			$totalCount = (integer)$columns[5];
			$funkyLat = (float)$columns[7];
			$degrees = floor($funkyLat/100);
			$minutes = $funkyLat-($degrees*100);
			$lat = $degrees + ($minutes/60);
			$latDirectional = $columns[8];
			if($latDirectional=="S"){
				$lat = $lat * -1;
			}
			$funkyLng = (float)$columns[9];
			$degrees = floor($funkyLng/100);
			$minutes = $funkyLng-($degrees*100);
			$lng = $degrees + ($minutes/60);
			$lngDirectional = $columns[10];
			if($lngDirectional=="W"){
				$lng = $lng * -1;
			}
			$gpsAltitude = (float)$columns[11];
			$gpsPrecision = (float)$columns[13];
			$gpsQuality = (int)$columns[14];
			
			try {
				$reading = array($driveId, $datetime, $sieverts, 3, $CPM, 2, $lat, $lng, $rollingCount, $totalCount, $gpsQuality, $gpsPrecision, $gpsAltitude);
				$newFormatInsert->execute($reading); 
				/*
			    $reading = new Drivingdata();
			    $reading->setReadingDate($datetime);
			    $reading->setDriveId($driveId);
			    $reading->setReadingValue($sieverts);
			    $reading->setUnitId(3);
			    $reading->setAltReadingValue($CPM);
			    $reading->setAltUnitId(2);
			    $reading->setLatitude($lat);
			    $reading->setLongitude($lng);
			    $reading->setRollingCount($rollingCount);
			    $reading->setTotalCount($totalCount);
			    $reading->setGpsQualityIndicator($gpsQuality);
			    $reading->setGpsPrecision($gpsPrecision);
			    $reading->setGpsAltitude($gpsAltitude);
			    $reading->store();	
			    	 
			} catch (fExpectedException $e) {
			    echo $e->printMessage();
			}*/
			}catch (PDOException $e) {  
			    //echo $e->getMessage();
			    echo "corrupt line ".$row." \n";
				continue;
 
		    }
			
			
			
		}else{
			if($wasNewFormat){
				echo "incomplete line ".$row." \n";
				continue;
			}
			$columns = explode(",",$line);
			if(count($columns)<12){
				echo "incomplete line ".$row." \n";
				continue;
			}
			$date = $columns[0]; //str_replace("/", "-", $columns[0]);
			$time = $columns[1];
			$datetime = $date." ".$time;
			//$timestamp = new DateTime($datetime);
			
			$CPM = (float)$columns[2];
			$sieverts = round($CPM/350, 3);
			
			$funkyLat = (float)$columns[3];
			$degrees = floor($funkyLat/100);
			$minutes = $funkyLat-($degrees*100);
			$lat = $degrees + ($minutes/60);
			$latDirectional = $columns[4];
			if($latDirectional=="S"){
				$lat = $lat * -1;
			}
			$funkyLng = (float)$columns[5];
			$degrees = floor($funkyLng/100);
			$minutes = $funkyLng-($degrees*100);
			$lng = $degrees + ($minutes/60);
			$lngDirectional = $columns[6];
			if($lngDirectional=="W"){
				$lng = $lng * -1;
			}
			
			//GPS garbage
			$gpsQuality = (int)$columns[7];
			$satellites = (int)$columns[8];
			$gpsPrecision = (float)$columns[9];
			$gpsAltitude = (float)$columns[10];
			$gpsModel = $columns[11];
			$measurementType = $columns[12];
			
			$prevLat = $lat;
			$prevLng = $lng;
			try {
				$reading = array($driveId, $datetime, $sieverts, 3, $CPM, 2, $lat, $lng, $gpsQuality, $satellites, $gpsPrecision, $gpsAltitude, $gpsModel, $measurementType);
				$oldFormatInsert->execute($reading);
				
				/*
			    $reading = new Drivingdata();
			    $reading->setReadingDate($datetime);
			    $reading->setDriveId($driveId);
			    $reading->setReadingValue($sieverts);
			    $reading->setUnitId(3);
			    $reading->setAltReadingValue($CPM);
			    $reading->setAltUnitId(2);
			    $reading->setLatitude($lat);
			    $reading->setLongitude($lng);
			    $reading->setGpsQualityIndicator($gpsQuality);
			    $reading->setSatelliteNum($satellites);
			    $reading->setGpsPrecision($gpsPrecision);
			    $reading->setGpsAltitude($gpsAltitude);
			    $reading->setGpsDeviceName($gpsModel);
			    $reading->setMeasurementType($measurementType);
			    $reading->store();		 
			} catch (fExpectedException $e) {
			    echo $e->printMessage();
			}
			*/
			}catch (PDOException $e) {  
			    print_r($e); 
			    echo "corrupt line ".$row." \n";
				continue;
		    }

			
		}
		
				
		//print_r($timestamp);

		// I was skipping data if it was redundant but am commenting it out in favor of always storing it all
		/*
		if($lat==$prevLat && $lng==$prevLng){
			$output.="skipping row ".$row." \n";
			continue;
		}
		*/
	}
	
	$db = null;

?>