<?php

////api методы доставки
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/shipping_methods', array(
		'methods'             => 'GET',
		'callback'            => 'get_shipping_meth'
	));
});


function get_shipping_meth() {

	$cache = get_cache('cache_get_shipping_meth');
	if(empty($cache)){
		global $woocommerce;	

		$active_methods   = array();
		$values = array ('country' => 'RU',
						'amount'  => 0);
						
		WC()->frontend_includes();

		WC()->session = new WC_Session_Handler();
		WC()->session->init();
		WC()->customer = new WC_Customer( 1, true );
		WC()->cart = new WC_Cart();


		WC()->cart->add_to_cart(89087);

		WC()->shipping->calculate_shipping(get_shipping_packages($values));
		$shipping_methods = WC()->shipping->packages;
		
		
		//точки самовывоза
		$pickup_locations = 0;
		$pick_locationsposts = get_posts( array(
			'numberposts' => 10,
			'fields' => 'ids',
			'post_type'   => 'pickuppoint'
		) );
		$pick_locations = array();
		foreach( $pick_locationsposts as $post ){
			setup_postdata($post);
			$slug = get_post_field( 'post_title', $post );
			$tmp = array();
			$tmp['name'] = $slug;
			$tmp['warehouse_id'] = intval(get_field( 'pickup_sklad_id', $post ));
			$pick_locations[] = $tmp;   
		}
		wp_reset_postdata(); // сброс
		//////
		
		$active_methods = [];

		foreach ($shipping_methods[0]['rates'] as $id => $shipping_method) { //echo $shipping_method->label.' | ';
			if($shipping_method->method_id == 'local_pickup') { 
				$selectopts = $pick_locations; 
			} else {
				$selectopts = false;
			}
			$active_methods[] = array(  'id'        => $shipping_method->method_id,
										//'type'      => $shipping_method->method_id,
										//'provider'  => $shipping_method->method_id,
										'name'      => $shipping_method->label,
										"pickup_locations" => $selectopts,
										//'price'     => number_format($shipping_method->cost, 2, '.', '')
										);
		}
		$cache = set_cache('cache_get_shipping_meth', $active_methods);
	}

	

	return $cache;
}


function get_shipping_packages($value) {

    // Packages array for storing 'carts'
    $packages = array();
    $packages[0]['contents']                = WC()->cart->cart_contents;
    $packages[0]['contents_cost']           = $value['amount'];
    $packages[0]['applied_coupons']         = WC()->session->applied_coupon;
    $packages[0]['destination']['country']  = $value['country'];
    $packages[0]['destination']['state']    = '';
    $packages[0]['destination']['postcode'] = '';
    $packages[0]['destination']['city']     = '';
    $packages[0]['destination']['address']  = '';
    $packages[0]['destination']['address_2']= '';


    return apply_filters('woocommerce_cart_shipping_packages', $packages);
}