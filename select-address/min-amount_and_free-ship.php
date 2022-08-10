<?php

add_action( 'woocommerce_check_cart_items', 'listen_cart_items' );

function listen_cart_items() {
    required_min_cart_subtotal_amount();
    free_ship_subtotal_amount();
}

function required_min_cart_subtotal_amount() {

    $cart_subtotal = WC()->cart->subtotal;
    $minimum_required_amount = WC_Admin_Settings::get_option( 'woocommerce_store_min_amount' );


    // Add an error notice is cart total is less than the minimum required
    if( $cart_subtotal < $minimum_required_amount  ) {
        // Display an error message
        wc_add_notice( '<strong>' . sprintf( __("Минимльная сумма заказа равна %s"), wc_price($minimum_required_amount) ) . '<strong>', 'error' );
        add_action( 'woocommerce_proceed_to_checkout', 'disable_checkout_button_no_shipping', 1 );

        if (is_checkout()) {
            wp_redirect( home_url() );
            exit;
        }
    }
}

function free_ship_subtotal_amount() {

    $minimum_free_shipping_amount = get_free_shipping_minimum();;

    // Total (before taxes and shipping charges)
    $cart_subtotal = WC()->cart->subtotal;

    // Add an error notice is cart total is less than the minimum required
    if( $cart_subtotal < $minimum_free_shipping_amount  ) {
        wc_add_notice( sprintf( __( 'Бесплатная доставка доступна при покупке свыше %s' ), wc_price( $minimum_free_shipping_amount ) ), 'notice' );
    }
}

function get_free_shipping_minimum($zone_name = 'Россия') {
  if ( ! isset( $zone_name ) ) return null;

  $result = null;
  $zone = null;

  $zones = WC_Shipping_Zones::get_zones();
  foreach ( $zones as $z ) {
    if ( $z['zone_name'] == $zone_name ) {
      $zone = $z;
    }
  }

  if ( $zone ) {
    $shipping_methods_nl = $zone['shipping_methods'];
    $free_shipping_method = null;
    foreach ( $shipping_methods_nl as $method ) {
      if ( $method->id == 'free_shipping' ) {
        $free_shipping_method = $method;
        break;
      }
    }

    if ( $free_shipping_method ) {
      $result = $free_shipping_method->min_amount;
    }
  }

  return $result;
}

function disable_checkout_button_no_shipping() {
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
        echo '<a href="#" class="checkout-button button alt wc-forward" style="pointer-events: none; opacity: 0.5;">'. __( 'Place order', 'woocommerce' ) .'</a>';
}

function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
  $new_rates = [];

  /* смотрим время работы доставки и самовывоза */
  $pickup_enable =  check_delivery_status('pickup');
  $devivery_enable = check_delivery_status('delivery');
	foreach ( $rates as $rate_id => $rate ) {
    
		if ( 'local_pickup' === $rate->method_id ) {
      if(!$pickup_enable)
        unset($rates[$rate_id]);
    }elseif ( 'flat_rate' === $rate->method_id ){
      if(!$devivery_enable)
        unset($rates[$rate_id]);
    } 
  }


  if($devivery_enable)
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}



  
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 105 );


add_filter('woocommerce_general_settings', 'general_settings_shop_phone');
function general_settings_shop_phone($settings) {
    $key = 0;

    foreach( $settings as $values ){
        $new_settings[$key] = $values;
        $key++;

        // Inserting array just after the post code in "Store Address" section
        if($values['id'] == 'woocommerce_calc_discounts_sequentially'){
            $new_settings[$key] = array(
                'title'    => __('Минимальная сумма заказа'),
                'desc'     => __('Минимальная сумма при котором возможно оформить заказ'),
                'id'       => 'woocommerce_store_min_amount', // <= The field ID (important)
                'default'  => '',
                'type'     => 'text',
                'desc_tip' => true, // or false
            );
            $key++;
        }
    }
    return $new_settings;
}

?>
