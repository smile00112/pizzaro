<?php

////приложение получает данные о статусе заказа

////получение статуса заказа
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/orderstatusold', array( //регистрация маршрута /(?P<status>\w+)
		'methods' => 'GET',
		'callback' => 'get_order_status_old',
	));
});

function get_order_status_old(WP_REST_Request $request) {
	//$order_id = $request['order_id'];
	$user_id = $request['customer'];
	$limit = $request['limit'];
	//$order = wc_get_order( $order_id );
	//$order_status  = $order->get_status();
	//debug_to_file('order status: '.$order_id.' '.$order_status);

	$args = array(
		'customer_id' => $user_id,
		'limit' => $limit,
	);
	$orders = wc_get_orders($args);

	//debug_to_file($orders);

	$order_id = 0;
	$order_status = false;
	$arr_orders_data = array();
	$tmp_arr = array();

	foreach ($orders as $item) {
		$tmp_arr['order'] = $item->get_id();
		$tmp_arr['status'] = $item->get_status();
		$tmp_date = $item->get_date_created(); 
		$order_date = (array)$tmp_date; 
		$tmp_arr['date'] = $order_date['date'];
	//	$tmp_arr['rated'] = $order_date['rate-order'];
	$tmp_arr['time_deliv'] = $order_date['time_deliv'];
		array_push($arr_orders_data, $tmp_arr);
		
	}

	return $arr_orders_data;
}

//////////запись статуса заказа в файл(альтернативный вариант, может существовать отдельно)
add_action('woocommerce_order_status_changed', 'order_status_file', 10, 3);
function order_status_file($order_id, $status_from, $status_to) {
	//$order = wc_get_order( $order_id );
	//$order_status  = $order->get_status();
	$cont = $order_id . ' ' . $status_to;

	$ctime = time();
	$path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/app_sync/status.json';
	$path_tmp = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/app_sync/tmp/status_' . $ctime . '.json';
	$file_tmp = fopen($path_tmp, 'a');

	//запись во временный файл
	fwrite($file_tmp, $cont . PHP_EOL);
	fclose($file_tmp);

	if (file_exists($path_tmp)) {
		$cont_tmp = file_get_contents($path_tmp);
		$file = fopen($path, 'a');
		fwrite($file, $cont_tmp);
		fclose($file);
	}

	unlink($path_tmp);
}