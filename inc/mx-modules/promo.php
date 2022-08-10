<?php

////акции  (срабатывают при добавлении товара в корзину)
function get_all_active_stocks(){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'asc',
		'post_type'        => 'promo',
		'post_status'      => 'publish',
		'meta_key'         => '',
		'meta_value'  =>'',
	);
	$stocks = [];
	$promos = get_posts( $args );
	foreach ($promos as &$post) {
		
		$stock_type = get_post_meta($post->ID, 'stock_type', true);
		$sklad = get_post_meta($post->ID, 'sklad_promo', true);
		switch($stock_type){
			case 'one_category_product_free':
				$categoryes_data = [];
				$i=0;
				$code = get_post_meta($post->ID, 'stock_code', true);
				while( $category = get_post_meta($post->ID, 'free_product_fields_'.$i.'_category', true) ){
					//$category = get_post_meta($post->ID, 'free_product_fields_'.$i.'_category', true);
					$product_min_quantity = get_post_meta($post->ID, 'free_product_fields_'.$i.'_product_min_count', true);
					$replay = get_post_meta($post->ID, 'free_product_fields_'.$i.'_replay', true);					
					$categoryes_data[]=[
						'category_id' => $category,
						'product_min_quantity' => $product_min_quantity,
						'replay' => $replay,						
					];
					$i++;
				}
				$stocks[]=[
					'ID' => $post->ID,
					'post_title' => $post->post_title,
					'post_status' => $post->post_status,
					'post_date' => $post->post_date,
					'stock_data'=>[
						'code' => $code,
						'type' => $stock_type,
						'sklad' => $sklad,						
						'categoryes_data' => $categoryes_data,
					]
				];
			break;
		}
	}

	return $stocks;
}

/*API получение всех акций */
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/stocks', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'get_all_active_stocks'
	) );
});

/* Отключаем купоны, если есть активная акция */
add_filter( 'woocommerce_coupons_enabled', 'truemisha_coupon_field_on_checkout' );
 function truemisha_coupon_field_on_checkout( $enabled ) {
	//echo '___'.WC()->session->get('active_promo');
	if( !is_admin() && ! defined( 'DOING_AJAX' ) )
	{  
		if( WC()->session->get('active_promo') ) {
			$enabled = false; // купоны отключены
			WC()->cart->remove_coupons( );//Удалим всё применённые купоны
		}
	}
	
	return $enabled;
}

/* обработка акций */
add_action('woocommerce_cart_calculate_fees' , 'custom_item_discount', 10, 1);
function custom_item_discount( $cart_object  ){
	/* АКЦИИ */

	/* Получим настройки взаимодействия купонов и акций*/
	$promo_summing = get_field('promo_summing', 'option');
	$coupons_summing = get_field('coupons_summing', 'option');
	$coupons_and_promo_summing = get_field('coupons_and_promo_summing', 'option');


	WC()->session->set('active_promo', false);
	/* Собираем данные для оценки */
	$data_for_stocks = [];
	foreach( $cart_object->get_cart_contents() as $cart_item ){
		// echo '<pre>';
		// print_r($cart_item);
		$product_cats = get_the_terms( $cart_item['product_id'], 'product_cat' );
		//print_r($product_cats);
		foreach($product_cats as $pc){
			// $data_for_stocks['categories'][$pc->term_id][]=[
			// 	'product_id' => $cart_item['product_id'],
			// 	'price' => $cart_item['data']->price,
			// 	'quantity' => $cart_item['quantity'],				
			// ];
			if(!$data_for_stocks['categories_min_price'][$pc->term_id] || ($data_for_stocks['categories_min_price'][$pc->term_id] > $cart_item['data']->price))
				$data_for_stocks['categories_min_price'][$pc->term_id] = $cart_item['data']->price;

			$data_for_stocks['categories_quantity'][$pc->term_id]+= $cart_item['quantity'];
		}

	}

	/* Перебираем акции смотрим, выполняются ли условия */
	$stocks = get_all_active_stocks();
	//print_r($stocks);
	$fee_added = false;

	/* Проверим привязку акции к складу */
	foreach($stocks as $stock){
		if(!empty( $stock['stock_data']['sklad'] )){
			$StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; // Id склада из куки
			if( !in_array($StockId, $stock['stock_data']['sklad']) ){
				continue;
			}
		}

		switch($stock['stock_data']['type']){
			case 'one_category_product_free':
				$make_stock = false;
				$min_price = 0;
				foreach($stock['stock_data']['categoryes_data'] as $single_stock_data){
					$category_quantity = $data_for_stocks['categories_quantity'][$single_stock_data['category_id']];

					if(!empty($category_quantity) && $category_quantity >= $single_stock_data['product_min_quantity']){
						$make_stock = true;
						$min_price = $data_for_stocks['categories_min_price'][$single_stock_data['category_id']];
						
						if($make_stock && $min_price){ 
						
							if($single_stock_data['replay']){
								$stock_price = ($min_price*-1)*floor( $category_quantity / $single_stock_data['product_min_quantity'] );
							}else
								$stock_price = $min_price*-1;

							if( !$fee_added || ($fee_added &&  $promo_summing!=='false') ){
								$cart_object->add_fee( 'Акция &laquo;'.$stock['post_title'].'&raquo;', $stock_price, true, 'standard' );
								WC()->session->set('active_promo', true);
								$fee_added = true;
							}
						}
					}
				}
			break;
		}
	}
}

/*API получение всех доступных акций для переданной корзины*/
// add_action('rest_api_init', function () {
	 
// 	register_rest_route('wc/v3/stocks', '/autoapply', array(  
// 		'methods' => 'POST',
// 		'callback' => 'get_stocks_by_cart',
// 	));
// });

// /* Получаем корзину по API и отдаём автокупоны, которые можно к ней применить */
// function get_stocks_by_cart( WP_REST_Request $request  ){

// 	$couponsList = [];
// 	$parameters = $request->get_params();

// 	$cart_total = $parameters['cart_total'];
// 	$delivery_type  = $parameters['delivery_type'] ? $parameters['delivery_type'] : '';
// 	$cart_items = json_decode($parameters['cart_items'], true);

// 	if(!$cart_total || empty($cart_items)){
// 		return json_encode([]);
// 	}

// 	$items_count = 0;
// 	$itemsList = [];
// 	$fake_cart = [];

// 	/* Проходим по товарам считаем основные параметры*/
// 	foreach($cart_items as $product){
// 		$product_id = $product['product']['id'];
// 		$quantity = $product['quantity'];
// 		$variation_id = $product['variation'] ? $product['variation'] : 0;
		
// 		if(!$product_id || !$quantity) continue;

// 		$items_count+=$quantity;
// 		$itemsList[]=$product_id;
// 		$fake_cart[]=[
// 			'product_id' => $product_id,
// 			'quantity' => $quantity,
// 		];
// 		//print_r($product);
// 	}

// 	/* Перебираем купоны */
// 	$coupon_codes = array();
// 	$coupons = get_all_coupons();
	
// 	$mystring = 'abc';
// 	$find_code   = 'auto_';
// 	$pos = strpos($mystring, $find_code);
// 	foreach($coupons as $item){
// 		//print_r($item);
// 		$coupon_code = $item->post_title;
// 		$coupon_id = $item->ID;
// 		$is_auto_on = get_post_meta($coupon_id, 'coupon-autoapply', true);
// 		$type_auto_coupon = get_post_meta($coupon_id, 'coupon-type-cond', true);
// 		$type_coupon_origin = get_post_meta($coupon_id, 'discount_type', true);

// 		if($is_auto_on[0] == 'yes'){
// 			//array_push( $coupon_codes, $coupon_code ); //если у купона включено Автокупон - Да, значит обрабатываем его
// 			/* ТИП 1  (дублируется c condition_cart_sum() )*/
// 			if($type_auto_coupon == 'cartsum'){
// 				$cart_subtotal = $cart_total; //WC()->cart->subtotal; //debug_to_file('cart subtotal: '.$cart_subtotal);
// 				$coupon_cart_sum = intval(get_post_meta( $coupon_id, 'coupon-cond-cart-sum', true));
// 				if($cart_subtotal >= $coupon_cart_sum){
// 					array_push( $coupon_codes, $coupon_id );  //Добавляем купон
// 				}
// 			}
// 			/* ТИП 2  (дублируется c condition_cart_prod() )*/  
// 			elseif($type_auto_coupon == 'prodcombo'){

// 				$coupon_prod_list = array();
// 				for($i = 0; $i < 10; $i++){
// 					$coupon_meta_key = 'coupon-products-list_'.$i.'_coupon-products';
// 					$temp_prod = get_post_meta($coupon_id, $coupon_meta_key, true); //debug_to_file($temp_prod);
// 					$temp_prod_qty = get_post_meta($coupon_id, 'coupon-products-list_'.$i.'_coupon-products-qty', true);
// 					$tmp_arr = [$temp_prod => $temp_prod_qty];
// 					if($temp_prod != '') array_push($coupon_prod_list, $tmp_arr);
// 				}
				
// 				//print_r( $fake_cart );
// 				foreach ( $fake_cart as $cart_item ){ //проход по товарам корзины
// 					foreach($coupon_prod_list as $item_prod){ //проход по массиву товар => количество
// 						if ( array_key_exists( $cart_item['product_id'], $item_prod ) ){
// 							$coupon_prod_qty = $item_prod[$cart_item['product_id']]; //кол-во товара, заданое в условии купона
// 							//debug_to_file('cart qty: '.$cart_item['quantity']);
// 							if(intval($coupon_prod_qty) <= $cart_item['quantity']){
// 								//return false;
// 								array_push( $coupon_codes, $coupon_id );  //Добавляем купон
// 							}
								
// 								//debug_to_file($cart_item);
// 						}
// 						//else return false;
// 					}
// 				}
// 				//WC()->cart->apply_coupon( $coupon_code );
// 				//return true;
// 			}
// 			/* ТИП 3  (дублируется c condition_delivery() )*/
// 			elseif($type_auto_coupon == 'delivery') {
// 				if($delivery_type == 'local_pickup'){
// 					array_push( $coupon_codes, $coupon_id );
// 				}
			
// 			}
// 			else{
// 				/* БЕЗ ТИПА */
// 				if($type_coupon_origin=='free_gift'){
// 					$coupon_product_ids = get_post_meta($coupon_id, 'product_ids', true);
// 					$coupon_product_ids = explode(',', $coupon_product_ids);
// 					if(empty($coupon_product_ids)) continue;

// 					$coupon_add = true;
// 					/* Проверяем наличие всех товаров купона в переданной корзине */
// 					foreach ( $coupon_product_ids as $coupon_product ){
// 						if(!in_array($coupon_product, $itemsList))
// 							$coupon_add = false;
// 					}
// 					if($coupon_add)
// 						array_push( $coupon_codes, $coupon_id );  //Добавляем купон
// 				}
// 				//echo 'type_auto_coupon___'.$type_auto_coupon.'__'.$coupon_id;

// 			}
// 		}
// 		//debug_to_file($is_auto_on);
// 	}



// 	// echo 'coupon_codes';
// 	// print_r($coupon_codes);
// 	foreach($coupon_codes as $coupon_id){
// 		foreach($coupons  as &$coupon){
// 			if( $coupon->ID == $coupon_id){
// 				//print_r($woocommerce->get('coupons'));
// 				$curl = curl_init( 'https://testo.dolinger-app.ru/wp-json/wc/v3/coupons/'.$coupon_id.'?consumer_key=ck_8e9043f849e95e6d003c3cc2474fc22b2ed01eec&consumer_secret=cs_74c746f821c405606c0950997a33b194ffc06876' );
// 				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// 				// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 				 curl_setopt($curl, CURLOPT_HEADER, false);
// 				 curl_setopt($curl, CURLOPT_HTTPHEADER, [
// 					'Content-Type: application/json',
// 				]);
// 				$response = curl_exec( $curl );
// 				curl_close( $curl );
// 				$couponsList[] = json_decode($response, true);
// 				//print_r($response);
// 			}
// 		}
// 	}


// 	//debug_to_file(date("D M j G:i:s T Y").'--_______--'.print_r( $parameters, true));
// 	return $couponsList;
// }
