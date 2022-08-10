<?php

/// добавляем информацию об искюченных ингредиентах в сессию WC
function update_meta_excluded_ingridients($product_id) {
  if (!empty($_POST['excluded'])) {
    $variation_id = !empty($_POST['variation_id']) ? $_POST['variation_id'] : 0;
    $excluded_ingridients = WC()->session->get('excluded_ingridients', []);
    $excluded_ingridients[$product_id . '_' . $variation_id] = $_POST['excluded'];
    WC()->session->set('excluded_ingridients', $excluded_ingridients);
  }
}
add_action('woocommerce_ajax_added_to_cart', 'update_meta_excluded_ingridients');
///////////////////////

/// добавляем метаданные об исключенных ингредиентах к заказу
function add_meta_excluded_ingridients($order, $data) {
  $excluded_ingridients = WC()->session->get('excluded_ingridients', []);
  if (!empty($excluded_ingridients)) {
    $order->add_meta_data('excluded_ingridients', json_encode($excluded_ingridients));

    $items = $order->get_items();

    foreach ($items as $item) {
      $variation_id = $item->get_variation_id() ?: 0;
      if (isset($excluded_ingridients[$item->get_product_id() . '_' . $variation_id])) {
        $item->add_meta_data( 'excluded_ingridients', $excluded_ingridients[$item->get_product_id() . '_' . $variation_id] );
      }
    }

    WC()->session->set('excluded_ingridients', []);
  }
}
add_action('woocommerce_checkout_create_order', 'add_meta_excluded_ingridients', 10, 2);
//////////////////////

/// отображение поля с иксключенными ингредиентами в заказе
function filter_wc_order_item_excluded_ingridients( $display_key, $meta, $item ) {

    if( $meta->key === 'excluded_ingridients' )
        $display_key = __("Исключить ингредиенты", "woocommerce" );

    return $display_key;    
}
add_filter('woocommerce_order_item_display_meta_key', 'filter_wc_order_item_excluded_ingridients', 20, 3 );


function change_order_item_excluded_ingridients_meta_value( $value, $meta, $item ) {

    if( $meta->key === 'excluded_ingridients' ) {
        $display_value = wp_unslash( $value );
        return $display_value;
    }

    return $value;
}
add_filter( 'woocommerce_order_item_display_meta_value', 'change_order_item_excluded_ingridients_meta_value', 20, 3 );
////////////////////


/// добавляем информацию об исключенных ингредиентах в корзину
function filter_woocommerce_cart_item_product( $cart_item_data, $cart_item, $cart_item_key ) {
    $excluded_ingridients = WC()->session->get('excluded_ingridients', []);
    $variation_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
    if (!empty($excluded_ingridients) && in_array($cart_item['product_id'] . '_' . $variation_id, array_keys($excluded_ingridients))) {
      $cart_item_data->attributes['excluded_ingridients'] = $excluded_ingridients[$cart_item['product_id'] . '_' . $variation_id];
    }

    return $cart_item_data; 
}; 
add_filter( 'woocommerce_cart_item_product', 'filter_woocommerce_cart_item_product', 10, 3 ); 


function get_item_data_excluded_ingridients( $item_data, $cart_item ){
  $excluded_ingridients = WC()->session->get('excluded_ingridients', []);
  $variation_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
  if (!empty($excluded_ingridients) && in_array($cart_item['product_id'] . '_' . $variation_id, array_keys($excluded_ingridients))) {
    $item_data['excluded_ingridients']['key'] = __('Исключить ингредиенты');
    $item_data['excluded_ingridients']['display'] = wp_unslash($excluded_ingridients[$cart_item['product_id'] . '_' . $variation_id]);
  }
  return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'get_item_data_excluded_ingridients', 10, 2 );
////////////////////


/// удаляем информацию об исключенных ингредиентах, при удалении товара из корзины
function after_remove_product_from_cart($removed_cart_item_key, $cart) {
  $product_id = $cart->cart_contents[$removed_cart_item_key]['product_id'];
  $variation_id = !empty($cart->cart_contents[$removed_cart_item_key]['variation_id']) ? $cart->cart_contents[$removed_cart_item_key]['variation_id'] : 0;
  $excluded_ingridients = WC()->session->get('excluded_ingridients', []);
  if (!empty($excluded_ingridients) && in_array($product_id . '_' . $variation_id, array_keys($excluded_ingridients))) {
    unset($excluded_ingridients[$product_id . '_' . $variation_id]);
    WC()->session->set('excluded_ingridients', $excluded_ingridients);
  }
}
add_action( 'woocommerce_remove_cart_item', 'after_remove_product_from_cart', 10, 2 );
////////////////////

?>