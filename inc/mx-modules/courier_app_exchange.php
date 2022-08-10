<?php
add_action( 'wp_ajax_nopriv_order_to_couriers_app', 'order_to_couriers_app' );
add_action( 'wp_ajax_order_to_couriers_app', 'order_to_couriers_app' );
function order_to_couriers_app($order_id){


	$order_id = intval($_POST['order_id']);
	if(empty($order_id)) return false;

	ini_set('display_errors', 'On'); //отключение ошибок на фронте
	ini_set('log_errors', 'On');

	$order = new WC_Order($order_id);
	$user = $order->get_user();
	//$user_id = $user->ID;

	$data['number'] = $order->get_id();
	$data['price'] = $order->get_total();
	$data['address_to'] = get_post_meta($order_id, '_billing_address_1', true ); //адрес
	$data['floor'] =  get_post_meta($order_id, '_billing_company' , true ); //этаж
	$data['kvart'] =  get_post_meta($order_id, '_billing_address_2' , true ); //квартира
	if($data['kvart']) $data['address_to'].=' кв.'.$data['kvart'];
	if($data['floor']) $data['address_to'].=' этаж '.$data['floor'];
	$data['long'] =  $order->get_meta('long' ); //long
	$data['lat'] =  $order->get_meta('lat' ); //lat
	$data['coordinates_to'] =  $data['long'].' ,'.$data['lat']; //coordinates_to	
	$data['payment_method'] =  get_post_meta($order_id, '_payment_method_title' , true ); //метод оплаты
	$data['phone'] =  $order->get_meta('billing_order_phone' ); //телефон клиента
	$data['customer'] =  ($user->user_firstname || $user->user_lastname ) ? trim($user->user_lastname.' '.$user->user_firstname) : $user->display_name; 
	$data['deliv_time'] =  $order->get_meta('_shipping_deliv_time' , true ); //заложенное время на доставку
	$data['order_created_at'] =  get_post_meta($order_id, '_paid_date' , true ); //Время платежа(создания заказа)

	$dateToDelivery = null;
	if(!empty($data['deliv_time'])){
		$dateToDelivery = strtotime($data['order_created_at']) + $data['deliv_time']*60;
		$dateToDelivery =  date('Y-m-d H:i:s', $dateToDelivery);
	}

	$data['order_close_time'] =  $dateToDelivery; //Время платежа(создания заказа)


	$app_ex = new Courier_app_exchange(); 
	$res = $app_ex->post_order($data);
	if($res['success'] == true){
		add_post_meta($order_id, '_sended_to_couriers', 1, true);
	}


	wp_send_json($res);
	//echo strtotime($data['paid_date'])
;
	//$od_diff_hours = abs($od_modified - $od_created)/(60*60); //в часах
	// strtotime(date('Y-m-d')  ." ". $currentTime); 
	/*
		$dateCreated = strtotime((string) $order->get_date_created()) + $fiveHours;
		$delive_timestamp = $dateCreated + intval($deliv_time) * 60;	
	*/


	//print_R($data);

	//return $data;
	// выход нужен для того, чтобы в ответе не было ничего лишнего,
	// только то что возвращает функция
	//wp_die();
}

class Courier_app_exchange {

	
	/**
	 * Submit request.
	 *
	 * Return array with the api answer or false if there are any errors.
	 * Write errors into the $skyweb_wc_iiko_logs.
	 *
	 * @param string $url
	 * @param array $add_headers
	 * @param string $body
	 *
	 * @return false|array
	 */
	public $token;

	public function __construct() {
		$this->token = '4|2Ng6QCpXsAmneewsYcUL6dSqOgHvP3gr9XzCXtP1';
	}

	public function test( ) {
		echo 'HELLO from HTTP_Request Class';
	}	

	public function get_user( $delivery_id = '' ) {	
		//$access_token = $this->get_access_token();
		//$access_token = $this->token;
		//уточняем адрес
		$address_request_url = 'api/user'; 
		$headers = array(
			'Authorization' => 'Bearer '.$this->token,
			//'X-SBISSessionId' => $access_token['sid']
		);

		$resp = $this->remote( $address_request_url, $headers, '', 'GET' );

		return $resp;

	}

	public function post_order( $order = [] ) {	
		if(empty($order)) return;
		//$access_token = $this->get_access_token();
		//$access_token = $this->token;
		//уточняем адрес
		$address_request_url = 'api/orders/add'; 
		$headers = array(
			'Authorization' => 'Bearer '.$this->token,
			'X-CSRF-TOKEN' => 'ObSK41G1xe',
			//'X-SBISSessionId' => $access_token['sid']
		);
//echo wp_json_encode( $order );
		$resp = $this->remote( $address_request_url, $headers, $order, 'POST' );

		if(!empty($resp['error'])){
			return ['success'=> false];
		}else 
			return ['success'=> true];

		//print_r( $resp );
		//print_r($this->get_user());
		//return $resp;



	}
	public static function remote( $url, $add_headers = array(), $body = '', $method = "POST") {


		$url = 'https://courier.dolinger-app.ru/'.$url;	


		//echo '-'.$url.'-';

		$url      = esc_url( $url );
		///$timeout  = absint( get_option( 'skyweb_wc_iiko_timeout' ) ) > 0 ? absint( get_option( 'skyweb_wc_iiko_timeout' ) ) : 10;
		$headers  = array(
			'Content-Type' => 'application/json;',
			'Accept' => 'application/json',
		);
		$headers  = is_array( $add_headers ) && ! empty( $add_headers ) ? array_merge( $headers, $add_headers ) : $headers;
		$body     = is_array( $body ) ? $body : array();
		$args     = array(
			'method'      => $method,
			'redirection' => 5,
			'httpversion' => '1.1',
			//'blocking'    => $blocking, // Currently not used. Always true.
			'headers'     => $headers,
			'body'        => wp_json_encode( $body ),
			'data_format' => 'body'
		);


		$ch = curl_init( $url );
		curl_setopt($ch, CURLOPT_POSTFIELDS,  wp_json_encode( $body ) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer 4|2Ng6QCpXsAmneewsYcUL6dSqOgHvP3gr9XzCXtP1', ]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// 3. получаем HTML в качестве результата
		$output = curl_exec($ch);
		//print_R($output);
		// 4. закрываем соединение
		curl_close($ch);
		//$response = wp_safe_remote_post( $url, $args );
return json_decode($output, true);
	//	print_r($response);
		//Logs::add_wc_debug_log( $response, 'remote-post' );

		// WP_Error.
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$error_code    = $response->get_error_code();
			//$skyweb_wc_iiko_logs->add_error( "Request failed. WP_Error: $error_code - {$error_message}." );
echo "Request failed. WP_Error: $error_code - {$error_message}.";
			return false;
		}

		// Wrong response code error.
		$response_code    = wp_remote_retrieve_response_code( $response );
		$response_message = wp_remote_retrieve_response_message( $response );

		if ( 200 !== $response_code ) {
			//$skyweb_wc_iiko_logs->add_error( "Request failed. {$response_code} - {$response_message}." );
echo "Request failed. {$response_code} - {$response_message}." ;
			//Logs::add_wc_error_log( "Request failed. {$response_code} - {$response_message}.", 'remote-post' );

			// No return cause we can have iiko errors.
		}

		// Decode JSON response body to an associative array.
		$response = json_decode( wp_remote_retrieve_body( $response ), true );

		// JSON decode error.
		if ( JSON_ERROR_NONE !== json_last_error() ) {
			//$skyweb_wc_iiko_logs->add_error( 'Response body is not a correct JSON.' );
echo 'Response body is not a correct JSON.' ;
		}

		// Response body is empty error.
		if ( ! is_array( $response ) || empty( $response ) ) {
			//$skyweb_wc_iiko_logs->add_error( 'Response is not an array or is empty.' );
echo  'Response is not an array or is empty.' ;
			return false;
		}

		// Iiko error.
		// if ( array_key_exists( 'errorDescription', $response ) ) {
		// 	$error_number = isset( $response['error'] ) ? $response['error'] : '';
		// 	$skyweb_wc_iiko_logs->add_error( "Iiko response contains the error: $error_number - {$response['errorDescription']}." );


		// 	return false;
		// }

		return $response;
	}

}



// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'add_meta_box_couriers' );
function add_meta_box_couriers(){
	add_meta_box( 'couriers_box', __('Отправить курьерам','woocommerce'), 'mv_add_other_fields_for_courier', 'shop_order', 'side', 'core' );
}
function mv_add_other_fields_for_courier()
{
	global $post;

	// $meta_field_data = get_post_meta( $post->ID, '_my_field_slug', true ) ? get_post_meta( $post->ID, '_my_field_slug', true ) : '';
		$s = get_post_meta( $post->ID, '_sended_to_couriers', true );
		$order_sended =  empty($s) ? true : false;
		if($order_sended)
		$btn = '<button type="button" class="button send_order_to_courier button-primary" onclick="send_order_to_couriers('.$post->ID.');">Отправить курьерам</button>';
	else 
		$btn = '<button type="button" class="button button-primary" onclick="alert(\'Заказ уже отправлен курьерам\');">Уже оправлено</button>';
	echo 	$btn.'
	<style>
	#couriers_box .inside{
		text-align: center;
	}
	</style>
	<script>
		function send_order_to_couriers(order_id){
			jQuery.ajax({
				type : "POST",
				url : "/wp-admin/admin-ajax.php",
				async: true,
				data : {action: "order_to_couriers_app", order_id: order_id},
				dataType: "json",
				beforeSend: function (xhr) {
				},
				complete: function() {
				},
				success: function (data) {
					console.log(data);
					if(data.success == false) alert(\'Произошла ошибка\');
					else{ 
						alert(\'Заказ отправлен курьерам\');
						jQuery(\'.send_order_to_courier\').prop("disabled", true);
					}
				},
				});
		}
	</script>
	';

}


