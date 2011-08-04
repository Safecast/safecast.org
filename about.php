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
$tmpl->add('css', '/style/about.css');
$tmpl->set('title', 'About Safecast');
$tmpl->place('header');
?>
		<div class="content">
		  <div class="info">
    			<div class="infoCopy">
    				<!--
                    <div class="infoHeader"><?php echo $translations->about; ?></div>                    
                    <div class="infoParagraph"><?php echo $translations->aboutBody1; ?></div>
                    
                    <div class="infoHeader"><?php echo $translations->aboutQuestForData; ?></div>
					<div class="infoParagraph">
					<?php echo $translations->aboutBody2; ?>
					</div>
					<div class="infoParagraph">
					<?php echo $translations->aboutBody3; ?>
					
					</div>
                    <div class="infoHeader"><?php echo $translations->aboutOurGlobalTeam; ?></div>
					<div class="infoParagraph">
					<?php echo $translations->aboutBody4; ?>
					</div>
                    <div class="infoHeader"><?php echo $translations->aboutFuture; ?></div>
					<div class="infoParagraph">
					<?php echo $translations->aboutBody5; ?>
					</div>
					-->
					
					<div class="infoHeader">Mission</div> 
                	<div class="infoParagraph">
                    <p>Safecast is a global project working to empower people with data, primarily by building a sensor network and enabling people to both contribute and freely use the data we collect. After the 3/11 earthquake and resulting nuclear situation at Fukushima Diachi it became clear that people wanted more data than what was available. Through joint efforts with partners such as International Medcom and Keio University, Safecast has been building a radiation sensor network comprised of static and mobile sensors actively being deployed around Japan &#8211; both near the exclusion zone and elsewhere in the country.</p> 
</div>
<div class="infoParagraph"><p>Safecast supports the idea that more data – freely available data at that – is better. Our goal is not to single out any single source of data as untrustworthy, but rather to contribute to the existing measurement data and make it more robust. Multiple sources of data are always better and more accurate when aggregated.</p> 
</div>
<div class="infoParagraph"><p>While Japan and radiation is the primary focus of the moment, this work has made us aware of a need for more environmental data on a global level and the longterm work that Safecast engages in will address these needs. Safecast is based in the US but is currently focused on outreach efforts in Japan. Our <a title="Our Team" href="/info">team</a> has contributors from around the world.</p> 
</div>

<div class="infoHeader">Some Key Facts About Safecast</div>
 <div class="infoParagraph">
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
                </div>
                
                
					<div class="infoHeader">Credits</div> 
					<div class="infoParagraph">
                    <p>We have been fortunate to have been introduced to a few really amazing people who have been willing to listen to our idea, put us in contact with others, and help make this project a reality In addition to these new colleagues, we have also been working closely with many old friends on this. Thank you to everyone who has helped out in any and every capacity.</p> 
                    </div>
<div class="infoParagraph"> 
<table cellspacing="0" cellpadding="0"> 
<tbody> 
<tr> 
<td>Ed Borden<br /> 
Jesse Conway<br /> 
Silke von Esenwein<br /> 
David Evans<br /> 
<a href="http://freaklabs.org" target="_blank">Freaklabs</a><br /> 
GeigerCrowd.net<br /> 
Usman Haque<br /> 
Peter Hoffman<br /> 
<a href="http://www.twitter.com/huslage" target="_blank">Aaron Huslage</a><br /> 
<a href="http://www.twitter.com/joi" target="_blank">Joi Ito</a><br /> 
<a href="http://www.twitter.com/tokyomango" target="_blank">Lisa Katayama</a><br /> 
<a href="http://www.twitter.com/magck" target="_blank">Margaret Kendall</a><br /> 
Ivy Lin<br /> 
<a href="http://www.twitter.com/anisthmus" target="_blank">Jeremy Lind</a></td> 
<td>&nbsp;&nbsp;&nbsp;</td> 
<td>Masataka Odaka<br /> 
Megan Parish<br /> 
<a href="http://www.twitter.com/paryshnikov" target="_blank">Nick Parish</a><br /> 
Surj Patel<br /> 
Christina Perry<br /> 
Staci Bernard Roth<br /> 
Brad Rhodes<br /> 
Alexey Sidorenko<br /> 
<a href="http://www.twitter.com/MarianSteinbach" target="_blank">Marian Steinbach</a><br /> 
Gregg Sullivan<br /> 
Dan Sythe<br /> 
<a href="http://www.tokyohackerspace.org/" target="_blank">Tokyo Hackerspace</a><br /> 
<a href="http://www.twitter.com/turoczy" target="_blank">Rick Turoczy</a><br /> 
James Yaegashi</td> 
</tr> 
</tbody> 
</table> 
</div>
<div class="infoParagraph"> 
<p>In addition to our friends and colleagues, several organizations and academic institutions have provided assistance, in either tactical or material support. We are grateful for their help.</p> 
</div>
<div class="infoParagraph"> 
<table cellspacing="0" cellpadding="0"> 
<tbody> 
<tr> 
<td><a href="http://www.amazon.com" target="_blank">Amazon.com</a></td> 
</tr> 
<tr> 
<td><a href="http://garage.co.jp/en/ " target="_blank">Digital Garage</a></td> 
</tr> 
<tr> 
<td><a href="http://medcom.com" target="_blank">International Medcom</a></td> 
</tr> 
<tr> 
<td><a href="http://www.keio.ac.jp/" target="_blank">Keio University</a></td> 
</tr> 
<tr> 
<td><a href="http://www.kickstarter.com" target="_blank">Kickstarter</a></td> 
</tr> 
<tr> 
<td><a href="http://www.media.mit.edu/" target="_blank">MIT</a></td> 
</tr> 
<tr> 
<td><a href="http://www.pachube.com" target="_blank">Pachube</a></td> 
</tr> 
<tr> 
<td><a href="http://mb.softbank.jp/en/ " target="_blank">Softbank</a></td> 
</tr> 
</tbody> 
</table> 
</div>
<div class="infoParagraph">  
<p>Also, we completed a really successful Kickstarter campaign. As part of that, we wanted to give a shout out to people who donated. Thank you for your help in sending radiation detection devices to Japan.</p> 
</div>
<div class="infoHeader">Kickstarter $25 Donors</div>
<div class="infoParagraph"> 
<table cellspacing="0" cellpadding="0"> 
<tbody> 
<tr> 
<td>@brady<br /> 
/hug<br /> 
Aaron Azlant<br /> 
Ana &amp; Daniel Bagelomat<br /> 
Andrea<br /> 
Audrey Penven<br /> 
Ben Romer<br /> 
Benjamin Peter Ragheb<br /> 
Caitlin Beery<br /> 
Cameron Horn<br /> 
Chloe Lewis<br /> 
Chuck<br /> 
Cragg Nilson<br /> 
Craig Everett Jones<br /> 
Dan Watts<br /> 
Daniel<br /> 
Danielle Bauer<br /> 
David<br /> 
David Feuer<br /> 
Dominique LABIE<br /> 
Drew &amp; Harumi Blomquist<br /> 
Eddie Codel<br /> 
Eric Mendelsohn<br /> 
Eve and Zoe<br /> 
Gail taylor<br /> 
Hans Scharler
</td> 
<td></td> 
<td> 
Hermann Keldenich<br /> 
ikiya<br /> 
J. M. Lee<br /> 
Jan &amp; Durl<br /> 
Jason D<br /> 
Jessie<br /> 
JO-ANN HILLMAN<br /> 
John J. Rynne<br /> 
John LaRosa<br /> 
Jonathan Nathan<br /> 
Josh F<br /> 
Julie Daugherty<br /> 
Kate Wilentz<br /> 
kaysindre<br /> 
kenyatta cheese<br /> 
Kevin Coates<br /> 
Kim and Jamie Place-Gateau<br /> 
Kimberly Goldsworth<br /> 
Kinya Nakayama<br /> 
Lee Dale<br /> 
Lorraine Olla<br /> 
mariuss<br /> 
Mark Shea<br /> 
Matt Hunter<br /> 
Matthew Middleton<br /> 
MEENO
</td> 
<td></td> 
<td> 
Micki Krimmel<br /> 
Mike Shaver<br /> 
Mike ward<br /> 
Mike Weihs<br /> 
Nicolas INNOCENT<br /> 
Nora Olsen<br /> 
Pat Allan<br /> 
Pat Baer<br /> 
Pat Kiewicz<br /> 
Paul Bunnell<br /> 
R. Perkins<br /> 
Ricket Family<br /> 
Robert Mohns<br /> 
Ryan D. Edwards<br /> 
Sandro<br /> 
Shane Kohatsu<br /> 
Shout out<br /> 
Sonaar Luthra<br /> 
Stephen Burt<br /> 
Sue Stoessel<br /> 
Tarl<br /> 
Tomas BE<br /> 
&#8220;Verdell &#8220;&#8221;Crazy Legs&#8221;" Wilson&#8221;<br /> 
Victor Sahlstedt<br /> 
Zatapathique
</td> 
</tr> 
</tbody> 
</table> 
</div>
<div class="infoHeader">Kickstarter $50 and up Donors</div>
<div class="infoParagraph">  
<table> 
<tbody> 
<tr> 
<td><a href="http://twitter.com/#!/abluesheep" target="_blank">abluesheep</a><br /> 
<a href="http://www.twitter.com/albertonaranjo" target="_blank">Alberto Naranjo Galet</a><br /> 
<a href="http://twitter.com/#!/mandydale" target="_blank">Amanda Kelso</a><br /> 
<a href="http://twitter.com/andreanugget" target="_blank">Andrea Antoine</a><br /> 
<a href="http://andrewhy.de" target="_blank">Andrew Hyde</a><br /> 
<a href="http://twitter.com/waveposition" target="_blank">Barrett Kenney</a><br /> 
<a href="http://twitter.com/brekasis" target="_blank">brian rekasis</a><br /> 
Carl Rigney<br /> 
<a href="http://twitter.com/#!/ockapus" target="_blank">Christopher Mangum</a><br /> 
<a href="http://twitter.com/vogon" target="_blank">Colin B.</a><br /> 
<a href="http://twitter.com/zestygeek" target="_blank">Crystal Mora</a><br /> 
<a href="http://twitter.com/CurtHarpold" target="_blank">Curt Harpold</a><br /> 
<a href="http://twitter.com/dannygreenspun" target="_blank">Danny Greenspun</a><br /> 
<a href="http://twitter.com/iamewald" target="_blank">David Ewald</a><br /> 
<a href="http://twitter.com/davidharper" target="_blank">David Harper</a><br /> 
<a href="http://twitter.com/organicjapan" target="_blank">Donald Nordeng</a><br /> 
<a href="http://www.facebook.com/pages/Nuclear-News-and-Clean-Energy/124880387590985?sk=wall" target="_blank">E.A. and D.A. Bailey</a><br /> 
<a href="http://twitter.com/erik_curtis" target="_blank">Erik Curtis</a><br /> 
<a href="http://www.vanschip.com/words/" target="_blank">Gerard van Schip</a><br /> 
Hal Coyle<br /> 
<a href="http://www.twitter.com/HeatherSlutzky" target="_blank">Heather RCF Slutzky</a><br /> 
<a href="http://twitter.com/heatherarussell" target="_blank">Heather Russell</a><br /> 
<a href="http://twitter.com/rongzoni" target="_blank">Helen</a><br /> 
hhf<br /> 
<a href="http://twitter.com/pitbullsrock" target="_blank">Hilary</a><br /> 
<a href="http://twitter.com/imf" target="_blank">Ian McFarland</a><br /> 
<a href="http://twitter.com/vanzaj" target="_blank">Ivan</a><br /> 
J Gibbs &amp; D Page
</td> 
<td>&nbsp;&nbsp;&nbsp;</td> 
<td> 
Jay &amp; Caroline Brock<br /> 
<a href="http://twitter.com/Zumbee" target="_blank">Jem</a><br /> 
<a href="http://twitter.com/vertigo" target="_blank">Jon Swanson</a><br /> 
<a href="http://trananshjalp.org" target="_blank">Jonas Sjöstrand</a><br /> 
<a href="http://twitter.com/#!/jpoq" target="_blank">Juan P Ordonez Q.</a><br /> 
<a href="http://www.julesbeulen.nl" target="_blank">Jules Beulen</a><br /> 
<a href="http://twitter.com/#!/afromusing" target="_blank">Juliana Rotich</a><br /> 
<a href="http://www.justinchung.com" target="_blank">JustinChung.com</a><br /> 
<a href="http://twitter.com/kajisa" target="_blank">Kajisa</a><br /> 
<a href="http://booksandbamboo.wordpress.com" target="_blank">Kate Yonezawa</a><br /> 
<a href="http://twitter.com/kmcgivney" target="_blank">Kathleen</a><br /> 
<a href="http://twitter.com/kenbrady" target="_blank">Ken Brady</a><br /> 
<a href="http://ki.tt/" target="_blank">Kitt Hodsden</a><br /> 
<a href="http://usrportage.de" target="_blank">Lars</a><br /> 
<a href="http://twitter.com/Osiramon" target="_blank">Louis Srygley</a><br /> 
<a href="http://twitter.com/#!/lrock" target="_blank">Lrock</a><br /> 
<a href="http://twitter.com/#!/punkfreak" target="_blank">maarten smekens</a><br /> 
<a href="http://twitter.com/#!/MagCK" target="_blank">Margaret</a><br /> 
<a href="http://www.marijnrongen.com" target="_blank">Marijn Rongen</a><br /> 
<a href="http://twitter.com/MarkBruns" target="_blank">Mark</a><br /> 
Matt<br /> 
<a href="http://twitter.com/tari_manga" target="_blank">Matteo Mortari</a><br /> 
<a href="http://learntoduck.com" target="_blank">Micah Baldwin</a><br /> 
<a href="http://twitter.com/mdalto" target="_blank">Michael</a><br /> 
<a href="http://ionari.com" target="_blank">Michael Gorton</a><br /> 
<a href="http://twitter.com/michellechilds" target="_blank">Michelle Childs</a><br /> 
<a href="http://www.facebook.com/michiaki.tada" target="_blank">Michiaki Tada</a><br /> 
MIKA H.
</td> 
<td>&nbsp;&nbsp;&nbsp;</td> 
<td> 
<a href="http://www.twitter.com/momoetomo" target="_blank">momoetomo</a><br /> 
Morris Family<br /> 
<a href="http://twitter.com/nadinetoukan" target="_blank">Nadine Toukan</a><br /> 
<a href="http://steampunknancy.posterous.com" target="_blank">Nancy Wu</a><br /> 
Nick Ribis<br /> 
<a href="http://mod-blog.blogspot.com" target="_blank">Nomad</a><br /> 
<a href="http://oleg.sharedresearch.jp" target="_blank">Oleg Z.</a><br /> 
<a href="http://twitter.com/paulca" target="_blank">Paul Campbell</a><br /> 
<a href="http://twitter.com/Peterschout" target="_blank">Peter Schout</a><br /> 
<a href="http://twitter.com/plibin" target="_blank">Phil Libin</a><br /> 
<a href="http://twitter.com/marcopifferi" target="_blank">piff</a><br /> 
<a href="http://twitter.com/qasimRYD" target="_blank">Qasim</a><br /> 
<a href="http://facebook.com/ozzie" target="_blank">Ray Ozzie</a><br /> 
<a href="http://twitter.com/research4us" target="_blank">Research4Us</a><br /> 
<a href="http://siliconflorist.com" target="_blank">Rick Turoczy</a><br /> 
<a href="http://www.room77.de" target="_blank">ROOM77</a><br /> 
<a href="http://about.me/russelsimmons" target="_blank">Russel Simmons</a><br /> 
<a href="http://twitter.com/Sabocat" target="_blank">Sabocat</a><br /> 
<a href="http://www.facebook.com/SaveChildrenOfFukushima" target="_blank">SAVE CHILDREN OF FUKUSHIMA</a><br /> 
<a href="http://twitter.com/jessegri" target="_blank">Shauna &amp; Jesse</a><br /> 
<a href="http://www.kickstarter.com/profile/1771335626" target="_blank">Stephen [kiwin] PALM</a><br /> 
<a href="http://sc-fa.com" target="_blank">Steve Cooley</a><br /> 
<a href="http://twitter.com/tara" target="_blank">Tara Tiger Brown</a><br /> 
The Rudmin Family<br /> 
<a href="kevinryan.com" target="_blank">TokyoKevin</a><br /> 
Tom<br /> 
<a href="http://about.me/veronica" target="_blank">Veronica Belmont</a> 
</td> 
</tr> 
</tbody> 
</table> 
</div>
    			</div>
    			<div class="infoRightColumn">
					<div class="sectionHeadRight">Press</div>
					<div class="infoBox"><br/>
						<p>	
							For press information, <a href="/info">please visit our INFO page</a>.
						</p>
					</div>
					
				</div>
            </div>
		</div>
<?php $tmpl->place('footer') ?>
