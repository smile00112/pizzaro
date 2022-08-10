<?php

add_filter('woocommerce_checkout_fields','remove_checkout_fields');
function remove_checkout_fields($fields){
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    //unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_postcode']);
return $fields;
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
	 $fields['billing']['billing_address_2']['type'] = 'hidden';
     $fields['billing']['billing_address_2']['label'] = '';
     $fields['billing']['billing_address_1']['type'] = 'hidden';
     $fields['billing']['billing_address_1']['label'] = '';
     $fields['billing']['billing_city']['type'] = 'hidden';
     $fields['billing']['billing_city']['label'] = '';
     $fields['billing']['billing_country']['type'] = 'hidden';
     $fields['billing']['billing_country']['label'] = '';

     $fields['shipping']['shipping_address_1']['type'] = 'hidden';
     $fields['shipping']['shipping_address_1']['label'] = '';
     $fields['shipping']['shipping_city']['type'] = 'hidden';
     $fields['shipping']['shipping_city']['label'] = '';
     $fields['shipping']['shipping_country']['type'] = 'hidden';
     $fields['shipping']['shipping_country']['label'] = '';
     return $fields;
}


add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

function my_custom_checkout_field( $checkout ) {

    get_template_part( 'select-address/select-address' );

}

?>
