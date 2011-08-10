<?php
include './inc/init.php';
$tmpl->add('js',  'http://maps.google.com/maps/api/js?sensor=false');
//$tmpl->add('js',  'script/maps.js');
$tmpl->add('js',  'script/jquery.xml2json.pack.js');
$tmpl->add('js',  'script/mainPageSimpleFeed.js');
$tmpl->add('js',  'script/geigermap/grid_map.js');
$tmpl->add('js', 'http://widgets.twimg.com/j/2/widget.js');
$tmpl->add('js', '/script/fancybox/jquery.fancybox-1.3.4.pack.js');
$tmpl->add('js', '/script/fancybox/jquery.mousewheel-3.0.4.pack.js');
$tmpl->add('js', '/script/flot/jquery.flot.js');
$tmpl->add('js', '/script/flot/jquery.flot.axislabel.js');
$tmpl->add('css', '/script/fancybox/jquery.fancybox-1.3.4.css');
$tmpl->set('title', 'Safecast');
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
$tmpl->set('pageName', 'index');
$tmpl->set('lang', $lang);

$tmpl->place('header');
?>
			<div class="content">
				<div class="relativeWrap">
					<div class="fullScreenWidth posColumn1 whiteBg">
					  <div class="sectionHeadRight">Safecast Map</div> 
						<div id="grid_map_canvas" class="map_canvas"></div>
						<script> 
							gm_init({
								zoom:7,
								lat:37.207889,
								lng:139.969678,
								div: "grid_map_canvas",
								map_name: "jp_grid",
								show_label:true,
								view_by: "grid"
							});
							gm_load_feeds({
								json: new Array("/feeds/gridFusionSmall.json"),
							});
							create_maps();
						</script>					
 						<div class="oneColumnWidth posColumn3">
			    		<div id="steps" class="box">
						<div class="sectionHeadRight">&nbsp;</div>
						  <div class="leftHalfSizeBody" style=""> 
						  	<!--<p style="padding-top: 10px;"><img src="/images/gridmap_grey.png" class="pinImage" height="18" width="18"/> <?php echo $translations->gridMapLegend1; ?></p> 
						      <p style="padding-top: 10px;"><img src="/images/gridmap_green.png" class="pinImage" height="18" width="18"/> <?php echo $translations->gridMapLegend2; ?></p> -->
							  <p><?php echo $translations->gridMapBody; ?> <a href="<?php echo $translations->driveListHref; ?>"><?php echo $translations->gridMapBodyLink; ?></a></p> 
							  <p><br /><br /><div class="redButton"><a href="/fusion">Interact with full data set</a></div><br /></p>
  						      <p><div class="redButton"><a href="<?php echo $translations->driveListHref; ?>">Individual drive maps</a></div></p>

						      <div id="donateBox" align="center" style="margin-top: 180px;">Want to help us collect more data?<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="DK487PKCJLGX6">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></div>
						  </div> 
						  </div>
						 </div>
						  
						  
					<!--
					<div class="oneColumnWidth posColumn3">
    							<div id="steps" class="box">
    								<div class="sectionHeadRight"><?php echo $translations->safecastBlogPosts; ?></div>
    								<div id="blogBody">
    									<script src="http://feeds.feedburner.com/safecastorg?format=sigpro" type="text/javascript" ></script>
    									<noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/rdtnorg"></a></p> </noscript>
    								</div>
    								<div class="leftHalfSizeBody">
						      		<li><a href="http://blog.safecast.org">... more</a></li>
   						      		<div class="rowSpacer20"></div>
   						      		</div>
    							</div>
    							
						</div>
					-->		  
				</div>
				<div class="rowSpacer20"></div>
				<div class="relativeWrap">
					<div class="halfScreenWidth posColumn1">
						<div id="safecasting" class="wideBox">
    								<div class="sectionHeadRight"><?php echo $translations->safecastingTitle; ?></div>
    								<a href="/drive/6"><img src="/images/sampleDrive.jpg" alt="Drive 6" /></a>
							<div class="leftHalfSizeBody" style="background-color: white;padding: 10px;line-height: 15px; font-size: 11px; color: black;"> 
							  <p><?php echo $translations->safecastingBody; ?>  </p> 
						      <div style="padding-top: 10px;"><?php echo $translations->featuredMaps; ?></div> 
						      <li><a href="/drive/6"><?php echo $translations->featuredMap1; ?></a></li>
						      <li><a href="/drive/2"><?php echo $translations->featuredMap2; ?></a></li>
						      <li><a href="/drive/23"><?php echo $translations->featuredMap3; ?></a></li>
						      <li><a href="/drive/19"><?php echo $translations->featuredMap4; ?></a></li>
						      <li><a href="/drive/46"><?php echo $translations->featuredMap5; ?></a></li>
						      <div class="rowSpacer20"></div>
						      <div class="redButton"><a href="<?php echo $translations->driveListHref; ?>">Full List of Maps</a></div>
   						      <div class="rowSpacer20"></div>

							</div> 
						</div>
					</div>
					
					<div class="halfScreenWidth posSecondHalf">
						
								
						<div class="relativeWrap">
							<div class="halfScreenWidth posColumn1">
								<div class="wideBox" id="safecastNetwork">
									<div class="sectionHeadRight"><?php echo $translations->sensorNetworkTitle; ?></div>

									<div id="small_map_canvas" class="map_canvas"></div>
									<div class="wideBoxContent" style="background-color: white;padding: 10px;line-height: 15px; font-size: 11px; color: black;"> 
							  <p><?php echo $translations->sensorNetworkBody1; ?></p> 
						      <p><?php echo $translations->sensorNetworkBody2; ?> </p>
						      <div class="rowSpacer20"></div>
						      <div class="redButton"><a href="/feedmap"><?php echo $translations->aggregateButton; ?></a></div>
   						      <div class="rowSpacer20"></div>

						  </div> 

								</div>
							</div>
						</div>
						<!--
							<div class="oneColumnWidth posColumn1">
    							<div class="box" id="aboutRdtn">
    								<div class="sectionHeadRight"><?php echo $translations->aboutHead; ?></div>
    								<div class="leftHalfSizeBody">
    								    <p>
    								        <?php echo $translations->aboutPart1; ?>
                                        </p>
                                        <p>
                                            <?php echo $translations->aboutPart2; ?>
                                        </p>
                                        <p>
                                            <?php echo $translations->aboutPart3; ?>
                                        </p>
                                        <p>
                                            <?php echo $translations->aboutPart4; ?>
                                        </p>
                                        
										<li><a href="http://www.pachube.com">Pachube.com</a></li>
										<li><a href="http://www.sendung.de/japan-radiation-open-data/">Marian Steinbach</a></li>
										<li><a href="http://www.GeigerCrowd.net">GeigerCrowd.net</a></li>
										<li><a href="http://www.mext.go.jp/english/"><?php echo $translations->mextOfJapan; ?></a></li>
										<li><a href="http://radiation.crowdmap.com/">Radiation Crowdmap</a></li>
                                        <hr class="dotted"/>
                                        <p>
                                            <?php echo $translations->aboutPart5; ?>
                                        </p>
										<li><a href="http://bit.ly/fgGX3a"><?php echo $translations->otherWaysLink1; ?></a></li>
										<li><a href="http://bit.ly/fWD0Hi"><?php echo $translations->otherWaysLink2; ?></a></li>
										<li><a href="http://bit.ly/icxEJR"><?php echo $translations->otherWaysLink3; ?></a></li>
										<li><a href="https://www.mercycorps.org/donate/japan"><?php echo $translations->otherWaysLink4; ?></a></li>
										<li><a href="http://www.2hj.org/index.php/eng_home"><?php echo $translations->otherWaysLink5; ?></a></li>
    								</div>	
    							</div>
								
													
							
						</div>
						-->
						
					</div>
					<div class="rowSpacer20"></div>
					
						
						<div class="relativeWrap">
							<!--
							<div class="oneColumnWidth posColumn1">
	    							<div class="box">
									<script>
									new TWTR.Widget({
									version: 2,
									type: 'profile',
									rpp: 6,
									interval: 6000,
									width: 305,
									height: 300,
									theme: {
									 shell: {
									  background: '#e6e7ed',
									  color: '#7a767a'
									 },
									 tweets: {
									  background: '#ffffff',
									  color: '#474547',
									  links: '#5ea6c0'
									 }
									},
									features: {
									 scrollbar: false,
									 loop: false,
									 live: true,
									 hashtags: true,
									 timestamp: true,
									 avatars: false,
									 behavior: 'all'
									}
									}).render().setUser('safecastdotorg').start();
									</script>
								</div>
								
	    							
							</div>
							-->
							
							<!--	
							<div class="oneColumnWidth posColumn2">
								<div class="box">
									<div style="padding-left:15px;"><br/>
										<iframe src="http://www.facebook.com/plugins/like.php?href=http://www.safecast.org"
												scrolling="no" frameborder="0"
												style="border:none; width:250px; height:80px"></iframe>
									</div>
								</div>
							-->
								<!--
								<div class="box" id="creditRdtn">
										<div class="sectionHeadRight"><?php echo $translations->creditAndThanks; ?></div>
										<div class="creditSectionBody">
											<div class="creditIntro"><?php echo $translations->creditBlurb; ?></div>
											<p><?php echo $translations->alphabeticalOrder; ?></p>
											<div class="creditNames">
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>
															
															<p>Ed Borden</p>
															<p>Jesse Conway</p>
															<p>Silke von Esenwein</p>
															<p>David Evans</p>
															<p><a href="http://freaklabs.org/">FreakLabs</a></p>
															<p>GeigerCrowd.net</p>
															<p>Usman Haque</p>
															<p>Peter Hoffman</p>
															<p>Aaron Huslage</a></p>
															<p><a href="http://www.twitter.com/joi" target="_blank">Joi Ito</a></p>
															<p><a href="http://www.twitter.com/tokyomango" target="_blank">Lisa Katayama</a></p>
															<p><a href="http://www.twitter.com/magck" target="_blank">Margaret Kendall</a></p>
															<p>Ivy Lin</p>
															<p><a href="http://www.twitter.com/anisthmus" target="_blank">Jeremy Lind</a></p>
															<p>Masataka Odaka </p>
															
														</td>
														<td>
															<p>Megan Parish</p>
															<p><a href="http://www.twitter.com/paryshnikov" target="_blank">Nick Parish</a></p>
															<p>Surj Patel</p>
															<p>Christina Perry</p>
															<p>Staci Bernard Roth</p>
															<p>Brad Rhodes</p>
															<p>Alexey Sidorenko</p>
															<p><a href="http://www.twitter.com/MarianSteinbach" target="_blank">Marian Steinbach</a></p>
															<p>Gregg Sullivan</p>
															<p>Dan Sythe</p>
															<p><a href="http://www.tokyohackerspace.org/">Tokyo Hackerspace</a></p>
															<p><a href="http://www.twitter.com/turoczy" target="_blank">Rick Turoczy</a></p>
															<p>James Yaegashi</p>
															<p>Amazon.com</p>
															<p>Digital Garage</p>
														</td>
													</tr>
												</table>
											</div>
										</div>	
									</div>
									
									-->
								
	    				        
							</div>
						</div>
					</div>
				</div>
				
					
				</div>
			</div>
<script>
	$("#address").keyup(function(event){
	  if(event.keyCode == 13){
	    $("#addressSubmit").click();
	  }
	});
</script>
<?php $tmpl->place('footer') ?>
