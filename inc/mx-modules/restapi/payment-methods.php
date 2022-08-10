<?php

////api методы оплаты
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/payment_methods', array(
		'methods'             => 'GET',
		'callback'            => 'get_payment_meth'
	));
});
function get_payment_meth() {

	$cache = get_cache('cache_get_payment_meth');
	if(empty($cache)){
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		$available_payment_gateways = [];

		$WC_Shipping = new WC_Shipping();
		$shipping_methods = $WC_Shipping->get_shipping_methods();

		function find_available_shipping_for_gateway($shipping_methods, $payment_gateway_id) {
			$available_shipping = [];

			foreach ($shipping_methods as $shipping_method) {
				$available_payment_methods = get_option('custom_shipping_' . $shipping_method->id);
				if (in_array($payment_gateway_id, $available_payment_methods)) {
					$available_shipping[] = $shipping_method->id;
				}
			}

			return $available_shipping;
		}

		foreach ($payment_gateways as $payment_gateway_id => $payment_gateway) {
			if ($payment_gateway->enabled == "yes") {
				$available_payment_gateways[] = [
					'id' => $payment_gateway_id,
					'title' => $payment_gateway->title,
					'metod_title' => $payment_gateway->method_title,
					'description' => $payment_gateway->description,
					'enabled'	=> true,
					'shipping_methods' => find_available_shipping_for_gateway($shipping_methods, $payment_gateway_id)
				];
			}
		}
		$cache = set_cache('cache_get_payment_meth', $available_payment_gateways);
	}

	return $cache;
}

function get_payment_meth_old(WP_REST_Request $request ){
	$gateways = WC()->payment_gateways->get_available_payment_gateways();
	$enabled_gateways = [];

	if( $gateways ) {
		foreach( $gateways as $gateway ) {
			$tmp_arr = [];
			$tmp_arr['id'] = $gateway->id;
			$tmp_arr['title'] = $gateway->title;
			$tmp_arr['metod_title'] = $gateway->method_title;
			$tmp_arr['description'] = $gateway->description;
			if($gateway->enabled == 'yes') $tmp_arr['enabled'] = true;
			else $tmp_arr['enabled'] = false;
			//$tmp_arr['enabled'] = $gateway->enabled;
			array_push($enabled_gateways, $tmp_arr);
		}
	}

	return array_values($enabled_gateways);
}