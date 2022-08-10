<?php
/*
* Template Name: Настройки заказа
*/

function get_flat_rate() {
    $delivery_zones = WC_Shipping_Zones::get_zones(); 

    $min = false;

    foreach ((array) $delivery_zones as $key => $the_zone) {
        foreach ($the_zone['shipping_methods'] as $value) {
            if ($value->id == 'flat_rate') {
                $min = $value->cost;
            }
        }
    }

    return (int)$min;
}

$store_min_amount = get_option('woocommerce_store_min_amount');
$free_shipping_min_amount = get_free_shipping_minimum();

$settings = array(
    'min_amount'        => (int)$store_min_amount,
    'flat_cost'         => get_flat_rate(),
    'free_shipping_min' => (int)$free_shipping_min_amount
);

header('Content-Type: application/json');
echo json_encode($settings);

exit();
