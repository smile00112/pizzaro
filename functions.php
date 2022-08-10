<?php
/* Pizzaro engine room
 *
 * @package pizzaro
 */

//error_reporting(E_ALL ^ E_NOTICE);  //отключение notice

ini_set('display_errors', 'Off'); //отключение ошибок на фронте
ini_set('log_errors', 'On'); //запись ошибок в логи

/**
 * Assign the Pizzaro version to a var
 */
$theme = wp_get_theme('pizzaro');
$pizzaro_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 980; /* pixels */
}
//////////////////////////////////////////////////////////////////////////lubluedu
/**
 * Initialize all the things.
 */
require get_template_directory() . '/inc/class-pizzaro.php';

require get_template_directory() . '/inc/pizzaro-functions.php';
require get_template_directory() . '/inc/pizzaro-template-hooks.php';
require get_template_directory() . '/inc/pizzaro-template-functions.php';

/**
 * Redux Framework
 * Load theme options and their override filters
 */
if (is_redux_activated()) {
	require get_template_directory() . '/inc/redux-framework/pizzaro-options.php';
	require get_template_directory() . '/inc/redux-framework/hooks.php';
	require get_template_directory() . '/inc/redux-framework/functions.php';
}

if (is_jetpack_activated()) {
	require get_template_directory() . '/inc/jetpack/class-pizzaro-jetpack.php';
}

if (is_woocommerce_activated()) {
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-woocommerce.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-shortcode-products.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-products.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-wc-helper.php';
	require get_template_directory() . '/inc/woocommerce/pizzaro-woocommerce-template-hooks.php';
	require get_template_directory() . '/inc/woocommerce/pizzaro-woocommerce-template-functions.php';
	require get_template_directory() . '/inc/woocommerce/integrations.php';
}

if (is_wp_store_locator_activated()) {
	require get_template_directory() . '/inc/wp-store-locator/class-pizzaro-wpsl.php';
}

///адрес модифицированный
require get_template_directory() . '/inc/mx-modules/my_address.php'; 



/**
 * One Click Demo Import
 */
if (is_ocdi_activated()) {
	require get_template_directory() . '/inc/ocdi/hooks.php';
	require get_template_directory() . '/inc/ocdi/functions.php';
}

if (is_admin()) {
	require get_template_directory() . '/inc/admin/class-pizzaro-admin.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woothemes/theme-customisations
 */

// Подключенин минимальной суммы заказа и бесплатной доставки
require_once get_template_directory() . '/select-address/min-amount_and_free-ship.php';

// Подключение карты

require_once get_template_directory() . '/select-address/functions.php';

function banners_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name' => 'Банеры',
		'singular_name' => 'Банер',
		'menu_name' => 'Банеры',
		'all_items' => 'Все банеры',
		'view_item' => 'Просмотр банера',
		'add_new_item' => 'Добавить банер',
		'add_new' => 'Добавить новый',
		'edit_item' => 'Редактировать банер',
		'update_item' => 'Обновить банер',
		'search_items' => 'Поиск банера',
		'not_found' => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в Корзине',
	);

// Set other options for Custom Post Type

	$args = array(
		'label' => 'banners',
		'description' => 'Банеры',
		'labels' => $labels,
		'supports' => array('title', 'excerpt', 'thumbnail', 'custom-fields'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'can_export' => true,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => false,
		'capability_type' => 'post',
		'show_in_rest' => true,

	);

	// Registering your Custom Post Type
	register_post_type('banners', $args);

	$labels2 = array(
		'name' => 'Поп-апы',
		'singular_name' => 'Поп-ап',
		'menu_name' => 'Поп-апы',
		'all_items' => 'Все Поп-апы',
		'view_item' => 'Просмотр Поп-ап',
		'add_new_item' => 'Добавить Поп-ап',
		'add_new' => 'Добавить новый',
		'edit_item' => 'Редактировать Поп-ап',
		'update_item' => 'Обновить Поп-ап',
		'search_items' => 'Поиск Поп-ап',
		'not_found' => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в Корзине',
	);

// Set other options for Custom Post Type

	$args2 = array(
		'label' => 'mapppops',
		'description' => 'Поп-апы',
		'labels' => $labels2,
		'supports' => array('title', 'excerpt', 'thumbnail', 'custom-fields'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'can_export' => true,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => false,
		'capability_type' => 'post',
		'show_in_rest' => true,

	);

	// Registering your Custom Post Type
	register_post_type('mapppops', $args2);

	$labels3 = array(
		'name' => 'Точки доставки',
		'singular_name' => 'Точка доставки',
		'menu_name' => 'Точки доставки',
		'all_items' => 'Все',
		'view_item' => 'Просмотр ',
		'add_new_item' => 'Добавить',
		'add_new' => 'Добавить',
		'edit_item' => 'Редактировать ',
		'update_item' => 'Обновить ',
		'search_items' => 'Поиск ',
		'not_found' => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в Корзине',
	);

// Set other options for Custom Post Type

	$args3 = array(
		'label' => 'deliverypoint',
		'description' => 'Точки доставки',
		'labels' => $labels3,
		'supports' => array('title', 'custom-fields'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'can_export' => true,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => false,
		'capability_type' => 'post',
		'show_in_rest' => true,

	);

	// Registering your Custom Post Type
	register_post_type('deliverypoint', $args3);


	//АКЦИИ в админке
	$labels4 = array(
		'name' => 'Акции',
		'singular_name' => 'Акция',
		'menu_name' => 'Акции',
		'all_items' => 'Все',
		'view_item' => 'Просмотр ',
		'add_new_item' => 'Добавить',
		'add_new' => 'Добавить',
		'edit_item' => 'Редактировать ',
		'update_item' => 'Обновить ',
		'search_items' => 'Поиск ',
		'not_found' => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в Корзине',
	);
	$args4 = array(
		'label' => 'promo',
		'description' => 'Акции на сайте',
		'labels' => $labels4,
		'supports' => array('title', 'custom-fields'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'can_export' => true,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => false,
		'capability_type' => 'post',
		'show_in_rest' => true,

	);
	// Registering your Custom Post Type
	register_post_type('promo', $args4);


	//Массовые скидки
	$labels5 = array(
		'name' => 'Масс. Скидки',
		'singular_name' => 'Массовые Скидки',
		'menu_name' => 'Масс. Скидки',
		'all_items' => 'Все',
		'view_item' => 'Просмотр ',
		'add_new_item' => 'Добавить',
		'add_new' => 'Добавить',
		'edit_item' => 'Редактировать ',
		'update_item' => 'Обновить ',
		'search_items' => 'Поиск ',
		'not_found' => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в Корзине',
	);
	$args5 = array(
		'label' => 'mass_discount',
		'description' => 'Масс. скидки на сайте',
		'labels' => $labels5,
		'supports' => array('title', 'custom-fields'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'can_export' => true,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => false,
		'capability_type' => 'post',
		'show_in_rest' => true,

	);
	// Registering your Custom Post Type
	register_post_type('mass_discount', $args5);
    
}
// добавляем ссылку на панель WordPress
function custom_toolbar_link($wp_admin_bar) {
	$args = array(
		'id' => 'wpincode',
		'title' => 'Комментарии', 
		'href' => '/wp-admin/edit-comments.php', 
		'meta' => array(
			'class' => 'wpincode', 
			'title' => 'Комментарии'
			)
	);
	$wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);
 
// /* Добавляем поле сортировки menu_sort к посту */
// add_action( 'admin_init', 'posts_order_wpse_91866' );
// function posts_order_wpse_91866() 
// {
//     add_post_type_support( 'banners', 'page-attributes' );
//     add_post_type_support( 'post', 'page-attributes' );
// }

//для отображения баннеров/попапов
add_action('rest_api_init', function () {
	register_rest_route('popup/v1', '/banner', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'get_pop_json1',
	));
});

function get_pop_json1(WP_REST_Request $request) {
	// получение данных из mapppops
	$posts = get_posts(array(
		'post_type' => 'mapppops',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
	));

	$ar_all_posts = [];

	foreach ($posts as $post) {
		$ar_post = array();
		setup_postdata($post);
		$uuid = $post->ID;
		$title = $post->post_title;
		$message = get_field("message", $post->ID);
		$button_link = get_field("button_link", $post->ID);
		$button_title = get_field("button_title", $post->ID);
		$repeat_mode = get_field("repeat_mode", $post->ID);
		$is_marketing = get_field("marketing", $post->ID);
		if ($is_marketing == true) {
			$is_marketing = true;
		} else {
			$is_marketing = false;
		}

		$disable_ordering = get_field("disable_ordering", $post->ID);
		if ($is_marketing == true) {
			$disable_ordering = false;
		} else if ($is_marketing == false) {
			//echo '-'.$disable_ordering;
			if ($disable_ordering != null && !empty($disable_ordering)) {
				$disable_ordering = get_field("disable_ordering", $post->ID);
			} else {
				$disable_ordering = false;
			}

		}
		//$disable_ordering = get_field( "disable_ordering", $post->ID );

		if ($repeat_mode == 'Повторять') {
			$repeat_mode = 'repeatable';
		} else if ($repeat_mode == 'Один раз') {
			$repeat_mode = 'one-off';
		} else {
			$repeat_mode = 'one-off';
		}

		$sklad = get_field("skladtaxon", $post->ID); //var_dump($sklad);
		$sklad_ar = [];
		foreach ($sklad as $item) {
			//echo $sklad['choices'][$v].'|'; //var_dump($v);
			// if ($item == 110) {
			// 	array_push($sklad_ar, 110);
			// }

			// if ($item == 111) {
			// 	array_push($sklad_ar, 111);
			// }
			array_push($sklad_ar, $item);

		} //echo '---';

		//var_dump($sklad);

		$picture_url = get_the_post_thumbnail_url($post->ID, 'full');
		if (!$picture_url) {
			$picture_url = '';
		}

		$ar_post += ['title' => $title];
		$ar_post += ['message' => $message];
		$ar_post += ['picture_url' => $picture_url];
		$ar_post += ['uuid' => $uuid];
		$ar_post += ['button_link' => $button_link];
		$ar_post += ['button_title' => $button_title];
		$ar_post += ['repeat_mode' => $repeat_mode];
		$ar_post += ['marketing' => $is_marketing];
		$ar_post += ['disable_ordering' => $disable_ordering];
		$ar_post += ['sklad' => $sklad_ar];

		//$ar_all_posts += $ar_post;
		array_push($ar_all_posts, $ar_post);

		//var_dump($ar_post);
	}
	//var_dump($ar_all_posts);
	return $ar_all_posts;
}

/* Hook into the 'init' action so that the function
 * Containing our post type registration is not
 * unnecessarily executed.
 */

add_action('init', 'banners_post_type', 0);

//add_filter('woocommerce_product_needs_shipping', function(){return false;});

add_action("after_setup_theme", function () {

	load_plugin_textdomain('pizzaro', false, get_stylesheet_directory() . '/languages');
	load_theme_textdomain('pizzaro', false, get_stylesheet_directory() . '/languages');

}, 5);

// New order hook
add_action('woocommerce_thankyou', 'fresh_new_order');

function fresh_new_order($order_id) {
	$order = wc_get_order($order_id);
	//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/_ordrs.txt',print_r($order,1)."\n",FILE_APPEND);
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		<?/*
	ga('require', 'ecommerce'); // Подгружаем плагин отслеживания электронной коммерции
	console.log('Send ecommerce');
	// Данные о транзакциях
	ga('ecommerce:addTransaction', {
	'id': '<?php echo $order_id;?>',
	'affiliation': '<?php echo get_option( "blogname" );?>',
	'revenue': '<?php echo $order->get_total();?>',
	'shipping': '<?php echo $order->get_total_shipping();?>',
	'tax': '<?php echo $order->get_total_tax();?>',
	'currency': '<?php echo get_woocommerce_currency();?>'
	});*/?>

	gtag('event', 'purchase', {
		  "transaction_id": "<?php echo $order_id; ?>",
		  "affiliation": "<?php echo get_option("blogname"); ?>",
		  "value": <?php echo $order->get_total(); ?>,
		  "currency": "<?php echo get_woocommerce_currency(); ?>",
		  "tax": <?php echo $order->get_total_tax(); ?>,
		  "shipping": <?php echo $order->get_total_shipping(); ?>,
		  "items": [
	<?
	//Данные о товарах
	if (sizeof($order->get_items()) > 0) {
		foreach ($order->get_items() as $item) {
			$product_cats = get_the_terms($item["product_id"], 'product_cat');
			if ($product_cats) {
				$cat = $product_cats[0];
			}
			/*
					ga('ecommerce:addItem', {
					'id': '<?php echo $order_id;?>',
					'name': '<?php echo $item['name'];?>',
					'sku': '<?php echo get_post_meta($item["product_id"], '_sku', true);?>',
					'category': '<?php echo $cat->name;?>',
					'price': '<?php echo $item['line_subtotal'];?>',
					'quantity': '<?php echo $item['qty'];?>',
					'currency': '<?php echo get_woocommerce_currency();?>'
				});
			*/
			?>
			{
			  "id": "<?php echo $item['id']; ?>",
			  "name": "<?php echo $item['name']; ?>",
			  "category": "<?php echo $cat->name; ?>",
			  "quantity": <?php echo $item['qty']; ?>,
			  "price": '<?php echo $item['line_subtotal']; ?>'
			},
	<?
		}
	}?>
		]
	});
	<?/*
	ga('ecommerce:send');
	console.log('Sended ecommerce');*/?>
	});
		</script>
	<?

}

function new_order($order_id) {

	// $exchange = new Exchange();
	// $exchange->sendOrder($order_id);

	$order = wc_get_order($order_id);
	if (($order->get_status() != 'pending')) {
		SMS::send($order->get_billing_phone(), 'Ваш заказ принят, спасибо. Ожидайте курьера. Родная доставка.');
	}

}


add_filter('woocommerce_checkout_fields', 'prefill_checkout');

function prefill_checkout($fields) {

	$data = WC()->session->get('custom_data');

	$fields['billing']['billing_address_1']['default'] = $data['address_1'];
	$fields['billing']['billing_city']['default'] = $data['city'];

	$fields['shippiing']['shippiing_address_1']['default'] = $data['address_1'];
	$fields['shippiing']['shippiing_city']['default'] = $data['city'];

	return $fields;
}

add_action('init', function () {

});

// if (isset($_GET['phone']) && isset($_GET['sendmessage'])) {
// SMS::send($_GET['phone'], 'Ваш заказ принят. Ждите курьера в течение 30 минут. Родная Доставка.');
// exit();
// }

add_action('woocommerce_rest_product_cat_query', 'filter_function_name_7971', 10, 2);

function filter_function_name_7971($prepared_args) {
	$prepared_args['menu_order'] = 1;

	return $prepared_args;
}

add_action('wp_enqueue_scripts', 'baur_theme_scripts');
add_action('wp_enqueue_scripts', 'baur2_theme_scripts');
function baur2_theme_scripts() {
	wp_deregister_script('jquery-core');
	//wp_register_script( 'jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'); 
	//wp_register_script('jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
	wp_enqueue_script('jquery-core', get_template_directory_uri() . '/assets/libs/jquery/jquery.min.js', array(), '1.0.' . strval(rand(123, 999) ) );

	wp_enqueue_script('jquery');
}
function baur_theme_scripts() {
	wp_enqueue_style('baurCss', get_template_directory_uri() . '/assets/css/baur.css', array(), '1.0.' . strval(rand(123, 999)));
//	wp_enqueue_style('baurCss-1', "https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css", array(), '1.0.0');
	wp_enqueue_script('baurJs', get_template_directory_uri() . '/assets/js/baur.js', array('jquery'), '1.1.' . strval(rand(123, 999)), true);

	if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse') == false)
    	wp_enqueue_script('baurJs-1map', "https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat&amp;apikey=86b68654-96e5-4565-a50b-58ca9d7a9f79", '1.1.0', true);
 
//	wp_enqueue_script('baurJs-1', "https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js", '1.1.0', true);

}

///////////////////mx media connect
add_action('wp_enqueue_scripts', 'mx_theme_scripts');
function mx_theme_scripts() {
	wp_enqueue_style('mxCss', get_template_directory_uri() . '/assets/css/mx.css', array(), '1.0.' . strval(rand(123, 999)));
	wp_enqueue_script('mxJs', get_template_directory_uri() . '/assets/js/mx.js', array('jquery'), '1.0.' . strval(rand(123, 999)), true);
	wp_enqueue_script('jquery-maskedinput', get_template_directory_uri() . '/assets/js/jquery.maskedinput.min.js');
	//wp_enqueue_script( 'mxJsadmin', get_template_directory_uri() . '/assets/js/admin/mx-admin.js', array( 'jquery' ), '1.0.'.strval(rand(123, 999)), true );
}

add_action( 'wp_enqueue_scripts', 'mx_theme_scripts_page' );
function mx_theme_scripts_page() { //echo get_template_directory_uri();
	if ( get_page_template_slug() == 'templates/bonus-template.php' ) { echo 'test2';
  //if ( is_page_template( 'bonus-template' ) ) { echo 'test';
    wp_enqueue_script('mxJs', get_template_directory_uri() . '/assets/js/mx.js', array('jquery'), '1.0.' . strval(rand(123, 999)), true);
  } 
}


add_action('admin_enqueue_scripts', 'mx_admin_media');
function mx_admin_media() {

	// wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

	wp_enqueue_script('admin-script', get_template_directory_uri() . '/assets/js/admin/mx-admin.js', array(), '1.0.' . strval(rand(123, 999)), true);
	wp_enqueue_style('admin-css', get_template_directory_uri() . '/assets/css/admin/mx-admin.css', array(), '1.0.' . strval(rand(123, 999)));

	//wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/style-admin.css');
}

////////////////////////////

///!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function my_awesome_func( WP_REST_Request $request ){ ///имеется баг, нужно проверять/исправлять
$user = get_user_by( 'login',  $request['slug'] );
$have_gift = get_user_meta( $user->ID, 'have_gift', true );
	if ( empty( $have_gift ) )
		return 'false';
 
	return $have_gift;
	//return 'true';
}

add_action('rest_api_init', function () {

	register_rest_route('dostavka/v1', '/customer-gift/(?P<slug>\d+)', [
		'methods' => 'GET',
		'callback' => 'my_awesome_func',
	]);

});

function user_extra_meta_fields() {

	return array(
		'have_gift' => __('Gift', 'yourtext_domain'),

	);

}

function add_contact_methods($contactmethods) {
	$contactmethods = array_merge($contactmethods, user_extra_meta_fields());
	return $contactmethods;
}

add_filter('user_contactmethods', 'add_contact_methods', 10, 1);

//add_action('woocommerce_after_order_notes', 'my_custom_checkout_fieldss');

function my_custom_checkout_fieldss($checkout) {

	foreach (user_extra_meta_fields() as $name => $label) {

		$value = get_user_meta(get_current_user_id(), $name, true);
		if (empty($value)) {
			$value = false;
		}

		woocommerce_form_field($name, array(
			'type' => 'text',
			'class' => array('my-field-class form-row-wide hidden'),
			'label' => $label,
		), $value);

	}
}

add_action('woocommerce_order_status_processing', 'mysite_hold2');
function mysite_hold2($order_id) {

	$order = wc_get_order($order_id);

	$gift_coupons = array();
	foreach ($order->get_coupon_codes() as $coupon_code) {
		// Get the WC_Coupon object
		$coupon = new WC_Coupon($coupon_code);

		$discount_type = $coupon->get_discount_type(); // Get coupon discount type
		$gift_data = array();
		if (!is_wp_error($coupon) && 'free_gift' == $discount_type) {

			$coupon_meta = $coupon->get_meta('_wc_free_gift_coupon_data');
//var_dump($coupon_meta);
			// Only return meta if it is an array, since coupon meta can be null, which results in an empty model in the JS collection.
			$gift_data = is_array($coupon_meta) ? $coupon_meta : array();

			foreach ($gift_data as $gift_id => $gift) {

				//$gift_product = wc_get_product( $gift_id );

				$gift_coupons[$gift_id] = $coupon_code;

			}
			//$gift_coupons[$coupon_code] = $gift_data
		}

	}

	foreach ($order->get_items() as $order_item) {
		$product_id = $order_item->get_product_id();

		if (array_key_exists($product_id, $gift_coupons)) {
			$order_item->add_meta_data('_free_gift', $gift_coupons[$product_id], true);
			//var_dump($product_id);
			//$total = $order_item->get_total();
			$order_item->set_subtotal(0);
			$order_item->set_total(0);
			$order_item->save();

		}
	}

	$order->calculate_totals();
	$order->save();

	// $exchange = new Exchange();
	// $exchange->sendOrder($order_id);

	if (($order->get_status() != 'pending')) {
		$sms_text = get_field('sms-text', 'option');
		SMS::send($order->get_billing_phone(), $sms_text);
	}
}

add_action('woocommerce_order_status_processing', 'mysite_hold');
add_action('woocommerce_order_status_completed', 'mysite_hold');
add_action('woocommerce_order_status_on-hold', 'mysite_hold');
function mysite_hold($order_id) {

// $exchange = new Exchange();
	// $exchange->sendOrder($order_id);

// 1. Get order object
	$order = wc_get_order($order_id);

	// 2. Initialize $cat_in_order variable
	$cat_in_order = false;

	// 3. Get order items and loop through them...
	// ... if product in category, edit $cat_in_order
	$items = $order->get_items();

	foreach ($items as $item) {
		$product_id = $item->get_product_id();
		if (has_term(129, 'product_cat', $product_id)) {
			$cat_in_order = true;
			break;
		}
	}

	$user = $order->get_user();
	if ($user) {
		$user_id = $user->ID;
		if ($cat_in_order == true) {
			update_user_meta($user_id, 'have_gift', 'true');
		} else {
			//update_user_meta( $user_id,  'have_gift', 'false' );
		}
	}

}

add_action('rest_api_init', 'slug_register_purchasing', 1, 2);

function slug_register_purchasing() {
	register_rest_field('product',
		'free_limits',
		array(
			'get_callback' => 'slug_get_purchasing_cost',
			'update_callback' => null,
			'schema' => null,
		)
	);
}

function slug_get_purchasing_cost($object, $field_name, $request) {

	$return = array();

	$values = get_field('free_limis', $object['id']);
	if ($values) {
		arsort($values);
		foreach ($values as $row) {

			$return[] = array('order_value' =>  intval($row['order_value']), 'quantity' => intval($row['quantity']));

		}
	}

	return $return;

}

add_action('rest_api_init', 'slug_register_purchasing2');

function slug_register_purchasing2() {
	register_rest_field('product',
		'recommended_to_category',
		array(
			'get_callback' => 'recommended_to_category',
			'update_callback' => null,
			'schema' => null,
		)
	);
}

function recommended_to_category($object, $field_name, $request) {

	$values = get_field('recommended_to_category', $object['id']);
	if (!$values) {
		$values = array();
	}

	return $values;

}

add_action('rest_api_init', 'slug_register_purchasing3');

function slug_register_purchasing3() {
	register_rest_field('product',
		'recommend_to_product',
		array(
			'get_callback' => 'recommend_to_product',
			'update_callback' => null,
			'schema' => null,
		)
	);
}

function recommend_to_product($object, $field_name, $request) {

	$values = get_field('recommend_to_product', $object['id']);
	if (!$values) {
		$values = array();
	}

	return $values;

}

// BACS payement gateway description: Append custom select field
add_filter('woocommerce_gateway_description', 'gateway_bacs_custom_fields', 20, 2);
function gateway_bacs_custom_fields($description, $payment_id) {
	//
	if ('cod' === $payment_id) {
		ob_start(); // Start buffering

		echo '<div  class="cod-options" style="padding:10px 0;">';

		woocommerce_form_field('cod_details', array(
			'type' => 'text',
			'label' => __("С какой суммы приготовить сдачу?", "woocommerce"),
			'class' => array('form-row-wide'),
			'required' => true,

		), '');

		echo '<div>';

		$description .= ob_get_clean(); // Append buffered content
	}
	return $description;
}

// Checkout custom field validation
add_action('woocommerce_checkout_process', 'bacs_option_validation');
function bacs_option_validation() {
	if (isset($_POST['payment_method']) && $_POST['payment_method'] === 'cod'
		&& isset($_POST['cod_details']) && empty($_POST['cod_details'])) {
		wc_add_notice(__('Пожалуйста укажите сумму с которой нужно приготовить сдачу.'), 'error');
	}
}

// Checkout custom field save to order meta
add_action('woocommerce_checkout_create_order', 'save_bacs_option_order_meta', 10, 2);
function save_bacs_option_order_meta($order, $data) {
	if (isset($_POST['cod_details']) && !empty($_POST['cod_details'])) {
		$order->update_meta_data('cod_details', esc_attr($_POST['cod_details']));
	}
}

// Display custom field on order totals lines everywhere
add_action('woocommerce_get_order_item_totals', 'display_bacs_option_on_order_totals', 10, 3);
function display_bacs_option_on_order_totals($total_rows, $order, $tax_display) {
	if ($order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('cod_details')) {
		$sorted_total_rows = [];

		foreach ($total_rows as $key_row => $total_row) {
			$sorted_total_rows[$key_row] = $total_row;
			if ($key_row === 'payment_method') {
				$sorted_total_rows['cod_details'] = [
					'label' => __("Детали доставки", "woocommerce"),
					'value' => esc_html($cod_details),
				];
			}
		}
		$total_rows = $sorted_total_rows;
	}
	return $total_rows;
}

// Display custom field in Admin orders, below billing address block
add_action('woocommerce_admin_order_data_after_billing_address', 'display_bacs_option_near_admin_order_billing_address', 10, 1);
function display_bacs_option_near_admin_order_billing_address($order) {
	if ($cod_details = $order->get_meta('cod_details')) {
		echo '<div class="cod-option">
        <p><strong>' . __('Детали доставки') . ':</strong> ' . $cod_details . '</p>
        </div>';
	}
}

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields2');

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields2($fields) {
	$fields['billing']['billing_gatetimecheckout'] = array(
		'label' => __('Время доставки', 'woocommerce'),
		'placeholder' => _x('Время доставки', 'placeholder', 'woocommerce'),
		'required' => false,
		'class' => array('form-row-wide'),
		'clear' => true,
	);

	return $fields;
}

// Checkout custom field save to order meta
add_action('woocommerce_checkout_create_order', 'save_bacs_option_order_meta2', 10, 2);
function save_bacs_option_order_meta2($order, $data) {
	if (isset($_POST['billing_gatetimecheckout']) && !empty($_POST['billing_gatetimecheckout'])) {
		$order->update_meta_data('billing_gatetimecheckout', esc_attr($_POST['billing_gatetimecheckout']));
	}
	if (isset($_POST['_shipping_deliv_time']) && !empty($_POST['_shipping_deliv_time'])) {
		$order->update_meta_data('_shipping_deliv_time', esc_attr($_POST['_shipping_deliv_time']));
	}
	if (!empty(WC()->session->get('deliverypoint1C'))) {
		$order->update_meta_data('deliverypoint1C', esc_attr(WC()->session->get('deliverypoint1C')));
	}

}

// Display custom field on order totals lines everywhere
add_action('woocommerce_get_order_item_totals', 'display_bacs_option_on_order_totals2', 10, 3);
function display_bacs_option_on_order_totals2($total_rows, $order, $tax_display) {
	if ($order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('billing_gatetimecheckout')) {
		$sorted_total_rows = [];

		foreach ($total_rows as $key_row => $total_row) {
			$sorted_total_rows[$key_row] = $total_row;
			if ($key_row === 'payment_method') {
				$sorted_total_rows['cod_details'] = [
					'label' => __("Время доставки", "woocommerce"),
					'value' => esc_html($cod_details),
				];
			}
		}
		$total_rows = $sorted_total_rows;
	}
	return $total_rows;
}

/**
 * Display field value on the order edit page
 */

add_action('woocommerce_admin_order_data_after_billing_address', 'display_bacs_option_near_admin_order_billing_address_time', 10, 1);
function display_bacs_option_near_admin_order_billing_address_time($order) {
	$deliv_time = get_post_meta($order->get_id(), '_shipping_deliv_time', true);
	$cod_details = $order->get_meta('billing_gatetimecheckout');
	if (!empty($cod_details)) {
		// если выбрано конкретное время
		echo '<div class="cod-option">
        <p><strong>' . __('Время доставки') . ':</strong> ' . $cod_details . '</p>
        </div>';
	} else if (!empty($deliv_time)) {
		// если не выбрано, то максимально возможное в соответствии с зоной доставки
		$fiveHours = 3600 * 5; //deliver local gmt
		$dateCreated = strtotime((string) $order->get_date_created()) + $fiveHours;
		$delive_timestamp = $dateCreated + intval($deliv_time) * 60;
		$delive_cr = $order->get_date_created();
		$date = date('d.m.Y', $delive_timestamp);
		$time = date('G:i', $delive_timestamp);
		echo '<div class="cod-option">
        <p><strong>' . __('Время доставки') . ':</strong> ' . $date . ', ' . $time . '</p>
        </div>';
	}

}

add_filter('wp_sitemaps_add_provider', 'kama_remove_sitemap_provider', 10, 2);
function kama_remove_sitemap_provider($provider, $name) {
	if (in_array($name, ['users'])) {
		return false;
	}

	return $provider;
}

add_filter('wp_sitemaps_post_types', 'wpkama_remove_sitemaps_post_types');
function wpkama_remove_sitemaps_post_types($post_types) {
	unset($post_types['static_block']);
	unset($post_types['tribe_events']);
	return $post_types;
}

function your_custom_function_name($allcaps, $caps, $args) {
	if (isset($caps[0])) {
		switch ($caps[0]) {
		case 'pay_for_order':

			$order_id = isset($args[2]) ? $args[2] : null;
			$order = wc_get_order($order_id);
			$user = $order->get_user();
			$user_id = $user->ID;

// When no order ID, we assume it's a new order
			// and thus, customer can pay for it
			if (!$order_id) {
				$allcaps['pay_for_order'] = true;
				break;
			}

			$order = wc_get_order($order_id);

			if ($order && ($user_id == $order->get_user_id() || !$order->get_user_id())) {
				$allcaps['pay_for_order'] = true;
			}
			break;
		}
	}

	return $allcaps;
}

add_filter('user_has_cap', 'your_custom_function_name', 10, 3);

add_action('woocommerce_init', function () {
	if (isset(WC()->session)) {
		if (!WC()->session->has_session()) {
			WC()->session->set_customer_session_cookie(true);
		}
	}
});

// function that gets the Ajax data
add_action('wp_ajax_setshippoints', 'setshippoints');
add_action('wp_ajax_nopriv_setshippoints', 'setshippoints');
function setshippoints() {

	if (isset($_POST['deliverypoint1C'])) {
		WC()->session->set('deliverypoint1C', $_POST['deliverypoint1C']);
	}
	echo WC()->session->get('deliverypoint1C');
	die(); // Alway at the end (to avoid server error 500)
}

class woocommerce_menu_with_thumbnails_walker extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$thumbnail_id = get_woocommerce_term_meta($item->object_id, 'thumbnail_id', true);
		$thumbnail_url = wp_get_attachment_url($thumbnail_id);
		$output .= '<li><span><img src="' . $thumbnail_url . '" alt="" /></span><a href="' . $item->url . '">' . $item->title . '</a></li>';
	}
}

//add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_bonus_to_order', 10, 3 );
/*function rest_bonus_to_order( $response, $order, $request ) { //add bonus data in order
//if( empty( $response->data ) ) return $response;
$order_id = $order->get_id();
$order = wc_get_order($order_id);
$total = $order->get_total();

$json = json_decode($request->get_body());
$bonuses = intval($json->bonuses); //bonuses from request
//$response->data['bonusesN'] = $bonuses;
//add_post_meta( $order_id, '_bonuses', $bonuses, true );

//$response->data['total']  = $new_total;
//var_dump($json->meta_data[4]->value);
$lead_time = $json->meta_data[4]->value;
add_post_meta( $order_id, 'lead_time', $lead_time, true );
//foreach($json as $key=>$value){
//  echo $key.'-'.$value.'<br>';
//}

return $response;
}*/


/////////////////////////////
function debug_to_file($cont) {
	//debug info to file
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/adeb.txt')) {
		$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/adeb.txt', 'a+');
		$results = print_r($cont, true);
		fwrite($file, $results . PHP_EOL);
		fclose($file);
	}

}

/*add_filter('woocommerce_rest_orders_prepare_object_query', function(array $args, \WP_REST_Request $request) {
//debug_to_file('test');
print_r($request);
//mail('qashqai911@gmail.com','file', $_SERVER['DOCUMENT_ROOT']);
$data_req = $request->get_param('meta_data');

if (!$data_req) { //debug_to_file('Failed');
return $args;
}

//debug_to_file($data_req);

//$args['date_query'][0]['column'] = 'post_modified';
//$args['date_query'][0]['after']  = $modified_after;

return $args;

}, 10, 2);*/

add_filter('woocommerce_rest_pre_insert_shop_order_object', 'custom_woocommerce_rest_pre_insert_shop_order_object22', 10, 3);
function custom_woocommerce_rest_pre_insert_shop_order_object22(  $order, $request, $creating ){ 
	debug_to_file('custom_woocommerce_rest_pre_insert_shop_order_object22________'.$order->get_id());
	  /* смотрим купоны в запросе */ 
	  $json = json_decode($request->get_body(), true); 
	 
	debug_to_file($request->get_body()); 
	debug_to_file($json);
	return $order;
}


add_filter( 'woocommerce_rest_prepare_shop_order_object', 'custom_filt_post_data', 10, 3 );
function custom_filt_post_data($response, $order, $request) {
	$json = json_decode($request->get_body());
	//debug_to_file($response);
	$order_id = $order->get_id();
	debug_to_file('woocommerce_rest_prepare_shop_order_object___'.$order_id);
	debug_to_file($json);
	debug_to_file($request->get_body()); 

	return $response;
}


//mail('qashqai911@gmail.com','file', $_SERVER['DOCUMENT_ROOT']);

//add_filter( 'woocommerce_checkout_create_order', 'filt_post_data', 10, 1 );
function filt_post_data($order) {
	//debug_to_file($order);
}

////////////////////////
//route for popup

add_action('rest_api_init', function () {
	register_rest_route('popup/v1', '/banner', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'get_pop_json',
	));
});

function get_pop_json(WP_REST_Request $request) {
	// получение данных из mapppops
	$posts = get_posts(array(
		'post_type' => 'mapppops',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
	));

	$ar_all_posts = [];

	foreach ($posts as $post) {
		$ar_post = array();
		setup_postdata($post);
		$uuid = $post->ID;
		$title = $post->post_title;
		$message = get_field("message", $post->ID);
		$button_link = get_field("button_link", $post->ID);
		$button_title = get_field("button_title", $post->ID);
		$repeat_mode = get_field("repeat_mode", $post->ID);
		$is_marketing = get_field("marketing", $post->ID);
		if ($is_marketing == true) {
			$is_marketing = true;
		} else {
			$is_marketing = false;
		}

		if ($repeat_mode == 'Повторять') {
			$repeat_mode = 'repeatable';
		} else if ($repeat_mode == 'Один раз') {
			$repeat_mode = 'one-off';
		} else {
			$repeat_mode = 'one-off';
		}

		$sklad = get_field("skladtaxon", $post->ID); //var_dump($sklad);
		$sklad_ar = [];
		foreach ($sklad as $item) {
			//echo $sklad['choices'][$v].'|'; //var_dump($v);
			// if ($item == 110) {
			// 	array_push($sklad_ar, 110);
			// }

			// if ($item == 111) {
			// 	array_push($sklad_ar, 111);
			// }
			array_push($sklad_ar, $item);

		} //echo '---';

		//var_dump($sklad);

		$picture_url = get_the_post_thumbnail_url($post->ID, 'full');
		if (!$picture_url) {
			$picture_url = '';
		}

		$ar_post += ['title' => $title];
		$ar_post += ['message' => $message];
		$ar_post += ['picture_url' => $picture_url];
		$ar_post += ['uuid' => $uuid];
		$ar_post += ['button_link' => $button_link];
		$ar_post += ['button_title' => $button_title];
		$ar_post += ['repeat_mode' => $repeat_mode];
		$ar_post += ['marketing' => $is_marketing];
		$ar_post += ['sklad' => $sklad_ar];

		//$ar_all_posts += $ar_post;
		array_push($ar_all_posts, $ar_post);

		//var_dump($ar_post);
	}
	//var_dump($ar_all_posts);
	return $ar_all_posts;
}

//////////////////////
/*** настройки купонов и бонусов ***/
// ссылка  /wp-json/wc/v3/coupons-seetings
add_action('rest_api_init', function () {
	register_rest_route('wc/v3', 'coupons-seetings', array(
		'methods' => 'GET',
		'callback' => 'get_coupons_seetings',
	));
});
function get_coupons_seetings() {
	
	$out['disable_point_if_coupon'] = (get_option('rs_disable_point_if_coupon')=="no") ? false : true;
	$out['point_not_award_when_sale_price'] = (get_option('rs_point_not_award_when_sale_price')=="no") ? false : true;
	
	return $out;
}

/*** часы работы ***/
// добавлпние времени работы в rest-api
// ссылка  /wp-json/wc/v3/general-info
add_action('rest_api_init', function () {
	register_rest_route('wc/v3', 'general-info', array(
		'methods' => 'GET',
		'callback' => 'get_openhours',
	));
});
function get_openhours($data) {
	$week_arr = array('Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7);
	$opening_hours = get_field('opening_hours', 'option'); // массив с временем работы из настроек

	$out = array();
	$out['around_clock'] = (get_field('around_clock', 'option') == "true" ? true : false); 
	$out['wp_timezone'] = wp_timezone_string();
	$out['closest_delivery_time_minutes'] = get_field('closest_delivery_time_minutes', 'option');
	$out['cooking_time_in_minutes'] = get_field('cooking_time_minutes', 'option');

	foreach ($opening_hours['week'] as $k => $v) {
		$from_arr = explode(":", $v['from']);
		$from = "{$from_arr[0]}:{$from_arr[1]}";
		$to_arr = explode(":", $v['to']);
		$to = "{$to_arr[0]}:{$to_arr[1]}";
		$out['week'][$week_arr[$k]] = array('from' => $from, 'to' => $to);
	}

	return $out;
}

// ajax проверка открыт ли магазин
add_action('wp_ajax_checkWorkingHours', 'checkWorkingHours_callback');
add_action('wp_ajax_nopriv_checkWorkingHours', 'checkWorkingHours_callback');
function checkWorkingHours_callback() {
	$opening_hours = get_field('opening_hours', 'option'); // массив с временем работы
	$timezone = wp_timezone_string(); // часовой пояс ВП
	$tz = new DateTimeZone($timezone); // часовой пояс
	$dt = new DateTime("now", $tz); // сегодняшняя дата
	$dt2 = new DateTime("now", $tz);

	$day_week = $dt->format("l"); // день недели сегодня
	$day_week_tomorrow = $dt2->modify('+1 day')->format('l'); // день недели завтра

	$open = new DateTime("today {$opening_hours['week'][$day_week]['from']}", $tz); // дата и время открытия сегодня
	$closed = new DateTime("today {$opening_hours['week'][$day_week]['to']}", $tz); // дата и время закрытия сегодня
	$status = (($dt > $open && $dt < $closed)) ? 'open' : 'closed'; // статус открыто/закрыто
	$opening_str = ($dt < $closed) ? $opening_hours['week'][$day_week]['from'] : $opening_hours['week'][$day_week_tomorrow]['from']; // время открытия, сегодняшнее если ещё не открыто или завтрашнее если уже закрыто
	$opening_arr = explode(":", $opening_str);
	$opening = "{$opening_arr[0]}:{$opening_arr[1]}"; // дата открытия без секунд
	$out = array('status' => $status, 'opening' => $opening);
	echo json_encode($out);
	wp_die();
}

// добавление  пункта в меню woocommerce в админке
if (function_exists('acf_add_options_page')) {
	acf_add_options_sub_page(array(
		'page_title' => 'Часы работы',
		'menu_title' => 'Часы работы',
		'parent_slug' => 'sys-custom-settings',
		'menu_slug' => 'woo-acf-settings'
		
	));

}
/*** END часы работы ***/

// Кнопка статистики в профиле
add_action('edit_user_profile', 'show_profile_fields');
add_action('show_user_profile', 'show_profile_fields');
function show_profile_fields($user) {
	if (is_admin()) {
		wp_enqueue_style('components-woocommerce-styles', '/wp-content/plugins/woocommerce/packages/woocommerce-admin/dist/components/style.css');
		wp_enqueue_style('app-woocommerce-styles', '/wp-content/plugins/woocommerce/packages/woocommerce-admin/dist/app/style.css');
		?>
<script>
    jQuery( document ).ready( function ( $ ) {
        jQuery( '<div id="statistics-cont"><button type="button" class="button button-primary button-hero" id="show-statistics" >Показать статистику пользователя</button><div id="statistics"></div></div>' ).insertBefore( jQuery( '#your-profile' ) );
        jQuery( document ).on( 'click', '#show-statistics', function () {
            var btn = jQuery( this );
            jQuery( '#statistics' ).html( '<h2 class="wp-ui-text-icon">Получение данных <i class="fa fa-spinner fa-spin fa-fw"></i></h2>' );
            jQuery( btn ).prop( "disabled", true );
            var data = {
                action: 'get_userstatistics',
                customer_id: <?php echo $user->ID; ?>
            };
jQuery.post( ajaxurl, data, function(response) {
            jQuery( '#statistics' ).html( response );
            jQuery( btn ).prop( "disabled", false );
        });
        } );
    } );
</script>
 <?php
}
}

// ajax получение статистики покупателя
add_action('wp_ajax_get_userstatistics', 'get_userstatistics_callback');
add_action('wp_ajax_nopriv_get_userstatistics', 'get_userstatistics_callback');
function get_userstatistics_callback() {
	$customer_id = (int) $_POST['customer_id'];
	if (!$customer_id) {wp_die();}
	require get_template_directory() . '/inc/customer-statistics.php';
	wp_die();
}

//add_action( 'admin_head', 'action_function_name_6132' );
// конец по статистике

//mail('qashqai911@gmail.com', 'rodn', 'test');

//add_filter( 'wp_insert_post_data', 'meta_time_note', 10, 2 ); // изменения комментария, добавление в него время доставки
/*function meta_time_note( $data, $postarr ){
$gatetime = get_post_meta( $postarr['ID'], 'billing_gatetimecheckout', true );

$isset_time_none = 'Время доставки:';
$pos = strripos($data['post_excerpt'], $isset_time_none);

if ($pos === false && !empty($gatetime)) {
$new_note = $data['post_excerpt'] . '  | Время доставки: '.$gatetime;
$data['post_excerpt'] = $new_note;
}

return $data;
}*/

/////////////////////////email отправка админам для разных складов
//add_filter('woocommerce_email_recipient_new_order', 'cs_conditional_email_recipient', 10, 2);
function cs_conditional_email_recipient($recipient, $order) {
	global $woocommerce;
	if ($order) {
		$check = get_post_meta($order->get_id(), 'stock_id')[0];
		$recipient = get_term_meta((int) $check, 'notince_email', true);
		$recipient = ($recipient) ? $recipient : 'operator.dostavka74@yandex.ru';
	}
	//debug_to_file($check);
	//debug_to_file($recipient);

	return $recipient;
}


/////////////////////////добавление раздела в админку Системный надстройки от ACF
include get_template_directory() . '/inc/mx-modules/acf-settings.php';

//получение системных надстроек приложение
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/app', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'get_systinf_app',
	));
});

function get_systinf_app(WP_REST_Request $request) {
	// информациионные поля для приложения, например, версия

	$ar_all_fields = [];

	$android_ver = floatval(get_field('android_ver', 'option'));
	$ios_ver = floatval(get_field('ios_ver', 'option'));

	$ar_all_fields += ['android_version' => $android_ver];
	$ar_all_fields += ['ios_version' => $ios_ver];

	return $ar_all_fields;
}

//получение системных надстроек стоимость доставки
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/delivery', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'get_systinf_deliv',
	));
});

function get_systinf_deliv(WP_REST_Request $request) {
	// информациионные поля для приложения, например, версия
	$cache = get_cache('cache_get_systinf_deliv');

	if(empty($cache)){
		$ar_all_fields = [];

		$type_calc_deliv = get_field('type_calc_deliv', 'option'); //тип расчёта доставки (расстояние/зоны)
		$type_calc_deliv = 'zone'; //расстояние теперь по другому маршруту
		$sklad_coord = get_field('sklad_coord', 'option'); // координаты склада, откуда доставляют
		$square_coord = get_field('square_coord', 'option'); // координаты области доставки
		$map_dot_start = get_field('dot_map_init', 'option'); // метка на карте
		$default_warehouse_id = get_field('default_warehouse_id', 'option');
		$ar_all_fields['default_warehouse_id'] = $default_warehouse_id; 

		$ar_all_fields['type_calc'] = $type_calc_deliv;
		//$ar_all_fields['sklad_coord'] = $sklad_coord;
		$ar_all_fields['square_coord'] = $square_coord;
		$ar_all_fields['dot_map_init'] = $map_dot_start;

		if ($type_calc_deliv == 'range') {
			//данные для расстояния
			$ar_all_fields['sklad_coord'] = $sklad_coord;
			$deliv_condition = get_field('block-deliv-condition', 'option'); // данные для условий стоимости доставки
			$tmp_cond = [];
			foreach ($deliv_condition as $condit) {
				
				//проход по блокам условий Растояния
				$ar_range = [];
				$ar_range['range'] = $condit['deliv_range_before']; //расстояние
				$ar_range['sklad'] = $condit['skladtaxon']; // склад
				$ar_range['time_deliv'] = $condit['time_deliv']; // время доставки
				$ar_range['sum'] = []; //массив имеет подблоки с разными условиями стоимости заказа
				foreach ($condit['sum_order'] as $calc) {
					//проход по блокам Стоимости заказа
					$ar_range_item = [];
					$ar_range_item['min_sum_order'] = $calc['min_sum_order']; //минимальная сумма заказа
					$ar_range_item['deliv_price'] = $calc['deliv_price']; //стоимость доставки
					array_push($ar_range['sum'], $ar_range_item);
				}
				array_push($tmp_cond, $ar_range);
			}
			$ar_all_fields['conditions'] = $tmp_cond;
		} else if ($type_calc_deliv == 'zone') {
		
			$deliv_condition_zone = get_field('block-deliv-condition-zone', 'option'); // данные для условий стоимости доставки
			$tmp_cond = [];
			foreach ($deliv_condition_zone as $condit) {
				//проход по блокам условий Растояния
				$ar_range = [];
				$ar_range['warehouse_id'] = $condit['skladtaxon']; // название зоны
				$ar_range['zone'] = $condit['deliv_zone_name']; // название зоны
				$ar_range['sklad'] = $condit['skladtaxon']; // склад
				$ar_range['time_deliv'] = $condit['time_deliv_zone']; // время доставки
				$ar_range['sum'] = []; //массив имеет подблоки с разными условиями стоимости заказа
				foreach ($condit['sum_order'] as $calc) {
					//проход по блокам Стоимости заказа
					$ar_range_item = [];
					$ar_range_item['min_sum_order'] = $calc['min_sum_order']; //минимальная сумма заказа
					$ar_range_item['deliv_price'] = $calc['deliv_price']; //стоимость доставки
					array_push($ar_range['sum'], $ar_range_item);
				}
				array_push($tmp_cond, $ar_range);
			}
			$ar_all_fields['conditions'] = $tmp_cond;
			$json_map = get_field('json-txt-map', 'option'); // код карты
			$url_file_map = get_field('file_map_json', 'option'); // файл карты
			$ar_all_fields['url_file_map'] = $url_file_map;
			//$ar_all_fields['map'] = $json_map;

		}

		$cache = set_cache('cache_get_systinf_deliv', $ar_all_fields);
	}

	

	return $cache;
}

/* Cache */

function get_cache($key, $group = 'rest'){
	//return wp_cache_get( $key);
	return get_cache_file($key);
}

function set_cache($key, $data, $group = 'rest'){
	
	//wp_cache_set( $key, $data );
	set_cache_file( $key, $data );

	return $data;
}


function get_cache_file($key){
	$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/'.$key.'.json';
	if(file_exists($path)) {
		$file = file_get_contents($path);
		$json = json_decode($file, true);
		return $json;
	}

	return [];

}

function set_cache_file($key, $content){
	$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/'.$key.'.json';
	//if(file_exists($path)) 
	{
		$file = fopen($path, 'w');
		fwrite($file, json_encode($content));
		fclose($file);
		//return $json;
	}
}

add_action('wp_ajax_remove_cache_files', 'remove_cache_files');
function remove_cache_files(){
	$keys = [
		'cache_get_systinf_deliv',
		'cache_get_payment_meth',		
		'cache_get_shipping_meth',		
		'cache_banners',
	];
	foreach($keys as $key){
		$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/'.$key.'.json';
		unlink($path);
	}

}
/*** Cache ***/

//////////////////////test/////////////////////

add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/delivery2', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'get_systinf_deliv2',
	));
});

function get_systinf_deliv2(WP_REST_Request $request) {
	// информациионные поля для приложения, например, версия

	$ar_all_fields = [];

	$type_calc_deliv = get_field('type_calc_deliv', 'option'); //тип расчёта доставки (расстояние/зоны)
	$sklad_coord = get_field('sklad_coord', 'option'); // координаты склада, откуда доставляют
	$square_coord = get_field('square_coord', 'option'); // координаты области доставки
	$map_dot_start = get_field('dot_map_init', 'option'); // метка на карте
	$default_warehouse_id = get_field('default_warehouse_id', 'option'); // метка на карте

	//$ar_all_fields['type_calc'] = $type_calc_deliv;
	$ar_all_fields['type_calc'] = 'range';
	$ar_all_fields['sklad_coord'] = $sklad_coord;
	$ar_all_fields['square_coord'] = $square_coord;
	$ar_all_fields['dot_map_init'] = $map_dot_start;
	$ar_all_fields['default_warehouse_id'] = $default_warehouse_id; 

	$deliv_condition = get_field('block-deliv-condition', 'option'); // данные для условий стоимости доставки
	$tmp_cond = [];
	foreach ($deliv_condition as $condit) {
		//проход по блокам условий Растояния
		$ar_range = [];
		$ar_range['range'] = $condit['deliv_range_before']; //расстояние
		$ar_range['time_deliv'] = $condit['time_deliv']; // время доставки
		$ar_range['sum'] = []; //массив имеет подблоки с разными условиями стоимости заказа
		foreach ($condit['sum_order'] as $calc) {
			//проход по блокам Стоимости заказа
			$ar_range_item = [];
			$ar_range_item['min_sum_order'] = $calc['min_sum_order']; //минимальная сумма заказа
			$ar_range_item['deliv_price'] = $calc['deliv_price']; //стоимость доставки
			array_push($ar_range['sum'], $ar_range_item);
		}
		array_push($tmp_cond, $ar_range);
	}
	$ar_all_fields['conditions'] = $tmp_cond;

	return $ar_all_fields;
}

/////////////////////////////////////////////

function upd_test_acf() {
	update_field('send_push_count', '4321', 'option');
}

//upd_test_acf();

/////добавление колонки в список заказов админка (без сортировки)
//add_filter('manage_edit-shop_order_columns', 'mx_order_items_column' );
function mx_order_items_column($order_columns) {
	//отображение в таблице
	$order_columns['order_products'] = "Дата готовности U";
	return $order_columns;
}

//add_action( 'manage_shop_order_posts_custom_column' , 'mx_order_items_column_cnt' );
function mx_order_items_column_cnt($colname) {
	//формирование колонки
	global $the_order; // the global order object

	if ($colname == 'order_products') {

		// get items from the order global object
		$order_items = $the_order->get_items();

		$order_id = $the_order->get_ID();
		$ready_time = get_post_meta($order_id, 'billing_gatetimecheckout')[0];
		$ready_time_stamp = strtotime(get_post_meta($order_id, 'billing_gatetimecheckout')[0]);
		echo $ready_time;
		//print_r($ready_time);

		if (!is_wp_error($order_items)) {
			foreach ($order_items as $order_item) {
				//billing_gatetimecheckout

				//echo $order_item['quantity'] .' × <a href="' . admin_url('post.php?post=' . $order_item['product_id'] . '&action=edit' ) . '">'. $order_item['name'] .'</a><br />';
				// you can also use $order_item->variation_id parameter
				// by the way, $order_item['name'] will display variation name too

			}
		}
	}
}

////////////вычисление заказов на сегодня

add_action('woocommerce_after_register_post_type', 'get_today_orders');
function get_today_orders() {
	$cur_time = time();
	$fine_date = date('d-m-Y', $cur_time);
	//$ready_time = date('d-m-Y', '1616792400');
	//echo $cur_time.'<br>';
	//echo date('d-m-Y H:m', $cur_time).'<br>';
	//if($ready_time == $fine_date) echo 'ready<br>';

	$orders = wc_get_orders(array('numberposts' => 1000)); //допустимое количество обрабатываемых заказов, чтоб небыло нагрузки на сервер, можно попробовать больше, но не желательно
	//echo 'count:'.count($orders).'<br>';

	$count_today_order = 0;
	$arr_ord = [];

	foreach ($orders as $order) {
		$ready_time = get_post_meta($order->get_id(), 'ready_time_stamp', true);
		if ($ready_time != '') {
			$ready_time = date('d-m-Y', $ready_time); //echo 'ready: '.$order->get_id().'<br>';
			//echo $fine_date.'<br>';
			//echo 'order_id:'.$order->get_id().' date: '.$ready_time.'<br>';
			if ($ready_time == $fine_date) {
				//echo 'ready: '.$order->get_id().'<br>';
				$count_today_order++;
				array_push($arr_ord, $order->get_id());
			}
		}

	}

	$GLOBALS['arr_order_today'] = $arr_ord;

	$res = [$count_today_order, $arr_ord];

	return $count_today_order;
}

///////////////////////////

////////////////добавление колонки в список заказов админка с сортировкой

// add
add_filter('manage_edit-shop_order_columns', 'mx_ready_time', 20);
// populate
add_action('manage_shop_order_posts_custom_column', 'mx_ready_time_2');
// make sortable
add_filter('manage_edit-shop_order_sortable_columns', 'mx_ready_time_3');
// how to sort
add_action('pre_get_posts', 'mx_ready_time_4');

function mx_ready_time($col_th) {
	//добавление колонки/название

	// a little different way of adding new columns
	return wp_parse_args(array('ready_time' => 'Время готовности'), $col_th);

}

function mx_ready_time_2($column_id) {
	//задать название meta для вывода в колонку

	if ($column_id == 'ready_time') {
		echo get_post_meta(get_the_ID(), 'billing_gatetimecheckout', true);
	}

}

function mx_ready_time_3($a) {
	return wp_parse_args(array('ready_time' => 'by_ready_time'), $a);

}

function mx_ready_time_4($query) {
	//формирование результата запроса

	if(isset($GLOBALS['arr_order_today'])) $order_today = $GLOBALS['arr_order_today']; //массив заказов на сегодня
	else $order_today = '';

	if (!is_admin() || empty($_GET['orderby']) || empty($_GET['order'])) {
		return;
	}

	if ($_GET['orderby'] == 'by_ready_time' && $_GET['cust_type'] != 'today') {
		//сортировка заказов по дате/времени
		//$query->set ( 'post__in', array(28305) );
		$query->set('meta_key', 'ready_time_stamp');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', $_GET['order']);
		wp_reset_postdata();
	} else if ($_GET['orderby'] == 'by_ready_time' && $_GET['cust_type'] == 'today') {
		//если фильтр "на сегодня"
		$query->set('post__in', $order_today);
		$query->set('meta_key', 'ready_time_stamp');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', $_GET['order']);
		//wp_reset_postdata();
	}

	return $query;

}

///////добавление в бд meta время доставки в вычисляемом формате timestamp для сортировки

add_action('woocommerce_checkout_create_order', 'save_meta_readytime_timestamp', 10, 2);
function save_meta_readytime_timestamp($order, $data) {
	if (isset($_POST['billing_gatetimecheckout']) && !empty($_POST['billing_gatetimecheckout'])) {
		$order->add_meta_data('ready_time_stamp', esc_attr(strtotime($_POST['billing_gatetimecheckout'])));
	}
}

///////добавление кнопки типа Фильтр в шапку таблицы заказов
add_action('manage_posts_extra_tablenav', 'admin_order_list_top_bar_button', 20, 1);
function admin_order_list_top_bar_button($which) {
	global $typenow;

	$count_today_order = get_today_orders();

	//$count_today_order = 999;

	if ('shop_order' === $typenow && 'top' === $which && $count_today_order > 0) {

		echo '<a class="button red" href="/wp-admin/edit.php?post_type=shop_order&orderby=by_ready_time&order=desc&cust_type=today">Заказы на сегодня (' . $count_today_order . ')</a>';

	}
}

///тестирование функционала low stock, остановка покупок при достижении товара определённого остатка

function test_low_stock() {
	$product = new WC_Product(28260); //борщ
	do_action('woocommerce_low_stock', $product);
	//debug_to_file('do low stock action');
}

add_action('woocommerce_low_stock', 'test_action_lowstock', 10, 1);
function test_action_lowstock($product) {
	//debug_to_file('low stock action');
}

//test_low_stock();

add_action('woocommerce_reduce_order_stock', 'test_stock_change');
function test_stock_change($order) {
	//debug_to_file('stock change action');
}

//mail('qashqai911@gmail.com', 'testmail', 'test');

//add_action( 'woocommerce_process_product_meta', 'save_low_stock_status', 20 );
//add_action( 'save_post', 'save_low_stock_status', 999 );

////////////////////////////////////////////////////////////////////

function admin_users_filter($query) {
	global $pagenow, $wp_query;

	if (is_admin() && $pagenow == 'users.php' && isset($_GET['billing_phone']) && $_GET['billing_phone'] != '') {
		$query->search_term = $_GET['billing_phone'];

		global $wpdb;

		if (!is_null($query->search_term)) {

			$query->query_from .= " INNER JOIN {$wpdb->usermeta} ON " .
				"{$wpdb->users}.ID={$wpdb->usermeta}.user_id AND " .
				"{$wpdb->usermeta}.meta_key='billing_phone' AND " . "{$wpdb->usermeta}.meta_value LIKE '%{$query->search_term}%'";

		}
	}
}

//add_filter( 'pre_user_query', 'admin_users_filter' );

//add_action( 'restrict_manage_users', 'restrict_abc_manage_list' );
function restrict_abc_manage_list() {
	?>
<select name="billing_phone" style="float: none;">
    <option value=""><?php _e('Filter Phone', 'baapf');?></option>
    <option value="1">79191232340</option>
    <option value="2">340</option>
    <option value="3">3</option>

 </select>
 <input id="post-query-submit" class="button" type="submit" value="Filter" name="">
<?php
}

/////////////////Новые типы товаров и их вывод
// add a product type
add_filter('product_type_selector', 'add_supplements_options_product_type');
function add_supplements_options_product_type($types) {
	$types['supplements_options'] = __('Опциональный товар');
	return $types;
}

add_action('init', 'supplements_options_create_product_class');
add_filter('woocommerce_product_class', 'supplements_options_load_product_class', 10, 2);

function supplements_options_create_product_class() {
	class WC_Product_Supplements_Options extends WC_Product {
		public function get_type() {
			return 'supplements_options'; // so you can use $product = wc_get_product(); $product->get_type()
		}
	}
}

function supplements_options_load_product_class($php_classname, $product_type) {
	if ($product_type == 'supplements_options') {
		$php_classname = 'WC_Product_Supplements_Options';
	}
	return $php_classname;
}

// add a product type
add_filter('product_type_selector', 'add_supplements_product_type');
function add_supplements_product_type($types) {
	$types['supplements'] = __('Групповой товар');
	return $types;
}

add_action('init', 'supplements_create_product_class');
add_filter('woocommerce_product_class', 'supplements_load_product_class', 10, 2);

function supplements_create_product_class() {
	class WC_Product_Supplements extends WC_Product {
		public function get_type() {
			return 'supplements'; // so you can use $product = wc_get_product(); $product->get_type()
		}
	}
}

function supplements_load_product_class($php_classname, $product_type) {
	if ($product_type == 'supplements') {
		$php_classname = 'WC_Product_Supplements';
	}
	return $php_classname;
}

// Вывод типа продукта в истории заказов rest
function product_type_wc_rest_prepare_order($response, $order, $request) {

	$order_data = $response->get_data();

	foreach ($order_data['line_items'] as $key => $item) {
		$product_id = $item['product_id'];
		$order_data['line_items'][$key]['product_type'] = WC_Product_Factory::get_product_type($product_id);
	}

	$response->data = $order_data;
	return $response;
}

add_filter('woocommerce_rest_prepare_shop_order_object', 'product_type_wc_rest_prepare_order', 10, 3);
function wh_variable_bulk_admin_custom_js() {

	if ('product' != get_post_type()):
		return;
	endif;
	?>
     <script type='text/javascript'>
        jQuery(document).ready(function () {
            //Групповой
            jQuery('.product_data_tabs .general_tab').addClass('show_if_supplements').show();
            jQuery('#general_product_data .pricing').addClass('show_if_supplements').show();
             //Опциональный
            jQuery('.product_data_tabs .general_tab').addClass('show_if_supplements_options').show();
            jQuery('#general_product_data .pricing').addClass('show_if_supplements_options').show();
		      //Простой товар
            jQuery('.product_data_tabs .general_tab').addClass('show_if_simple').show();
            jQuery('#general_product_data .pricing').addClass('show_if_simple').show();
        });
    </script>
    <?php

}

add_action('admin_footer', 'wh_variable_bulk_admin_custom_js');

/////////////Новый таб  в редактировании товара
add_filter('woocommerce_product_data_tabs', 'add_kkal_product_data_tab');
function add_kkal_product_data_tab($tabs) {
	$tabs['mx-kkal-tab'] = array(
		'label' => 'Ккал',
		'target' => 'mx_kkal_product_data',
	);
	
	$tabs['mx-time-tab'] = array(
		'label' => 'Время',
		'target' => 'mx_time_product_data',
	);
	
	return $tabs;	
}

// Create Product Data Tab Content
add_action('woocommerce_product_data_panels', 'add_kkal_product_data_fields');
function add_kkal_product_data_fields() {
	echo '<div id="mx_kkal_product_data" class="panel woocommerce_options_panel"></div>';
	echo '<div id="mx_time_product_data" class="panel woocommerce_options_panel"></div>';
}

//////////Самовывоз
include get_template_directory() . '/inc/mx-modules/samovivoz.php';

//////////Push уведомления
include get_template_directory() . '/inc/mx-modules/push.php';

//////////Мета-поля заказа в комментарий
include get_template_directory() . '/inc/mx-modules/order-comment.php';

//////////Фильтр для события Обновление acf поля, обновление настроек бонусов
include get_template_directory() . '/inc/mx-modules/bonus-settings.php';

//////////Фильтр для события Обновление acf поля, обработка бонусов
include get_template_directory() . '/inc/mx-modules/bonus.php';

////Контактный телефон доставки

add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1/contacts', '/phone', array( 
		'methods' => 'GET',
		'callback' => 'mx_contact_phone_number',
	));
});

function mx_contact_phone_number(WP_REST_Request $request) {
	$contact_phone = get_field('contact_phone', 'option');

	return $contact_phone;
}


////Все контактные данные
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1/contacts', '/all', array( 
		'methods' => 'GET',
		'callback' => 'mx_contact_all',
	));
});

function mx_contact_all(WP_REST_Request $request) {
	$contact_soc_arr = get_field('kont-social', 'option');
	$contact_soc_vk = $contact_soc_arr['kont-social-vk'];
	$contact_soc_inst = $contact_soc_arr['kont-social-inst'];
	$contact_soc_fb = $contact_soc_arr['kont-social-fb'];
	
	$contact_arr = [];
	$contact_arr['phone'] = get_field('contact_phone', 'option');
	$contact_arr['vk-link'] = $contact_soc_vk;
	$contact_arr['inst-link'] = $contact_soc_inst;
	$contact_arr['fb-link'] = $contact_soc_fb;
	$contact_arr['website-link'] = get_field('kont-website', 'option');
	$contact_arr['email'] = get_field('kont-email', 'option');
	//echo $contact_soc_vk.' '.$contact_soc_inst.' '.$contact_soc_fb;

	return $contact_arr;
}
///////////////////

//получение точки самовывоза из заказа через приложение и запись в бд
add_filter('woocommerce_rest_prepare_shop_order_object', 'rest_meta_pickup', 10, 3);
function rest_meta_pickup($response, $order, $request) {
	$order_id = $order->get_id();
	$json = json_decode($request->get_body());
	$local_pickup_name = $json->shipping_lines[0]->pickup_point;
	add_post_meta($order_id, 'local_pickup_name', $local_pickup_name, true); //точка самовывоза

	return $response;
}

//отладка
add_filter('woocommerce_rest_prepare_shop_order_object', 'rest_get_request', 10, 3);
function rest_get_request($response, $order, $request) {
	$json = json_decode($request->get_body());
	//debug_to_file($json);

	return $response;
}

////вывод фавикона
function add_my_favicon() {
	$favicon_path = get_field('gr_favicon_site', 'option');

	if ($favicon_path['favicon_type'] == 'svg') {
		$favicon_path = $favicon_path['favicon_svg'];
	} else if ($favicon_path['favicon_type'] == 'png') {
		$favicon_path = $favicon_path['favicon_png'];
	}

	echo '<link rel="shortcut icon" href="' . esc_url($favicon_path) . '" />';
}

add_action('wp_head', 'add_my_favicon'); //front end
add_action('admin_head', 'add_my_favicon'); //admin end

//////////Попапы
include get_template_directory() . '/inc/mx-modules/popup.php';

////api методы оплаты
include get_template_directory() . '/inc/mx-modules/restapi/payment-methods.php';

////api методы доставки
include get_template_directory() . '/inc/mx-modules/restapi/shipping-methods.php';

///дополнительные поля в профиле пользователя
include get_template_directory() . '/inc/mx-modules/user-extra-fields.php';

//////////авторизация по номеру телефона
include get_template_directory() . '/inc/mx-modules/auth-sms.php';

////передача данных пользователя, из/в приложение
include get_template_directory() . '/inc/mx-modules/restapi/set-get-userinfo.php';

//////////стоимость доставки в зависимости от адреса на карте(зона/расстояние)
include get_template_directory() . '/inc/mx-modules/deliv-cost-by-map.php'; 

////запись данных о товарах и категориях в json для приложения
include get_template_directory() . '/inc/mx-modules/product-data-json.php';

////интеграция с курьерами
include get_template_directory() . '/inc/mx-modules/courier_app_exchange.php';


add_filter('woocommerce_rest_prepare_product_object', 'custom_change_product_response2', 20, 3);
//add_filter('woocommerce_rest_prepare_product_variation_object', 'custom_change_product_response2', 20, 3);

function custom_change_product_response2($response, $object, $request) {
    $variations = $response->data['variations'];
    $variations_res = array();
    $variations_array = array();
	$variationList = [];
	if (!empty($variations) && is_array($variations)) {
		$curl = curl_init( 'https://'.$_SERVER['SERVER_NAME'].'/wp-json/wc/v3/products/'.$response->data['id'].'/variations/?consumer_key=ck_8e9043f849e95e6d003c3cc2474fc22b2ed01eec&consumer_secret=cs_74c746f821c405606c0950997a33b194ffc06876' );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($curl, CURLOPT_HEADER, false);
		 curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);
	$response2 = curl_exec( $curl );
	curl_close( $curl );
		$variationList = json_decode($response2, true);
		//фикс, а то поля не совпадали
		foreach($variationList as &$vl){
			$vl['in_stock'] = ( $vl['stock_status'] == 'instock') ? true : false;
			unset($vl['stock_status']);
		}
	}

    // if (!empty($variations) && is_array($variations)) {
    //     foreach ($variations as $variation) {
    //         $variation_id = $variation;
    //         $variation = new WC_Product_Variation($variation_id);
    //         $variations_res['id'] = $variation_id;
    //         $variations_res['on_sale'] = $variation->is_on_sale();
    //         $variations_res['regular_price'] = (float)$variation->get_regular_price();
    //         $variations_res['sale_price'] = (float)$variation->get_sale_price();
    //         $variations_res['sku'] = $variation->get_sku();
    //         $variations_res['description'] = $variation->get_description();
    //         $variations_res['stock_status'] = $variation->get_stock_status();
    //         $variations_res['menu_order'] = $variation->get_menu_order();			
    //         //$variations_res['image'] = $variation->get_image();

    //         $variations_res['quantity'] = $variation->get_stock_quantity();
    //         if ($variations_res['quantity'] == null) {
    //             $variations_res['quantity'] = '';
    //         }
    //         $variations_res['stock'] = $variation->get_stock_quantity();

    //         $attributes = array();
    //         // variation attributes
    //         foreach ( $variation->get_variation_attributes() as $attribute_name => $attribute ) {
    //             // taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
    //             $attributes[] = array(
    //                 'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ), $variation ),
    //                 'slug'   => str_replace( 'attribute_', '', wc_attribute_taxonomy_slug( $attribute_name ) ),
    //                 'option' => $attribute,
    //             );
    //         }

    //         $meta_data = array();
    //         // variation attributes

    //         $variations_res['attributes'] = $attributes;
    //         $variations_array[] = $variations_res;
    //     }
    // }
	
    $response->data['product_variations_data'] = $variationList;
	//$response->data['product_variations'] = $variations_array;

    return $response;
}




//сохранение информации пользователя в личном кабинете, файл form-edit-account
add_action('woocommerce_save_account_details', 'save_my_account_details', 10, 1);
function save_my_account_details($user_id) {
	//if ( !current_user_can( 'edit_user', $user_id ) )
	//    return false;
	if ($_POST['user-extra-sex']) {
		update_usermeta($user_id, 'user_sex', $_POST['user-extra-sex']);
	}

	if ($_POST['user-date-birth']) {
		update_usermeta($user_id, 'user_birth', $_POST['user-date-birth']);
	}

	if ($_POST['account_first_name']) {
		update_usermeta($user_id, 'billing_first_name', $_POST['account_first_name']);
	}

}

//убрать обязательное заполнение полей в аккаунте
//add_filter('woocommerce_save_account_details_required_fields', 'myaccount_required_fields');
function myaccount_required_fields($account_fields) {
	unset($account_fields['account_last_name']);
	return $required_fields;

}

////сохранять номер телефона пользователя, даже если в заказе был указан другой номер
/*function origin_user_phone(){
$origin_phone = get_user_meta($user_id, 'origin_phone_number')
}*/

add_action('save_post', 'origin_user_phone', 10, 3);
function origin_user_phone($post_id, $post, $update) {
	$post_type = get_post_type($post_id);
	if ($post_type == 'shop_order') {
		$order = new WC_Order($post_id);
		$user_id = $order->user_id;
		//debug_to_file($post);
		debug_to_file('user id: ' . $user_id);
		$phone_origin = get_user_meta($user_id, 'billing_phone', true);
		debug_to_file('user origin phone: ' . $phone_origin);
		update_user_meta($user_id, 'billing_order_phone', $phone_origin);
		debug_to_file('order phone after: ' . get_user_meta($user_id, 'billing_order_phone', true));
	}
}


////статус заказа для приложения
include get_template_directory() . '/inc/mx-modules/order-status-app.php';


/*
////переименовать вкладку Отзывы на странице товара
add_filter( 'woocommerce_product_tabs', 'rename_additional_info_tab' );
function rename_additional_info_tab( $tabs ) {
	$tabs['reviews']['title'] = 'Отзывы';
	return $tabs;

}
*/

////перенести данные файла json для карты из acf в подключаемый файл
add_action( 'wp_footer', 'sync_file_map_acf', 10 );
function sync_file_map_acf(){
	
	if ( is_checkout() ) { //обновление файла выполняется если страница оформления //debug_to_file('is checkout page');
		$from_file_path = get_field('file_map_json', 'option'); //debug_to_file('from path: '.$from_file_path);
		$to_file_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/func_file/map_delivery.json'; //debug_to_file('to path: '.$to_file_path);

		if ($from_file_path){ //debug_to_file('exist file map');
			$from_file_cont = file_get_contents($from_file_path);
			$to_file = fopen($to_file_path, 'w');
			fwrite($to_file, $from_file_cont);
			fclose($to_file);
		}
	}
}


////связь настроек Часы доставки с плагином Date Time Picker
include get_template_directory() . '/inc/mx-modules/work-time-order.php';



// не объединять одинаковые товары в один 
function bbloomer_split_product_individual_cart_items( $cart_item_data, $product_id ){
  $unique_cart_item_key = uniqid();
  $cart_item_data['unique_key'] = $unique_cart_item_key;
  return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'bbloomer_split_product_individual_cart_items', 10, 2 );

// отключить поле с количеством
// add_filter( 'woocommerce_is_sold_individually', '__return_true' );



add_filter( 'auth_cookie_expiration',  'cookie_expiration_new', 20, 3 );
function cookie_expiration_new ( $expiration, $user_id, $remember ) {
    // Время жизни cookies для администратора
    if ( $remember && user_can( $user_id, 'manage_options' ) ) {
        // Если установлена галочка
        if ( $remember == true ) {
            return 20 * DAY_IN_SECONDS;
        }

        // Если не установлена
        return 5 * DAY_IN_SECONDS;
    }
    // Для всех остальных пользователей
    // Если установлена галочка
    if ( $remember == true ) {
        return 360 * DAY_IN_SECONDS;
    }

    // Если не установлена
    return 180 * DAY_IN_SECONDS;
}


add_action( 'admin_init', 'shop_manager_user_editing_capability');
function shop_manager_user_editing_capability() {
    $shop_manager = get_role( 'shop_manager' );
    $shop_manager->add_cap( 'create_users' );
    //$shop_manager->add_cap( 'edit_user' );
}


//////////оценка заказов
include get_template_directory() . '/inc/mx-modules/rate-order.php';


/////бэйджи для товаров
add_action( 'woocommerce_before_shop_loop_item_title', 'product_loop_custom_labels', 10 );
function product_loop_custom_labels(){
	global $product;
	//echo $product->id;
	$labels = get_field( "product-badge", $product->id );
	if(in_array("hit", $labels)) echo '<div class="label-badge label-hit">хит</div>';
	if(in_array("spicy", $labels)) echo '<div class="label-badge label-spicy"></div>';
	if(in_array("vegan", $labels)) echo '<div class="label-badge label-vegan"></div>';
	if(in_array("preorder", $labels)) echo '<div class="label-badge label-preorder">предзаказ</div>';
	//var_dump($labels);
}

add_action( 'woocommerce_product_thumbnails', 'product_single_custom_labels', 10 );
function product_single_custom_labels(){
	global $product;
	$labels = get_field( "product-badge", $product->id );
	if(in_array("hit", $labels)) echo '<div class="label-badge label-hit">хит</div>';
	if(in_array("spicy", $labels)) echo '<div class="label-badge label-spicy"></div>';
	if(in_array("vegan", $labels)) echo '<div class="label-badge label-vegan"></div>';
	if(in_array("preorder", $labels)) echo '<div class="label-badge label-preorder">предзаказ</div>';
}


////автокупоны
include get_template_directory() . '/inc/mx-modules/auto-coupon.php';

////акции
require get_template_directory() . '/inc/mx-modules/promo.php';


/////////Добавление колонки Оценка заказа в админку список заказов
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns)
{
    $reordered_columns = array();

    foreach( $columns as $key => $column){
        $reordered_columns[$key] = $column;
        if( $key ==  'order_status' ){
            // после колонки Статус
            $reordered_columns['rate-order'] = __( 'Оценка заказа','theme_domain');
        }
    }
    return $reordered_columns;
}

// данные для колонки
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id )
{
    switch ( $column )
    {
        case 'rate-order' :
            // Get custom post meta data
            $rate_order = get_post_meta( $post_id, 'rate_order', true );
            if(!empty($rate_order)){
				if($rate_order == '5') echo '<span class="rate-green">' . $rate_order . '</span>';
				if($rate_order == '4') echo '<span class="rate-blue">' . $rate_order . '</span>';
				if($rate_order == '3') echo '<span class="rate-orange">' . $rate_order . '</span>';
				if($rate_order == '2') echo '<span class="rate-red">' . $rate_order . '</span>';
				if($rate_order == '1') echo '<span class="rate-red1">' . $rate_order . '</span>';
			}

            // Empty value case
            else
                echo '<small>(<em>нет оценки</em>)</small>';

            break;
    }
}

//////////Добавление колонки в таблицу Клиенты
///////////////

////Удаляем лишний символ " - " для вариативного товара
add_filter( 'woocommerce_get_price_html', 'change_html_price_variable', 100, 2 );
function change_html_price_variable( $price, $product ){
	if($product->get_type() == 'variable'){ //debug_to_file($product->get_type());
		$price = str_replace(' – ', '', $price);
	}

	return $price;
}


/////минимальная сумма корзины
add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' ); //для корзины
add_action( 'woocommerce_before_checkout_form', 'wc_minimum_order_amount' );  //для страницы оформления
function wc_minimum_order_amount() {
    $minimum = get_option( 'woocommerce_store_min_amount' ); //минимальная сумма корзины
 
    if ( WC()->cart->total < $minimum ) {
        if( is_cart() ) {
            wc_print_notice( 
                sprintf( 'Минимальная сумма корзины должна быть %s, текущая сумма %s.' , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->total )
                ), 'error' 
            );
        } else {
            wc_add_notice( 
                sprintf( 'Минимальная сумма корзины должна быть %s, текущая сумма %s.' , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->total )
                ), 'error' 
            );
        }
		remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 ); //отключения кнопки Оформить в корзине	
    }
 
} 

///автоматическое обновление отзыва товара после публикации отзыва(правка бага)
function after_comment_update($comment_id){
	$commentarr = [
		'comment_ID'      => $comment_id,
		'comment_approved' => 1,
	];
	
	$res = wp_update_comment( $commentarr, true );
	
	return $res;
}

add_action( 'wp_insert_comment', 'sh_com_upd', 10, 1 );
function sh_com_upd($comment_id) { debug_to_file('function schedule upd comment: '.$comment_id);
	if( ! wp_next_scheduled( 'schedule_upd_comment' )) {
		wp_schedule_single_event( time() + 5, 'schedule_upd_comment', array($comment_id) ); //3600 = 1 час с текущего момента
	}
}
add_action( 'schedule_upd_comment', 'after_comment_update', 10, 1 );
////////////////////////////


////отладка хуков
function dump_hook( $tag, $hook ) {
    ksort($hook);

    debug_to_file(">>>>>\t<strong>$tag</strong><br>");

    foreach( $hook as $priority => $functions ) {

	debug_to_file($priority);

	foreach( $functions as $function )
	    if( $function['function'] != 'list_hook_details' ) {

		echo "\t";

		if( is_string( $function['function'] ) )
		    debug_to_file($function['function']);

		elseif( is_string( $function['function'][0] ) )
		    debug_to_file($function['function'][0] . ' -> ' . $function['function'][1]);

		elseif( is_object( $function['function'][0] ) )
		    debug_to_file("(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1]);

		else
		    debug_to_file($function);

		debug_to_file( ' (' . $function['accepted_args'] . ') <br>');
		}
    }

    echo '';
}

function list_hooks( $filter = false ){
	global $wp_filter;

	$hooks = $wp_filter;
	ksort( $hooks );

	foreach( $hooks as $tag => $hook )
	    if ( false === $filter || false !== strpos( $tag, $filter ) )
			dump_hook($tag, $hook);
}


////////////testspeedload
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/testspeedload', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'test_speed_load',
	));
});

function test_speed_load(WP_REST_Request $request) {
	// информациионные поля для приложения, например, версия

	$test = 1;
	
	return $test;
}
///////////


// увеличение времени доставки для конкретного товара
function get_increase_delivery_time($id){
$cooking_time_minutes = intval(get_field('cooking_time_minutes', 'option') );	
$cooking_time = intval(get_field('cooking_time', $id) );
	if( !$cooking_time_minutes || !$cooking_time || $cooking_time <=$cooking_time_minutes ){
		return false;
	}
	
return 	$cooking_time - $cooking_time_minutes;	
}

function format_increase_delivery_time($time){
return '<span class="IncreaseDeliveryTime" title="Срок доставки увеличится на '.$time.' минут"><span class="wc-sa-icon-unif1db"></span> +'.$time.' мин.</span>';
}

// время доставки для корзины
function get_cart_delivery_time( $cart ) {
	$delivery_time = intval( get_field( 'closest_delivery_time_minutes', 'option' ) ); // стандартное время	доставки
	$cooking_time = intval( get_field( 'cooking_time_minutes', 'option' ) ); // стандартное время	приготовления
	$minutes = array( 0 );
	foreach ( $cart as $cart_item ) {
		$minuta = intval( get_field( 'cooking_time', $cart_item[ 'product_id' ] ) ); // время приготовления
		if ( $minuta && $minuta > $cooking_time ) {
			$minutes[] = $minuta - $cooking_time;
		}
	}
$plus = max($minutes);
return 	$delivery_time + $plus;
}

// время доставки для заказа
add_action( 'woocommerce_update_order', 'action_function_name_8763' );
function action_function_name_8763( $order_id ){
$order = wc_get_order( $order_id );
	
$deliv_time = get_post_meta($order->get_id(), '_shipping_deliv_time', true);
	
	$delivery_time = intval( get_field( 'closest_delivery_time_minutes', 'option' ) ); // стандартное время	доставки
	$cooking_time = intval( get_field( 'cooking_time_minutes', 'option' ) ); // стандартное время	приготовления
	$minutes = array( 0 );
foreach ( $order->get_items() as $item_id => $item_values ) {
		$minuta = intval( get_field( 'cooking_time', $item_values->get_product_id() ) ); // время приготовления
		if ( $minuta && $minuta > $cooking_time ) {
			$minutes[] = $minuta - $cooking_time;
		}
}
$plus = max($minutes); 
$dt = $delivery_time + $plus ;
	if( $deliv_time != $dt ){
$order->update_meta_data('_shipping_deliv_time', $dt );
$order->save();
	}
}



///привязка методов оплаты, в зависимости от метода доставки
if ( ! class_exists('WC_Shipping_Calc')){
    class WC_Shipping_Calc{
        public function __construct(){

            add_filter('woocommerce_get_sections_shipping', 
            	array( $this, 'add_shipping_settings_section_tab') );

            add_filter( 'woocommerce_get_settings_shipping', 
            	array( $this, 'add_shipping_settings'), 10, 2 );

            add_filter( 'woocommerce_available_payment_gateways', 
            	array( $this, 'update_available_payment_gateways') );
        }

        private function get_chosen_shipping_method_ids() {
						$method_ids     = array();
						$chosen_methods = WC()->session->get( 'chosen_shipping_methods', array() );
						foreach ( $chosen_methods as $chosen_method ) {
								$chosen_method = explode( ':', $chosen_method );
								$method_ids[]  = current( $chosen_method );
						}
						return $method_ids;
				}

        public function add_shipping_settings_section_tab( $section ){
            $section['custom_shipping_calc'] = __('Привязка методов оплаты', 'shipping-calculator');

            return $section;
        }

        public function update_available_payment_gateways( $available_gateways )
        {
        		if( is_admin() || !isset(WC()->session) || !empty($_GET['mobileapp']) ) {
								return $available_gateways;
						}

						$chosen_shipping_methods_ids = $this->get_chosen_shipping_method_ids();

						$custom_available_gateways = get_option('custom_shipping_' . $chosen_shipping_methods_ids[0]);

						foreach ($available_gateways as $payment_gateway_key => $payment_gateway) {
							if ( !in_array($payment_gateway_key, $custom_available_gateways) ) {
								unset($available_gateways[$payment_gateway_key]);
							}
						}

						return $available_gateways;
        }

        public function add_shipping_settings( $settings, $current_section )
        {
        	if ( $current_section == 'custom_shipping_calc' ) {
        		$WC_Shipping = new WC_Shipping();
        		$shipping_methods = $WC_Shipping->get_shipping_methods();

        		$payment_gateways = WC()->payment_gateways->payment_gateways();

        		$payment_gateways_options = [];
    		    foreach ($payment_gateways as $payment_gateway_id => $payment_gateway) {
    		    	$payment_gateways_options[$payment_gateway_id] = $payment_gateway->title ?: $payment_gateway->method_title;
    				}

        		$settings = [];

        		$settings[] = [
        			'name' => __( 'Привязка методов оплаты', 'text-domain' ), 
        			'type' => 'title', 
        			'desc' => __( 'Привязка методов оплаты, в зависимости от метода доставки'. $ids, 'text-domain' ), 
        			'id' => 'custom_shipping_calc' 
        		];

        		foreach ($shipping_methods as $shipping_method) {
        			if (isset($shipping_method->id)) {

      				  $settings[] = [
        					'name'     => __( $shipping_method->method_title, 'text-domain' ),
        					'type'		 => 'multiselect',
        					'class'		 => 'select2',
        					'id'			 => 'custom_shipping_'.$shipping_method->id,
        					'options'  => $payment_gateways_options
        				];

        			}
        		}

        		$settings[] = [
        			'type' => 'sectionend', 
        			'id' => 'custom_shipping_calc'
        		];

        		?>
				    <script type='text/javascript'>
				        jQuery(function($){
				            $(document).ready(function(){
 											$('select.select2').attr('multiple', 'multiple').select2();
				            });
				        });
				    </script>
				    <?php

        		return $settings;
        	}

        	return $settings;
        }

    }
    $GLOBAL['wc_shipping_calc'] = new WC_Shipping_Calc();
}
///////////////////////////



/// fix woocommerce_update_order_review_fragments
function update_order_review_fragments($array) {
	$array['.woocommerce-checkout-payment'] .= "<script>if($('#order_review .woocommerce-shipping-totals').html() != ''){var previous_selected_name = $('#wrap-clone-deliv #shipping_method input:checked').attr('name');$('#wrap-clone-deliv .woocommerce-shipping-totals').remove();$('#order_review .woocommerce-shipping-totals').clone(true).appendTo('#wrap-clone-deliv');$('#wrap-clone-deliv #shipping_method input[name='+previous_selected_name+']').attr('checked', 'checked');$('#order_review .woocommerce-shipping-totals').empty();}</script>";
	
	return $array;
}
add_filter( 'woocommerce_update_order_review_fragments', 'update_order_review_fragments' );
//////////////////////////


///добавляем поле delivery_time в статус заказа (Rest API)
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/orderstatus', array( //регистрация маршрута /(?P<status>\w+)
		'methods' => 'GET',
		'callback' => 'get_order_status',
	));
});

function get_order_status(WP_REST_Request $request) {
	$user_id = $request['customer'];
	$limit = $request['limit'];

	$args = array(
		'customer_id' => $user_id,
		'limit' => $limit
	);
	$orders = wc_get_orders($args);

	$order_id = 0;
	$order_status = false;
	$arr_orders_data = array();
	$tmp_arr = array();

	function get_time($billing_gatetimecheckout) {
		$replaced_str = preg_replace('/[^0-9\:]/', '', $billing_gatetimecheckout);

		if ($replaced_str[0] == ":") {
			$time_str = substr($replaced_str, 9);
		} else {
			$time_str = substr($replaced_str, 8);
		}

		return str_replace(' ', '-', trim(chunk_split($time_str, 5, ' ')));
	}

	foreach (array_reverse($orders) as $item) {
		$status = $item->get_status();
		$rate = $item->get_meta('rate_order');
		$orderstatus_hide = $item->get_meta('orderstatus_hide');		

		if ($rate || $orderstatus_hide || !in_array($status, ['processing', 'making', 'done', 'kurier', 'wait-stock', 'completed'])) continue;


		$tmp_date = $item->get_date_created(); 
		$order_date = (array)$tmp_date;
		$order_date_created = $order_date['date'];

		if ($status == 'completed') {			
			$tmp_date = $item->get_date_modified(); 
			$order_date = (array)$tmp_date;
			$order_date_modified = $order_date['date'];

			$od_created = strtotime($order_date_created);
			$od_modified = strtotime($order_date_modified);
			$od_diff_hours = abs($od_modified - $od_created)/(60*60);

			if ( $od_diff_hours >= 24 ) {
				continue;
			}
		}

		$tmp_arr['order'] = $item->get_id();
		$tmp_arr['status'] = $status;
		$tmp_arr['date'] = $order_date_created;
		$tmp_arr['delivery_time'] = $item->get_meta('billing_gatetimecheckout') ? get_time($item->get_meta('billing_gatetimecheckout')) : null;
		//$tmp_arr['delivery_time_full'] = $item->get_meta('billing_gatetimecheckout');
		array_push($arr_orders_data, $tmp_arr);
		
	}

	return $arr_orders_data;
}
///////////////////////////

// add_filter( 'woocommerce_variable_price_html', 'truemisha_variation_price', 20, 2 );
 
// function truemisha_variation_price( $price, $product ) {
 
// 	$min_regular_price = $product->get_variation_regular_price( 'min', true );
// 	$min_sale_price = $product->get_variation_sale_price( 'min', true );
// 	$max_regular_price = $product->get_variation_regular_price( 'max', true );
// 	$max_sale_price = $product->get_variation_sale_price( 'max', true );
 
// 	if ( ! ( $min_regular_price == $max_regular_price && $min_sale_price == $max_sale_price ) ) {
// 		if ( $min_sale_price < $min_regular_price ) {
// 			$price = sprintf( 'от <del>%1$s</del><ins>%2$s</ins>', wc_price( $min_regular_price ), wc_price( $min_sale_price ) );
// 		} else {
// 			$price = sprintf( 'от %1$s', wc_price( $min_regular_price ) );
// 		}
// 	}
 
// 	return $price;
 
// }


// отключение кеширования рассчета доставки
add_filter('transient_shipping-transient-version', function($value, $name) { return false; }, 10, 2);

/// функционал товаров с модальными окнами
require get_template_directory() . '/inc/mx-modules/custom-products.php';


/**
 * Вывод вариаций в каталоге */

function iconic_change_loop_add_to_cart()
{
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
	add_action('woocommerce_after_shop_loop_item', 'iconic_template_loop_add_to_cart', 10);
}

add_action('init', 'iconic_change_loop_add_to_cart', 10);



function iconic_template_loop_add_to_cart()
{
	global $product;

	if (!$product->is_type('variable')) {
		woocommerce_template_loop_add_to_cart();
		return;
	}

	remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
	add_action('woocommerce_single_variation', 'iconic_loop_variation_add_to_cart_button', 20);

	woocommerce_template_single_add_to_cart();
}


function iconic_loop_variation_add_to_cart_button()
{
	global $product;

?>
	<div class="woocommerce-variation-add-to-cart variations_button">
		<button type="submit" class="single_add_to_cart_button button"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
		<input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
		<input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
		<input type="hidden" name="variation_id" class="variation_id" value="0" />
	</div>
<?php
}

add_action('rest_api_init', function () {
	register_rest_route('test/v1', '/tttest', array( 
		'methods' => 'GET',
		'callback' => 'tttest',
	));
});
function tttest(){

    
}

/*
 * КОНЕЦ Вывод вариаций в каталоге
 **/


/// функционал с исключением ингредиентов
require get_template_directory() . '/inc/mx-modules/excluded-ingridients.php';

// add_action('wp_enqueue_scripts', 'swiper_js');
// function swiper_js()
// {
// 	//  wp_enqueue_script('jquery');
// 	wp_enqueue_style('style-swiper', 'https://unpkg.com/swiper@7/swiper-bundle.min.css');
// 	wp_enqueue_script('script-swiper', 'https://unpkg.com/swiper@7/swiper-bundle.min.js', array(), '1.0.0', true);
// }

// add_action('wp_enqueue_scripts', 'scroll_js');
// function scroll_js()
// {
// 	//  wp_enqueue_script('jquery');
// 	wp_enqueue_style('style-scroll', get_template_directory_uri() . '/assets/plugins/simplebar/simplebar.css');
// 	wp_enqueue_script('script-scroll', get_template_directory_uri() . '/assets/plugins/simplebar/simplebar.min.js', array(), '1.0.0', false);
// 	wp_enqueue_script('script-scroll-script', get_template_directory_uri() . '/assets/plugins/simplebar/script.js', array(), '1.0.0', true);
// }






//Именные (реферальные) купоны
require get_template_directory() . '/inc/mx-modules/referal_coupon.php';

//Купон на самовывоз
require get_template_directory() . '/inc/mx-modules/coupon_samovivoz.php';

//Ссылка на видео
require get_template_directory() . '/inc/mx-modules/video_url.php';

///Подарки в корзине (палочки, васаби...)
require get_template_directory() . '/inc/mx-modules/cart-gifts.php';  

///Бронирование
require get_template_directory() . '/inc/mx-modules/booking.php';  

///Склады
require get_template_directory() . '/inc/mx-modules/stocks.php';  

///Авто меню
require get_template_directory() . '/inc/mx-modules/auto_menu.php';  

///Выбор доставки в хедере
require get_template_directory() . '/inc/mx-modules/header-shippings.php';  

///модальные окна 
require get_template_directory() . '/inc/mx-modules/fancybox_modals.php';  

///Предпочтения пользователя 
require get_template_directory() . '/inc/mx-modules/customer_preferences.php';  

///Купоны пользователя 
require get_template_directory() . '/inc/mx-modules/customer_coupons.php';  

///События
require get_template_directory() . '/inc/mx-modules/auto_events.php';  

//////////Фильтр для события Обновление acf поля, обновление скидок товаров
include get_template_directory() . '/inc/mx-modules/mass-discount-settings.php';

//swiper slider
require get_template_directory() . '/inc/mx-modules/slider.php';  

//////////Функции купонов
include get_template_directory() . '/inc/mx-modules/coupons.php';

// add_action('rest_api_init', function () {
// 	register_rest_route('popup/v1', '/get_option', array( //регистрация маршрута
// 		'methods' => 'GET',
// 		'callback' => 'rrrrrr',
// 	));
// });
// function rrrrrr(WP_REST_Request $request) {

// }



/*Баннеры-галлереи в мобильной версии*/

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getMobileBanners() {
	global $post;
	$posts = get_posts(array(
		'numberposts' => -1,
		'category'    => 0,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'include'     => array(),
		'exclude'     => array(),
		'meta_key'    => '',
		'meta_value'  =>'',
		'post_type'   => 'banners',
		'suppress_filters' => true
	));

	foreach ($posts as $post) {
		setup_postdata($post);
		//if(empty(get_field('galereja')))
			//$g = !empty(get_field('galereja')) ? get_field('galereja') : 'gallery_'.rand(0, 2);
			//if($g == 'gallery_0') $g = 'gallery';
			$g = get_field('galereja');
			$banners[$g][]= array(
				'title'       => get_the_title(),
				'description' => get_the_excerpt(),
				'image'       => get_the_post_thumbnail_url(),
				'category'    => get_field('category') ? get_category_link( get_field('category') ) : '',
				'product'     => get_field('product') ? get_permalink( get_field('product') ) : '',
				'url'         => get_field('url'),
				'type'        => get_field('type'),
				'gallery'        => $g,
				'sklad'    => get_field('skladtaxon') ? get_field('skladtaxon') : []
			);
	}

	wp_reset_postdata();
	
	return $banners;
}
function showMobileBanners($banners) {
	$html = '';
	if( count($banners) == 1 ){
		if( $banners[0]['url'] || $banners[0]['category'] || $banners[0]['product'] )
			$html = '<div class="section-single-item" style=" background-image: url('.$banners[0]['image'].');" onclick="location.href=\''.$banners[0]['category'].$banners[0]['product'].$banners[0]['url'].'\'"></div>';
		else
			$html = '<div class="section-single-item" style=" background-image: url('.$banners[0]['image'].');"></div>';
	}else{
		foreach($banners as $index=>$b){
			if($b['url'] || $b['category'] || $b['product'])
				$html.= '<div class="section_item item'.($index+1).'" style=" background-image: url('.$b['image'].');" data-lazyload="'.$b['image'].'" onclick="location.href=\''.$b['category'].$b['product'].$b['url'].'\'"></div>';
			else 
				$html.= '<div class="section_item item'.($index+1).'" style=" background-image: url('.$b['image'].');" data-lazyload="'.$b['image'].'"></div>';
		}
		if(!empty($html))
			$html = '<div class="section-t section-i-'.count($banners).'">'.$html.'</div>';
	}
		
	return $html;
}


/*Время работы для самовывоза и доставки*/
add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v3', 'delivery-work-time', array(
        'methods' => 'GET',
        'callback' => 'get_delivery_openhours',
    ));
});
function get_delivery_openhours( ) {
		
	$data = [];

	/*Доставка*/
	$week_arr = array( 'Monday'=> 1,'Tuesday'=> 2,'Wednesday'=> 3,'Thursday'=> 4,'Friday'=> 5,'Saturday'=> 6,'Sunday'=> 7 );    
	$opening_hours = get_field('opening_hours', 'option'); // массив с временем работы из настроек
		
	$out = array();
	$out['wp_timezone'] = wp_timezone_string();
	//$out['closest_delivery_time_minutes'] = get_field('closest_delivery_time_minutes', 'option');
	$out['around_clock'] = (get_field('around_clock', 'option') == "true" ? true : false); 
	$out['cooking_time_in_minutes'] = get_field('cooking_time_minutes', 'option');

	foreach( $opening_hours['week'] as $k=>$v ){
	$from_arr = explode(":", $v['from']);   
	$from =     "{$from_arr[0]}:{$from_arr[1]}";
	$to_arr = explode(":", $v['to']);   
	$to =   "{$to_arr[0]}:{$to_arr[1]}";    
	$out['week'][$week_arr[$k]] = array( 'from'=> $from,'to'=> $to);
	}

	$data['delivery'] = $out;
		
	/*Самовывоз*/
	$out = [];
	$opening_hours = get_field('opening_hours_pickup', 'option'); // массив с временем работы из настроек
	$out['around_clock'] = (get_field('around_clock_pickup', 'option') == "true" ? true : false); 
	$out['wp_timezone'] = wp_timezone_string();

	foreach( $opening_hours['week'] as $k=>$v ){
	$from_arr = explode(":", $v['from']);   
	$from =     "{$from_arr[0]}:{$from_arr[1]}";
	$to_arr = explode(":", $v['to']);   
	$to =   "{$to_arr[0]}:{$to_arr[1]}";    
	$out['week'][$week_arr[$k]] = array( 'from'=> $from,'to'=> $to);
	} 
	$data['pickup'] = $out;

	return $data;
}

/* Проверяем статус доставки от рабочих часов */
function check_delivery_status($type){
	
	//для ajax/api запросов не смотрим время
	if( defined('REST_REQUEST') ) return true;
	
	$status = true;
	switch($type){
		case 'delivery':
			$opening_hours = get_field('opening_hours', 'option');
			$around_clock = (get_field('around_clock', 'option') == "true") ? true : false;
		break;
		
		case 'pickup':
			$opening_hours = get_field('opening_hours_pickup', 'option');
			$around_clock = (get_field('around_clock_pickup', 'option') == "true") ? true : false;

		break;
	}
	
	/*Если работа круглосуточная*/
	if($around_clock){
		return true;
	}
		
	$out = array();
	$week_arr = array( 'Monday'=> 1,'Tuesday'=> 2,'Wednesday'=> 3,'Thursday'=> 4,'Friday'=> 5,'Saturday'=> 6,'Sunday'=> 7 ); 
	$timezone = wp_timezone_string();
	$tz = new DateTimeZone($timezone); // часовой пояс
	$dt = new DateTime("now", $tz); // сегодняшняя дата

	//$out['closest_delivery_time_minutes'] = get_field('closest_delivery_time_minutes', 'option');
	$out['around_clock'] = (get_field('around_clock', 'option') == "true" ? true : false); 
		
	foreach( $opening_hours['week'] as $k=>$v ){
		$from_arr = explode(":", $v['from']);   
		$from =     "{$from_arr[0]}:{$from_arr[1]}";
		$to_arr = explode(":", $v['to']);   
		$to =   "{$to_arr[0]}:{$to_arr[1]}";    
		$out['week'][$week_arr[$k]] = array( 'from'=> $from,'to'=> $to);
	}
	
	/*Сравнение*/
	//   print_r($opening_hours) ;
	$start_work = $out['week'][date("N")]['from'];
	$end_work = $out['week'][date("N")]['to'];

	if(!empty($start_work && $end_work)){

		$open = new DateTime("today {$start_work}", $tz); // дата и время открытия сегодня
		$closed = new DateTime("today {$end_work}", $tz); // дата и время закрытия сегодня
		$status = (($dt > $open && $dt < $closed)) ? true : false; // статус открыто/закрыто


		// $currentTime = date("H:i");  
		// $currentDateTime = strtotime(date('Y-m-d')  ." ". $currentTime); 
		// $startDateTime = strtotime(date('Y-m-d')  ." ". $start_work);
		// $endDateTime = strtotime(date('Y-m-d')  ." ". $end_work);
		// if(( strtotime($currentTime) < strtotime($startDateTime) ) || (strtotime($endDateTime) < strtotime($currentTime)) ){
		// 	//echo 'WE CLOSED';
		// 	$status = false;
		// }
	}
	
	return $status;
}

/* Увеличиваем время жизни сессии */
add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember){
    //if "remember me" is checked;
    if ( $remember ) {
        //WP defaults to 2 weeks;
        $expiration = 14*24*60*60; //UPDATE HERE;
    } else {
        //WP defaults to 48 hrs/2 days;
        $expiration = 14*24*60*60; //UPDATE HERE;
    }

    if ( PHP_INT_MAX - time() < $expiration ) {
        //Fix to a little bit earlier!
        $expiration =  PHP_INT_MAX - time() - 5;
    }
    return $expiration;
}


////исключить заказ из выдачи статусовзаказа(для приложения) 
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/hide_orderstatus/(?P<order_id>\d+)', array( //регистрация маршрута /(?P<status>\w+)
		'methods' => 'GET',
		'callback' => 'hide_order_status',
	));
});
function hide_order_status(WP_REST_Request $request) {
	$params = $request->get_params();
	if(!empty($params['order_id'])){
		$order = wc_get_order($params['order_id']);
		if( $order ){
			global $wpdb ;
			
			$table = $wpdb->prefix . "postmeta" ;
			$wpdb->query( "DELETE FROM $table WHERE post_id = ".$params['order_id']." AND meta_key = 'orderstatus_hide'" ) ;
			$wpdb->query( "INSERT INTO $table VALUES( NULL, '".$params['order_id']."', 'orderstatus_hide', '1' )" ) ;
			
			//$order->update_meta_data('orderstatus_send', 66);
			return true;
		} 
		
	}
	
	return false;
}


/****Весовой***товар*******************************************/

add_filter( 'the_count_type', 'count_type_filter' );
function count_type_filter( $product ) {
	$count_type = $product->get_meta('countable');
	if($count_type  == 0){
		$portion_size = $product->get_meta('portion_size');
		$meausure_unit = $product->get_meta('meausure_unit');
		//$meausure_type = $product->get_meta( 'meausure_type' );		
		$meausure_unit = pre_meausure_unit( $meausure_unit );
		//$meausure_unit = ' гр';
    	return "$portion_size $meausure_unit";
	}
	else return false;

	//return '__'.$count_type.' (100 гр.)'.$product_id;
}

add_filter( 'fake_qty', 'fake_qty_filter', 1, 2 );
function fake_qty_filter( $product, $quantity = 1 ) {
	if(!$product) return '';
	$count_type = $product->get_meta('countable');
	if($count_type === "0"){ 
		$portion_size = $product->get_meta( 'portion_nat_size' );
		$meausure_type = $product->get_meta( 'meausure_type' );		
		$meausure_unit = pre_meausure_type( $meausure_type );
    	return '<input type="text" ttt="'.$count_type.'" class="fake-qty" data-portion="'.$portion_size.'" data-txt="'.$meausure_unit.'" disabled value="'.($portion_size * $quantity).' '.$meausure_unit.'">';
	}
	else return '';

	//return '__'.$count_type.' (100 гр.)'.$product_id;
}

function pre_meausure_type($meausure_type){
	$user_lang = get_field('user_menu_lang', 'option');

	if($user_lang == 'ru'){
		switch ($meausure_type) {
			case 'Килограмм':
				return 'кг';
				break;
			case 'Грамм':
				return 'гр';
				break;		
			case 'Литр':
				return 'л';
				break;	
			case 'Миллилитр':
				return 'мл';
				break;				
		}
	}elseif($user_lang == 'en'){
		switch ($meausure_type) {
			case 'Килограмм':
				return 'kg';
				break;
			case 'Грамм':
				return 'gr';
				break;		
			case 'Литр':
				return 'L';
				break;	
			case 'Миллилитр':
				return 'ml';
				break;				
		}
	}elseif($user_lang == 'deu'){
		switch ($meausure_type) {
			case 'Килограмм':
				return 'kg';
				break;
			case 'Грамм':
				return 'gr';
				break;		
			case 'Литр':
				return 'L';
				break;	
			case 'Миллилитр':
				return 'ml';
				break;				
		}
	}
	return $meausure_type;
}
function pre_meausure_unit($meausure_type){
	$user_lang = get_field('user_menu_lang', 'option');

	if($user_lang == 'ru'){
		return $meausure_type;
	}elseif($user_lang == 'en'){
		switch ($meausure_type) {
			case 'кг':
				return 'kg';
				break;
			case 'гр':
				return 'gr';
				break;		
			case 'л':
				return 'L';
				break;	
			case 'мл':
				return 'ml';
				break;	
			case 'шт':
				return 'pc';
				break;								
		}
	}elseif($user_lang == 'deu'){
		switch ($meausure_type) {
			case 'кг':
				return 'kg';
				break;
			case 'гр':
				return 'gr';
				break;		
			case 'л':
				return 'L';
				break;	
			case 'мл':
				return 'ml';
				break;	
			case 'шт':
				return 'pc';
				break;				
		}
	}
	return $meausure_type;
}
function pre_quantity_unit($product=null){
	$user_lang = get_field('user_menu_lang', 'option');
	$qua = $product->get_stock_quantity();
	if($qua)
		if($user_lang == 'ru'){
			return $qua.' шт.';
		}elseif($user_lang == 'en'){
			return  $qua.' pc';
		}elseif($user_lang == 'deu'){
			return  $qua.' pc';
		}
	return false;
}



/***** Меню пользователя приложение******************************/
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/user_menu', array( //регистрация маршрута /(?P<status>\w+)
		'methods' => 'GET',
		'callback' => 'get_app_user_menu',
	));
});

function get_app_user_menu() {

	$user_menu_bonuses = get_field('user_menu_bonuses', 'option');
	$user_menu_referal_system = get_field('user_menu_referal_system', 'option');
	$user_menu_online_chat = get_field('user_menu_online_chat', 'option');
	$user_menu_online_chat_url = get_field('user_menu_online_chat_url', 'option');
	$user_menu_blog = get_field('user_menu_blog', 'option');
	$user_menu_lang_enable = get_field('user_menu_lang_enable', 'option') == 'true' ? true : false;
	$user_menu_theme_color_enable = get_field('user_menu_theme_color_enable', 'option') == 'true' ? true : false;
	$show_unit_price = get_field('show_unit_price', 'option');
	if( is_null($show_unit_price) ) $show_unit_price = true;

	$soc_instagramm = $soc_facebook = $soc_vk = $soc_whatsapp = $soc_telegram = $soc_youtube = $soc_viber = '';
	// if($GLOBALS['pizzaro_options']['telegram'])		$soc_telegram = $GLOBALS['pizzaro_options']['telegram'];
	// if($GLOBALS['pizzaro_options']['whatsapp'])		$soc_whatsapp = $GLOBALS['pizzaro_options']['whatsapp'];
	// if($GLOBALS['pizzaro_options']['viber'])		$soc_viber = $GLOBALS['pizzaro_options']['viber'];
	// if($GLOBALS['pizzaro_options']['instagram'])	$soc_instagramm = $GLOBALS['pizzaro_options']['instagram'];
	// if($GLOBALS['pizzaro_options']['facebook']) 	$soc_facebook = $GLOBALS['pizzaro_options']['facebook'];
	// if($GLOBALS['pizzaro_options']['vk']) 			$soc_vk = $GLOBALS['pizzaro_options']['vk'];
	// if($GLOBALS['pizzaro_options']['youtube'])		$soc_youtube = $GLOBALS['pizzaro_options']['youtube'];

	$contact_soc_arr = get_field('kont-social', 'option');
	if($contact_soc_arr['kont-social-vk']) $soc_vk = $contact_soc_arr['kont-social-vk'];
	if($contact_soc_arr['kont-social-inst']) $soc_instagramm = $contact_soc_arr['kont-social-inst'];
	if($contact_soc_arr['kont-social-fb']) $soc_facebook = $contact_soc_arr['kont-social-fb'];

	$messengers_soc_arr = get_field('messendgers', 'option');	
	if($messengers_soc_arr['kont-social-whatsapp']) $soc_whatsapp = $messengers_soc_arr['kont-social-whatsapp'];
	if($messengers_soc_arr['kont-social-tg']) $soc_telegram = $messengers_soc_arr['kont-social-tg'];
	if($messengers_soc_arr['kont-social-viber']) $soc_telegram = $messengers_soc_arr['kont-social-viber'];

	$user_menu_theme_colors = get_field('user_menu_theme_color', 'option');
	$user_menu_lang = get_field('user_menu_lang', 'option');
	return [
		'user_menu_bonuses' => $user_menu_bonuses ? true : false,
		'user_menu_referal_system' => $user_menu_referal_system ? true : false,
		//'user_menu_online_chat' => ($user_menu_online_chat && $user_menu_online_chat_url) ? $user_menu_online_chat_url : null,
		'user_menu_online_chat' => $user_menu_online_chat ? true : false,
		'user_menu_blog' => $user_menu_blog ? true : false, 
		'user_menu_lang_enable' => $user_menu_lang_enable, 
		'user_menu_theme_color_enable' => $user_menu_theme_color_enable, 
		'user_menu_all_colors' => $user_menu_theme_colors, 
		'user_menu_all_langs' => $user_menu_lang, 
		'show_unit_price' => $show_unit_price, 
		'socials' => [
			'facebook' => $soc_facebook,
			'instagram' => $soc_instagramm,
			'vk' => $soc_vk,
			'youtube' => $soc_youtube,
		],
		'messengers' => [
			'telegram' => $soc_telegram,
			'whatsapp' => $soc_whatsapp,
			'viber' => $soc_viber,
		],
	];
}

/***** Информационные страницы ******************************/
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/info_pages', array( //регистрация маршрута /(?P<status>\w+)
		'methods' => 'GET',
		'callback' => 'get_app_info_pages',
	));
});
function get_app_info_pages() {

	$app_info_pages = get_field('app_info_pages', 'option');
	$pages = [];
//print_r($app_info_pages);
	foreach($app_info_pages as $page){
		$pages[]=[
			'name' => $page['page_name'],
			'url' => $page['page']->guid.'?mobile_app=true',
			'icon' => $page['icon'] ? $page['icon'] : '',
			
		];
	}
	// $user_menu_referal_system = get_field('user_menu_referal_system', 'option');
	// $user_menu_online_chat = get_field('user_menu_online_chat', 'option');
	// $user_menu_blog = get_field('user_menu_blog', 'option');

	return $pages;
}



/* Бронь тест */

add_action('rest_api_init', function () {
	register_rest_route('bron/v1', '/product-free-time', array( //регистрация маршрута
		'methods' => 'POST',
		'callback' => 'get_product_free_time',
	));
});

function get_product_free_time(WP_REST_Request $request) {
	// информациионные поля для приложения, например, версия
	if( $request['product_id'] && $request['date']){

		$product = wc_get_product( intval($request['product_id']) );

		if( yith_wcbk_is_booking_product( $product ) ){

			$time_data = $product->create_all_availability_time_array( $request[ 'date' ], 1 );
		}else{
			return [];
		}

		return array_map(function($el){ $el['enabled'] = $el['enabled']==1 ? true : false; return $el;}, $time_data);

	}


	return [];
}

add_action('rest_api_init', function () {
	register_rest_route('bron/v1', '/product_bron_test', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'product_test',
	));
});

function product_test(WP_REST_Request $request) { 
	// информациионные поля для приложения, например, версия

	if( $request['product_id'] && $request['date']){
		$product = wc_get_product( intval($request['product_id']) );
		$services = $product->get_service_ids();
		$services_arr = [];


		$enabled_people_types = array_map(function($el){ $el['title'] = get_the_title( $el['id'] ); return $el; },$product->get_enabled_people_types());
		//echo $cost = $product->calculate_service_costs(['persons'=> 2]);
		foreach ( $services as $service_id ) {
			$service = yith_get_booking_service( $service_id );
			//$service =  new YITH_WCBK_Service( $service_id );
			// print_r($service);
			// echo '__';
			$services_arr[]=$service;
		}
		$time_step = 15;
		if( yith_wcbk_is_booking_product( $product ) ){

			$time_data = $product->create_all_availability_time_array( $request[ 'date' ], 1 );
		};
		return $services_arr;
		//return array_map(function($el){ $el['enabled'] = $el['enabled']==1 ? true : false; return $el;}, $time_data);
	}else {
		
	}


	return [];
}
/* Бронь тест */



/* удаление покупателя по апи */
add_action('rest_api_init', function () { 

    register_rest_route('wc', '/user/delete', [

        'methods'  => 'GET',

        'callback' => 'delete_user',

    ]);

});
function delete_user( WP_REST_Request $request ){

	if ( is_user_logged_in() ) {
		$request_data = $request->get_params();
		$user_id = intval($request_data['user_id']);
		if( !$user_id ) return [];

		require_once(ABSPATH.'wp-admin/includes/user.php');
		$current_user = get_userdata($user_id);
		//print_r($roles = wp_get_current_user()->roles);
		//print_r( $current_user->roles );
		$del = false;
		if(!in_array('administrator', $current_user->roles))
			if(in_array('customer', $current_user->roles))
				$del = wp_delete_user($user_id);

		return new WP_REST_Response(['success' => true], 200);

	}
	return new WP_Error('unauthorized', __('You shall not pass'), [ 'status' => 401 ]); //can also use WP_REST_Response
}