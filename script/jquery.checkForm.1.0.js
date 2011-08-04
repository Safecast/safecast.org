/**
 * Form Check Fields plugin for jQuery
 * v 1.0
 * Check inputs form fields 
 *
 * By Michael Caillet, http://mi-ca.ch
 * project 	:	<null>
 * author	:	michael caillet http://mi-ca.ch
 * date		:	2010 03 30
 *
 * feedback and bug report are welcome
 */

/**
 * Usage:
 *
 * From JavaScript, use:
 *     $(<select>).checkForm({requireClass: <M>, errorClass: <N>, requireMark:<O>});
 *     where:
 *       <select> is the DOM form node selector, e.g. "form" or "#MyWonderfulForm"
 *       <M> is the css class which specifies that the fields must be checked (optional default:require)
 *       <N> is the css class which be added in case of error (optional default:error)
 *       <O> is a string or boolean to specify the requiered marker added after label text
 *       
 *      This plugin can check if an email is correctly formatted and if a field value is numeric.
 *      Simply add email or numeric in the id string :
 *      e.g 
 *          id="userEmail", id="email" 
 *          or 
 *          id="NPA_numeric" or id="numeric"
 */

(function($) {

	// jQuery plugin definition
	$.fn.checkForm = function(params) {

		// merge default and user parameters
		params = $.extend( {
							requireClass: 'require',
							errorClass: 'error',
							requireMark: '*'
						   }, params);

		// traverse all nodes
		this.each(function() {
			if($(this).is('form')){ // check if selector is a form tag
				$(this).submit(checkRequieredFields); // apply the checkin function
			}else{
				if($(this).find('form').index()>-1){ // check if child is a form
					$(this).find('form').submit(checkRequieredFields); // apply the checking function
				}else{ // no form tag found --- > alert an error
					alert("\n\nERROR plugin checkForm :\n\n$('->select<-').checkForm()\n\n ->select<- must be a <form> tag or contain at least one form!");
				}
			}
		});
		
		if(params.requireMark!=false){
			addRequireMark();
		}
		function addRequireMark(){
			if(typeof(params.requireMark)==='string'){
				$('form').find('.'+params.requireClass).each(function(){
					var id = $(this).attr('id');
					$('label[for="'+id+'"]').append(' <span class="requireMarker">'+params.requireMark+'</span>');
				});
			}
		}
		
		
		
		function checkRequieredFields(){
			var error = false; 					// set error to false (no error at this time)
			
			$(this).find('.'+params.requireClass).each(function(){ // find all .require fields
				var id = $(this).attr('id'); 		// grab id="" of item
				/*   check if email is correctly formated    */
				/* ----------------------------------------- */
				
				var emailInID = new RegExp("(email)",'gi'); // ereg for email 
				var numericInID = new RegExp("(numeric)",'gi'); // eret for numeric

				if(id.match(emailInID)){ // if email is found on field's id
				
					//  ereg filter for email
					var filter = /^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$/;
					// if email is not formated correctly
					if (!filter.test($('#'+id).val())){
						error = true;// now there is an error
						$('#'+id+', label[for="'+id+'"]').addClass(params.errorClass); 	// add .error class to input and linked label
					}else{ // no more error 
						$('#'+id+', label[for="'+id+'"]').removeClass(params.errorClass);// remove error class
					}	
				} //## end email test
				
				// check box test
				else if($(this).attr('type').toLowerCase()==="checkbox"){ // if input is a checkbox
					var name = $(this).attr('name');  // grab the name
					var checkBoxError=true; // no checkbox checked (error is true)
					$('input[name="'+name+'"]').each(function(){ // loop on each checkbox with the same name
						if($(this).attr('checked')===true){ // if one of check box is checked
							checkBoxError = false; // ok no more error
						} // ## end if one of checkbox is  checked
					});//## end loop each
					
					// if no checkbox are checked
					if(checkBoxError){
						$('input[name="'+name+'"]').each(function(){// loop on each checkbox with the same name
							var id=$(this).attr('id'); // grab id
							$('#'+id+', label[for="'+id+'"]').addClass(params.errorClass); // add class erreor
							error = true;
						});//# end loop
					}else{
						$('input[name="'+name+'"]').each(function(){ // loop on each checkbox
							var id=$(this).attr('id'); // grab id
							$('#'+id+', label[for="'+id+'"]').removeClass(params.errorClass); // remove Checkbox
						});
					}//#end if(checkBoxError)
				}//#end Check box text
				
				
				// radio test
				else if($(this).attr('type').toLowerCase()==="radio"){ //if input is a radiobutton
					var name = $(this).attr('name');  // grab the name
					var radioError=true; // no radio checked (error is true)
					$('input[name="'+name+'"]').each(function(){ // loop on each radio with the same name
						if($(this).attr('checked')===true){ // if one of radio button is checked (not really necessary)
							radioError = false; // ok no more error
						}// ## end if one of radio button is checked
					}); // ## end of loop on each radio
					
					
					// if no radio are checked
					if(radioError){
						$('input[name="'+name+'"]').each(function(){ // add error
							var id=$(this).attr('id');
							$('#'+id+', label[for="'+id+'"]').addClass(params.errorClass);
							error = true;
						});
					}else{ // remove error
						$('input[name="'+name+'"]').each(function(){
							var id=$(this).attr('id');
							$('#'+id+', label[for="'+id+'"]').removeClass(params.errorClass);
						});
					}
				}// #end radio test
				
				
			
				// Select test (doesn't work width multiple input) http://www.ryancramer.com/journal/entries/select_multiple/
				// check if the first is selected (generally "please choose")
				else if($(this).is('select')){ // if require is an select tag 
						var id=$(this).attr('id'); // grab the id
						if($(this).find("option:first").attr('selected')){ // find first item (if selected) add error
							$('#'+id+', label[for="'+id+'"],#'+id+' option:first').addClass(params.errorClass);
							error = true;
						}else{	// else remove error 
							$('#'+id+', label[for="'+id+'"],#'+id+' option:first').removeClass(params.errorClass);
						}
						
				}
					
				
				
				
				
				
				/* test numeric value */
				/* -----------------  */
				else if(id.match(numericInID)){ // if numeric value is found on the id
				
				   if(isNaN($('#'+id).val()) || $.trim($('#'+id).val())===''){ // check if is not a number and if empty
					   error = true; // set error
					   $('#'+id+', label[for="'+id+'"]').addClass(params.errorClass); 	// add error class
				   }else{ // else no error
						$('#'+id+', label[for="'+id+'"]').removeClass(params.errorClass);// remove error class
				   }	
				   
				} //## end numeric test
				
				/* test if fields are not empty */
				/* ---------------------------- */
				else if($.trim($('#'+id).val())==='' ){ //for other field (not checkbox,radio, select) check if empty
					error = true; // set error
					$('#'+id+', label[for="'+id+'"]').addClass(params.errorClass); 	// add class
				}else{ // else no error
					$('#'+id+', label[for="'+id+'"]').removeClass(params.errorClass);// remove class
				}	
				
				$('input#'+id+',textarea#'+id).val($.trim($('#'+id).val())); // remove space before and after the value (only on input & textarea)
			});

			if(error){ // if error 
				return false; // don't send form
			}else{ // else
				return true; // ok send form
			}
		};
		
		
		// allow jQuery chaining
		return this;
	};

})(jQuery);