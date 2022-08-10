<?php
 // регистрируем событие крон на уведомение об окончании срока доступности купона
add_action( 'wp', 'add_check_coupon_time_schedule' );
function add_check_coupon_time_schedule() {
	if ( ! wp_next_scheduled( 'check_coupon_time_schedule' ) ) {
		wp_schedule_event( time(), 'hourly', 'check_coupon_time_schedule'); //hourly можно заменить one_min для теста 
	}
}

// Проверяем время до окньчания купона и шдём уведомление за день до окончания
add_action( 'check_coupon_time_schedule', 'check_coupon_time' );
function check_coupon_time() {
	//Получаем все купоны
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'asc',
		'post_type'        => 'shop_coupon',
		'post_status'      => 'publish',
		'meta_key'         => 'individual_coupon',
		'meta_value'  	 => '1',
	);

	$coupons = get_posts( $args );
	foreach($coupons as $coupon){
		//Получаем даннные купона
		$avaible_users = get_field('usage_avaible_users', $coupon->ID );
		$find = false;
		$timezone = wp_timezone_string();
		$tz = new DateTimeZone($timezone);
		$now_dt =  new DateTime( "now" , $tz );
		//print_R($avaible_users);
		foreach($avaible_users as $user_data){
			$use_until_dt =  new DateTime($user_data['use_until'] , $tz );
			$dt_diff = $use_until_dt->diff($now_dt);
			//echo $dt_diff->d .'__'. $dt_diff->h.'|';
			if($dt_diff->invert && ($dt_diff->d == 1 && $dt_diff->h == 0) ){ //если отрицательный период (дата еще не наступила)
				//echo $coupon->post_title;
				send_push_app_test($user_data['user'], 'Внимание!', 'Завтра у Вас закончится срок действия промокода "'.$coupon->post_title.'". Успейте применить.');
			}
		}
	}
}


// Доступные купоны
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/user/avaible_coupons', array(
		'methods' => 'GET',
		'callback' => 'api_get_user_avaible_coupons',
		// 'permission_callback' => function($request){      
		// 	return is_user_logged_in();
		//   }
	));
});

function get_user_avaible_coupons($user_id) {
	$avaible_coupons = [];
	//Получаем все купоны
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'asc',
		'post_type'        => 'shop_coupon',
		'post_status'      => 'publish',
		'meta_key'         => '',
		//'meta_value'  =>'',
	);
	
	$coupons = get_posts( $args );

	foreach($coupons as $coupon){
		
		$is_auto_on = get_post_meta($coupon->ID, 'coupon-autoapply', true);
		$show_to_user = get_post_meta($coupon->ID, 'show_to_user', true);
		$individual_coupon =  get_post_meta($coupon->ID, 'individual_coupon', true );	
		$coupon_usage_limit_per_user =  get_post_meta($coupon->ID, 'usage_limit_per_user', true );	
		$coupon_max_users_uses =  get_post_meta($coupon->ID, '_used_by' );
		$coupon_max_users_uses = array_count_values($coupon_max_users_uses);

		//Пропускаем автокупоны
		if($is_auto_on[0] == 'yes') continue;
		//Пропускаем уже активированные купоны
		if($coupon_usage_limit_per_user && ($coupon_max_users_uses[$user_id] >= $coupon_usage_limit_per_user) ) continue;
		if( strpos($coupon->post_title, 'promo-') !== false ) continue;

		if($show_to_user || $individual_coupon){
			$is_individual = false;
			//В индивидуальном купоне делаем доп. проверку
			if($individual_coupon){
				$is_individual = true;
				$avaible_to = '';
				$avaible_users = get_field('usage_avaible_users', $coupon->ID );

				$find = false;
				$timezone = wp_timezone_string();
				$tz = new DateTimeZone($timezone);
				$now_timestamp =  new DateTimeImmutable( "now" , $tz );
				foreach($avaible_users as $user_data){
					if( $user_data['user'] == $user_id ){
						$avaible_to = $user_data['use_until'];
						$use_until =  new DateTimeImmutable($user_data['use_until'] , $tz );
						if( $now_timestamp->getTimestamp() <= $use_until->getTimestamp() ){
						
							$find = true;
							break;
						}
					}
				} 
				if(!$find){
					continue;
				}
			}
			$avaible_coupons[]=[ 
				'title' => $coupon->post_excerpt,
				'code' => $coupon->post_title,
				'individual' => $is_individual,
				'avaible_to' => $avaible_to,
			];

		}
	}

	return $avaible_coupons;
}

function api_get_user_avaible_coupons(WP_REST_Request $request) {
	// 	ini_set('display_errors', 'On'); //отключение ошибок на фронте
	// ini_set('log_errors', 'On'); //запись ошибок в логи
	// История пушей пользователя
	$request_data = $request->get_params();
	$limit = 100;
	$user_id = intval($request_data['user_id']);
	if(!$user_id ) return [];

	return get_user_avaible_coupons($user_id);
	
}