<?php
/*
* Template Name: Тест пустая
*/

get_header();

function testspee(){
	$url_domain = 'https://'.$_SERVER['SERVER_NAME']; //debug_to_file($url_domain);
	$url_api = '/wp-json/systeminfo/v1/testspeedload';
	
	
	$cost_settings = curl_init($url_domain.$url_api);
	
	curl_setopt($cost_settings, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
	//curl_setopt($cost_settings, CURLOPT_COOKIE, 'zone_deliv='.$zone_id);
	curl_setopt($cost_settings, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($cost_settings, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec ( $cost_settings ); //debug_to_file($result);
	
	curl_close($cost_settings);

	
	//$arr = json_decode($result); //debug_to_file($arr);
	
	return $result;
}

testspee();

get_footer( $footer_style );