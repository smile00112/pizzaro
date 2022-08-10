<?php
//add_action( 'woocommerce_before_cart', 'check_shipping_coupons' ); //на странице Корзина
add_filter( 'woocommerce_review_order_before_shipping', 'check_shipping_coupons' ); //сбрасываем автокупон доставки при обновлении Тип доставки

/* При смене метода доставки удаляем купоны*/
function check_shipping_coupons(){
	$coupons = get_all_coupons();
	foreach($coupons as $item){
		$coupon_code = $item->post_title;
		$coupon_id = $item->ID;
		//$type_auto_coupon = get_post_meta($coupon_id, 'coupon-type-cond', true);
        $pickup_coupon = get_post_meta($coupon_id, 'pickup_coupon', true);
        $is_auto_on = get_post_meta($coupon_id, 'coupon-autoapply', true);
        if($is_auto_on[0] != 'yes')
			if($pickup_coupon[0] == 'yes'){
                $shipping_ids = wc_get_chosen_shipping_method_ids();
                if($shipping_ids[0] != 'local_pickup'){
				    WC()->cart->remove_coupon($coupon_code); 
                }
			}

		}
}

/* сразу после проверки существования промокода, проверяем соответствует ли он условиям доставки */
add_filter( 'woocommerce_coupon_is_valid', 'filter_function_name_9896', 10, 3 );
function filter_function_name_9896( $true, $coupon, $that ){
	// filter...
	//if($coupon->get_code() == 'sam20'){
		// debug_to_file('woocommerce_coupon_is_valid-->');
		// debug_to_file($true);
		// debug_to_file('|');
		// debug_to_file($coupon->get_meta('coupon-deliv-type'));
		// debug_to_file('|');
		// debug_to_file($coupon->get_meta('coupon-type-cond'));
		// debug_to_file('|');
		// debug_to_file($coupon);
	//}

	if($true){
        $shipping_ids = [];
		if( !empty( WC()->session ) ){
            $shipping_ids = wc_get_chosen_shipping_method_ids();
		
			/*Если rest api запрос, смотрим тип доставки*/
			$json = file_get_contents('php://input');
			$body = json_decode($json, TRUE);
			$restaip_delivery_type = !empty($body['delivery_type']) ? $body['delivery_type'] : '';
			

            $pickup_coupon = $coupon->get_meta('pickup_coupon');
            $is_auto_on = $coupon->get_meta('coupon-autoapply');

            /* Если тип условия для купона - доставка */
            if(($is_auto_on != 'yes') && (!empty($pickup_coupon)) /*&& ( $diskount_type == 'delivery' && $delivery_taxonomy )*/){
                $delivery_type = get_terms('product_shipping_class', ['include'=> [$delivery_taxonomy]]);
                //debug_to_file('|$delivery_type|');
                // global $wpdb;
                // $res = $wpdb->get_results( "
                //     SELECT * FROM {$wpdb->prefix}terms as t
                //     INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON t.term_id = tt.term_id
                //     WHERE tt.taxonomy LIKE 'product_shipping_class' AND tt.term_id = '".$delivery_taxonomy."' 
                // " );
                // $shipping_name = $res[0]->name;

                /* проверяем */
                if($shipping_ids[0] == 'local_pickup' || $restaip_delivery_type == 'local_pickup'){
                    if($pickup_coupon[0] != 'yes'){
                        return false;
                    }
                    //WC()->cart->apply_coupon( $coupon_code );
                }else
                if($shipping_ids[0] != 'local_pickup'|| $restaip_delivery_type != 'local_pickup'){
                    if($pickup_coupon[0] == 'yes'){
                        return false;
                    }
                    //WC()->cart->apply_coupon( $coupon_code );
                }

            }
		
        }

	}

	return $true;
}