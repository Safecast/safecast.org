//
// Add a class of "ds" to any div you want to have a drop shadow
// Written by James Player 2009
//

window.addEventListener?window.addEventListener("load",addOldSchoolShadows,false):window.attachEvent("onload",addOldSchoolShadows);

function addOldSchoolShadows() {
	$(".shadow").drop_shadow();
}

(function($) {
    $.fn.drop_shadow = function(options) {
        // Check if CSS3 is supported
        var style = $('div')[0].style;
        var isCSS3 = style.MozBoxShadow !== undefined || style.WebkitBoxShadow !== undefined || style.BoxShadow !== undefined;
		if (isCSS3) {
			//dont bother doing anything if that is the case, CSS styles have already applied the shadow.
			return false;
		}

        $(this).each(function() {
        	$(this).css("border","1px solid #BBB");
            
        });
        
    }    
})(jQuery);