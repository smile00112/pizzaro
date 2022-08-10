<?php

function bonus_to_owner_for_first_checkout( $user_id ) {
	global $wpdb ;
	/* подключим модуль,т.к. в админке ошибку выдаёт при смене статуса заказа */
	include_once  ABSPATH . 'wp-content/plugins/rewardsystem/includes/frontend/tab/modules/class-rs-fpactionreward-frontend.php';
	$s_bon = get_field('owner_price', 'option');
	$ObjAction = new RSActionRewardModule(); //объект для функции бонусов
	$ObjAction::award_reg_points_instantly( $s_bon , $user_id , $event_slug = 'SLRRP' , $Network    = '', $noreg = true ) ;

	send_push_app_test($user_id, 'Внимание', 'Вам начислено '.$s_bon.' бонусов за реферала');

}


//* проверяем, введён ли реферальный купон и создаём его */
add_filter( 'woocommerce_get_shop_coupon_data', 'filter_function_name_3841', 10, 3 );
function filter_function_name_3841( $false, $data, $that ){
	// filter...
	//debug_to_file(print_r($data, true));
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	return;

	$referal_coupon_enabled = get_field('customer_promo_activity', 'option');
	if($referal_coupon_enabled != "true") return [];
	
	
	$coupon_code = $data;
	if( $user_id = rc_check_referal($coupon_code) ){

		if (!rc_coupon_exists($coupon_code)) {
			rc_generate_coupon($coupon_code, $user_id);
		 }
	}
	return ;
	//return 'test_coupon';
}

// add_filter('woocommerce_coupon_is_valid', array($this, 'validate_coupon'), 15, 2);
// function validate_coupon($valid, $coupon)
// {

//    // is_coupon_value is a public method in class WC_Discounts; and this method will run the protected 
//    // method validate_coupon_expiry_date:
//    //$valid = $discounts->is_coupon_valid($coupon);
   
//   //return $valid;
// }

/* Проверяет код на "Реферальность" */
function rc_check_referal($coupon_code) {
	if(strpos($coupon_code, 'promo-') !== false){
		return $user_id = intval(str_replace('promo-', '', $coupon_code));
	}
	return false;

}

/* Создаём купон */
function rc_generate_coupon($coupon_code, $user_id) {

	//$discount_type = 'fixed_cart';
			
	$coupon = array(
		'post_title' => $coupon_code,
		'post_excerpt' => 'Реферальный купон '.$user_id,
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type'     => 'shop_coupon'
	);
	
	$new_coupon_id = wp_insert_post( $coupon );
	
	$discount_type = get_field('rc_discount_type', 'option');
	$amount = get_field('rc_amount', 'option');
	$gift_product = get_field('rc_gift_product', 'option');
	$gift_product_quantity = get_field('rc_gift_product_quantity', 'option');
	// Add meta
	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	if(in_array( $discount_type, ['percent', 'fixed_cart'] ))
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
	if(in_array( $discount_type, ['free_gift'] )){
		//Для обычного товара
		if($gift_product->post_type == 'product_variation'){
			update_post_meta( $new_coupon_id, '_wc_free_gift_coupon_data', [$gift_product->ID => ['product_id' => $gift_product->post_parent, 'variation_id'=>$gift_product->ID, 'quantity'=> $gift_product_quantity]] );
		}
		//Для вариации
		if($gift_product->post_type == 'product'){
			update_post_meta( $new_coupon_id, '_wc_free_gift_coupon_data', [$gift_product->ID => ['product_id' => $gift_product->ID, 'variation_id'=>0, 'quantity'=> $gift_product_quantity]] );	
		}

		update_post_meta( $new_coupon_id, '_wc_free_gift_coupon_free_shipping', 'no' );
	}

	update_post_meta( $new_coupon_id, 'individual_use', 'no' );
	//update_post_meta( $new_coupon_id, 'product_categories', $categoryname );
	update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
	update_post_meta( $new_coupon_id, 'usage_limit', 999 );
	update_post_meta( $new_coupon_id, 'usage_limit_first_purchase', 'yes');

	//Склад для купона
	$stoks_terms = get_terms( ['taxonomy'=> ['location'], 'get' => 'all' ] );
	foreach($stoks_terms as $st){
		update_post_meta( $new_coupon_id, 'sklad_coupon', [$st->term_id]);
	}

	update_post_meta( $new_coupon_id, 'owner', $user_id );	
}

/* ПРоверка существования купона по коду*/
function rc_coupon_exists($coupon_code) {
	global $wpdb;

	$sql = $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_type = 'shop_coupon' AND post_name = '%s'", $coupon_code );
	$coupon_codes = $wpdb->get_results($sql);
	// /debug_to_file('____'.$sql.'____');
	if (count($coupon_codes)> 0) {
		return true;
	}
	else {
		return false;
	}
}

/* Создание купона */

function send_coupons_to_users_action_new($coupon_code, $discount_type, $coupon_amount) {
	// Get an empty instance of the WC_Coupon Object
	$coupon = new WC_Coupon();

	// Set the necessary coupon data (since WC 3+)
	$coupon->set_code( $coupon_code ); // (string)
	// $coupon->set_description( $description ); // (string)
	$coupon->set_discount_type( $discount_type ); // (string)
	$coupon->set_amount( $coupon_amount ); // (float)
	// $coupon->set_date_expires( $date_expires ); // (string|integer|null)
	// $coupon->set_date_created( $date_created ); // (string|integer|null)
	// $coupon->set_date_modified( $date_created ); // (string|integer|null)
	// $coupon->set_usage_count( $usage_count ); // (integer)
	$coupon->set_individual_use( true ); // (boolean) 
	// $coupon->set_product_ids( $product_ids ); // (array)
	// $coupon->set_excluded_product_ids( $excl_product_ids ); // (array)
	$coupon->set_usage_limit( 0 ); // (integer)
	// $coupon->set_usage_limit_per_user( $usage_limit_per_user ); // (integer)
	// $coupon->set_limit_usage_to_x_items( $limit_usage_to_x_items ); // (integer|null)
	// $coupon->set_free_shipping( $free_shipping ); // (boolean) | default: false
	// $coupon->set_product_categories( $product_categories ); // (array)
	// $coupon->set_excluded_product_categories( $excl_product_categories ); // (array)
	// $coupon->set_exclude_sale_items( $excl_sale_items ); // (boolean)
	// $coupon->set_minimum_amount( $minimum_amount ); // (float)
	// $coupon->set_maximum_amount( $maximum_amount ); // (float)
	// $coupon->set_email_restrictions( $email_restrictions ); // (array)
	// $coupon->set_used_by( $used_by ); // (array)
	// $coupon->set_virtual( $is_virtual ); // (array)

	// Create, publish and save coupon (data)
	$coupon->save();
}

function send_coupons_to_users_action() {
	$couponname =   $_POST['couponname'];
	$discountpercent = $_POST['discountpercent'];
	$categorycount = count($_POST['categoryname']);
	$usercount = count($_POST['checkedusers']);
	$categoryname = $_POST['categoryname'];
	$emailrestrict = $_POST['checkedusers'];
	
	/* -------- GENERATE COUPON ------------ */
	$discount_type = 'percent';
	
	$coupon = array(
	'post_title' => $couponname,
	'post_content' => '',
	'post_status' => 'publish',
	'post_author' => 1,
	'post_type'     => 'shop_coupon'
	);
	
	$new_coupon_id = wp_insert_post( $coupon );
	
	// Add meta
	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $discountpercent );
	update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
	update_post_meta( $new_coupon_id, 'product_categories', $categoryname );
	update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
	update_post_meta( $new_coupon_id, 'usage_limit', $usercount );
	update_post_meta( $new_coupon_id, 'expiry_date', '2017-05-09' );
	update_post_meta( $new_coupon_id, 'email_restrictions', $emailrestrict );echo 'Function End!';
}

//Получение общих данных по купону юзера
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1/coupons', '/referal_info', array( 
		'methods' => 'GET',
		'callback' => 'get_referal_info',
	));
});
function get_referal_info()
{
	$user_id = $_GET['user_id'];
	if(!$user_id) return ['no user id'];

	$referal_coupon_enabled = get_field('customer_promo_activity', 'option');
	if($referal_coupon_enabled != "true") return [];

	$discount_type = get_field('rc_discount_type', 'option');
	$amount = get_field('rc_amount', 'option');
	$owner_price = get_field('owner_price', 'option');
	$text = get_field('rc_promo_text', 'option');
	$text_for_share = get_field('rc_promo_share_text', 'option');
	$menu_text = get_field('rc_promo_menu_text', 'option');
	$coupon = 'promo-'.$user_id;
	$text_for_share = str_replace('{code}', $coupon, $text_for_share);
	$text_for_share = str_replace('{skidka}', $amount, $text_for_share);

	return [
		'discount_type' => $discount_type,
		//'amount' => $amount,
		'amount' => $owner_price, //.'₽',		
		'menu_text' => $menu_text,
		'text' => $text,
		'text_for_share' => $text_for_share,
		'coupon' => $coupon,
	];
}



function dsCrypt($input,$decrypt=false) {
    $o = $s1 = $s2 = array();
    $basea = array('?','(','@',';','$','#',"]","&",'*'); // base symbol set
    $basea = array_merge($basea, range('a','z'), range('A','Z'), range(0,9) );
    $basea = array_merge($basea, array('!',')','_','+','|','%','/','[','.',' ') );
    $dimension=9;
    for($i=0;$i<$dimension;$i++) { // create Squares
        for($j=0;$j<$dimension;$j++) {
            $s1[$i][$j] = $basea[$i*$dimension+$j];
            $s2[$i][$j] = str_rot13($basea[($dimension*$dimension-1) - ($i*$dimension+$j)]);
        }
    }
    unset($basea);
    $m = floor(strlen($input)/2)*2;
    $symbl = $m==strlen($input) ? '':$input[strlen($input)-1];
    $al = array();
    for ($ii=0; $ii<$m; $ii+=2) {
        $symb1 = $symbn1 = strval($input[$ii]);
        $symb2 = $symbn2 = strval($input[$ii+1]);
        $a1 = $a2 = array();
        for($i=0;$i<$dimension;$i++) {
            for($j=0;$j<$dimension;$j++) {
                if ($decrypt) {
                    if ($symb1===strval($s2[$i][$j]) ) $a1=array($i,$j);
                    if ($symb2===strval($s1[$i][$j]) ) $a2=array($i,$j);
                    if (!empty($symbl) && $symbl===strval($s2[$i][$j])) $al=array($i,$j);
                }
                else {
                    if ($symb1===strval($s1[$i][$j]) ) $a1=array($i,$j);
                    if ($symb2===strval($s2[$i][$j]) ) $a2=array($i,$j);
                    if (!empty($symbl) && $symbl===strval($s1[$i][$j])) $al=array($i,$j);
                }
            }
        }
        if (sizeof($a1) && sizeof($a2)) {
            $symbn1 = $decrypt ? $s1[$a1[0]][$a2[1]] : $s2[$a1[0]][$a2[1]];
            $symbn2 = $decrypt ? $s2[$a2[0]][$a1[1]] : $s1[$a2[0]][$a1[1]];
        }
        $o[] = $symbn1.$symbn2;
    }
    if (!empty($symbl) && sizeof($al))
        $o[] = $decrypt ? $s1[$al[1]][$al[0]] : $s2[$al[1]][$al[0]];
    return implode('',$o);
}



//Шлём пуши
// add_action('rest_api_init', function () {
// 	register_rest_route('test/v1', '/tttest', array( 
// 		'methods' => 'GET',
// 		'callback' => 'tttest',
// 	));
// });
// function tttest(){
// 	check_if_expiry_all_users();
// 	//send_push_app_test(7387, 'Привет', 'Вам начислен 1 000 000 бонусов');
// 	//send_push_app_test(7387, 'Привет', 'С Вас списали все бонусы. Увы :(');
// }

//тут будет проверка на первый заказ не нужна, есть в купоне)
// add_filter('woocommerce_coupon_is_valid', array($this, 'validate_coupon'), 15, 2);
// function validate_coupon($valid, $coupon)
// {

//    // is_coupon_value is a public method in class WC_Discounts; and this method will run the protected 
//    // method validate_coupon_expiry_date:
//    //$valid = $discounts->is_coupon_valid($coupon);
   
//   //return $valid;
// }

// add_action('woocommerce_applied_coupon', 'apply_product_on_coupon2');
// function apply_product_on_coupon2( ) {
//     global $woocommerce;
//     $coupon_id = '12345';
//     $free_product_id = 54321;

//     // if(in_array($coupon_id, $woocommerce->cart->get_applied_coupons())){
//     //     $woocommerce->cart->add_to_cart($free_product_id, 1);
//     // }
// 	//return 12345;
// }
/*
материалы 
код woocommerce корзины/купона
https://www.hardworkingnerd.com/woocommerce-how-to-programmatically-create-a-coupon/


купон на самовывоз

https://question-it.com/questions/4042304/optsija-mestnogo-samovyvoza-so-skidkoj-v-protsentah-v-woocommerce
https://question-it.com/questions/2767537/spetsialnaja-protsentnaja-skidka-dlja-mestnogo-samovyvoza-v-kasse-woocommerce



*/
