<?php

//////////////////////////////////
////выполнение процесса снятия бонусов через плагин
add_action( 'updated_post_meta', 'redeem_bonus_precess', 10, 4 );
function redeem_bonus_precess($meta_id, $object_id, $meta_key, $_meta_value){
	$order_id = $object_id;
	$N = new RSPointExpiry();
	$N::update_redeem_point_for_user( $order_id );

	//wp_trash_post( $object_id ); //после использования бонуса переместить купон в корзину

}

////создание купона для бонусов
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/createcouponebonus', array( //регистрация маршрута
		'methods'             => 'POST',
		'callback'            => 'create_bonus_coupon'
	));
});

function create_bonus_coupon(WP_REST_Request $request ){

	//debug_to_file($coupons);
	$coupon_code = $request['code'];
	$amount = $request['amount'];
	
	debug_to_file($coupon_code); debug_to_file($amount);
	
	
	$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product

	$coupon = array(
	'post_title' => $coupon_code,
	'post_content' => '',
	'post_status' => 'publish',
	'post_author' => 1,
	'post_type' => 'shop_coupon');

	$new_coupon_id = wp_insert_post( $coupon );

	// Add meta
	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
	update_post_meta( $new_coupon_id, 'individual_use', 'no' );
	update_post_meta( $new_coupon_id, 'product_ids', '' );
	update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
	update_post_meta( $new_coupon_id, 'usage_limit', '' );
	update_post_meta( $new_coupon_id, 'expiry_date', '' );
	update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
	update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
	
	//return $response;
}

//удаление купона-бонуса после создания заказа
add_action( 'save_post', 'bonus_coupon_trash', 10, 1 );
function bonus_coupon_trash($order_id){ debug_to_file('order saved');
	$post_type = get_post_type($order_id);
	if($post_type == 'shop_order'){ debug_to_file('if order');
		$order = wc_get_order($order_id);
		foreach( $order->get_coupon_codes() as $coupon_code ){
			$find_code = 'sumo_';
			$pos = strpos($coupon_code, $find_code);
			if ($pos !== false) { //если купон является бонусом, то есть имеет sumo
				$coupon_id = wc_get_coupon_id_by_code( $coupon_code );
				debug_to_file($coupon_id);
				if($coupon_id != 0) wp_trash_post( $coupon_id );
			}
		}
	}
}

//получение системный кастомных надстроек
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/bonus', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'get_systinf_bon_user'
	) );
});

function get_systinf_bon_user(WP_REST_Request $request ){ // информациионные поля бонусов
    
    $ar_all_fields = [];
	$user_id = (int) $request['user_id'];
	$percent_max = get_field('bon_percent_max', 'option');
	
	global $wpdb ;
	$table = $wpdb->prefix . "rspointexpiry" ;
	$result = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $user_id" , ARRAY_A ) ;
    $points_balance = round_off_type( number_format( ( int ) $result[0]["availablepoints"] , 2 , '.' , '' ) );
	
    //$points = (int) get_field('android_ver', 'option'); 

	$ar_all_fields += ['user_id' => $user_id];
	$ar_all_fields += ['points_balance' => $points_balance];
    $ar_all_fields += ['percent_max' => intval($percent_max)];		
            

    return $ar_all_fields; 
}

add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_bonus_to_order', 10, 3 );
function rest_bonus_to_order( $response, $order, $request ) { //add bonus data in order
  $order_id = $order->get_id();
  $order = wc_get_order($order_id);

  $json = json_decode($request->get_body());
  //debug_to_file($json);
  $bonuses = intval($json->bonuses); //bonuses from request
  $response->data['bonusesN'] = $bonuses;
  add_post_meta( $order_id, '_bonuses', $bonuses, true );


  return $response;
}



/////расписание для активации сгорания бонусов

// новый интервал для крон
add_filter( 'cron_schedules', 'cron_add_interval' );
function cron_add_interval( $schedules ) {
	$schedules['one_min'] = array(
		'interval' => 60,
		'display' => 'Раз в 1 минут'
	);
	return $schedules;
}

// регистрируем событие крон
add_action( 'wp', 'add_expiry_schedule' );
function add_expiry_schedule() {
	if ( ! wp_next_scheduled( 'expiry_schedule' ) ) {
		wp_schedule_event( time(), 'hourly', 'expiry_schedule'); //hourly можно заменить one_min для теста 
	}
}

// добавляем функцию к указанному хуку крон
add_action( 'expiry_schedule', 'do_expiry' );
function do_expiry() {
	check_if_expiry_all_users();
}

//проверка и удаление бонусов у которых истёк установленный срок
function check_if_expiry_all_users() { //debug_to_file('schedule function');
    global $wpdb ;
    $table_name = $wpdb->prefix . 'rspointexpiry' ;
			
	$users = get_users( array( 'fields' => array( 'ID' ) )); //debug_to_file($users);
	foreach ($users as $user) { 
		$user_id = $user->ID; //debug_to_file($user_id);

		//Смотрим у кого истекают бонусы через 24 часа и шлём им уведомления (сделаем пока 23 для тестирования)
		$Data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE expirydate NOT IN(999999999999) and expiredpoints IN(0) and TIMESTAMPDIFF(HOUR, NOW(), from_unixtime(expirydate) ) = 23  and userid = %d" ,$user_id ) , ARRAY_A ) ;
		if ( srp_check_is_array( $Data ) ){ //mail('qashqai911@gmail.com', 'expiry return', $str_data);
			foreach ( $Data as $key => $eacharray ) {
				send_push_app_test($user_id, 'Внимание!', 'Завтра у Вас сгорят '.$eacharray['totalearnedpoints'].' бонусов. Успейте потратить.');
			}
		}
		

		$Data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE expirydate < %d and expirydate NOT IN(999999999999) and expiredpoints IN(0) and userid = %d" , time() , $user_id ) , ARRAY_A ) ;
		if ( srp_check_is_array( $Data ) ){ //mail('qashqai911@gmail.com', 'expiry return', $str_data);
			foreach ( $Data as $key => $eacharray ) {
				$wpdb->update( $table_name , array( 'expiredpoints' => $eacharray[ 'earnedpoints' ] - $eacharray[ 'usedpoints' ] ) , array( 'id' => $eacharray[ 'id' ] ) ) ;
			}
		}
	}		
}

//бонус за регистрацию
add_action( 'user_register' , 'rest_register_user_bonus' , 10 , 1 ) ;
function rest_register_user_bonus($user_id){
	$ObjAction = new RSActionRewardModule(); //объект для функции регистрации
	$ObjAction::award_points_for_account_signup( $user_id ); //функция регистрации
}


//Уведомление о начислении бонусов
add_action('woocommerce_order_status_completed', 'add_bonus_to_coupon_owner');
function add_bonus_to_coupon_owner($order_id) {
	$order = wc_get_order( $order_id);
	$order_data = $order->get_data();
	// debug_to_file(print_r($order_data, true));
	$user_id   = $order->get_user_id();
	global $wpdb ;
	$table = $wpdb->prefix . "rsrecordpoints" ;
	$result = $wpdb->get_results( "SELECT SUM(earnedpoints) as availablepoints FROM $table WHERE orderid = '".$order_id."' and userid = $user_id" , ARRAY_A ) ;
	//$bonuses = get_post_meta( $order_id, '_bonuses', true);
	$bonuses = floor($result[0]['availablepoints']);

	if($bonuses >= 1)
		send_push_app_test($user_id, 'Внимание', 'Вам начислено '.$bonuses.' бонусов за заказ '.$order_id);
	
	foreach($order_data['coupon_lines'] as $c){
		$coupon_code = $c->get_code();
		if( $user_id = rc_check_referal($coupon_code) ){

			if (rc_coupon_exists($coupon_code)){
				// debug_to_file('bonus_to_owner_for_first_checkout');
				bonus_to_owner_for_first_checkout($user_id);
				
			}
		}
	}
}