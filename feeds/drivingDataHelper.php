<?php

//inlcude db access
include '../inc/pdoDB.php';



function parseData($filename) {
	global $db;
	//prepare the db insert statements for re-use:
	
	
	$oldSql = "INSERT INTO drivingdatas (drive_id, reading_date, reading_value, unit_id, alt_reading_value, alt_unit_id, latitude, longitude, gps_quality_indicator, satellite_num, gps_precision, gps_altitude, gps_device_name, measurement_type) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
	$oldFormatInsert = $db->prepare($oldSql);

	$newSql = "INSERT INTO drivingdatas (drive_id, reading_date, reading_value, unit_id, alt_reading_value, alt_unit_id, latitude, longitude, rolling_count, total_count, gps_quality_indicator, gps_precision, gps_altitude) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
	$newFormatInsert = $db->prepare($newSql);
	

	$input = file_get_contents($filename);
	$lines = preg_split('/[\n\r]+/', $input);
	$prevLat = 0;
	$prevLng = 0;
	$row = 0;
	$driveId = $_GET['drive'];
 
	$output = "";
	$successful = 0;
	$wasNewFormat = false;
	foreach($lines as $line){
		$row++;
		//check for # comment
		if(strpos($line,'#')!==false){
			//found comment line so skip and continue to next line
			$output.="found comment line ".$row." \n";
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
				$output.="incomplete line ".$row." \n";
				continue;
			}
			//confirm both radiation sensor and GPS have valid signals
			if($columns[6]=='V' || $columns[12]=='V'){
				//data from one or both sensors is void so skip it
				$output.="Void line ".$row." \n";
				continue;
			}
			$datetime = $columns[2];
			//$timestamp = new fTimestamp($columns[2]);
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
				$successful++;
				
			}catch (PDOException $e) {  
			    //return $e->getMessage(); 
			    echo "corrupt line ".$row." \n";
				//continue;
		    }			
			
			
		}else{
			if($wasNewFormat){
				$output.="incomplete line ".$row." \n";
				continue;
			}
			$columns = explode(",",$line);
			if(count($columns)<12){
				$output.="incomplete line ".$row." \n";
				continue;
			}
			$date = $columns[0]; //str_replace("/", "-", $columns[0]);
			$time = $columns[1];
			$datetime = $date." ".$time;
			//$timestamp = new fTimestamp($datetime);
			
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
				$successful++;
				
			}catch (PDOException $e) {  
			    //return $e->getMessage(); 
			    echo "corrupt line ".$row." \n";
				//continue;
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
	$output.='Successfully added '.$successful.' data points';
	return $output;
}  


/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }
     
    
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize > $this->sizeLimit || $uploadSize > $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        
		if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];
		/*
        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
          */             

        //$response = $this->file->parseData();
		//return array('success'=>true);
		
		
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
        	$response = parseData($uploadDirectory . $filename . '.' . $ext);
            return array('success'=>true, 'response'=>$response);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
		
        
    }    
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("log", "txt");
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

$result = $uploader->handleUpload('temp/');
// to pass data through iframe you will need to encode all html tags
echo json_encode($result);

//echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
