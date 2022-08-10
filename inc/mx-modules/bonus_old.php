<?php

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
  //if( empty( $response->data ) ) return $response;
  $order_id = $order->get_id();
  $order = wc_get_order($order_id);
  $total = $order->get_total();

  $json = json_decode($request->get_body());
  //debug_to_file($json);
  $bonuses = intval($json->bonuses); //bonuses from request
  $response->data['bonusesN'] = $bonuses;
  add_post_meta( $order_id, '_bonuses', $bonuses, true );

	if($order){
		$data = $order->get_data();
		if(intval($bonuses) > 0){
			$new_total = intval($total)-intval($bonuses);
			update_post_meta( $order_id, '_order_total', $new_total );
					
			$check_upd_total = get_post_meta($order_id, 'updated_total_bonus');
			if($check_upd_total == '' || empty($check_upd_total)) add_post_meta($order_id, 'updated_total_bonus', $new_total);
			$check_upd_flag = get_post_meta($order_id, 'updated_total_flag');
			if($check_upd_flag == '' || empty($check_upd_flag)) add_post_meta($order_id, 'updated_total_flag', '1');
					
		}
	}

  return $response;
}


//////register user bonus///////
add_filter( 'user_register', 'bonus_for_sign', 100 );
function bonus_for_sign( $user_id ) {
	global $wpdb ;

	$s_bon = get_field('bon_for_reg', 'option');

	$table = $wpdb->prefix . "rspointexpiry" ;
	$wpdb->query( "UPDATE $table SET earnedpoints = $s_bon WHERE userid = $user_id" ) ;
}

//add_action( 'woocommerce_order_status_cancelled', 'order_custom_cancelled', 1, 1);
function order_custom_cancelled($order_id){
	$bonus = get_metadata( 'post', $order_id, '_bonuses', true );

	global $wpdb ;
	$user_id = get_metadata( 'post', $order_id, '_customer_user', true );
	$table = $wpdb->prefix . "rspointexpiry" ;
	$aval_b = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = 1" , ARRAY_N ) ;
	$wpdb->query( "UPDATE $table SET earnedpoints = earnedpoints + $bonus WHERE userid = $user_id" ) ;

}

//если статус выполнен, тогда списываются бонусы, если использовались при заказе
add_action( 'woocommerce_order_status_completed', 'order_custom_completed', 1, 1);
function order_custom_completed($order_id){
	$bonuses = get_post_meta( $order_id, '_bonuses', true );
	global $wpdb ;
	$user_id = get_metadata( 'post', $order_id, '_customer_user', true );
	$table = $wpdb->prefix . "rspointexpiry" ;
	$wpdb->query( "UPDATE $table SET usedpoints = $bonuses WHERE orderid = $order_id" ) ;
}

//вывод информации в админку, раздел итого
add_action('woocommerce_admin_order_totals_after_tax', 'custom_admin_order_totals_bonus', 10, 1 );
function custom_admin_order_totals_bonus( $order_id ) {
    $label = 'Бонусы';
    $value = get_post_meta( $order_id, '_bonuses', true );;

    ?>
        <tr>
            <td class="label"><?php echo $label; ?>:</td>
            <td width="1%"></td>
            <td class="custom-total"><b><?php echo $value; ?><b></td>
        </tr>
    <?php
}

////////////////////////////

//проверка, если сумма с учётом бонусов, то больше не менять сумму
add_action( 'updated_post_meta', 'check_upd_total', 10, 4 );
function check_upd_total( $meta_id, $object_id, $meta_key, $_meta_value ){
	if($meta_key == '_order_total'){
		$total = get_metadata( 'post', $object_id, '_order_total', true );
		$bonus = get_metadata( 'post', $object_id, '_bonuses', true );
		$upd_flag_total = get_post_meta($object_id, 'updated_total_flag', true);
		if($upd_flag_total == '1'){
			$upd_total_new = get_post_meta($object_id, 'updated_total_bonus', true);
			//debug_to_file('upd meta hook: ' . get_post_meta($object_id, '_order_total', true));
			update_post_meta( $object_id, '_order_total', $upd_total_new);
		}
	}
}


//////////////////

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
		wp_schedule_event( time(), 'one_min', 'expiry_schedule');
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
				
		$Data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE expirydate < %d and expirydate NOT IN(999999999999) and expiredpoints IN(0) and userid = %d" , time() , $user_id ) , ARRAY_A ) ;
		if ( srp_check_is_array( $Data ) ){ //mail('qashqai911@gmail.com', 'expiry return', $str_data);
			foreach ( $Data as $key => $eacharray ) {
				$wpdb->update( $table_name , array( 'expiredpoints' => $eacharray[ 'earnedpoints' ] - $eacharray[ 'usedpoints' ] ) , array( 'id' => $eacharray[ 'id' ] ) ) ;
			}
		}
	}		
}