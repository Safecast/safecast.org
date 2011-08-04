<?php fHTML::sendHeader() ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->get('lang') ?>" lang="<?php echo $this->get('lang') ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="google-site-verification" content="vWXUlrnTL08knWQIGwumeb38qgYZlsrXc5VReJ1bUbs" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta property="og:title" content="Safecast" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://www.safecast.org" />
		<meta property="og:image" content="http://www.safecast.org/images/logo.png" />
		<meta property="og:site_name" content="Safecast" />
		<meta property="fb:admins" content="595809984" />
		<meta name="description" content="Safecast is a website that aggregates radioactivity data from throughout the world in order to provide real-time hyper-local information about the status of the Japanese nuclear crisis."> 
		<meta name="keywords" content="japan,fukushima,radiation,nuclear,reactor,geiger,counter,RDTN,Safecast">
		<title><?php echo $this->prepare('title') ?><?php echo (strpos($this->get('title'), 'Safecast') === FALSE ? ' - Safecast' : '') ?></title>
		
		<base href="<?php echo fURL::getDomain() . URL_ROOT ?>" />


		<link rel="stylesheet" type="text/css" href="style/reset.css" media="screen" />
		<!--<link rel="stylesheet" type="text/css" href="style/base.css" media="screen" />-->
		<?php echo $this->place('css') ?>
		<script type="text/javascript" src="script/jquery-1.5.1.min.js"></script>
		<?php echo $this->place('js', 'js') ?>
		<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
		<script type="text/javascript">  
		$(document).ready(function(){  	    
			$('a[href^="http"]').attr('target', '_blank');

 		});  
		$("#address").keyup(function(event){
		  if(event.keyCode == 13){
		    $("#addressSubmit").click();
		  }
		});
		</script>
    
    </head>
	<body style="background-color:#ffffff">
		<div class="base">
