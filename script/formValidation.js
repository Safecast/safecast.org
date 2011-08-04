window.addEventListener?window.addEventListener("load",addFormValidation,false):window.attachEvent("onload",addFormValidation);

function addFormValidation() {
	$('#lnglat').locationPicker();    
	$.geolocation.find(function(location){
	   $('#readings-lat').val(location.latitude);
   	   $('#readings-lng').val(location.longitude);
	   $('#lnglat').trigger('forceUpdate');
	}, function(){
	   //alert("Your device doesn't support jquery.geolocation.js");
	});
   //$('#lnglat').showPicker();

	
	$("#pickerDay").AnyTime_picker({ format: "%Y-%m-%d", monthAbbreviations:['01','02','03','04','05','06','07','08','09','10','11','12']})
	$("#pickerTime").AnyTime_picker({ format: "%l:%i %p"})
	
	$('#submitReading').checkForm();
	$('#submitReading').submit(prepSubmission);
	
	/*
	$('#input_reading_value').change(function() {
		$("#readings-reading_value").val(this.val());
	});
	
	$('#input_reading_system').change(function() {
		$("#readings-reading_system").val(this.val());
	});
	$('#input_equipment').change(function() {
		$("#readings-equipment").val(this.val());
	});
	$('#pickerDay').change(function() {
		updateTimestamp();
	});
	$('#pickerTime').change(function() {
		updateTimestamp();
	});
	*/
}

function prepSubmission(){
	//var success = $("#orderFormStep3").validationEngine('validate');
	
	var day = $("#pickerDay").val();
	var time = $("#pickerTime").val();
	var reading = $("#input_reading_value").val();
	var system = $("#input_reading_system").val();
	var equipment = $("#input_equipment").val();
	var day = $("#pickerDay").val();
	var time = $("#pickerTime").val();
	
	var valid = true;
	//validate!!
	if(!valid){
		//$('#'+id+', label[for="'+id+'"]').addClass("error");
		return false;
	}
	
	// create TimeStamp
	var fullTimeString = day + " " + time;
	var converter = new AnyTime.Converter({format:"%Y-%m-%d %l:%i %p"});
	var convertedDate = converter.parse(fullTimeString);
	var utc = convertedDate.getTime() + (convertedDate.getTimezoneOffset() * 60000);
	var utcDate = new Date(utc);
	var timestamp = jps_makeTimestamp(utcDate)
		
	//push values to the hidden equivelants 
	
	$("#readings-reading_value").val(reading);	
	$("#readings-reading_system").val(system);
	$("#readings-equipment").val(equipment);	
	$("#readings-reading_date").val(timestamp)

}
function updateTimestamp(){
	var day = $("#pickerDay").val();
	var time = $("#pickerTime").val();
	if(day!="" && time!=""){
		var fullTimeString = day + " " + time;
		var converter = new AnyTime.Converter({format:"%Y-%m-%d %l:%i %p"});
		var convertedDate = converter.parse(fullTimeString);
		var utc = convertedDate.getTime() + (convertedDate.getTimezoneOffset() * 60000);
		var utcDate = new Date(utc);
		var timestamp = jps_makeTimestamp(utcDate)
		$("#reading_date").val(timestamp)
	}
}

function jps_makeTimestamp( dateobj )
{
	var date = new Date( dateobj );
	var yyyy = date.getFullYear();
    var mm = date.getMonth() + 1;
    var dd = date.getDate();
    var hh = date.getHours();
    var min = date.getMinutes();
    var ss = date.getSeconds();
 
	var mysqlDateTime = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min + ':' + ss;
 
    return mysqlDateTime;
}


function deleteFile(which){
	//make ajax call to remove file from server
	//remove table row for file
	$('.fileSuccessRow').remove();
	//re-instate the form upload
	$('#fileInput').css("display","block");
	$('#file_upload input').removeAttr("disabled");
}

function contactSubmit(){
	var success = $("#contactForm").h5Validate({
			errorClass:'error'
		});
	if(success==true){
		$("#contactForm").submit();
	}
	return false;
}

function renderStep1Submit(){
	$('#orderFormStep1').h5ValidateNow({
			errorClass:'error'
		});
	var errors = $(".error").length
	if (errors > 0){
		return false;
	}else{
		return false;
	}
}

function renderStep2Submit(){
	//$("#orderFormStep2").submit();
	//return false;
}

function renderStep3Submit(){
	//var success = $("#orderFormStep3").validationEngine('validate');
	var atLeastOneIsChecked = $('input:checkbox:checked').length > 0;
	if(atLeastOneIsChecked==true){
		$("#orderFormStep3").submit();
	}else{
		$('input:checkbox').each(function(){
			var id=$(this).attr('id');
			$('#'+id+', label[for="'+id+'"]').addClass("error");
		});
	}
	return false;
}

function validateStep3(){
	var atLeastOneIsChecked = $('input:checkbox:checked').length > 0;
	if(atLeastOneIsChecked==true){
		$('input:checkbox').each(function(){
			var id=$(this).attr('id');
			$('#'+id+', label[for="'+id+'"]').removeClass("error");
		});
	}
}

function renderStep4Submit(){
	var submitForm = true;
	if($('input:checkbox:checked').length == 6){
		//all are checked
	}else{
		submitForm = false;
		$('input:checkbox').not(':checked').each(function(){
			var id=$(this).attr('id');
			$('#'+id+', label[for="'+id+'"]').addClass("error");
		});
	}
	if(submitForm){
		$("#orderFormStep4").submit();
	}
}

function shouldShowFileUpload(){
	if($('input:checkbox:checked').length == 6){
		//all are checked
		//enable file upload
		$('#uploaderDisabled').css("display", "none");
		$('#uploaderWidget').css("visibility","visible");
	}
	$('input:checkbox:checked').each(function(){
		var id=$(this).attr('id');
		$('#'+id+', label[for="'+id+'"]').removeClass("error");
	});
}


function updateSideCart(){
	$("#sideCartItems").empty();
	var price = 0.0;
	if($("#typicals-view_straight").is(':checked')){
		$("#sideCartItems").append('<div class="sideCartRow"><div class="sideCartRowDescription">Straight On View</div><div class="sideCartRowPrice">$49.99</div></div>');
		price += 49.99;
	}
	if($("#typicals-view_three_quarters").is(':checked')){
		$("#sideCartItems").append('<div class="sideCartRow"><div class="sideCartRowDescription">Three Quarters View</div><div class="sideCartRowPrice">$49.99</div></div>');
		price += 49.99;
	}
	if($("#typicals-view_detail").is(':checked')){
		$("#sideCartItems").append('<div class="sideCartRow"><div class="sideCartRowDescription">Detail View</div><div class="sideCartRowPrice">$49.99</div></div>');
		price += 49.99;
	}
	if($("#typicals-view_overhead").is(':checked')){
		$("#sideCartItems").append('<div class="sideCartRow"><div class="sideCartRowDescription">Overhead View</div><div class="sideCartRowPrice">$49.99</div></div>');
		price += 49.99;
	}
	$("#sideCartTotal").empty().append('<div class="sideCartTotalDescription">Total</div><div class="sideCartTotalPrice">$'+price+'</div>');


	
}
