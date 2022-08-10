<?php

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

//echo get_field('gr_mob_app_text_button', 'option');

require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;


$link_market = '#';

if($detect->isiOS() ){ 
	$link_market = get_field('gr_mob_app_link_ios', 'option');
}
if($detect->isAndroidOS() ){ 
	$link_market = get_field('gr_mob_app_link', 'option'); 
}



?>