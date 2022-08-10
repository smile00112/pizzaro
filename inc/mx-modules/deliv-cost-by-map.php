<?php
////доставка расчитывается в зависимости от зоны или расстояния. связано с настройками условий в админке Доставка
//используется правильная json карта, а также select-address-start.js, mx.js
//фронт пишет данные в куки
add_filter( 'woocommerce_package_rates', 'wc_delivery_zone_cost1', 100, 2 );
function wc_delivery_zone_cost1( $rates, $package ) { //debug_to_file('hook deliver cost1');
	
	if(! wp_doing_ajax()){ // не проверять если не аякс запрос и не корзина
	return $rates;
}

 //print_r( $rates );
	$deliv_data = set_zone_session(); //debug_to_file('| '.$deliv_data[0].' - '.$deliv_data[1]);
	
	$deliv_cost = $deliv_data[0];
	
	$min_cart_amount = $deliv_data[1];
	
	$flag_cond = $deliv_data[2];


	if($_COOKIE['range_cost_error'])
	{
	//	wc_add_notice( $_COOKIE['range_cost_error'], 'error' );
		/*
		$result  = array();
		$result['result'] = 'failure';
		$result['messages'] = '<ul class="woocommerce-error" role="alert"><li>'.$_COOKIE['range_cost_error'].'</li></ul>';
		$result['reload'] = true;
		$result['fragments'] = array();
		echo json_encode($result);
		wp_die();
*/
	}
		

	//setcookie("zone_cost2", $_COOKIE["zone_cost"]);
	//debug_to_file('deliv_cost: '.$deliv_cost);

	//if(empty($deliv_cost) || $deliv_cost == '') $deliv_cost = $_COOKIE["zone_cost2"];

	if($deliv_cost || $deliv_cost == 0){
		foreach($rates as $key => $rate ) { //echo 'qwerty1 '.$rate; echo 'qwerty2 '.$key;
			//$rates[$key]->cost = $rates[$key]->cost - ( $rates[$key]->cost * ( $discount_amount/100 ) );
			if($rates[$key]->method_id == 'flat_rate'){  //debug_to_file('deliv_cost: '.$deliv_cost);
				$rates[$key]->cost = $deliv_cost;
				
				//$rates[$key]->upd = '1';
			}
		}
	}
	//debug_to_file($rates);
	
	//$minimum = 0;
	
	$cur_ship_meth = WC()->session->get( 'chosen_shipping_methods' )[0]; //debug_to_file($cur_ship_meth);debug_to_file('---');
	$is_flat = strpos($cur_ship_meth, 'flat_rate'); //если выбрана доставка
	
	if($flag_cond > 0 && is_checkout() && $is_flat !== false){ //debug_to_file('flag_cond > 0');
		//wc_print_notice( sprintf( 'Минимальная сумма корзины должна быть , текущая сумма .') ); 
		wc_add_notice( sprintf( 'Минимальная сумма корзины должна быть %s, текущая сумма %s.',  
                    $flag_cond, 
                    wc_price( WC()->cart->total )),
					'error' );
	}
	

	//wc_minimum_order_amount_checkout($min_cart_amount);
	
	return $rates;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'delivery/v1', '/changecost', array( 
		'methods'             => 'GET',
		'callback'            => 'set_zone_session'
	));
});


//обрабатываем настройки условий доставки из админки
function set_zone_session(){
	$cart_total =floatval( preg_replace( '#[^\d.]#', '',  WC()->cart->get_cart_total() ) ); //debug_to_file('cart_total: '.$cart_total);
	$deliv_cost_settings = get_delivcost_settings();
	$deliv_cost_settings = json_decode($deliv_cost_settings);
	$deliv_cost = 0;
	
	$flag_cond = 0;
	
	$min_sum = get_option( 'woocommerce_store_min_amount' );
	
	if($deliv_cost_settings->type_calc == 'zone') {
		$res_condition_zone = deliv_condition_zone($cart_total,$zone_deliv); //debug_to_file($res_condition_zone);
		$deliv_cost = $res_condition_zone[0];
		$min_sum = $res_condition_zone[1];
		$flag_cond = $res_condition_zone[2];
	}
	else if($deliv_cost_settings->type_calc == 'range'){
		$res_condition_range = deliv_condition_range($cart_total,$zone_deliv);
		$deliv_cost = $res_condition_range[0];
		$min_sum = $res_condition_range[1];
	}
	
	//debug_to_file($deliv_cost.' -+- '.$min_sum. ' -- '.$flag_cond);
	
	return [$deliv_cost, $min_sum, $flag_cond];
}

//получаем данные настроек условий доставки из админки
function get_delivcost_settings(){
$WP_REST_Request = new WP_REST_Request();
	
// use class methods	
	$result = get_systinf_deliv($WP_REST_Request,1); 
	return json_encode( $result ) ;
	/*
	$url_domain = 'https://'.$_SERVER['SERVER_NAME']; //debug_to_file($url_domain);
	$url_api = '/wp-json/systeminfo/v1/delivery';
	
	
	$cost_settings = curl_init($url_domain.$url_api);
	
	curl_setopt($cost_settings, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
	//curl_setopt($cost_settings, CURLOPT_COOKIE, 'zone_deliv='.$zone_id);
	curl_setopt($cost_settings, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($cost_settings, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec ( $cost_settings ); //debug_to_file($result);
	
	curl_close($cost_settings);

	
	$arr = json_decode($result); //debug_to_file($arr);
	
	
	return $result;
	*/
}

//выбираем условие доставки по расстоянию
function deliv_condition_range($cart_total){
	// unset($_COOKIE['range_cost_error']);
	// unset($_COOKIE['range_cost']);

	$range_cost_error = '';
	$deliv_cost = 0;
	$tmp_range = 0;
	$min_sum = get_option( 'woocommerce_store_min_amount' ); 
	$range_deliv =  $_COOKIE['range_deliv']; //debug_to_file('zone_deliv: '.$zone_deliv);
	//debug_to_file($_COOKIE);
	
	if(!empty($range_deliv) && $range_deliv != ''){
		setcookie("range_deliv2", $range_deliv, time()+3600, COOKIEPATH, COOKIE_DOMAIN); //дополнительно создаём/дублируем куки, потом что wp не везде видит
	}
	if(empty($range_deliv) || $range_deliv == '') $range_deliv = $_COOKIE['range_deliv2'];
	if(!empty($range_deliv) && $range_deliv != ''){ //debug_to_file('if range_deliv: '.$range_deliv);
		$deliv_cost_settings = get_delivcost_settings(); //debug_to_file('json: '.$deliv_cost_settings);
		$deliv_cost_settings = json_decode($deliv_cost_settings);
	
	/*
		foreach($deliv_cost_settings->conditions as $key=>$dcs){ //находим подходящее условие по расстоянию
			$tmp_range = floatval($dcs->range);
			if(floatval($range_deliv)  <= floatval($dcs->range)){
				
				break;
			}
		} //debug_to_file('tmp range: '.$tmp_range);
		$find_range = $find_cost = false;
		$needed_summ = 0;
		foreach($deliv_cost_settings->conditions as $dcs){ //debug_to_file('foreach range: '.intval($dcs->range).' - '.$tmp_range); //перебираем условия для подходящего расстояния, повторный проход по массиву, уже знаем нужное условие
			if(floatval($dcs->range) == $tmp_range){ //debug_to_file('if range: '.$tmp_range);
				foreach($dcs->sum as $sum_rate){ //перебираем условия стоимости корзины, ищем подходящее 
						if($cart_total > floatval($sum_rate->min_sum_order)){ //echo $cart_total .'>'.floatval($sum_rate->min_sum_order).'<br>';
							$deliv_cost = $sum_rate->deliv_price;
							$min_sum = $sum_rate->min_sum_order;

							$find_cost = true;
						}else{
							$needed_summ = $sum_rate->min_sum_order;
						}
				}
			//	$find_range	=  true;

			}
		}
					
		if(!$find_range) $range_cost_error = 'Для Вашего адреса доставка не производится';
		if(!$find_cost) $range_cost_error = 'Минимальная цена заказа для осуществения доставки по вашему адресу составляет '.$needed_summ.'&nbsp;<span class="woocommerce-Price-currencySymbol">₽</span>';
	*/	
		$range_a = array();
		foreach($deliv_cost_settings->conditions as $key=>$dcs){ //находим подходящее условие по расстоянию
			if(floatval($range_deliv)  <= floatval($dcs->range)){
			$range_a = 	$dcs;
				break;
			}
		}

//	print_r( $range_a );
if ( !$range_a ) {
//	wc_clear_notices();
	$range_cost_error = 'Для Вашего адреса доставка не производится';
	wc_add_notice( $range_cost_error, 'error' );
	$deliv_cost = 0;
} else {
	$mso = 0;
	foreach ( $range_a->sum as $sum ) {
		if ( $cart_total >= $sum->min_sum_order ) {
			$deliv_cost = $sum->deliv_price;
			$min_sum = $sum->min_sum_order;
			$mso = 1;
		}
	}
	if ( !$mso ) {
	//	wc_clear_notices();
		$range_cost_error = 'Минимальная цена заказа для осуществения доставки по вашему адресу составляет ' . $range_a->sum[ 0 ]->min_sum_order . '&nbsp;<span class="woocommerce-Price-currencySymbol">₽</span>';
		wc_add_notice( $range_cost_error, 'error' );
		$deliv_cost = 0;
	}
}	

		setcookie("range_cost", $deliv_cost, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
	//	setcookie("range_cost_error", $range_cost_error, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
		return [$deliv_cost, $min_sum];
	}
	return [$deliv_cost, $min_sum];
}

function deliv_condition_zone($cart_total,$zone_deliv){
	$deliv_cost = 0;
	$zone_deliv = $_COOKIE['zone_deliv']; //debug_to_file('zone_deliv: '.$zone_deliv);
	$min_sum = get_option( 'woocommerce_store_min_amount' );
	
	$flag_min_cart = 0; //флаг должен переключиться, когда проход по циклу достигнет хотя бы одной минимальной суммы корзины, то есть хотя бы одно условие доставки сработает, иначе сообщение
	$first_min_sum = 0; //если ни одно условие корзины не сработает, тут запишем минимальную сумму корзины для выбранной зоны
	
	//debug_to_file($_COOKIE);
	if(!empty($zone_deliv) && $zone_deliv != ''){
		setcookie("zone_deliv2", $zone_deliv, time()+3600, COOKIEPATH, COOKIE_DOMAIN); //дополнительно создаём/дублируем куки, потом что wp не везде видит
	}
	if(empty($zone_deliv) || $zone_deliv == '') $zone_deliv = $_COOKIE['zone_deliv2'];
	if(!empty($zone_deliv) && $zone_deliv != ''){ //debug_to_file('if zone_deliv: '.$zone_deliv);
		$deliv_cost_settings = get_delivcost_settings(); //debug_to_file('json: '.$deliv_cost_settings);
		$deliv_cost_settings = json_decode($deliv_cost_settings);
		foreach($deliv_cost_settings->conditions as $dcs){ debug_to_file('dsc: '.$dcs->zone); //перебираем зоны
			if($dcs->zone == $zone_deliv){ //debug_to_file('zone equal');
				foreach($dcs->sum as $sum_rate){ //debug_to_file('cart total: '.$cart_total.' | min order: '.$sum_rate->min_sum_order);  //перебираем условия стоимости корзины, ищем первое 
					if($cart_total > floatval($sum_rate->min_sum_order)){ //echo $cart_total .'>'.floatval($sum_rate->min_sum_order).'<br>';
						$deliv_cost = $sum_rate->deliv_price; //debug_to_file('deliv cost zone: '.$deliv_cost);
						$min_sum = $sum_rate->min_sum_order;  //debug_to_file('min sum: '.$min_sum);
						$flag_min_cart = 1; //сработало условие доставки
					}
				}
			}
		}
		
		if($flag_min_cart == 0){ //если сумма корзины не достигла ни одного условия, то возвращаем соответ. инф.
			foreach($deliv_cost_settings->conditions as $dcs){  //перебираем зоны
				if($dcs->zone == $zone_deliv){ $first_min_sum = $dcs->sum[0]->min_sum_order; break;
					foreach($dcs->sum as $sum_rate){   //перебираем условия стоимости корзины, ищем первое 
						if($cart_total > floatval($sum_rate->min_sum_order)){ 
							$deliv_cost = $sum_rate->deliv_price; 
							$min_sum = $sum_rate->min_sum_order;  
						}
					}
				}
			}
		}
		
		//debug_to_file('deliv cost zone after: '.$deliv_cost);
		unset($_COOKIE['zone_cost']);
		setcookie("zone_cost", $deliv_cost, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
		return [$deliv_cost, $min_sum, $first_min_sum];
	}
	
	return [$deliv_cost, $min_sum];
}

////проверки ограничения минимальной стоимости заказа
function wc_minimum_order_amount_checkout($minimum) { //debug_to_file('check min| '. WC()->cart->total .' - '.$minimum);
    //$minimum = get_option( 'woocommerce_store_min_amount' ); //минимальная сумма корзины
 
    if ( WC()->cart->total < $minimum ) { //debug_to_file('is min sum');
        if( is_cart() ) {
            wc_print_notice( 
                sprintf( 'Минимальная сумма корзины должна быть %s, текущая сумма %s.' , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->total )
                ), 'error' 
            );
        } else {
            wc_add_notice( 
                sprintf( 'Минимальная сумма корзины должна быть %s, текущая сумма %s.' , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->total )
                ), 'error' 
            );
        }
		remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 ); //отключения кнопки Оформить в корзине	
    }
 
} 