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
		$("#address").keyup(function(event){
		  if(event.keyCode == 13){
		    $("#addressSubmit").click();
		  }
		});
		</script>
		
		<script type="text/javascript"> 
		  var geocoder = new google.maps.Geocoder();
		  var globalMap;
		  
		  function codeAddress() {
		    var address = document.getElementById("address").value;
		    geocoder.geocode( { 'address': address}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK) {
		        globalMap.setCenter(results[0].geometry.location);
		        var marker = new google.maps.Marker({
		            map: globalMap, 
		            position: results[0].geometry.location
		        });
		      } else {
		        alert("Geocode was not successful for the following reason: " + status);
		      }
		    });
		  }
		</script> 

	</head>
	<body>
		<div class="base">
			<div class="header">
				<div class="logo">
					<a href="http://www.safecast.org/<?php if($this->get('lang')=="jp")echo "ja/"?>"><img src="images/logo.png" alt="Safecast" align="left" /></a><br/>
				</div>
				<div class="mainNav">
					<div id="navBlogButton" class="mainNavButton <?php $pageName=$this->get('pageName'); if($pageName=="about")echo "navActive"; ?>"><a href="http://blog.safecast.org/<?php if($this->get('lang')=="jp")echo "ja/"?>about/" title="<?php echo $this->get('aboutCaps') ?>"><?php echo $this->get('aboutCaps') ?></a></div>
					<div id="navBlogButton" class="mainNavButton <?php $pageName=$this->get('pageName'); if($pageName=="drives")echo "navActive"; ?>"><a href="/drives" title="<?php echo $this->get('maps') ?>"><?php echo $this->get('maps') ?></a></div>
					<div id="navBlogButton" class="mainNavButton"><a href="http://blog.safecast.org" title="<?php echo $this->get('blog') ?>"><?php echo $this->get('blog') ?></a></div>
					<div id="navBlogButton" class="mainNavButton"><a href="/wiki" title="<?php echo $this->get('wiki') ?>"><?php echo $this->get('wiki') ?></a></div>
					<div id="navSubmitButton" class="mainNavButton <?php $pageName=$this->get('pageName'); if($pageName=="submit")echo "navActive"; ?>" style="width:116px;"><a href="/submit<?php $lang=$this->get('lang'); if($lang=="jp")echo "?lang=jp"; ?>" title="<?php echo $this->get('submitAReading') ?>"><?php echo $this->get('submitAReading') ?></a></div>
				</div>
				<div class="languageNav">
				    <div><?php echo $this->get('languageSelect'); ?></div>
					<div id="navEnglish" class="mainNavButton langNavButton <?php $lang=$this->get('lang'); if($lang=="en")echo "active"; ?>"><a href="./en" title="English" >English</a></div>
					<div id="navJapanese" class="mainNavButton langNavButton <?php $lang=$this->get('lang'); if($lang=="jp")echo "active"; ?>"><a href="./jp" title="日本語">日本語</a></div>
					<!--<div id="navEnglish" class="mainNavButton langNavButton <?php $lang=$this->get('lang'); if($lang=="de")echo "active"; ?>"><a href="./de" title="Deutsch" >Deutsch</a></div>-->
				</div>
			</div>
