<?php
include './inc/init.php';
$tmpl->set('siteName', $translations->siteName);
$tmpl->set('siteTagline', $translations->siteTagline);
$tmpl->set('formallyKnownAs', $translations->formallyKnownAs);
$tmpl->set('maps', $translations->maps);
$tmpl->set('blog', $translations->blog);
$tmpl->set('forums', $translations->forums);
$tmpl->set('submitAReading', $translations->submitAReading);
$tmpl->set('languageSelect', $translations->languageSelect);
$tmpl->set('contact', $translations->contact);
$tmpl->set('follow', $translations->follow);
$tmpl->set('termsPolicy', $translations->termsPolicy);
$tmpl->set('title', 'Safecast Press');
$tmpl->set('pageName', 'press');
$tmpl->place('header');
?>
		<div class="content">
			<h1>Press</h1>
			<div class="relativeWrap">

			</div>
		</div>
<?php $tmpl->place('footer') ?>
