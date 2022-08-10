<?php

///////////////Добавление мета-данных в поле комментарий заказа
add_filter( 'wp_insert_post_data', 'meta_time_note', 999, 2 ); // добавление информации мета-полей в комментарий заказа, пригодится для приложения Woo
function meta_time_note( $data, $postarr ){ //debug_to_file($data);
	$order_id = $postarr['ID']; 
	$gatetime = get_post_meta( $order_id, '_billing_gatetimecheckout', true );
	$pay_meth = get_post_meta( $order_id, '_payment_method_title', true );
	//$phone = get_post_meta( $order_id, '_billing_phone', true );
	$phone = get_post_meta( $order_id, 'billing_order_phone', true );
	$addr = get_post_meta( $order_id, '_billing_address_1', true ); //адрес
	$etazh = get_post_meta( $order_id, '_billing_company', true ); //этаж
	$kvart = get_post_meta( $order_id, '_billing_address_2', true ); //квартира
	$local_pick = get_post_meta( $order_id, 'local_pickup_name', true ); //точка самовывоза
	$user_id = get_post_meta( $order_id, '_customer_user', true );
	if($local_pick == '') $local_pick = get_post_meta( $order_id, '_carrier_name', true );
	//$local_pick = 'Свободы 139';
	$bonuses = get_post_meta( $order_id, '_bonuses', true ); //использовано бонусов
	//debug_to_file('--bon-'.$bonuses.'--');
	
	
	$origin_note = $data['post_excerpt']; //debug_to_file($origin_note);
	
	//$is_online_paid = get_post_meta( $origin_note, 'order_is_paid', true ); 

	$isset_pay_meth = 'Вид оплаты';
	$pos_pay_meth = strripos($origin_note, $isset_pay_meth);	
	
	$isset_addr = 'Адрес доставки';
	$pos_addr = strripos($origin_note, $isset_addr);
	
	$isset_samovivoz = 'Самовывоз';
	$pos_samovivoz = strripos($origin_note, $isset_samovivoz);
	
	$isset_samovivoz_point = 'Точка';
	$pos_samovivoz_point = strripos($origin_note, $isset_samovivoz_point);
	
	$isset_time_none = 'Время доставки';
	$pos_time = strripos($origin_note, $isset_time_none);
	
	$isset_tel = 'Телефон';
	$pos_tel = strripos($origin_note, $isset_tel);
	
	$isset_bon = 'Бонусы';
	$pos_bon = strripos($origin_note, $isset_bon);
	
	if(function_exists('get_user_preferences_text')){
		if($user_id){
			$isset_pref = 'Предпочтения';
			$pos_pref = get_user_preferences_text($user_id);
		}
	}
	$new_note = '';
	
	$shipping_method = '';
	$order = wc_get_order( $order_id );
	if($order){
		$shipping_method = @array_shift($order->get_shipping_methods());
		$ship_meth_id = $shipping_method['method_id'];
	}
	else{
		$ship_meth_id = '';
	}
	
	
	if ($pos_pay_meth === false && !empty($pay_meth)) { 
		$new_note .= PHP_EOL . '  | Вид оплаты : '.$pay_meth . ' |'.PHP_EOL;
	}
	
	if ($pos_bon === false && !empty($bonuses)) { 
		$new_note .= PHP_EOL . '  | Бонусы : '.$bonuses . ' |'.PHP_EOL;
	}
	

	if ($pos_addr === false && !empty($addr) && $ship_meth_id != 'local_pickup') { //&& $isset_samovivoz === false && empty($local_pick)
		//$new_note .= '  | Адрес доставки : '.$addr . ', Этаж: ' . $etazh . ', Квартира: '. $kvart . ' |'.PHP_EOL;
		$new_note .= '  | Адрес доставки : '.$addr . ', Этаж: ' . $etazh . ' |'.PHP_EOL;
	}

	
	if($pos_samovivoz === false && $ship_meth_id == 'local_pickup'){
		$str_sam = '  | Самовывоз |'.PHP_EOL;
		$new_note .= $str_sam;
			
	}
	
	if ($pos_samovivoz_point === false && !empty($local_pick)) { 
		$new_note .= '  | Точка : '. $local_pick . ' |'.PHP_EOL;
	}
	
	if ($pos_tel === false && !empty($phone)) { 
		$new_note .= '  | Телефон : '. $phone . ' |'.PHP_EOL;
	}
	
	if ($pos_time === false && !empty($gatetime)) { 
		$new_note .=  '  | Время доставки: '.$gatetime . ' |'.PHP_EOL;
	}

	if ($pos_tel === false && !empty($pos_pref)) { 
		$new_note .=  '  | Предпочтения3: '.$pos_pref . ' |'.PHP_EOL;
	}	
	
	$origin_note .= $new_note;
	
	$data['post_excerpt'] .= $new_note;
	
	return $data;
	
} 

