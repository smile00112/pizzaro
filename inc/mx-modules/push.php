<?php

//получение системных надстроек push
add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/push/(?P<order_id>[\d]+)/(?P<status>[\w]+)', array( //регистрация маршрута /(?P<status>\w+)
		'methods'             => 'GET',
		'callback'            => 'rest_push_app'
	) );
});

function rest_push_app(WP_REST_Request $request){
	$order_id = $request['order_id'];
	$status = $request['status'];
	$result = send_push_app($order_id, $status);
	return $result;
}

//отправка push уведомлений при смене статуса заказа
add_action( 'woocommerce_order_status_kurier', 'notif_push_kurier', 10, 1 );
add_action( 'woocommerce_order_status_wait-stock', 'notif_push_ready', 10, 1 ); 
add_action( 'woocommerce_order_status_pending', 'notif_push_pending', 10, 1 );
add_action( 'woocommerce_order_status_making', 'notif_push_making', 10, 1 );
add_action( 'woocommerce_order_status_processing', 'notif_push_processing', 10, 1 );
add_action( 'woocommerce_order_status_completed', 'notif_push_completed', 10, 1 );
add_action( 'woocommerce_order_status_cancelled', 'notif_push_cancelled', 10, 1 );
add_action( 'woocommerce_order_status_failed', 'notif_push_failed', 10, 1 );
add_action( 'woocommerce_order_status_refunded', 'notif_push_refunded', 10, 1 );

function notif_push_kurier($order_id){
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['kurier_notif'];
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_ready($order_id){
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['waitstock_notif'];
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_pending($order_id){
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['pending_notif'];
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_making($order_id){
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['making_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_processing($order_id){ //debug_to_file('status processing: '.$status);
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['proccessing_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_completed($order_id){ //debug_to_file('status processing: '.$status);
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['completed_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_cancelled($order_id){ //debug_to_file('status processing: '.$status);
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['cancelled_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_failed($order_id){ //debug_to_file('status processing: '.$status);
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['failed_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function notif_push_refunded($order_id){ //debug_to_file('status processing: '.$status);
	$arr_notif = get_field('push_notif_text_group', 'option');
	$status = $arr_notif['refunded_notif']; 
	if(!empty($status)) send_push_app($order_id, $status);
}

function send_push_app($order_id, $status){ //debug_to_file('change order status for: '.$order_id. ' status: '.$status);
	//$token_auth = 'AAAAiWKttNQ:APA91bEfkwD3oo5ZWgbKVd4XopSF7aW7pGeXjWJm6T_cLpwyCPasXnMxBowtakbEJDpjsa0zI-moRfYqGX-s_hiIx7AubL1oJnYmqA7vzpyGcNxAMAWR00By1Lrr-jdO-VdBOtDgI37I';
	$token_auth = get_field('push_fcm_token', 'option');
	
	$order = wc_get_order( $order_id );
	$user_id   = $order->get_user_id();
	$token_device = get_user_meta( $user_id, 'fcm_device_token', true );
	

	//$arr_notif = get_field('push_notif_text_group', 'option');
	
	//if($status == 'kurier') $body = $arr_notif['kurier_notif'];
	//else if($status == 'ready') $body = 'Заказ ожидает выдачи';
	//else $body = 'Изменён статус заказа '.$status;
	$body = $status;
	
	$title = 'Статус заказа';

	$notif = ['body'=>$body, 'title'=>$title, 'sound'=>'default'];
	
	$prior_andr = ['priority' => 'normal'];

	$headers = ['headers' => ['apns-priority' => '5'] ];	
	
	
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
	/**/
	
	upd_push_counter();
	
	//return $result;
	return $get_token;
	//return $fields;
}


function send_push_app_test($user_id, $title = '', $body =''){ //debug_to_file('change order status for: '.$order_id. ' status: '.$status);
	//$token_auth = 'AAAAiWKttNQ:APA91bEfkwD3oo5ZWgbKVd4XopSF7aW7pGeXjWJm6T_cLpwyCPasXnMxBowtakbEJDpjsa0zI-moRfYqGX-s_hiIx7AubL1oJnYmqA7vzpyGcNxAMAWR00By1Lrr-jdO-VdBOtDgI37I';
	$token_auth = get_field('push_fcm_token', 'option');
	//echo 'start';
	$token_device = get_user_meta( $user_id, 'fcm_device_token', true );

	//log_push(['body'=>$body, 'title'=>$title, 'recipient_count'=> 1 , 'tokens'=>serialize([$token_device])]);

	$notif = ['body'=>$body, 'title'=>$title, 'sound'=>'default'];
	
	$prior_andr = ['priority' => 'normal'];

	$headers = ['headers' => ['apns-priority' => '5'] ];	
	
	
	$message  = ['notification' => $notif];
	$message += ['priority' => 'high'];
	$message += ['time_to_live' => 3600];
	$message += ['to' => $token_device];
	

	/*Закрою пока, чтобы пуши о бонусах не захлавляли лог*/
	$type = 'simple';
	$log_id = log_push(['body'=>$body, 'title'=>$title, 'resource_data'=>serialize( [] ), 'type'=>$type, 'recipient_count'=> 1, 'tokens'=>$user_id]);

	if(!empty($log_id) ){
		$message['data']['push_id'] = $log_id;
	}

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
	//print_R($result);
	if(curl_error($ch)) { debug_to_file(curl_error($ch));}
	curl_close($ch);
	/**/
	
	upd_push_counter();
	//echo 'sended';
	//return $result;
	return $get_token;
	//return $fields;
}
//send_push_app();

//установка token пользователю
add_action( 'rest_api_init', function () {
	register_rest_route( 'fcm-push/v1', '/set-token', array( //регистрация маршрута /(?P<status>\w+)
		'methods'             => 'POST',
		'callback'            => 'fcm_setuser_token'
	) );
});

function fcm_setuser_token(WP_REST_Request $request){ debug_to_file('set user token:'.$request['user_id']);
	$user_id = $request['user_id'];
	$device_token = $request['fcm_device_token'];
	//echo $user_id.'|'.$device_token;

	$get_token = get_user_meta( $user_id, 'fcm_device_token', true );
	//echo $get_token;
	if($get_token == ''){ debug_to_file('add user token:'.$device_token); //если такого поля вообще нет
		//echo 'add';
		add_user_meta( $user_id, 'fcm_device_token', $device_token, true );
	}
	else{ debug_to_file('update token:'.$device_token); //echo 'update';
		update_user_meta( $user_id, 'fcm_device_token', $device_token);
	}

	//return $get_token;
}

//удаление token пользователя
add_action( 'rest_api_init', function () {
	register_rest_route( 'fcm-push/v1', '/remove-token', array( //регистрация маршрута /(?P<status>\w+)
		'methods'             => 'POST',
		'callback'            => 'fcm_removeuser_token'
	) );
});

function fcm_removeuser_token(WP_REST_Request $request){  //debug_to_file('remove user token:'.$request['user_id']);
	$user_id = $request['user_id'];
	$get_token = get_user_meta( $user_id, 'fcm_device_token', true );
	//echo 'before remove='.$get_token.'|';
	if($get_token != '0'){ debug_to_file('remove user token done:'.$request['user_id']);
		update_user_meta( $user_id, 'fcm_device_token', '0');
	}
	$get_token_rem = get_user_meta( $user_id, 'fcm_device_token', true );
	
	//return $get_token_rem;
}

//обновление счётчика push
function upd_push_counter(){
	global $wpdb;
	$push_count = get_push_counter();
	$push_count = (int)$push_count + 1;
	$push_count = $push_count.''; //echo 'push_count:'.$push_count;
	$wpdb->query( "UPDATE service_options SET name_value = $push_count WHERE name_key = 'push_count' " ) ;
}
//upd_push_counter();
//получение счётчика push
function get_push_counter(){
	global $wpdb;
	$query = $wpdb->get_results("SELECT * FROM service_options WHERE name_key = 'push_count' " , ARRAY_A);
	$push_count = $query[0]['name_value'];
	return $push_count;
}

//вывод количества отправленых push из бд в админку
function push_counter_admin(){
	$push_count = get_push_counter();
	update_field('send_push_count', $push_count, 'option');
}
push_counter_admin();



add_action( 'rest_api_init', function () { //для теста, можно удалить
	register_rest_route( 'systeminfo/v1', '/notif', array(
		'methods'             => 'GET',
		'callback'            => 'get_notif_text'
	) );
});
function get_notif_text(){ //для теста, можно удалить
	$arr_notif = get_field('push_notif_text_group', 'option');
	echo $arr_notif['kurier_notif'];
	print_r($arr_notif);
	
	//debug_to_file('text_notif:'.$arr_notif);
}



/////////////////////отправка групповых Push

add_action( 'rest_api_init', function () { 
	register_rest_route( 'systeminfo/v1', '/notif_all', array(
		'methods'             => 'GET',
		'callback'            => 'send_push_app_group'
	) );
});

function send_push_app_group(WP_REST_Request $request){ 

	//debug_to_file('send_push_app_group');
	//$token_auth = 'AAAAiWKttNQ:APA91bEfkwD3oo5ZWgbKVd4XopSF7aW7pGeXjWJm6T_cLpwyCPasXnMxBowtakbEJDpjsa0zI-moRfYqGX-s_hiIx7AubL1oJnYmqA7vzpyGcNxAMAWR00By1Lrr-jdO-VdBOtDgI37I';
	$token_auth = get_field('push_fcm_token', 'option');
	$ar_all_token_device = [];
	$arr_user_id = $request['push_user_id'];
	$tokens = implode(",", $arr_user_id);
	//$str_user_id = str_replace(' ', '', $str_user_id);
	//$arr_user_id = explode(",", $str_user_id);

	$send_to_topic = '';

	//if(empty($arr_user_id[0])) debug_to_file('empty');
	if(!empty($arr_user_id[0])){ 
		//debug_to_file(count($arr_user_id)); //если заданы конкретные id
		foreach($arr_user_id as $user_id){
			$user_token = get_user_meta(intval($user_id), 'fcm_device_token', true);
			if($user_token != '' && $user_token != '0') array_push($ar_all_token_device, $user_token);
		}
	}
	else{ //debug_to_file('all users'); //если не задано, берём токены всех пользователей
		$all_users = get_users();
		foreach($all_users as $user_c){
			$user_id_c = $user_c->ID;
			$user_token = get_user_meta($user_id_c, 'fcm_device_token', true);
			if($user_token != '' && $user_token != '0') array_push($ar_all_token_device, $user_token);
		}
		$send_to_topic = '/topics/topic_general';
		//$send_to_topic = '/topics/topic_android';
	}

	if($send_to_topic != '') $token_device = $send_to_topic;
	else $token_device = $ar_all_token_device;

	$body = $status;
	
	$title = $request['push_title'];
	$body = $request['push_text'];

	$add_content = $request['add_content'];
	$content_id = $request['content_id'];

	switch ($add_content) {
		case 'category':
			$content_id = $request['product_cat'];
			break;
		case 'product':
			$content_id = $request['product'];
			break;
		case 'article':
			$content_id = $request['blog'];
			break;			
		case 'promo':
			$content_id = $request['coupon'];
			break;	

		default:
			break;
	}

	$notif = ['title'=>$title, 'body'=>$body];
		
	$message  = ['notification' => $notif];
	if($send_to_topic != '') $message += ['to' => $token_device];
	else $message += ['registration_ids' => $token_device];
	
	$type = 'simple';
	$res_data = [];
	if(!empty($add_content) && !empty($content_id)){
		$res_data = ['data' => [ "push_action" =>  $add_content, "push_extra_data" => $content_id] ];
		$type = 'simple_'.$add_content;
		$message += $res_data;
	}

	$log_id = log_push(['body'=>$body, 'title'=>$title, 'resource_data'=>serialize($res_data), 'type'=>$type, 'recipient_count'=> count($ar_all_token_device), 'tokens'=>$tokens]);

	if(!empty($log_id) ){
		//$message += ['push_id' => $log_id];
		$message['data']['push_id'] = $log_id;
	}


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
	//print_r($result);
	if(curl_error($ch)) { debug_to_file(curl_error($ch)); }
	curl_close($ch);
	/**/
	
	//upd_push_counter();
	
	
	//return 'send all';
	
	$push_title = $request['push_user_id'].' req';
	
	//$param = $request->get_body();
	//debug_to_file('------'.$param);
	
	return count($ar_all_token_device);
}
function send_push_app_event($user_id, $data){ //debug_to_file('change order status for: '.$order_id. ' status: '.$status);
	//echo '____send_push_app_event_____';
	//$token_auth = 'AAAAiWKttNQ:APA91bEfkwD3oo5ZWgbKVd4XopSF7aW7pGeXjWJm6T_cLpwyCPasXnMxBowtakbEJDpjsa0zI-moRfYqGX-s_hiIx7AubL1oJnYmqA7vzpyGcNxAMAWR00By1Lrr-jdO-VdBOtDgI37I';
	$token_auth = get_field('push_fcm_token', 'option');
	
	//$order = wc_get_order( $order_id );
	//$user_id   = $order->get_user_id();
	$token_device = get_user_meta( $user_id, 'fcm_device_token', true );
	
	//log_push(['body'=>$data['body'], 'title'=>$data['title'], 'recipient_count'=> 1, 'tokens'=>serialize([$token_device])]);
	//$arr_notif = get_field('push_notif_text_group', 'option');
	
	//if($status == 'kurier') $body = $arr_notif['kurier_notif'];  	
	//else if($status == 'ready') $body = 'Заказ ожидает выдачи';
	//else $body = 'Изменён статус заказа '.$status;
	//$body = $status;
	
	$title = $data['title'];

	$notif = ['body'=>$data['body'], 'title'=>$data['title'], 'sound'=>'default'];
	
	$prior_andr = ['priority' => 'normal'];

	$headers = ['headers' => ['apns-priority' => '5'] ];	
	
	
	$message  = ['notification' => $notif];
	$message += ['priority' => 'high'];
	$message += ['time_to_live' => 3600];
	$message += ['to' => $token_device];

	// if(!empty($data['add_content']) && !empty($data['content_id'])){
	// 	$message += ['data' => [ "push_action" =>  $data['add_content'], "push_extra_data" => $data['content_id'] ]];
	// }
	$type = 'event';
	$res_data = [];
	if(!empty($data['add_content']) && !empty($data['content_id'])){
		$res_data = ['data' => [ "push_action" =>  $data['add_content'], "push_extra_data" =>  is_array($data['content_id']) ? (string)$data['content_id'][0] : $data['content_id'] ] ];
		$type = 'event_'.$data['add_content'];
		$message += $res_data;
	}

	$log_id = log_push(['body'=>$data['body'], 'title'=>$data['title'], 'resource_data'=>serialize( $res_data ), 'type'=>$type, 'recipient_count'=> 1, 'tokens'=>$user_id]);

	if(!empty($log_id) ){
		//$message += ['push_id' => $log_id];
		$message['data']['push_id'] = $log_id;
	}


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
	/**/
	
	upd_push_counter();
	
	//return $result;
	return $get_token;
	//return $fields;
}

function log_push($data){
	global $wpdb;
	
	if(empty(trim($data['tokens']))){
		$data['tokens'] = 'all';
	}
	$query = $wpdb->prepare( "INSERT INTO push_logs SET title=%s, text=%s, resource_data=%s,  type='".$data['type']."', tokens='".$data['tokens']."', date_added=NOW(), recipient_count='".$data['recipient_count']."', open_count=0 ", $data['title'], $data['body'], $data['resource_data'] );
	$rows_affected = $wpdb->query($query);

	return $wpdb->insert_id;
}

add_action( 'rest_api_init', function () { 
	register_rest_route( 'systeminfo/v1', '/notif_all_t', array(
		'methods'             => 'GET',
		'callback'            => 'send_push_app_group'
	) );
});
add_action( 'rest_api_init', function () { 
	register_rest_route( 'systeminfo/v1', '/push_open', array(
		'methods'             => 'POST',
		'callback'            => 'update_push_open' 
	) );
});

function update_push_open(WP_REST_Request $request){
	global $wpdb;
	debug_to_file(print_r($request, true));
	if(!$request['push_id']) return false;

	$wpdb->query( "UPDATE push_logs SET open_count=open_count+1 WHERE id=".intval($request['push_id'])." " );

	return false;
	//print_r($request['push_id']); 
}

add_action('wp_ajax_get_push_logs', 'get_push_logs');
add_action('wp_ajax_nopriv_get_push_logs', 'get_push_logs');
function get_push_logs() {
	global $wpdb;
	$_limit = 50;

	$offset = intval($_POST['offset']);
	$type = $_POST['type'];

	if(isset($_POST['offset']) && $type){

		$sql ="SELECT * FROM push_logs logs 
		WHERE type LIKE '%$type%' ORDER BY id DESC LIMIT $_limit OFFSET $offset ";
		$Data = $wpdb->get_results( $sql , ARRAY_A );
		$table = '';
		if(count($Data)){
			//$data = [];
			foreach($Data as &$d){
				$d['resource_data'] = unserialize($d['resource_data']);
				$d['open_count'] = ($d['open_count'] <= $d['recipient_count'] ) ? $d['open_count'] : $d['recipient_count'];
				$d['open_proc'] = (!empty($d['open_count'])) ? ( $d['open_count'] / $d['recipient_count'] * 100 ) . '%' : '0%';
				$push_content = ($d['resource_data']['data']['push_action'] && $d['resource_data']['data']['push_extra_data']) ? $d['resource_data']['data']['push_action'].'/'.$d['resource_data']['data']['push_extra_data'] : '';
				$table.='
					<tr id="post-29472" class="iedit author-self level-0 post-29472 type-promo status-publish hentry">
						<td class="title column-title has-row-actions column-primary page-title" data-colname="Заголовок">
							'.$d['title'].'
						</td>
						<td class="column-resent" data-colname="Текст" title="'.$d['text'].'" class="text-column">'.$d['text'].'</td>
						<td class="column-resent" data-colname="Тип ресурса">'.$push_content.'</td>

						<td class="date column-date" data-colname="Дата">'.$d['date_added'].'</td>
						<td class="column-resent" data-colname="Отправлено">'.$d['recipient_count'].'</span></td>
						<td class="column-resent" data-colname="Получено">'.$d['open_count'].'</span></td>
						<td class="column-resent" data-colname="Просмотрено">'.$d['open_proc'].'</td>
						<td class="column-resent" data-colname="Повторить"><span class="push-resent dashicons dashicons-image-rotate" data-push_resent data-push_id="'.$d['id'].'"></span></td>
					</tr>
				';
			}
			//print_R($Data);
			echo json_encode(['data' => $Data, 'table'=>$table, 'offset'=>($offset+$_limit)], JSON_UNESCAPED_UNICODE); 
		}else
			echo json_encode([]);
	}else
		echo json_encode([]);

	//print_r($_POST);
	wp_die(

	); // Alway at the end (to avoid server error 500)
}

add_action('wp_ajax_resent_push', 'resent_push');
add_action('wp_ajax_nopriv_resent_push', 'resent_push');
function resent_push() {
	global $wpdb;	

	$push_id = intval($_POST['push_id']);
	

	if($push_id){
		$sql ="SELECT * FROM push_logs logs 
		WHERE id = $push_id ";
		$Data = $wpdb->get_results( $sql , ARRAY_A );
		$table = '';
		if(count($Data)){
			//$data = [];
			foreach($Data as &$d){
				$d['resource_data'] = unserialize($d['resource_data']);
				//	$d['tokens'] = unserialize($d['tokens']);
				$request   = new WP_REST_Request( 'GET', 'your route here' );
				//$request->set_header( 'content-type', 'application/json' );
				// $request->set_body( [
				// 	'push_user_id' => 111111,
				// 	'push_title' => 22222,
				// 	'push_text' => 33333,
				// ] );
				// $add_content = $request['add_content'];
				// $content_id = $request['content_id'];

				$request->set_query_params(array(
					'push_user_id' => $d['tokens'],
					'push_title' => $d['title'],
					'push_text' => $d['text'],
					'add_content' => $d['resource_data']['data']['push_action'],
					'content_id' => $d['resource_data']['data']['push_extra_data'],
				  ));

				//echo 'send_push_app_group';
				send_push_app_group($request);
				/*
					$title = $request['push_title'];
					$body = $request['push_text'];


					$add_content = $request['add_content'];
					$content_id = $request['content_id'];
				*/

			}
			//print_R($Data);
			//echo json_encode(['data' => $Data, 'table'=>$table, 'offset'=>($offset+$_limit)], JSON_UNESCAPED_UNICODE); 
		}
		echo json_encode(['success']);	
	}else
		echo json_encode([]);

	//print_r($_POST);
	wp_die(); // Alway at the end (to avoid server error 500)
}

// История пушей пользователя
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/push/user_history', array(
		'methods' => 'GET',
		'callback' => 'get_user_push_history',
		// 'permission_callback' => function($request){      
		// 	return is_user_logged_in();
		//   }
	));
});

function get_user_push_history(WP_REST_Request $request) {
	// История пушей пользователя
	$request_data = $request->get_params();
	$limit = 100;
	$user_id = intval($request_data['user_id']);
	if(!$user_id ) return [];

	global $wpdb;

	$result = $wpdb->get_results( 
		"SELECT * FROM push_logs WHERE  (tokens = 'all') OR ( tokens = '".$user_id."' OR tokens LIKE '".$user_id.",%' OR tokens LIKE '%,".$user_id."' OR tokens LIKE '%,".$user_id.",%') ORDER BY id DESC LIMIT $limit"
	, ARRAY_A ) ;
	 
	foreach($result as &$r){
		$r['resource_data'] = unserialize($r['resource_data']);
		//print_r($r['resource_data']);
		if($r['resource_data']['data']['push_extra_data'])
			$r['resource_data']['data']['push_extra_data'] = (string)$r['resource_data']['data']['push_extra_data'];
		if(empty($r['resource_data']['data']))
		$r['resource_data']['data'] = null;
	}
	
	return $result;
}
