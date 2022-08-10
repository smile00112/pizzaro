<?php
///////////////////////////
add_filter( 'woocommerce_rest_prepare_product_object', 'video_url_fix', 10, 3 ); 

function video_url_fix( $response, $object, $request ){
	$video = 0; $k = 0;
	//if( empty( $response->data ) ) 
	foreach( $response->data['meta_data'] as $key=> &$m){
		// $m['value'] = 1234;
		if($m->key == 'video_url'){
			$video = $m->value;
			$k = $key;
		}

		//$response->data['meta_data'][$key]->value =  1234;
			
		//$response->data['meta_data']["25"]["value"] = "7777";
	}
	if($k && $video){
		$file_path = parse_url( wp_get_attachment_url( $video ) );

		$response->data['meta_data'][$k] = [
			"id" =>  3344452,
			"key"=> "video_url",
			"value"=> $file_path['scheme']."://".$file_path['host'].$file_path['path']
		];
	}
	//$response->data['reviews_allowed'] = 7777;
	return $response; 
	
}