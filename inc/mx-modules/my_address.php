<?php
/* 
Адрес структура
	[coordinates] => Array
		(
			[0] => 61.368112
			[1] => 55.189105
		)

	[short_address] => улица Островского, 64
	[country] => Россия
	[city] => Array
		(
			[0] => Челябинск
		)

	[street] => улица Островского
	[premice_number] => 64
	[apartment] => 44
	[AddressLine] => Россия, Челябинск, улица Островского, 64
	[default] => 20220401014800
*/
function get_address_data($index=null){

	/* Получаем адреса */
	$user_id = get_current_user_id();
	if($user_id){
		$data = get_user_meta($user_id, 'data_addreses', true);
		$data = !empty($data) ? json_decode($data, true) : [];
		return ($index != null) ? $data[$index] : $data;
	}else{
		session_start();
		$data = !empty($_SESSION['data_addreses']) ? json_decode($_SESSION['data_addreses'], true) : [];
		return ($index != null) ? $data[$index] : $data;
	}

}

function sortdef($a, $b){ 
	return $a['default'] <=> $b['default'];
}

function get_default_address(){
	session_start();
	//print_R($_SESSION['user_address']);
	return $_SESSION['user_address'];	
}

function default_address_to_session(){
	//echo '<pre>';
	$data = get_address_data();
	//print_r(array_column($data, 'default'));
	//print_r($data);
	usort($data, function ($a, $b) {
		return $a['default'] < $b['default'];
	});

	session_start();
	$_SESSION['user_address'] = $data[0];	
}

default_address_to_session();


function set_address_default($index=null){
	$data = get_address_data($index);
	$data['default'] = date('Ymdhis');
	save_address_data($data, $index);
}

function remove_address_data($index=null){
	if(is_null($index)) return false;
	/* Получаем адреса */
	$user_id = get_current_user_id();
	if($user_id){
		$data = get_user_meta(get_current_user_id(), 'data_addreses', true);
		$data = !empty($data) ? json_decode($data, true) : [];
		unset($data[$index]);
		update_user_meta($user_id, 'data_addreses', json_encode(array_values($data), JSON_UNESCAPED_UNICODE));

	}else{
		session_start();
		$data = !empty($_SESSION['data_addreses']) ? json_decode($_SESSION['data_addreses'], true) : [];
		unset($data[$index]);	

		$_SESSION['data_addreses'] = json_encode(array_values($data), JSON_UNESCAPED_UNICODE);
	}

	return false;
}

function save_address_data($new_data, $index=null){

	/* Получаем адреса */
	$user_id = get_current_user_id();
	if($user_id){
		$data = get_user_meta($user_id, 'data_addreses', true);
		$data = !empty($data) ? json_decode($data, true) : [];
		if(is_null($index)){
			$data[]=$new_data;
			$index_new = count($data) - 1;
		}else{
			$data[$index]=$new_data;
		}

		if(is_null($index) && isset($index_new)){
			//Делаем новый адрес по умолчанию
			$data[$index_new]['default'] = date('Ymdhis');
			//set_address_default($index_new);
		}
		update_user_meta($user_id, 'data_addreses', json_encode($data, JSON_UNESCAPED_UNICODE));

	}else{
		session_start();
		$data = !empty($_SESSION['data_addreses']) ? json_decode($_SESSION['data_addreses'], true) : [];
		if(is_null($index)){
				$data[]=$new_data;
			}else{
				$data[$index]=$new_data;
			}		
	}

	$_SESSION['data_addreses'] = json_encode($data, JSON_UNESCAPED_UNICODE);
	if($new_data['StockId']){
		/* Сохраняем склад в куки */
		$setStock =  setStockId($new_data['StockId'], 0);
		/* Кусок кода из stock.php */
		if ($setStock['unset_items']) { // если удалялись товары из корзины показываем алерт
			$out['alert'] = 1;
			$out['changed'] = 1;
			// $out['alert_title'] = 'Предупреждение!';
			$out['alert_body'] = '<div class="alert title_baur"><span>Некоторые товары недоступны для доставки по выбранному адресу и были удалены из корзины:<span class="baur_nogif"></span></span> <ul class="text-warning">';
			foreach ($setStock['unset_items'] as $item) { //print_r($item);
				$out['alert_body'] .=   '<li>' . $item['name'] . '</li>';
			}
			$out['alert_body'] .=   '</ul></div>';
		}
	}

	return false;
}


add_action( 'wp_ajax_nopriv_save_address', 'save_address_callback' );
add_action( 'wp_ajax_save_address', 'save_address_callback' );
function save_address_callback(){
	if(!isset($_POST['mode'])) return false;
	$mode = $_POST['mode'];

	//print_r($_POST);
	unset($_POST['mode']);
	unset($_POST['action']);
	//print_r($_POST);
	
	$_POST['default'] = date('Ymdhis');
	if($mode=='new'){
		save_address_data($_POST);
	}else{
		save_address_data($_POST, intval($mode));
	}
	// выход нужен для того, чтобы в ответе не было ничего лишнего,
	// только то что возвращает функция
	wp_die();
}

add_action( 'wp_ajax_nopriv_select_address', 'select_address_callback' );
add_action( 'wp_ajax_select_address', 'select_address_callback' );
function select_address_callback(){
	if(!isset($_POST['index'])) return false;
	
	//print_r($_POST);
	$index = $_POST['index'];
	set_address_default($index);
	

	// выход нужен для того, чтобы в ответе не было ничего лишнего,
	// только то что возвращает функция
	wp_die();
}

add_action( 'wp_ajax_nopriv_delete_address', 'delete_address_callback' );
add_action( 'wp_ajax_delete_address', 'delete_address_callback' );
function delete_address_callback(){
	if(!isset($_POST['index'])) return false;
	//print_r($_POST);
	$index = $_POST['index'];
	remove_address_data($index);
	

	// выход нужен для того, чтобы в ответе не было ничего лишнего,
	// только то что возвращает функция
	wp_die();
}


add_action( 'wp_ajax_nopriv_get_user_addres', 'get_user_addres' );
add_action( 'wp_ajax_get_user_addres', 'get_user_addres' );
function get_user_addres(){
	
	$data = get_default_address();
	if(empty($data)) $data = [];
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	// выход нужен для того, чтобы в ответе не было ничего лишнего,
	// только то что возвращает функция
	wp_die();
}

///////////////Добавление мета-данных нового адреса в заказ
add_filter( 'wp_insert_post_data', 'order_meta_my_address', 999, 2 ); // добавление информации мета-полей в комментарий заказа, пригодится для приложения Woo
function order_meta_my_address( $data, $postarr ){ //debug_to_file($data);
	/* для сайта */
	if(get_current_user_id()){
		$user_address = get_default_address();
		$order_id = $postarr['ID']; 
		if(!empty($user_address['coordinates'])){
			update_post_meta( $order_id, 'long', $user_address['coordinates'][0] );
			update_post_meta( $order_id, 'lat', $user_address['coordinates'][1] );
		}
		// if(!empty($user_address['short_address'])){
		// 	$data['short_address'] = $user_address['short_address'];
		// }
		// if(!empty($user_address['AddressLine'])){
		// 	$data['long_address'] = $user_address['AddressLine'];
		// }
		if(!empty($user_address['apartment'])){
			update_post_meta( $order_id, 'apartment', $user_address['apartment'] );
		}
	}

	return $data;
}
?>