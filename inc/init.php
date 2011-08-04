<?php
include dirname(__FILE__) . '/config.php';

$lang = fRequest::get('lang',"string?" );
if($lang!=NULL){
	fCookie::set('safecast_lang', $lang, '+1 year', '/', '.safecast.org');
}
if($lang==NULL){
	$lang = fCookie::get('safecast_lang', 'en');
}

// get language file
$lang_file = DOC_ROOT . '/lang/' . $lang . '.json';
if (!file_exists($lang_file)) {
	$lang_file = DOC_ROOT . '/lang/en.json';
}
$file = new fFile($lang_file);
$lang_file_content = $file->read();
//print_r("file content: ".$lang_file_content);
$translations = json_decode($lang_file_content);

//print_r('file: '.$lang_file);
//print_r('************'.$translations.'^^^^^^^^^^^^^^^');

//die;




$tmpl = new fTemplating(DOC_ROOT . '/views/');
$tmpl->set('header', 'header.php');
$tmpl->set('header_headless', 'header_headless.php');
$tmpl->set('info_header', 'info_header.php');
$tmpl->set('footer', 'footer.php');
$tmpl->set('lang',$lang);

require_once(DOC_ROOT.'/Browser.php');
$mobile = mobile_device_detect(true,false,true,true,true,true,true,false,false);

if($mobile){
	$tmpl->add('css',  'style/mobile.css');
}else{
	$tmpl->add('css',  'style/base.css');
}




