Safecast.org
======================================================

How to Start?
----------------
 
* Ask Safecast for a copy of the database for you to run on your mysql server 
* Add the following files to your build
 
##/inc/flourishDB.php

	<?php 
	$db  = new fDatabase('mysql', 'DATABASE_NAME', 'DATABASE_USER', 'DATABASE_PASSWORD', 'localhost');
	fORMDatabase::attach($db);

##/inc/pdoDB.php

	<?php
	try {  
	
  		$db = new PDO("mysql:host=localhost;dbname=DATABASE_NAME", 'DATABASE_USER', 'DATABASE_PASSWORD');  
  		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
	}  
	catch(PDOException $e) {  
    	return $e->getMessage();  
	}
	
CHMODing
---------------------
The following directories need to be write-able by your webserver for a fully functioning site
* /feeds/driveCache
* /feeds/temp

After CHMODing, for any of the drive maps that you want to display you need to generate them 
by going to /drive/manage and clicking on the "Generate Static Files" link for every drive.  
This will populate the /feeds/driveCache directory with the static JSON (and KML and CSV) files 
for each drive.
