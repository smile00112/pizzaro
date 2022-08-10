<?php

//////авторизация без пароля(код в смс)
//отправка смс с проверочным кодом. если номер не существует, происходит регистрация(без авторизации)
function auth_site_sms_send(WP_REST_Request $request){ //это первый шаг, запрашивается номер тел и отправляется на него смс. если номер-пользователь не существет, система создаёт пользователя
	//$user_id = 7307;
	$phone = $request['auth-phone'];
	$sender = 'LoveFood';
	//$phone = '+380958042750';
	$code = rand(1099,9898).'';
	
	$replace = ['+', '(', ')', '-', ' '];
	$phone = str_replace($replace, '', $phone);
	$phone = '7'.$phone; debug_to_file('login: '.$phone);
	//$phone = '380958042750';
	$user = get_user_by('login', $phone);
	if($user){ //если пользователь существует
		$is_sms_send = get_user_meta($user->ID, 'site_auth_code_send', true);
		//if($is_sms_send == '0' || $is_sms_send == '' || empty($is_sms_send)){ debug_to_file('sms send');
			//SMS::send($phone, 'Код для авторизации на сайте '.$sender. ' '.$code);
			send_sms($code, $phone);
			update_user_meta($user->ID, 'site_auth_code', $code);
			update_user_meta($user->ID, 'site_auth_code_send', '1');
			update_user_meta($user->ID, 'billing_phone', '+'.$phone);
		//}
	}
	else{ //если пользователь не существует, регистрируем
		$user_email = $phone.'@maill.ru'; //debug_to_file('new user email: '.$user_email);
		$user_id = register_new_user( $phone, $user_email );
		if(! is_wp_error($user_id)){
		update_user_meta($user_id, 'site_auth_code', $code);
		update_user_meta($user_id, 'site_auth_code_send', '1');
		update_user_meta($user->ID, 'billing_phone', '+'.$phone);
		
		send_sms($code, $phone);
		
		$redirect_url = 'https://'.$_SERVER['SERVER_NAME'];
		//wp_safe_redirect( $redirect_url );
		//$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
		}
		
		return $user_email;
	}
	
	return $phone; 
	
}


//проверка введённого кода из смс
function check_auth_by_sms(WP_REST_Request $request){//пользователь получает смс и вводит код на следующем этапе и система проверяет код, в случае успеха авторизирует
	$phone = $request['phone'];
	$enter_user_code = $request['auth-sms-code'].'';
	if(!is_user_logged_in()){ //если не авторизирован
		$replace = ['+', '(', ')', '-', ' '];
		$phone = str_replace($replace, '', $phone);
		$phone = '7'.$phone;
		$user = get_user_by('login', $phone);
		if($user){ //если пользователь существует
			//debug_to_file('is user : '.$phone);
			$get_sms_code = get_user_meta($user->ID, 'site_auth_code', true);
			if($get_sms_code == $enter_user_code){
				wp_set_auth_cookie( $user->ID, true );
				update_user_meta($user->ID, 'site_auth_code_send', '0');
				return 'loginsuccess';
			}
			//return 'user_id:'.$user->ID.' code: '.$enter_user_code.' unvalid sms code';
			
			return 'unvalidsmscode';
		}
		else return 'usernotfound';
	}
	else return 'useralreadylogedin';
}


//продление время сессии пользователя
add_filter( 'auth_cookie_expiration', 'set_long_session', 20, 1 );
function set_long_session( $expiration ) {
	return $expiration * 300; // $expiration = 2, return 600 дней
}


add_action( 'rest_api_init', function () {
	register_rest_route( 'authsms/v1', '/sendsms', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'auth_site_sms_send'
	));
});

add_action( 'rest_api_init', function () {
	register_rest_route( 'authsms/v1', '/authbysms', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'check_auth_by_sms'
	));
});

add_action( 'wp_footer', 'print_auth_form' );
function print_auth_form(){
	$img_path = get_template_directory_uri();
	echo '<div id="fade-auth"></div>
	<div id="auth-sms-wrap">
		<div class="close-auth"><img src="'. $img_path.'/assets/images/close-auth.png"></div>
		<div class="wrap">
		<form id="auth-sms" action="">
			<div class="text-head">Войдите или зарегистрируйтесь</div>
			<div class="auth-phone-contener">
				<div class="before-input"><img src="'. $img_path .'/assets/images/flag_ru.png"> +7</div>
				<input type="text" name="auth-phone" id="auth-phone" value="" placeholder="Ваш номер">
				<hr>
			</div>
			
			<button type="button" class="send-sms">Активировать</button>
			<div class="pass-auth">Пропустить этот шаг</div>
		</form>
		</div>
		<img class="loader-auth" src="'. $img_path .'/assets/images/ajax-loader.gif">
		<div class="error-input-code"></div>
	</div>'; 
}


function send_sms($code, $phone){ //debug_to_file($code.' - '.$phone);

	$login = get_field('login-sms-api', 'option');  
	$pass =  get_field('pass-sms-api', 'option'); 
	//$text = get_field('sms-text-auth', 'option');
	$text = 'Проверочный код: ';
	
	$text_code = $text.' '.$code;
	$text_code = str_replace(' ', '%20', $text_code);
	
	//debug_to_file('login: '.$login);
	//debug_to_file('pass: '.$pass);
	//debug_to_file('text: '.$text_code);
	
	$url = 'https://api.iqsms.ru/messages/v2/send/?phone=%2B'.$phone.'&text='.$text_code.'&login='.$login.'&password='.$pass;
	//$url = 'https://api.iqsms.ru/messages/v2/send/?phone=%2B'.$phone.'&text=Proverochniy&login='.$login.'&password='.$pass;
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$response = curl_exec($ch);

	if( strpos($response, 'error;not enough balance') !== false ){
		wp_mail(
			get_option('admin_email'),
			'Закончился баланс на СМС',
			'Пополните счет'
		);
	}

	if( strpos($response, 'not enough credits') !== false ){
		wp_mail(
			get_option('admin_email'),
			'Закончился баланс на СМС',
			'Пополните счет'
		);
	}
	if( strpos($response, 'invalid mobile phone') !== false ){
		wp_mail(
			get_option('admin_email'),
			'СМС сообщение не отправлено',
			'Неверный номер ('.$phone.')'
		);
	}

	if( empty($response) ){
		wp_mail(
			get_option('admin_email'),
			'СМС сервис Не работает!',
			'Отправка смс не удалась'
		);
	}	
	//error;
	//accepted;
	curl_close($ch);
	
	return $response;
}

//получение и запись данных пользователя из приложения 
add_action( 'rest_api_init', function () {
	register_rest_route( 'userinfo/v1', '/os', array( 
		'methods'             => 'POST',
		'callback'            => 'set_user_os_data'
	));
});

function set_user_os_data(WP_REST_Request $request){
	global $wpdb;
	$user_login = $request['phone_number'];
	$user = get_user_by('login', $user_login);

	if(!empty($user->ID)){
		if( $request['platform'] )
			update_user_meta($user->ID, 'platform', $request['platform']);
		if( $request['app_version'] )
			update_user_meta($user->ID, 'app_version', $request['app_version']);
		if( $request['build_number'] )
			update_user_meta($user->ID, 'build_number', $request['build_number']);
	}
	// $android_install = get_field('android_install', 'option');
	// $ios_install = get_field('ios_install', 'option');

	$query = $wpdb->get_row("select (select count(um.meta_value) FROM wp_usermeta um WHERE um.meta_key = 'platform' AND um.meta_value = 'iOS') as ios, (select count(um.meta_value) FROM wp_usermeta um WHERE um.meta_key = 'platform' AND um.meta_value = 'Android') as android" , ARRAY_A);
	$push_count = $query[0]['name_value'];
	//print_r($query);

	update_option('options_ios_install', $query['ios']);
	update_option('options_android_install', $query['android']);

	return true;

	// $name = update_user_meta($user->ID, 'first_name', $request['name']);
	// //if($request['email'] != ''){
	// $email = update_user_meta($user->ID, 'billing_email', $request['user_email']);
	// //}
	// $user_sex = update_user_meta($user->ID, 'user_sex', $request['user_sex']);
	// $user_birth = update_user_meta($user->ID, 'user_birth', $request['user_birth']);
	
	//return true;
}


/* ARI авторизации */
add_action('rest_api_init', function () {
    register_rest_route('wc/auth', '/send_code', [
        'methods'  => 'POST',
        'callback' => 'api_get_auth_code',
    ]);
});

function api_get_auth_code(WP_REST_Request $request)
{

	if ( is_user_logged_in() ) {

		$phone = $request['phone'];
		$sender = 'LoveFood';
		//$phone = '+380958042750';
		$code = rand(1099,9898).'';
		
		$replace = ['+', '(', ')', '-', ' '];
		$phone = str_replace($replace, '', $phone);
		
		if(!$phone) return new WP_REST_Response(['error' => 'wrong phone'], 200);

		$phone = '7'.$phone; 
		//$phone = '380958042750';
		$user = get_user_by('login', $phone);
		if($user){ //если пользователь существует
			//$is_sms_send = get_user_meta($user->ID, 'site_auth_code_send', true);
			//if($is_sms_send == '0' || $is_sms_send == '' || empty($is_sms_send))
			{ 
				//SMS::send($phone, 'Код для авторизации на сайте '.$sender. ' '.$code);
				//echo 'sended_code_'.$phone;
				if($phone != '71231234567' && $phone != '79191232340'){
					$response = send_sms($code, $phone);
					if( strpos($response, 'error;') !== false ){
						return new WP_REST_Response(['error' => 'code send error'], 200); 
					}
					//error;
					//accepted;
				}else{
					$code = 1234;
				}
				
				update_user_meta($user->ID, 'site_auth_code', $code);
				update_user_meta($user->ID, 'site_auth_code_send', '1');
				update_user_meta($user->ID, 'billing_phone', '+'.$phone);
			}

			return new WP_REST_Response(['success' => true], 200);
		}
		else{ //если пользователь не существует, регистрируем
			$user_email = $phone.'@maill.ru';
			$user_id = register_new_user( $phone, $user_email );
			if(! is_wp_error($user_id)){

				if($phone != '71231234567' && $phone != '79191232340'){
					$response = send_sms($code, $phone);
					if( strpos($response, 'error;') !== false ){
						return new WP_REST_Response(['error' => 'code send error'], 200);
					}
				}else{
					$code = 1234;
				}

				update_user_meta($user_id, 'site_auth_code', $code);
				update_user_meta($user_id, 'site_auth_code_send', '1');
				update_user_meta($user->ID, 'billing_phone', '+'.$phone);
				
			}
			
			return new WP_REST_Response(['success' => true], 200);
		}

		//your stuff only for legged in user 123
		return new WP_REST_Response(['error' => 'user not found'], 200);


    }

    return new WP_Error('unauthorized', __('You shall not pass'), [ 'status' => 401 ]); //can also use WP_REST_Response

 }

add_action('rest_api_init', function () {
    register_rest_route('wc/auth', '/check_code', [
        'methods'  => 'POST',
        'callback' => 'api_check_auth_code',
    ]);
});

function api_check_auth_code(WP_REST_Request $request)
{

	if ( is_user_logged_in() ) {

		$phone = $request['phone'];
		$enter_user_code = $request['auth-sms-code'].'';
		$replace = ['+', '(', ')', '-', ' '];
		$phone = str_replace($replace, '', $phone);
		$phone = '7'.$phone;
		$user = get_user_by('login', $phone);
		if($user){ //если пользователь существует
			//debug_to_file('is user : '.$phone);
			$get_sms_code = get_user_meta($user->ID, 'site_auth_code', true);
			if($get_sms_code == $enter_user_code){
				wp_set_auth_cookie( $user->ID, true );
				update_user_meta($user->ID, 'site_auth_code_send', '0');

				/* Формируем ответ */
				$name = get_user_meta($user->ID, 'first_name', true);
				$user_sex = get_user_meta($user->ID, 'user_sex', true);
				$user_birth = get_user_meta($user->ID, 'user_birth', true);
				$user_email_meta = get_user_meta($user->ID, 'billing_email', true);
				
	
				$shipping = get_shipping_address($user->ID);
				$billing = get_billing_address($user->ID);
	
				$expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user->ID, true);
				$cookie = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');

				$response = array(
					"id" => $user->ID,
					"username" => $user->user_login,
					"nicename" => $user->user_nicename,
					"email" => $user->user_email,
					"user_email" => $user_email_meta,
					"name" => $name,
					"user_sex" => $user_sex,
					"user_birth" => $user_birth,
					"url" => $user->user_url,
					"registered" => $user->user_registered,
					"displayname" => $user->display_name,
					"firstname" => $user->user_firstname,
					"lastname" => $user->last_name,
					"nickname" => $user->nickname,
					"description" => $user->user_description,
					"capabilities" => $user->wp_capabilities,
					"role" => $user->roles,
					"shipping" => $shipping,
					"billing" => $billing,
					"avatar" => get_avatar_url($user->ID),
					'cookie' => $cookie,
				);


				return new WP_REST_Response(['success' => true, 'user' => $response], 200);
			}
			//return 'user_id:'.$user->ID.' code: '.$enter_user_code.' unvalid sms code';
			
			return new WP_REST_Response(['error' => 'invalid code'], 200);
		}
			

		//your stuff only for legged in user 123
		return new WP_REST_Response(['error' => 'user not found'], 200);


    }

    return new WP_Error('unauthorized', __('You shall not pass'), [ 'status' => 401 ]); //can also use WP_REST_Response

 }
 function get_shipping_address($userId){
	$shipping = [];

	$shipping["first_name"] = get_user_meta($userId, 'shipping_first_name', true );
	$shipping["last_name"] = get_user_meta($userId, 'shipping_last_name', true );
	$shipping["company"] = get_user_meta($userId, 'shipping_company', true );
	$shipping["address_1"] = get_user_meta($userId, 'shipping_address_1', true );
	$shipping["address_2"] = get_user_meta($userId, 'shipping_address_2', true );
	$shipping["city"] = get_user_meta($userId, 'shipping_city', true );
	$shipping["state"] = get_user_meta($userId, 'shipping_state', true );
	$shipping["postcode"] = get_user_meta($userId, 'shipping_postcode', true );
	$shipping["country"] = get_user_meta($userId, 'shipping_country', true );
	$shipping["email"] = get_user_meta($userId, 'shipping_email', true );
	$shipping["phone"] = get_user_meta($userId, 'shipping_phone', true );

	if(empty($shipping["first_name"]) && empty($shipping["last_name"]) && empty($shipping["company"]) && empty($shipping["address_1"]) && empty($shipping["address_2"]) && empty($shipping["city"]) && empty($shipping["state"]) && empty($shipping["postcode"]) && empty($shipping["country"]) && empty($shipping["email"]) && empty($shipping["phone"])){
		return null;
	}
	return $shipping;
}

function get_billing_address($userId){
	$billing = [];

	$billing["first_name"] = get_user_meta($userId, 'billing_first_name', true );
	$billing["last_name"] = get_user_meta($userId, 'billing_last_name', true );
	$billing["company"] = get_user_meta($userId, 'billing_company', true );
	$billing["address_1"] = get_user_meta($userId, 'billing_address_1', true );
	$billing["address_2"] = get_user_meta($userId, 'billing_address_2', true );
	$billing["city"] = get_user_meta($userId, 'billing_city', true );
	$billing["state"] = get_user_meta($userId, 'billing_state', true );
	$billing["postcode"] = get_user_meta($userId, 'billing_postcode', true );
	$billing["country"] = get_user_meta($userId, 'billing_country', true );
	$billing["email"] = get_user_meta($userId, 'billing_email', true );
	$billing["phone"] = get_user_meta($userId, 'billing_phone', true );

	if(empty($billing["first_name"]) && empty($billing["last_name"]) && empty($billing["company"]) && empty($billing["address_1"]) && empty($billing["address_2"]) && empty($billing["city"]) && empty($billing["state"]) && empty($billing["postcode"]) && empty($billing["country"]) && empty($billing["email"]) && empty($billing["phone"])){
		return null;
	}
	
	return $billing;
}