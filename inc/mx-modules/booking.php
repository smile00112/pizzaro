<?php
////////////информация о товаре бронирования для конкретного покупателя////////////

add_action( 'rest_api_init', function () {

    register_rest_route( 'bookyth/v1', '/product/prod=(?P<prod_id>\d+)/user=(?P<user_id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'get_book_inf'
    ) );
    
});

function get_book_inf(WP_REST_Request $request ){ 
	$prod_id = $request['prod_id'];
	$user_id = $request['user_id'];
	
	global $wpdb ;
	$table = $wpdb->prefix . "postmeta" ;
	$query_post = $wpdb->get_results( "SELECT * FROM $table WHERE  post_id = $prod_id" , ARRAY_A ) ; //_yith_booking_max_per_block  total_sales
	//debug_to_file($query_post);
	
	$total_sales = 0; //всего продано
	$max_ticket = 0;  //максимальное количество заказов на одно время
	$raspisanie_master = ''; //расписание
	$temp_ser_rasp = ''; //temp var for unserialize data
	$duration_time = '';
	$duration_time_unit = ''; //часы/минуты/дни

	foreach($query_post as $row){
		if($row['meta_key'] == 'total_sales') $total_sales = $row['meta_value']; //всего продано
		if($row['meta_key'] == '_yith_booking_max_per_block') $max_ticket = $row['meta_value']; //максимальное количество заказов на одно время
		if($row['meta_key'] == '_yith_booking_availability_range') $temp_ser_rasp = $row['meta_value']; //расписание
		if($row['meta_key'] == '_yith_booking_duration') $duration_time = $row['meta_value']; //длительность в единицах
		if($row['meta_key'] == '_yith_booking_duration_unit') $duration_time_unit = $row['meta_value']; //часы/минуты/дни
		
	}
	
	$temp_ser_rasp = unserialize($temp_ser_rasp);
	foreach($temp_ser_rasp as $rule){
		if($rule['name'] == 'New time'){
			$raspisanie_master = $rule;
		} 
	}

	//debug_to_file($raspisanie_master);

	
	$user = get_user_by('id',$user_id);
	$is_paid = false;
	if($user){
		if ( wc_customer_bought_product( $user->user_email, $user_id, $prod_id ) ){
		$is_paid = true;
		}
	}
	
	return array(
			'prod_id' => $prod_id,
			'user_id' => $user_id,
			'is_paid' => $is_paid,
            'total_prod_sales' => $total_sales,
            'max_ticket'   => $max_ticket,
			'duration' => $duration_time,
			'duration_unit' => $duration_time_unit,
			'raspisanie' => $raspisanie_master
        );
}


///////////////вывод свободного времени для бронирования
add_action( 'rest_api_init', function () {
    register_rest_route( 'bookyth/v1', '/ticket/master', array( //регистрация маршрута
        'methods'             => 'POST',
        'callback'            => 'get_avail_time'
    ) );
});
function get_avail_time(WP_REST_Request $request){
    $prod_id = $request['prod_id'];
    $time_date = $request['date'];
    $time_duration = $request['duration'];
    $product = wc_get_product( $prod_id );
    $time_data  = $product->create_availability_time_array( $time_date, $time_duration ); //метод из плагина yith
    //print_r(json_encode($time_data));
    return $time_data;
}


add_filter( 'woocommerce_rest_prepare_product_object', 'booking_prep_prod', 10, 3 );

function booking_prep_prod( $response, $object, $request ){

// ЗАполняем полк regular_price, т.к. его нет у бронируемых товаров

if($response->data['type'] == "booking"){
    $response->data['regular_price'] = $response->data['price'];
};

return $response;

}