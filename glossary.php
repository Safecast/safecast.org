<?php
include './inc/init.php';
$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('pageName', 'glossary');
$tmpl->set('title', 'RDTN.ORG Glossary');
$tmpl->place('header');
?>
		<div class="content">
			<h1>Glossary</h1>
			<div class="relativeWrap">
				<div style="padding: 10px;">
						Iodine-131 (<sup>131</sup>I), also called radioiodine (though many other radioactive isotopes of this element are known), is an 
						important radioisotope of iodine. It has a radioactive decay half life of about eight days. Its uses are mostly 
						medical and pharmaceutical. It also plays a role as a major radioactive hazard present in nuclear fission products, 
						and was a significant contributor to the health effects from open-air atomic bomb testing in the 1950s, and from the 
						Chernobyl disaster, as well as being a threatening presence today in the Japanese nuclear crisis. This is because I-131 
						is a major uranium, plutonium and indirectly thorium fission product, comprising nearly 3% of the total products of fission (by weight).
				</div>
				<div style="padding: 10px;">
						Cesium-137 (137 55Cs, Cs-137) is a radioactive isotope of cesium which is formed as a fission product by nuclear fission.
						It has a half-life of about 30.17 years, and decays by beta emission to a metastable nuclear isomer of barium-137: 
						barium-137m (137mBa, Ba-137m). (About 95 percent of the nuclear decay leads to this isomer. The other 5.0 percent directly populates 
						the ground state, which is stable.) Ba-137m has a half-life of about 2.55 minutes, and it is responsible for all of the 
						emissions of gamma rays. One gram of cesium-137 has an activity of 3.215 terabecquerel (TBq).[2]
				</div>
				<div style="padding: 10px;">
						Plutonium-239 is an isotope of plutonium. Plutonium-239 is the primary fissile isotope used for the production of 
						nuclear weapons, although uranium-235 has also been used and is currently the secondary isotope. Plutonium-239 is 
						also one of the three main isotopes demonstrated usable as fuel in nuclear reactors, along with uranium-235 and 
						uranium-233. Plutonium-239 has a half-life of 24,200 years.
				</div>
			</div>
		</div>
<?php $tmpl->place('footer') ?>
