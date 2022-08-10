<?php
if (!is_admin()){

	add_filter( 'woocommerce_account_menu_items', 'available_coupons_link', 25 );
	function available_coupons_link( $menu_links ){
		$new_list = [];
		foreach($menu_links as $code=>&$l){
			if($code =='log-preferences'){
				$new_list[$code]=$l;
				$new_list[ 'available_coupons' ] = 'Доступные купоны';
			}else{
				$new_list[$code]=$l;
			}
		}
		return $new_list;
	
	}

	add_action( 'init', 'available_coupons_add_endpoint', 25 );
	function available_coupons_add_endpoint() {
	
		add_rewrite_endpoint( 'available_coupons', EP_PAGES );
	
	}
	
	add_action( 'woocommerce_account_available_coupons_endpoint', 'available_coupons_content', 25 );
	function available_coupons_content() {

		$user_coupons = get_user_avaible_coupons(1);
		//print_R($user_preferences);
		//$list = get_field('preferences_list', 'option') ;

		echo '<h3>Вам доступны следующие купоны</h3>';
		echo '<form method="POST" id="preferences-form" action="" onsubmit="save_preferences(this);return false;">
		<input type="hidden" name="action" value="save_preferences">
		';
		echo '<ul class="preferences-list">';
		foreach( $user_coupons as $c ){
			echo '<li><div class="list-left"><div class="list-coupon">Код '.$c['code'].'</div> <div class="list-coupon-description">'.$c['title'].'</div></div><div class="list-right"><button type="button" class="copy_promo" title="Скопировать" onclick="copy_promo(\''.$c['code'].'\')"><i class="fa-copy"></i></button><div></li>';
		}
		echo '</form>';

	}

	add_action( 'wp_footer', 'customer_available_coupons_script_js' );
	function customer_available_coupons_script_js() {
	
			?>
			<script type="text/javascript">

				function copy_promo($text){
					var area = document.createElement('textarea');

					document.body.appendChild(area);  
						area.value = $text;
						area.select();
						document.execCommand("copy");
					document.body.removeChild(area);  

					$('.woocommerce-MyAccount-content').append('<div class="copyed_message">Скопировано</div>');
					// не правильно!
					setTimeout(function(){
						$('.copyed_message').remove();
					}, 2000);
				}
				
			</script>

			<style>
			body.woocommerce-account ul li.woocommerce-MyAccount-navigation-link--log-preferences a:before{
				content: "\f0f6";
			}
			.preferences-list{
				width: 100%;
				margin: 0;
				margin-top: 30px;
			}
			.preferences-list li{
				list-style: none;
				border-bottom: 1px solid #0000001c;
				line-height: 20px;
				cursor: pointer;
				display: flex;
				justify-content: space-between;
				align-items: center;
				height: 60px;
			}
			.preferences-list li label{
				margin-left: 15px;
				cursor: pointer;
			}
			.woocommerce-MyAccount-navigation-link--available_coupons a:before{
				content: "\f06b"!important;
			}

			.copy_promo{
				width: 30px;
				height: 30px;
				padding: 0;
				border-radius: 10px;
				margin-left: 15px;
			}		
			.list-left{
				display: flex;
				align-items: flex-start;
				flex-direction: column;
				gap: 8px;
			}
			.list-coupon{
				font-size: 16px;
    			font-weight: 600;
			}
			.list-coupon-description{
				font-size: 16px;
    			color: #00000069;
			}
			</style>
			
			<?
	
	}


	// function get_user_preferences($user_id = 0){
	// 	if(empty($user_id))
	// 	    $user_id = get_current_user_id();
	// 	if(!$user_id) return false;
	//     $preferences = get_user_meta($user_id, 'preferences', true);
	//     $preferences_comment = get_user_meta($user_id, 'preferences_comment', true);
    //     $data['preferences']= json_decode($preferences, true);
	// 	$data['comment'] = $preferences_comment;

	// 	return $data;
	// }

	// function get_user_preferences_text($user_id = 0){
	// 	if(empty($user_id))
	// 	    $user_id = get_current_user_id();
	// 	if(!$user_id) return false;
	// 	$list = get_field('preferences_list', 'option');
	// 	$list_new = [];
	// 	$user_preferences = get_user_preferences($user_id);
	// 	$comment = $user_preferences['comment'];
	// 	foreach( $list as $l ){
	// 		$checked = in_array($l['code'], $user_preferences['preferences']) ? true : false;	
	// 		if($checked)
	// 		$list_new[]=$l['text'];
	// 	}
	// 	$user_preferences = implode(', ', $list_new);
	// 	if(!empty($comment))
	// 	    $user_preferences.=' - '.$comment;

	// 	return $user_preferences;
	// }	



}

?>
