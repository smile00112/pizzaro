<?php

////передача данных пользователя, из/в приложение
add_action( 'rest_api_init', function () {
	register_rest_route( 'userinfo/v1', '/getinfo/(?P<user_login>[\d]+)', array( 
		'methods'             => 'GET',
		'callback'            => 'get_user_info_field'
	));
});

function get_user_info_field(WP_REST_Request $request){
	$user_login = $request['user_login'];
	$user = get_user_by('login', $user_login);
	$name = get_user_meta($user->ID, 'first_name', true);
	$email = get_user_meta($user->ID, 'billing_email', true);
	$user_sex = get_user_meta($user->ID, 'user_sex', true);
	$user_birth = get_user_meta($user->ID, 'user_birth', true);
	
	$data = [];
	$data['name'] = $name;
	$data['user_email'] = $email;
	$data['user_sex'] = $user_sex;
	$data['user_birth'] = $user_birth;
	
	return $data;
}


//получение и запись данных пользователя из приложения
add_action( 'rest_api_init', function () {
	register_rest_route( 'userinfo/v1', '/setinfo/(?P<user_login>[\d]+)', array( 
		'methods'             => 'POST',
		'callback'            => 'set_user_info_field'
	));
});

function set_user_info_field(WP_REST_Request $request){
	$user_login = $request['user_login'];
	$user = get_user_by('login', $user_login);
	$name = update_user_meta($user->ID, 'first_name', $request['name']);
	//if($request['email'] != ''){
	$email = update_user_meta($user->ID, 'billing_email', $request['user_email']);
	//}
	$user_sex = update_user_meta($user->ID, 'user_sex', $request['user_sex']);
	$user_birth = update_user_meta($user->ID, 'user_birth', $request['user_birth']);
	
	//return true;
}