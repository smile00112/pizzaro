<?php

/////оценка заказов

//отправляем push после выполнения заказа(через заданое время)
function send_push_rate($order_id){
	$is_rate_order = get_post_meta($order_id, 'rate_order', true); //debug_to_file('is rate order: "'.$is_rate_order.'"');
	if($is_rate_order == ''){ //если заказ ещё не оценён, тогда отправляем push с предложением оценить
		$token_auth = get_field('push_fcm_token', 'option');
		
		$order = wc_get_order( $order_id );
		$user_id   = $order->get_user_id();
		$token_device = get_user_meta( $user_id, 'fcm_device_token', true );
		
		$body = 'Пожалуйста, поставьте оценку заказу '.$order_id;
		
		$title = 'Благодарим за заказ!';
		//$title = 'Благодарим за заказ! Пожалуйста, оцените сервис Родная доставка ⭐';

		$notif = ['body'=>$body, 'title'=>$title, 'sound'=>'default'];
		
		$message  = ['notification' => $notif];
		$message += ['priority' => 'high'];
		$message += ['time_to_live' => 3600];
		$message += ['to' => $token_device];
		
		$fields = json_encode ( $message );
		
		//debug_to_file($fields);
		/**/
		$ch = curl_init("https://fcm.googleapis.com/fcm/send");
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: key='.$token_auth
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
		
		$result = curl_exec ( $ch );
		
		if(curl_error($ch)) { debug_to_file(curl_error($ch)); }
		curl_close($ch);
	}
	
}

//endpoint для получения оценки из приложения
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/rateorder', array( 
		'methods' => 'GET',
		'callback' => 'get_order_rate',
	));
});

function get_order_rate(WP_REST_Request $request) {
	$order_id = $request['order'];
	$rate = $request['rate'];
	$text_rate = $request['text'];
	//debug_to_file('order_id: '.$order_id.' rate: '.$rate);
	
	$post_type = get_post_type( $order_id ); //debug_to_file('post type: '.$post_type);
	if($post_type == 'shop_order') {
		update_post_meta($order_id, 'rate_order', $rate);
		update_post_meta($order_id, 'rate_order_text', $text_rate);
	}

	set_user_rate_order($order_id);
}


//вычисление средней оценки конкретного пользователя
function set_user_rate_order($order_id){
	$order = wc_get_order( $order_id );
	$user_id = $order->get_user_id();
	
	$args = array( 'customer_id' => $user_id, 'numberposts' => 1000 );
	$user_orders = wc_get_orders($args); //debug_to_file('count orders: '. count($user_orders));
	$rates_arr = array();
	foreach($user_orders as $order){//debug_to_file($order->id);
		$rate = get_post_meta($order->id, 'rate_order', true);
		array_push($rates_arr, $rate);
	}
	//debug_to_file($rates_arr);
	$rates_arr = array_filter($rates_arr);
	if(count($rates_arr)) {
		$average = array_sum($rates_arr)/count($rates_arr);  //debug_to_file('average: '. $average);
		update_user_meta($user_id, 'user_rate_order', $average);
	}
	
	return $average;
}

//add_action( 'wp_footer', 'test_user_order', 10 );
function test_user_order(){
	set_user_rate_order(29277);
}


//после того, как заказ установлен Выполнен, добавляем одноразовую cron задачу. отправка push с предложением оценить заказ.
add_action( 'woocommerce_order_status_completed', 'push_after_order', 10, 1 );
function push_after_order($order_id) { //debug_to_file('function schedule of complete order: '.$order_id);
	$rate = get_post_meta($order->id, 'rate_order', true);
	if( ! wp_next_scheduled( 'schedule_push_rate' ) && ! $rate ) {
		wp_schedule_single_event( time() + 60*2, 'schedule_push_rate', array($order_id) ); //3600 = 1 час с текущего момента
	}
}
add_action( 'schedule_push_rate', 'send_push_rate', 10, 1 );


//получение средней оценки заказов от конкретного пользователя
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/userrateorder', array( 
		'methods' => 'GET',
		'callback' => 'get_user_order_rate',
	));
});

function get_user_order_rate(WP_REST_Request $request){
	$user_id = $request['user'];
	$user_rate = get_user_meta($user_id, 'user_rate_order', true);
	
	return $user_rate;
}

/* сохраняем поля с отзывом в админке */ 
add_action( 'woocommerce_process_shop_order_meta', 'save_order_customrer_comment' );


add_action('woocommerce_admin_order_data_after_order_details', 'misha_editable_order_meta_general');

function misha_editable_order_meta_general($order) {
	?>

		<br class="clear" />
		<!--<h4>Источник заказа <a href="#" class="edit_address">Редактировать</a></h4>-->
		<?php

	$gift_name = get_post_meta($order->get_id(), 'order_meta_source', true);
	$OS = get_post_meta($order->get_id(), 'os', true);
	$OS_data = get_post_meta($order->get_id(), 'environment_information', true);
	$utm_data = get_user_meta($order->get_user_id(), 'utm_data', true);
	$utm = !empty($utm_data) ? 'utm_source:'.$utm_data['utm_source'].', utm_campaign:'.$utm_data['utm_campaign'] : '';

	if(!$OS)
		$OS=$OS_data['platform'].' '.$OS_data['build_number'];	
	?>
		<div class="address">
			<p><strong>Источник заказа:</strong> <?php echo $gift_name ?></p>
		</div>
		<div class="address"><p><strong>ОС польз.:</strong> <?php echo $OS ?></p></div>
		<div class="address"><p><strong>utm: </strong> <?php echo $utm ?></p></div>
		
		<div class="edit_address"><?php

	woocommerce_wp_text_input(array(
		'id' => 'order_meta_source',
		'label' => 'Источник заказа:',
		'value' => $gift_name,
		'wrapper_class' => 'form-field-wide',
	));

	?></div>

 		<br class="clear" />
		<!--<h4>Код точки доставки <a href="#" class="edit_address">Редактировать</a></h4>-->
		<?php //$deliverypoint1C = get_post_meta( $order->get_id(), 'deliverypoint1C', true ); ?>
		<!--<div class="address">
			<p><strong>Код точки доставки:</strong> <?php //echo $deliverypoint1C ?></p>
		</div>
		<div class="edit_address">-->
			<?php
		/*woocommerce_wp_text_input( array(
		'id' => 'deliverypoint1C',
		'label' => 'Код точки доставки:',
		'value' => $deliverypoint1C,
		'wrapper_class' => 'form-field-wide'
		) );*/
	?>
		<!--</div>-->
	<?php }

add_action('woocommerce_checkout_update_order_meta', 'misha_save_general_details');

function misha_save_general_details($ord_id) {
	$order = wc_get_order($ord_id);
	$meta_source = $order->get_meta('order_meta_source');

	if (empty($meta_source)) {

		update_post_meta($ord_id, 'order_meta_source', wc_clean('siteorder'));
	} else {

	}

	// wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
}


add_action('woocommerce_admin_order_data_after_order_details', 'admin_deliv_time_order');

function admin_deliv_time_order($order) {
	$deliv_time = get_post_meta($order->get_id(), '_shipping_deliv_time', true);
	echo '<div class="deliv_time"><b>Срок доставки:</b> ' . $deliv_time . ' мин</div>';
	//$data = $order->get_data();
	//$order_status = $data['status'];

}

/* добавляем поля с отзывом в админке */
add_action('woocommerce_admin_order_data_after_order_details', 'admin_order_rate_inf');
function admin_order_rate_inf($order){
	$order_rate = get_post_meta($order->get_id(), 'rate_order', true);
	$rate_order_text = get_post_meta($order->get_id(), 'rate_order_text', true); 
	$rate_order_answer_text = get_post_meta($order->get_id(), 'rate_order_answer_text', true); 
	
	?>
	
	<div class="admin-rate-order">

	<?php

	woocommerce_wp_text_input(array(
		'id' => 'rate_order',
		'label' => 'Оценка заказа:',
		'value' => $order_rate,
		'wrapper_class' => '',
	));
	woocommerce_wp_textarea_input(array(
		'id' => 'rate_order_text',
		'label' => 'Коммент к оценке:',
		'value' => $rate_order_text,
		'wrapper_class' => '',
	));
	woocommerce_wp_textarea_input(array(
		'id' => 'rate_order_answer_text',
		'label' => 'Ответ к комменту:',
		'value' => $rate_order_answer_text,
		'wrapper_class' => '',
	));
	
	?>
	
	</div>


<?php
}


/* Сохраняем utm метки в мета юзера или сессию*/
if(!empty($_GET['utm_source'])){

	$keys = array('utm_source', 'utm_campaign',);
	foreach ($keys as $row) {
		if (!empty($_GET[$row])) {
			$value = strval($_GET[$row]);
			$value = stripslashes($value);
			$value = htmlspecialchars_decode($value, ENT_QUOTES);	
			$value = strip_tags($value); 		
			$value = htmlspecialchars($value, ENT_QUOTES);	
			$out[$row] = $value;
		}
	}

	if(empty(is_user_logged_in())){
		session_start();
		$_SESSION['utm_source'] = $out;	
	}else{
		$user_id = get_current_user_id();
		update_user_meta($user_id, 'utm_data', $out);
		session_start();
		$_SESSION['utm_source'] = [];
	}

}
/* Если метки сохранены в сессии и пользователь авторизован, сохраняем их в мета */
if(!empty($_SESSION['utm_source'])){
	if(!empty(is_user_logged_in())){
		$user_id = get_current_user_id();
		update_user_meta($user_id, 'utm_data', $_SESSION['utm_source']);
		session_start();
		$_SESSION['utm_source'] = [];
	}
}
