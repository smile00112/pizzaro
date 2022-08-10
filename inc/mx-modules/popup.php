<?php
//////////////попап окна для фронта
//add_shortcode( 'popupmx', 'popupmx_get_field' );

function popupmx_get_field( $popup_id ){
	//$popup_id = $atts['id'];
	$ar_post = array();
	$post = get_post( $popup_id );
	
	$title = $post->post_title;
    $message = get_field( "message", $post->ID );
    $button_link = get_field( "button_link", $post->ID );
    $button_title = get_field( "button_title", $post->ID );
    $repeat_mode = get_field( "repeat_mode", $post->ID );
	$is_marketing = get_field( "marketing", $post->ID );
	if($is_marketing == true) $is_marketing = true;
	else $is_marketing = false;
		
	$disable_ordering = get_field( "disable_ordering", $post->ID );
	if($is_marketing == true) $disable_ordering = false;
	else if($is_marketing == false){ //echo '-'.$disable_ordering;
		if($disable_ordering != null && !empty($disable_ordering) ) $disable_ordering = get_field( "disable_ordering", $post->ID );
		else $disable_ordering = false;
	}
		
	if($repeat_mode == 'Повторять') $repeat_mode = 'repeatable';
	else if($repeat_mode == 'Один раз') $repeat_mode = 'one-off';
	else $repeat_mode = 'one-off';
        
    $sklad = get_field( "skladtaxon", $post->ID ); //var_dump($sklad);
    $sklad_ar = [];
	echo $_COOKIE['StockId'];
	print_r($sklad);
	echo '111111122222222222';	
    foreach($sklad as $item){ //echo $sklad['choices'][$v].'|'; //var_dump($v);  
        if($item == 110) array_push($sklad_ar, 110);
        if($item == 111) array_push($sklad_ar, 111);
		if($item == 118) array_push($sklad_ar, 118);
		if($item == 119) array_push($sklad_ar, 119);
    } //echo '---';  
		
        
        //var_dump($sklad); 
    $picture_url = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
    if(!$picture_url) $picture_url = '';
        
    // $ar_post += ['title' => $title];
    // $ar_post += ['message' => $message];
    // $ar_post += ['picture_url' => $picture_url];
    // //$ar_post += ['uuid' => $uuid];
    // $ar_post += ['button_link' => $button_link];
    // $ar_post += ['button_title' => $button_title];
    // $ar_post += ['repeat_mode' => $repeat_mode];
	// $ar_post += ['marketing' => $is_marketing];
	// $ar_post += ['disable_ordering' => $disable_ordering];
	// $ar_post += ['sklad' => $sklad_ar];  		
    
	$dis_ord_js = '';
	if($disable_ordering == true){  
		$dis_ord_js = 'true';
	}
	else $dis_ord_js = 'false';

	$str_sklad = '';
	foreach($sklad_ar as $sk) $str_sklad .= $sk.',';
	
	$str_html  = '<div class="wrap popup-mx mode-'.$repeat_mode.'" data-id="'.$popup_id.'" data-sklad="'.$str_sklad.'" data-is-ordering="'.$dis_ord_js.'">'; 
	$str_html .= '<div class="pop-title">'.$title.'</div>';
	if(!empty($picture_url))
	$str_html .= '<div class="img_wrap"><img src="'.$picture_url.'"></div>';
	$str_html .= '<div class="pop-mess">'.$message.'</div>';
	if($button_link && $button_title){
		//$str_html .= '<a href="'.$button_link.'" class="ss">'.$button_title.'</a>';
		if($button_link == '#')
			$str_html .= '<span class="pop-button-mx pop-button-mx-close">'.$button_title.'</span>';
		else if($button_link != '#' && $button_link != '')
			$str_html .= '<a href="'.$button_link.'" class="pop-button-mx">'.$button_title.'</a>';
	}	
	$str_html .= '</div>';
	
	echo $str_html;
	
	//return $str_html;
}



add_action( 'wp_footer', 'activate_popup' ); 
function activate_popup(){
	$popups_post = get_posts( array( 'post_type' => 'mapppops' ) ); //получаем попапы
	//debug_to_file($popups_post);
	$popup_id = [];
	foreach($popups_post as $popup) popupmx_get_field($popup->ID);
}