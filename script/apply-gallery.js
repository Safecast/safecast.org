window.addEventListener?window.addEventListener("load",createGallery,false):window.attachEvent("onload",createGallery);

function createGallery() {
	$("a.gallery").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	true,
		'overlayColor'  :  '#333333',
		'overlayOpacity'  :  0.6,
		'showNavArrows' : true
	});

}