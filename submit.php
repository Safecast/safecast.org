<?php
include './inc/init.php';
include './inc/flourishDB.php';

$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('formallyKnownAs', $translations->formallyKnownAs);
$tmpl->set('aboutCaps', $translations->about);
$tmpl->set('maps', $translations->maps);
$tmpl->set('wiki', $translations->wiki);
$tmpl->set('blog', $translations->blog);
$tmpl->set('japan', $translations->japan);
$tmpl->set('forums', $translations->forums);
$tmpl->set('submitAReading', $translations->submitAReading);
$tmpl->set('languageSelect', $translations->languageSelect);
$tmpl->set('contact', $translations->contact);
$tmpl->set('follow', $translations->follow);
$tmpl->set('termsPolicy', $translations->termsPolicy);
$tmpl->set('conceivedPart1', $translations->conceivedPart1);
$tmpl->set('conceivedPart2', $translations->conceivedPart2);
$tmpl->set('lang', $lang);

$tmpl->set('pageName', 'submit');
$action = fRequest::get('action',"string?" );

if($action=="submit"){
	$tmpl->set('title', 'Reading Submitted');
	$tmpl->place('header');
	$whitelist = array("joi@ito.com","pieterfranken@mac.com");

	echo '<div class="content">
				<div class="relativeWrap">
					<div class="submitFormWrap">
						<div class="sectionHeadForm">' . $translations->submitReading . '</div><div id="submitFormBody" class="form">';
	
	$success = true;
	try {
		//look for an existing user
		$email = fRequest::get('email',"string?" );
		if($email!=NULL){
			$user = new User(array('email' => $email));
		}else{
			$success = false;
		}
	} catch (fExpectedException $e) {
	    $success = false;
	}
		
	try {
		//$user = new User();
		$email = fRequest::get('email',"string?" );
		if(!$success){
			$user = new User();
			$user->populate();
			$user->store();
		}
	    $reading = new Reading();
	    $reading->populate();
	    $reading->setUserId($user->getUserId());
	    
//	     if (in_array(strtolower($email), $whitelist)) {
    		$reading->setIsVerified(1);
//		}
		
	    $reading->store();
	    
	    echo "Thank you for submitting your radiation reading.";
	 
	} catch (fExpectedException $e) {
	    echo $e->printMessage();
	}


	
	
	echo '</div>
		</div>
	</div>
</div>';
	
	$tmpl->place('footer');
	die;
}

$tmpl->set('title', 'Submit Radiation Reading');
if($mobile){
	$tmpl->add('css',  'style/formalizeMobile.css');
}else{
	$tmpl->add('css',  'style/formalize.css');
}
$tmpl->add('css',  'style/anytime.css');
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
$tmpl->add('js',  'script/jquery.formalize.min.js');
$tmpl->add('js',  'script/jquery.checkForm.1.0.js');
$tmpl->add('js',  'script/jquery.locationpicker.js');
$tmpl->add('js',  'script/jquery.geolocation.js');
$tmpl->add('js',  'script/anytime.js');
$tmpl->add('js',  'script/anytimetz.js');
$tmpl->add('js',  'script/formValidation.js');


$tmpl->place('header');



/*
    "enterLocation": "enter location",
    "submitReading": "Submit a reading:",

    */
?>

			<div class="content">
				<div class="relativeWrap">
					<div class="submitFormWrap">
						<div class="sectionHeadForm"><?php echo $translations->submitReading; ?></div>
						<div id="submitFormBody" class="form">
								<fieldset>
    								<legend><?php echo $translations->aboutYourReading; ?></legend>
									<div class="formRow">
										<label for="input_reading_value"><?php echo $translations->readingValue; ?></label><br />
										<input type="text" name="input_reading_value" value="" id="input_reading_value" class="input_xxlarge" />
									</div>
									<div class="formRow">
										<label for="input_reading_system"><?php echo $translations->readingUnits; ?></label><br />
										<select size="1" id="input_reading_system" name="input_reading_system" class="">
											<option value=1>Color (Green, Yellow, Red)</option>
											<option value=2>Clicks Per Minute (CPM)</option>
											<option value=3>&#181;Sv/h</option>
											<option value=4>&#181;Rem/h</option>
											<option value=5>Roentgen (R)</option>
											<option value=6>Rad (Radiation Absorbed Dose)</option>
											<option value=7>nGy/h</option>
										</select>
									</div>
									<div class="formRow">
										<label for="input_equipment"><?php echo $translations->equipmentDescription; ?></label><br />
										<input type="text" name="input_equipment" value="" id="input_equipment" class="input_xxlarge" />
									</div>
									<div class="latlngWrap">
										<div class="latlngbox">
											<label for="pickerDay"><?php echo $translations->whatDay; ?></label><br />
											<input type="text" name="pickerDay" value="" id="pickerDay" class="input_medium" />
										</div>
										<div class="latlngbox">
											<label for="pickerTime"><?php echo $translations->whatTime; ?></label><br />
											<input type="text" name="pickerTime" value="" id="pickerTime" class="input_medium" />
										</div>
									</div>
								</fieldset>
								<fieldset>
    								<legend><?php echo $translations->whereReadingTaken; ?></legend>
    									<div id="locationWrapper">
											<div class="formRow">
												<label for="lnglat"><?php echo $translations->location; ?></label><br />
												<input type="text" name="lnglat" value="" id="lnglat" class="input_xxlarge" />
											</div>
											<div id="locationOrSpacer"> - or - </div>
											
											<form id="submitReading" name="submitReading" method="post" action="submit/do" class="">

											<div class="latlngWrap">
												<div class="latlngbox">
													<label for="readings-lat"><?php echo $translations->latitude; ?></label><br />
													<input type="text" name="lat" value="" id="readings-lat" class="input_medium" />
												</div>
												<div class="latlngbox">
													<label for="readings-lng"><?php echo $translations->longitude; ?></label><br />
													<input type="text" name="lng" value="" id="readings-lng" class="input_medium" />
												</div>
											</div>
											<div id="locationMap"></div>
											<div id="mapNote"><?php echo $translations->youMayDragPin; ?></div>
										</div>
										
										
								</fieldset>
								<fieldset>
    								<legend><?php echo $translations->yourInfo; ?></legend>
									<div class="formRow">
										<label for="readings-first_name"><?php echo $translations->firstName; ?></label><br />
										<input type="text" name="first_name" value="" id="readings-first_name" class="input_xxlarge require" />
									</div>
									<div class="formRow">
										<label for="readings-last_name"><?php echo $translations->lastName; ?></label><br />
										<input type="text" name="last_name" value="" id="readings-last_name" class="input_xxlarge require" />
									</div>
									<div class="formRow">
										<label for="readings-email"><?php echo $translations->email; ?></label><br />
										<input type="text" name="email" value="" id="readings-email" class="input_xxlarge require" />
									</div>
								</fieldset>
								<div class="formRowSubmit"><br />
									<input type="hidden" name="reading_value" id="readings-reading_value" value="" /> 
									<input type="hidden" name="reading_system" id="readings-reading_system" value="" /> 
									<input type="hidden" name="equipment" id="readings-equipment" value="" /> 
									<input type="hidden" name="reading_date" id="readings-reading_date" value="" /> 
									<input type="image" src="images/submitNow.png" alt="" style="width:168px; height:33px:" />
								</div>			
  							</form>
						</div>
					</div>
					
				            <div class="submitSteps box">
								<div class="sectionHeadRight"><?php echo $translations->howSubmitHead; ?></div>
								<div id="stepsBody">
									<div id="stepsIntro"><?php echo $translations->howSubmitBody; ?>
									</div>
									<div id="stepsHead">
										<?php echo $translations->toSubmitHead; ?> 
									</div>
									<div class="step">
										<?php echo $translations->toSubmitStep1; ?> 
										<li><a href="http://shop.medcom.com/">INTERNATIONAL MEDCOM</a></li>
										<li><a href="http://amzn.to/hnk4g7">AMAZON</a></li>
										<li><a href="http://bit.ly/dNzH87">LAB SAFETY SUPPLY</a></li>
										<li><a href="http://bit.ly/dZBL3y">COLE-PARMER</a></li>
									</div>
									<div class="step">
										<?php echo $translations->toSubmitStep2; ?> 
									</div>
									<div class="step">
										<?php echo $translations->toSubmitStep3; ?> 
									</div>
								</div>
							</div>
					
				</div>
			</div>	
				
				
<?php $tmpl->place('footer') ?>
