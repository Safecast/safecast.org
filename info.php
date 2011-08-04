<?php
include './inc/init.php';
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

$tmpl->set('pageName', 'about');
$tmpl->add('css', '/style/info.css');
$tmpl->set('title', 'Safecast Information');
$tmpl->place('header');
?>
		<div class="content">
		  <div class="info">
    			<div class="infoCopy">
                    <div class="infoParagraph">
	                    <div class="infoHeader">Some Key Facts About Safecast</div><br/>
                    	<ul> 
<li>Safecast was created 1 week after the 3/11 Japan earthquake.</li> 
<li>Core team of 5 people, more than 50 regular volunteers worldwide.</li> 
<li>Team concentrations in Tokyo, Portland and Los Angeles.</li> 
<li>Raised $37K with Kickstarter and $40K from private donations.</li> 
<li>Currently deployed sensors: 6 mobile / 40 handheld / 10 static</li> 
<li>Another 50 devices will be active by the end of July 2011.</li> 
<li>Short term goal is to have 500 sensors deployed in Japan in 6 months.</li> 
<li>As of July, over 300,000 data points collected.</li> 
<li>All data collected and published by Safecast is open and available under <a href="http://creativecommons.org/choose/zero/">CC0</a> dedication.</li> 
<li>Currently designing networkable sensor devices for production later this year.</li> 
<li>Seeking additional funding to help with all of the above.</li> 
</ul>
                    	<p>For additional information, <a href="/about">please visit our About page</a>.</p>
             	
	                    <div class="infoHeader">Team</div><br/>
                        <table cellpadding="5" cellspacing="5">
                            <tr>
                                <td><img src="../images/team/_0000_akiba.jpg" alt="_0000_akiba" /><div class="team_label">Akiba<br/>hardware</div></td>
                                <td><img src="../images/team/_0002_alvarez.jpg" alt="_0002_alvarez" /><div class="team_label">Marcelino Alvarez<br/>cofounder and software lead</div></td>
                                <td><img src="../images/team/_0004_bonner.jpg" alt="_0004_bonner" /><div class="team_label">Sean Bonner<br/>cofounder and acting director</div></td>
                            </tr>
                            <tr>
                                <td><img src="../images/team/_0008_huang.jpg" alt="_0008_huang" /><div class="team_label">bunnie<br/>hardware lead</div></td>
                                <td><img src="../images/team/_0001_ewald.jpg" alt="_0001_ewald" /><div class="team_label">David Ewald<br/>cofounder and creative lead</div></td>
                                <td><img src="../images/team/_0009_franken.jpg" alt="_0009_franken" /><div class="team_label">Pieter Franken<br/>cofounder and Japan lead</div></td>
                            </tr>
                            <tr>
                                <td><img src="../images/team/_0007_ito.jpg" alt="_0007_ito" /><div class="team_label">Joi Ito<br/>advisor</div></td>
                                <td><img src="../images/team/_0006_murai.jpg" alt="_0006_murai" /><div class="team_label">Jun Murai<br/>keio university</div></td>
                                <td><img src="../images/team/_0005_ozzie.jpg" alt="_0005_ozzie" /><div class="team_label">Ray Ozzie<br/>advisor</div></td>
                            </tr>
                            <tr>
                                <td><img src="../images/team/_0010_sythe.jpg" alt="_0010_sythe" /><div class="team_label">Dan Sythe<br/>advisor</div></td>
                                <td><img src="../images/team/_0003_zhang.jpg" alt="_0003_zhang" /><div class="team_label">Haiyan Zhang<br/>data visualization</div></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>


    			</div>
    			<div class="infoRightColumn">
					<div class="sectionHeadRight">Contact</div>
					<div class="infoBox">
						<p> <br/>
                            Please feel free to contact us with any questions you may have about our project. 
                            </p>
                            <div class="infoBoxSubheader">For press inquiries:</div> 
                            <p><a href="mailto:press@safecast.org">press@safecast.org </a></p>
							
							<div class="infoBoxSubheader">For general inquiries:</div> 
                            <p><a href="mailto:info@safecast.org">info@safecast.org </a></p>
                            
                            <div class="infoBoxSubheader">Volunteers in Japan:</div> 
                            <p><a href="mailto:volunteer@safecast.org">volunteer@safecast.org </a></p>
                            
                            <div class="infoBoxSubheader">All other inquiries in Japan:</div> 
                            <p><a href="mailto:japan@safecast.org">japan@safecast.org </a></p>
                            
						<p>	
							Follow us on Twitter: <a href="http://www.twitter.com/safecastdotorg">@safecastdotorg</a>
						</p>
					</div>
					
<!--
					<div class="sectionHeadRight">Team</div>
					<div class="infoBox">
						<p> <br/>
                            The Safecast team is comprised of talented individuals from around the world. We have volunteers, dedicated members, 
                            and advisers who have come together to help us realize our goal of an open sensor network.
						</p>
                        <div class="infoBoxSubheader">Advisory Team:</div>
                        <p>
	                        Joi Ito<br/>
							Ray Ozzie<br/>
							Dan Sythe<br/>
                        </p>
                        <div class="infoBoxSubheader">Core Team:</div>
                        <p>
	                        Marcelino Alvarez<br/>
							Sean Bonner<br/>
							David Ewald<br/>
							Pieter Franken<br/>
							bunnie<br/>
                        </p>
                        <div class="infoBoxSubheader">Hardware/Software Development Team:</div>
                        <p>
							Akiba<br/>
							Scott Bates<br/>
							Shawn Bernard<br/>
							Rick Knowles<br/>
							Tokyo Hackerspace<br/>
							Haiyan Zhang<br/>
                        </p>
					</div>
-->
					<div class="sectionHeadRight">Logos and Images</div>
					<div class="infoBox">
						<p> <br/>
                            Feel free to use these in any context. For photography, please provide attribution.
						</p>
						<p>
							<a href="http://www.safecast.org/downloads/Safecast_Logos.zip">Logos</a></a><br/>
							Photos of Team Members<br/>
							PDF of Background Information<br/>
							Other Photos<br/>
						</p>					
					</div>
					
<!--
					<div class="sectionHeadRight">Recent Press</div>
					<div class="infoBox">
						<p> <br/>
                            <a href="http://www.npr.org/blogs/health/2011/03/24/134823329/citizen-scientists-crowdsource-radiation-measurements-in-japan">NPR</a><br/>
                            <a href="http://www.bbc.co.uk/news/technology-12803643">BBC News</a><br/>
                            <a href="http://www.bbc.co.uk/iplayer/episode/p00fvkxf/Click_05_04_2011/">BBC Radio</a><br/>
                            <a href="http://www.cbsnews.com/8301-501465_162-20045784-501465.html">CBS</a><br/>
                            <a href="http://www.wired.co.uk/news/archive/2011-03/22/rdtn-radiation">Wired UK</a><br/>
                            <a href="http://www.boingboing.net/2011/03/19/rdntorg-collects-cro.html">Boing Boing</a><br/>
						</p>
					</div>
-->
				</div>
            </div>
		</div>
<?php $tmpl->place('footer') ?>
