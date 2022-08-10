<?php
 /* Pizzaro engine room
 *
 * @package pizzaro
 */

/**
 * Assign the Pizzaro version to a var
 */
$theme              = wp_get_theme( 'pizzaro' );
$pizzaro_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

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
if ( is_redux_activated() ) {
	require get_template_directory() . '/inc/redux-framework/pizzaro-options.php';
	require get_template_directory() . '/inc/redux-framework/hooks.php';
	require get_template_directory() . '/inc/redux-framework/functions.php';
}

if( is_jetpack_activated() ) {
	require get_template_directory() . '/inc/jetpack/class-pizzaro-jetpack.php';
}

if ( is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-woocommerce.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-shortcode-products.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-products.php';
	require get_template_directory() . '/inc/woocommerce/class-pizzaro-wc-helper.php';
	require get_template_directory() . '/inc/woocommerce/pizzaro-woocommerce-template-hooks.php';
	require get_template_directory() . '/inc/woocommerce/pizzaro-woocommerce-template-functions.php';
	require get_template_directory() . '/inc/woocommerce/integrations.php';
}

if( is_wp_store_locator_activated() ) {
	require get_template_directory() . '/inc/wp-store-locator/class-pizzaro-wpsl.php';
}

/**
 * One Click Demo Import
 */
if ( is_ocdi_activated() ) {
	require get_template_directory() . '/inc/ocdi/hooks.php';
	require get_template_directory() . '/inc/ocdi/functions.php';
}

if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/class-pizzaro-admin.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woothemes/theme-customisations
 */








// Подключенин минимальной суммы заказа и бесплатной доставки
require_once( get_template_directory() . '/select-address/min-amount_and_free-ship.php' );

// Подключение карты

require_once( get_template_directory() . '/select-address/functions.php' );

function banners_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => 'Банеры',
        'singular_name'       => 'Банер',
        'menu_name'           => 'Банеры',
        'all_items'           => 'Все банеры',
        'view_item'           => 'Просмотр банера',
        'add_new_item'        => 'Добавить банер',
        'add_new'             => 'Добавить новый',
        'edit_item'           => 'Редактировать банер',
        'update_item'         => 'Обновить банер',
        'search_items'        => 'Поиск банера',
        'not_found'           => 'Не найдено',
        'not_found_in_trash'  => 'Не найдено в Корзине',
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => 'banners',
        'description'         => 'Банеры',
        'labels'              => $labels,
        'supports'            => array( 'title', 'excerpt', 'thumbnail', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type('banners', $args);
	
	  $labels2 = array(
        'name'                => 'Поп-апы',
        'singular_name'       => 'Поп-ап',
        'menu_name'           => 'Поп-апы',
        'all_items'           => 'Все Поп-апы',
        'view_item'           => 'Просмотр Поп-ап',
        'add_new_item'        => 'Добавить Поп-ап',
        'add_new'             => 'Добавить новый',
        'edit_item'           => 'Редактировать Поп-ап',
        'update_item'         => 'Обновить Поп-ап',
        'search_items'        => 'Поиск Поп-ап',
        'not_found'           => 'Не найдено',
        'not_found_in_trash'  => 'Не найдено в Корзине',
    );
     
// Set other options for Custom Post Type
     
    $args2 = array(
        'label'               => 'mapppops',
        'description'         => 'Поп-апы',
        'labels'              => $labels2,
        'supports'            => array( 'title', 'excerpt', 'thumbnail', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type('mapppops', $args2);
	
	
		  $labels3 = array(
        'name'                => 'Точки доставки',
        'singular_name'       => 'Точка доставки',
        'menu_name'           => 'Точки доставки',
        'all_items'           => 'Все',
        'view_item'           => 'Просмотр ',
        'add_new_item'        => 'Добавить',
        'add_new'             => 'Добавить',
        'edit_item'           => 'Редактировать ',
        'update_item'         => 'Обновить ',
        'search_items'        => 'Поиск ',
        'not_found'           => 'Не найдено',
        'not_found_in_trash'  => 'Не найдено в Корзине',
    );
     
// Set other options for Custom Post Type
     
    $args3 = array(
        'label'               => 'deliverypoint',
        'description'         => 'Точки доставки',
        'labels'              => $labels3,
        'supports'            => array( 'title', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type('deliverypoint', $args3);
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action('init', 'banners_post_type', 0);

//add_filter('woocommerce_product_needs_shipping', function(){return false;});

add_action("after_setup_theme", function () {

    load_plugin_textdomain( 'pizzaro', false, get_stylesheet_directory() . '/languages' );
    load_theme_textdomain( 'pizzaro', false, get_stylesheet_directory() . '/languages' );


}, 5);


// New order hook
add_action('woocommerce_thankyou', 'fresh_new_order');


function fresh_new_order($order_id) {
    $order = wc_get_order($order_id);
	//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/_ordrs.txt',print_r($order,1)."\n",FILE_APPEND);
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		<? /*
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
		});*/ ?>

	gtag('event', 'purchase', {
		  "transaction_id": "<?php echo $order_id;?>",
		  "affiliation": "<?php echo get_option( "blogname" );?>",
		  "value": <?php echo $order->get_total();?>,
		  "currency": "<?php echo get_woocommerce_currency();?>",
		  "tax": <?php echo $order->get_total_tax();?>,
		  "shipping": <?php echo $order->get_total_shipping();?>,
		  "items": [
	<?
		//Данные о товарах
	if ( sizeof( $order->get_items() ) > 0 ) {
		foreach( $order->get_items() as $item ) {
			$product_cats = get_the_terms( $item["product_id"], 'product_cat' );
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
			  "id": "<?php echo $item['id'];?>",
			  "name": "<?php echo $item['name'];?>",
			  "category": "<?php echo $cat->name;?>",
			  "quantity": <?php echo $item['qty'];?>,
			  "price": '<?php echo $item['line_subtotal'];?>'
			},
	<?
		}	
	} ?>
		]
	});
	<? /*
		ga('ecommerce:send');
		console.log('Sended ecommerce');*/ ?>
	});
		</script>
	<?

}

function new_order($order_id) {
    
   // $exchange = new Exchange();
   // $exchange->sendOrder($order_id);
        
    $order = wc_get_order($order_id);
	if(($order->get_status() != 'pending')){
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

add_action('init', function(){
    
});

// if (isset($_GET['phone']) && isset($_GET['sendmessage'])) {
    // SMS::send($_GET['phone'], 'Ваш заказ принят. Ждите курьера в течение 30 минут. Родная Доставка.');
    // exit();
// }




 add_action( 'woocommerce_rest_product_cat_query', 'filter_function_name_7971', 10, 2 ); 

function filter_function_name_7971( $prepared_args ){
$prepared_args['menu_order'] = 1;

	return $prepared_args;
}




add_action( 'wp_enqueue_scripts', 'baur_theme_scripts' );
add_action( 'wp_enqueue_scripts', 'baur2_theme_scripts' );
function baur2_theme_scripts() {
    wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
	wp_enqueue_script( 'jquery' );
}
function baur_theme_scripts() {
    wp_enqueue_style( 'baurCss', get_template_directory_uri() . '/assets/css/baur.css', array(), '1.0.'.strval(rand(123, 999)) );
    wp_enqueue_style( 'baurCss-1', "https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css", array(), '1.0.0' );
    wp_enqueue_script( 'baurJs', get_template_directory_uri() . '/assets/js/baur.js', array( 'jquery' ), '1.1.'.strval(rand(123, 999)), true );

    wp_enqueue_script( 'baurJs-1', "https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js", '1.1.0', true );
    wp_enqueue_script( 'baurJs-1map', "https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat&amp;apikey=86b68654-96e5-4565-a50b-58ca9d7a9f79", '1.1.0' );
}





function my_awesome_func( WP_REST_Request $request ){
$user = get_user_by( 'login',  $request['slug'] );
$have_gift = get_user_meta( $user->ID, 'have_gift', true );
	//if ( empty( $have_gift ) )
		//return 'false';
 


	

	//return $have_gift;
	return 'true';
}


add_action( 'rest_api_init', function(){

	register_rest_route( 'dostavka/v1', '/customer-gift/(?P<slug>\d+)', [
		'methods'  => 'GET',
		'callback' => 'my_awesome_func',
	] );
	
	

} );



function user_extra_meta_fields(){

 return array(
   'have_gift' => __( 'Gift', 'yourtext_domain'),

 ); 

} 

function add_contact_methods( $contactmethods ) {
     $contactmethods = array_merge( $contactmethods, user_extra_meta_fields());
     return $contactmethods;
}

add_filter('user_contactmethods','add_contact_methods',10,1);


//add_action('woocommerce_after_order_notes', 'my_custom_checkout_fieldss');

function my_custom_checkout_fieldss( $checkout ) {

  foreach( user_extra_meta_fields() as $name => $label) {

     $value = get_user_meta( get_current_user_id(), $name, true );
if(empty($value) ) $value = false;
      woocommerce_form_field( $name, array(
            'type'          => 'text',
            'class'         => array('my-field-class form-row-wide hidden'),
            'label'         => $label,
            ), $value );

      }
}

add_action( 'woocommerce_order_status_processing', 'mysite_hold2');
function mysite_hold2( $order_id) {
 
 
  $order = wc_get_order($order_id);
 
 
 		  $gift_coupons = array();
  foreach( $order->get_coupon_codes() as $coupon_code ) {
    // Get the WC_Coupon object
    $coupon = new WC_Coupon($coupon_code);

    $discount_type = $coupon->get_discount_type(); // Get coupon discount type
  $gift_data = array();
	if ( ! is_wp_error( $coupon ) && 'free_gift' == $discount_type ) {
			
			$coupon_meta = $coupon->get_meta( '_wc_free_gift_coupon_data' );
//var_dump($coupon_meta);
			// Only return meta if it is an array, since coupon meta can be null, which results in an empty model in the JS collection.
			$gift_data = is_array( $coupon_meta ) ? $coupon_meta : array();

			foreach ( $gift_data as $gift_id => $gift ) {

								//$gift_product = wc_get_product( $gift_id );

				$gift_coupons[$gift_id] = $coupon_code;
		


			}
			//$gift_coupons[$coupon_code] = $gift_data
		}
		
}
 

  foreach($order->get_items() as $order_item){
    $product_id = $order_item->get_product_id();

    if( array_key_exists($product_id , $gift_coupons) ){
		$order_item->add_meta_data( '_free_gift', $gift_coupons[$product_id], true );
		//var_dump($product_id);
        //$total = $order_item->get_total();
        $order_item->set_subtotal(0);
        $order_item->set_total(0);
        $order_item->save();
	   
		
    }
}


  
   $order->calculate_totals();
$order->save();
 
 
 
 $exchange = new Exchange();
    $exchange->sendOrder($order_id);
	
	
	if(($order->get_status() != 'pending')){
    SMS::send($order->get_billing_phone(), 'Ваш заказ принят, спасибо. Ожидайте курьера. Родная доставка.');
	}
}


add_action( 'woocommerce_order_status_processing', 'mysite_hold');
add_action( 'woocommerce_order_status_completed', 'mysite_hold');
add_action( 'woocommerce_order_status_on-hold', 'mysite_hold');
function mysite_hold( $order_id) {
 
// $exchange = new Exchange();
   // $exchange->sendOrder($order_id);

// 1. Get order object
   $order = wc_get_order( $order_id );
  
   // 2. Initialize $cat_in_order variable
   $cat_in_order = false;
  
   // 3. Get order items and loop through them...
   // ... if product in category, edit $cat_in_order
   $items = $order->get_items(); 
      
   foreach ( $items as $item ) {      
      $product_id = $item->get_product_id();  
      if ( has_term( 18, 'product_cat', $product_id ) ) {
         $cat_in_order = true;
         break;
      }
   }
   
   
   
     $user = $order->get_user();
     if( $user ){
		$user_id = $user->ID;
		 if($cat_in_order == true){
        update_user_meta( $user_id,  'have_gift', 'true' );
		 } else {
			//update_user_meta( $user_id,  'have_gift', 'false' ); 
		 }
     }
	 
	 
  
}



// add_action( 'rest_api_init', 'slug_register_purchasing' );

// function slug_register_purchasing() {
//         register_rest_field( 'product',
//             'free_limits',
//             array(
//                 'get_callback'    => 'slug_get_purchasing_cost',
//                 'update_callback' => null,
//                 'schema'          => null,
//             )
//         );
//     }

// function slug_get_purchasing_cost( $object, $field_name, $request ) {
	
// 	$return = array(); 
	
// 	$values = get_field('free_limits',$object[ 'id' ]); 
// 	if($values){
// 		arsort($values);
// 	  foreach( $values as $row ) {
		  
//         $return[] = array('order_value' => $row['order_value'], 'quantity' => $row['quantity']);
		
// 	  }
// 	}
	
//     return $return; 
	
	
// }



add_action( 'rest_api_init', 'slug_register_purchasing2' );

function slug_register_purchasing2() {
        register_rest_field( 'product',
            'recommended_to_category',
            array(
                'get_callback'    => 'recommended_to_category',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

function recommended_to_category( $object, $field_name, $request ) {
	
	
	
	$values = get_field('recommended_to_category',$object[ 'id' ]); 
	if(!$values) $values = array(); 
	
    return $values; 
	
	
}


add_action( 'rest_api_init', 'slug_register_purchasing3' );

function slug_register_purchasing3() {
        register_rest_field( 'product',
            'recommend_to_product',
            array(
                'get_callback'    => 'recommend_to_product',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

function recommend_to_product( $object, $field_name, $request ) {
	
	
	
	$values = get_field('recommend_to_product',$object[ 'id' ]); 
	if(!$values) $values = array(); 
	
    return $values; 
	
	
}



// BACS payement gateway description: Append custom select field
add_filter( 'woocommerce_gateway_description', 'gateway_bacs_custom_fields', 20, 2 );
function gateway_bacs_custom_fields( $description, $payment_id ){
    //
    if( 'cod' === $payment_id ){
        ob_start(); // Start buffering

        echo '<div  class="cod-options" style="padding:10px 0;">';

        woocommerce_form_field( 'cod_details', array(
            'type'          => 'text',
            'label'         => __("С какой суммы приготовить сдачу?", "woocommerce"),
            'class'         => array('form-row-wide'),
            'required'      => true,
           
        ), '');

        echo '<div>';

        $description .= ob_get_clean(); // Append buffered content
    }
    return $description;
}

// Checkout custom field validation
add_action('woocommerce_checkout_process', 'bacs_option_validation' );
function bacs_option_validation() {
    if ( isset($_POST['payment_method']) && $_POST['payment_method'] === 'cod'
    && isset($_POST['cod_details']) && empty($_POST['cod_details']) ) {
        wc_add_notice( __( 'Пожалуйста укажите сумму с которой нужно приготовить сдачу.' ), 'error' );
    }
}

// Checkout custom field save to order meta
add_action('woocommerce_checkout_create_order', 'save_bacs_option_order_meta', 10, 2 );
function save_bacs_option_order_meta( $order, $data ) {
    if ( isset($_POST['cod_details']) && ! empty($_POST['cod_details']) ) {
        $order->update_meta_data( 'cod_details' , esc_attr($_POST['cod_details']) );
    }
}

// Display custom field on order totals lines everywhere
add_action('woocommerce_get_order_item_totals', 'display_bacs_option_on_order_totals', 10, 3 );
function display_bacs_option_on_order_totals( $total_rows, $order, $tax_display ) {
    if ( $order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('cod_details') ) {
        $sorted_total_rows = [];

        foreach ( $total_rows as $key_row => $total_row ) {
            $sorted_total_rows[$key_row] = $total_row;
            if( $key_row === 'payment_method' ) {
                $sorted_total_rows['cod_details'] = [
                    'label' => __( "Детали доставки", "woocommerce"),
                    'value' => esc_html( $cod_details ),
                ];
            }
        }
        $total_rows = $sorted_total_rows;
    }
    return $total_rows;
}

// Display custom field in Admin orders, below billing address block
add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_bacs_option_near_admin_order_billing_address', 10, 1 );
function display_bacs_option_near_admin_order_billing_address( $order ){
    if( $cod_details = $order->get_meta('cod_details') ) {
        echo '<div class="cod-option">
        <p><strong>'.__('Детали доставки').':</strong> ' . $cod_details . '</p>
        </div>';
    }
}


add_action( 'woocommerce_admin_order_data_after_order_details', 'misha_editable_order_meta_general' );
 
function misha_editable_order_meta_general( $order ){  ?>
 
		<br class="clear" />
		<h4>Источник заказа <a href="#" class="edit_address">Редактировать</a></h4>
		<?php 
			/*
			 * get all the meta data values we need
			 */ 
			
			$gift_name = get_post_meta( $order->get_id(), 'order_meta_source', true );
			
		?>
		<div class="address">
			
			<?php
				// we show the rest fields in this column only if this order is marked as a gift
			
				?>
					
					<p><strong>Источник заказа:</strong> <?php echo $gift_name ?></p>
					
				<?php
			
			?>
		</div>
		<div class="edit_address"><?php
 

 
			woocommerce_wp_text_input( array(
				'id' => 'order_meta_source',
				'label' => 'Источник заказа:',
				'value' => $gift_name,
				'wrapper_class' => 'form-field-wide'
			) );
 
			
 
		?></div>
 
 		<br class="clear" />
		<h4>Код точки доставки <a href="#" class="edit_address">Редактировать</a></h4>
		<?php 
			/*
			 * get all the meta data values we need
			 */ 
			
			$deliverypoint1C = get_post_meta( $order->get_id(), 'deliverypoint1C', true );
			
		?>
		<div class="address">
			
			<?php
				// we show the rest fields in this column only if this order is marked as a gift
			
				?>
					
					<p><strong>Код точки доставки:</strong> <?php echo $deliverypoint1C ?></p>
					
				<?php
			
			?>
		</div>
		<div class="edit_address"><?php
 

 
			woocommerce_wp_text_input( array(
				'id' => 'deliverypoint1C',
				'label' => 'Код точки доставки:',
				'value' => $deliverypoint1C,
				'wrapper_class' => 'form-field-wide'
			) );
 
			
 
		?></div>
<?php }
 
add_action( 'woocommerce_checkout_update_order_meta', 'misha_save_general_details' );
 
function misha_save_general_details( $ord_id ){
	 $order = wc_get_order( $ord_id );
	$meta_source = $order->get_meta('order_meta_source');
	
	
	  if ( empty($meta_source) ) {
	
	
	update_post_meta( $ord_id, 'order_meta_source', wc_clean( 'siteorder' ) );
	} else {
		
	}
	
	// wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields2' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields2( $fields ) {
     $fields['billing']['billing_gatetimecheckout'] = array(
        'label'     => __('Время доставки', 'woocommerce'),
    'placeholder'   => _x('Время доставки', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}



// Checkout custom field save to order meta
add_action('woocommerce_checkout_create_order', 'save_bacs_option_order_meta2', 10, 2 );
function save_bacs_option_order_meta2( $order, $data ) {
    if ( isset($_POST['billing_gatetimecheckout']) && ! empty($_POST['billing_gatetimecheckout']) ) {
        $order->update_meta_data( 'billing_gatetimecheckout' , esc_attr($_POST['billing_gatetimecheckout']) );
    }
	if ( isset($_POST['_shipping_deliv_time']) && ! empty($_POST['_shipping_deliv_time']) ) {
        $order->update_meta_data( '_shipping_deliv_time' , esc_attr($_POST['_shipping_deliv_time']) ); 
    }
	if( !empty( WC()->session->get( 'deliverypoint1C' ) ) ) {
		 $order->update_meta_data( 'deliverypoint1C' , esc_attr(WC()->session->get( 'deliverypoint1C' )) );
    }
		
}

// Display custom field on order totals lines everywhere
add_action('woocommerce_get_order_item_totals', 'display_bacs_option_on_order_totals2', 10, 3 );
function display_bacs_option_on_order_totals2( $total_rows, $order, $tax_display ) {
    if ( $order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('billing_gatetimecheckout') ) {
        $sorted_total_rows = [];

        foreach ( $total_rows as $key_row => $total_row ) {
            $sorted_total_rows[$key_row] = $total_row;
            if( $key_row === 'payment_method' ) {
                $sorted_total_rows['cod_details'] = [
                    'label' => __( "Время доставки", "woocommerce"),
                    'value' => esc_html( $cod_details ),
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
 
add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_bacs_option_near_admin_order_billing_address_time', 10, 1 );
function display_bacs_option_near_admin_order_billing_address_time( $order ){
	$deliv_time = get_post_meta( $order->get_id(), '_shipping_deliv_time', true );
	$cod_details = $order->get_meta('billing_gatetimecheckout');
	if( !empty($cod_details)) { // если выбрано конкретное время
        echo '<div class="cod-option">
        <p><strong>'.__('Время доставки').':</strong> ' . $cod_details . '</p>
        </div>';
    }
	
	else if(!empty($deliv_time)){ // если не выбрано, то максимально возможное в соответствии с зоной доставки 
		$fiveHours = 3600 * 5; //deliver local gmt
		$dateCreated = strtotime((string)$order->get_date_created()) + $fiveHours;
		$delive_timestamp = $dateCreated + intval($deliv_time)*60; $delive_cr = $order->get_date_created();
		$date = date('d.m.Y', $delive_timestamp);
		$time = date('G:i', $delive_timestamp);
		echo '<div class="cod-option">
        <p><strong>'.__('Время доставки').':</strong> ' . $date.', '.$time . '</p>
        </div>';
	}
	
    
}

function custom_woocommerce_rest_pre_insert_shop_order_object(  $order, $request, $creating ){ 

   //update_post_meta( $order->get_id(), 'order_meta_source', wc_clean( 'mobile_app' ) );
   // $body_params = $request->get_body_params();
  // $coupon_code = $body_params['coupon_lines']['code'];

    return $order;
} 


	
	
//add the action 
//add_filter('woocommerce_rest_pre_insert_shop_order_object', 'custom_woocommerce_rest_pre_insert_shop_order_object', 10, 3);

add_filter( 'wp_sitemaps_add_provider', 'kama_remove_sitemap_provider', 10, 2 );
function kama_remove_sitemap_provider( $provider, $name ){
	if(in_array($name, ['users']))
		return false;

	return $provider;
}

add_filter( 'wp_sitemaps_post_types', 'wpkama_remove_sitemaps_post_types' );
function wpkama_remove_sitemaps_post_types( $post_types ){
	unset($post_types['static_block']);
	unset($post_types['tribe_events']);
	return $post_types; 
}


function your_custom_function_name( $allcaps, $caps, $args ) {
if ( isset( $caps[0] ) ) {
switch ( $caps[0] ) {
case 'pay_for_order' :


$order_id = isset( $args[2] ) ? $args[2] : null;
$order = wc_get_order( $order_id );
$user = $order->get_user();
$user_id = $user->ID;

// When no order ID, we assume it's a new order
// and thus, customer can pay for it
if ( ! $order_id ) {
  $allcaps['pay_for_order'] = true;
  break;
}

$order = wc_get_order( $order_id );

if ( $order && ( $user_id == $order->get_user_id() || ! $order->get_user_id() ) ) {
  $allcaps['pay_for_order'] = true;
}
break;
}
}

return $allcaps;
}

add_filter( 'user_has_cap', 'your_custom_function_name', 10, 3 );


add_action( 'woocommerce_init', function(){
	if (isset(WC()->session)) {
    if ( ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }
	}
} );

// function that gets the Ajax data
add_action( 'wp_ajax_setshippoints', 'setshippoints' );
add_action( 'wp_ajax_nopriv_setshippoints', 'setshippoints' );
function setshippoints() {

    if ( isset($_POST['deliverypoint1C']) ){
        WC()->session->set('deliverypoint1C', $_POST['deliverypoint1C'] );
    } 
    echo  WC()->session->get('deliverypoint1C');
    die(); // Alway at the end (to avoid server error 500)
}



class woocommerce_menu_with_thumbnails_walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $thumbnail_id = get_woocommerce_term_meta( $item->object_id, 'thumbnail_id', true );
        $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
        $output .= '<li><span><img src="'.$thumbnail_url.'" alt="" /></span><a href="'.$item->url.'">'.$item->title.'</a></li>';
    }
}


//add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_bonus_to_order', 10, 3 );
function rest_bonus_to_order( $response, $order, $request ) { //add bonus data in order
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
}



add_action( 'woocommerce_admin_order_data_after_order_details', 'admin_deliv_time_order' ); 

function admin_deliv_time_order( $order ){
	$deliv_time = get_post_meta( $order->get_id(), '_shipping_deliv_time', true );
	echo '<div class="deliv_time"><b>Срок доставки:</b> '.$deliv_time.' мин</div>';
	//$data = $order->get_data();
	//$order_status = $data['status']; 
	
}

/////////////////////////////
function debug_to_file($cont){ //debug info to file
	if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/adeb.txt')) {
		$file = fopen($_SERVER['DOCUMENT_ROOT']. '/adeb.txt', 'a+');
		$results = print_r($cont, true);
		fwrite($file, $results);
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


//add_filter( 'wp_insert_post_data', 'custom_filt_post_data', 10, 2 );
//add_filter( 'woocommerce_rest_prepare_shop_order_object', 'custom_filt_post_data', 10, 3 );
function custom_filt_post_data( $response, $order, $request ) {
	$json = json_decode($request->get_body()); 
	//debug_to_file($response);
	
	return $response;
}
 
//mail('qashqai911@gmail.com','file', $_SERVER['DOCUMENT_ROOT']);


//add_filter( 'woocommerce_checkout_create_order', 'filt_post_data', 10, 1 );
function filt_post_data( $order ){
	//debug_to_file($order);
}

////////////////////////
//route for popup

add_action( 'rest_api_init', function () {
register_rest_route( 'popup/v1', '/banner', array( //регистрация маршрута
    'methods'             => 'GET',
    'callback'            => 'get_pop_json'
) );
});

function get_pop_json(WP_REST_Request $request ){ // получение данных из mapppops
    $posts = get_posts( array(
        'post_type'   => 'mapppops',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );
    
    $ar_all_posts = [];
    
    foreach( $posts as $post ){
        $ar_post = array();
        setup_postdata($post);
        $uuid = $post->ID;
        $title = $post->post_title;
        $message = get_field( "message", $post->ID );
        $button_link = get_field( "button_link", $post->ID );
        $button_title = get_field( "button_title", $post->ID );
        $repeat_mode = get_field( "repeat_mode", $post->ID );
		$is_marketing = get_field( "marketing", $post->ID );
		if($is_marketing == true) $is_marketing = true;
		else $is_marketing = false;
		
		if($repeat_mode == 'Повторять') $repeat_mode = 'repeatable';
		else if($repeat_mode == 'Один раз') $repeat_mode = 'one-off';
		else $repeat_mode = 'one-off';
        
        $sklad = get_field( "skladtaxon", $post->ID ); //var_dump($sklad);
        $sklad_ar = [];
        foreach($sklad as $item){ //echo $sklad['choices'][$v].'|'; //var_dump($v);  
            if($item == 110) array_push($sklad_ar, 110);
            if($item == 111) array_push($sklad_ar, 111);
        } //echo '---';  
		
        
        //var_dump($sklad); 
        
        
        $picture_url = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
        if(!$picture_url) $picture_url = '';
        
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

/*** показываем товары с нужного склада ***/
add_action( 'woocommerce_product_query', 'set_stock_product_query' );
function set_stock_product_query( $q ){
$StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; // Id склада из куки
if( $StockId ){ // если Id получен добавляем в запрос товаров условие
    $meta_query = $q->get( 'meta_query' ); 
    $meta_query[] = array(
        'key' => '_stock_at_'.$StockId, // метаполе с количеством товара 
        'value' => 0,
        'compare' => '>'
        ); 
    $q->set( 'meta_query', $meta_query );
}
}


add_filter( 'woocommerce_shortcode_products_query', 'filter_function_name_4282', 10, 3 );
function filter_function_name_4282( $query_args, $attributes, $type ){
$StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; 
if( $StockId ){ 
    $meta_query = $query_args['meta_query']; 
    $meta_query[] = array(
        'key' => '_stock_at_'.$StockId, 
        'value' => 0,
        'compare' => '>'
        ); 
    $query_args['meta_query'] = $meta_query;
}
    return $query_args;
}

// установка статуса in_stock в зависимости от наличия на нужном складе
add_filter( 'woocommerce_product_is_in_stock', 'filter_function_name_5277', 10, 2 );
function filter_function_name_5277( $in_stock, $product ) { // in_stock - статус 0/1
    if ($in_stock && is_product() ) { // если страница товара
        $StockId = ( $_COOKIE[ 'StockId' ] ) ? intval( $_COOKIE[ 'StockId' ] ) : null; // id склада
        if ( $StockId ) { // если получен id склада
            $quantity = get_post_meta( $product->get_id(), '_stock_at_' . $StockId, true ); // проверяем количество на этом складе
            if ( !$quantity ) { // если нет на складе
                $in_stock = 0; // указываем что нет на складе
            }
        }
    }
    return $in_stock;
}
/*** END показываем товары с нужного склада ***/

/*** добавление скрипта для смены id склада ***/

// подключение js в футер
add_action('wp_footer', 'setStockId_javascript', 99); 
function setStockId_javascript() {
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {    
var adsressStreetAndFlat = localStorage.getItem( 'adsressStreetAndFlat' );
var adsressStreet = localStorage.getItem( 'adsressStreet' );
if(adsressStreetAndFlat != null){
jQuery('span.add_address').html(adsressStreetAndFlat);
}
if(adsressStreet != null){
jQuery('#address-map').val(adsressStreet);
}       
});
        
jQuery( window ).load(function() {
checkStockId();
});
    
function checkStockId(){
var StockId = localStorage.getItem( 'storageId' );
if (StockId !== null && StockId != '' ) {
return true;
} else {
// jQuery('.editing_an_address').trigger('click'); //изменения от 26,02,2021 baur
return false;   
}   
}
        
jQuery(document).on( 'hidden.bs.modal', '#alertModal.close-reload', function() {  
location.reload();
});
        
jQuery(document).on( 'click', '#set-first-address .modal-address__overlay, #set-first-address .modal-address__close, #set-first-address .select-address-start__button', function() {  
set_StockId(0);
}); 
    
function set_StockId(always_check){
var check = checkStockId();
if(check == false){
    return false;
}   
var StockId = localStorage.getItem( 'storageId' );
        var data = {
            action: 'setStockId',
            StockId: StockId,
            always_check: always_check
        };
jQuery.post( '<?php echo admin_url('admin-ajax.php') ?>', data, function(response) {
    var obj = JSON.parse( response );
    if ( obj.alert == 1 ) {
    jQuery('#alertModal .modal-title').html(obj.alert_title);
    jQuery('#alertModal .modal-body').html(obj.alert_body);
    jQuery('#alertModal').modal('show');
        if(obj.changed == 1){jQuery('#alertModal').addClass('close-reload');}
                    } else {
        if(obj.changed == 1){
        location.reload();  
        }               
                    }

        }); 
}   
<?php if(!$_COOKIE['StockId'] || is_cart() || is_checkout() ): // если нет куки со складом или корзина или оформление заказа ?>
set_StockId(1);
<?php endif; ?>     
    
checkWorkingHours();
let timerId = setInterval(() => checkWorkingHours(), 1000*60*1);
function checkWorkingHours(){
        var data = {
            action: 'checkWorkingHours',
        };
jQuery.post( '<?php echo admin_url('admin-ajax.php') ?>', data, function(response) {
var obj = JSON.parse( response );
if ( obj.status == 'closed' ){
jQuery('.top_header-date').show(500);
jQuery('.top_header-date .top_header-date_top').html('Мы работаем с '+obj.opening+', но уже сейчас готовы принять заказ.'); 
} else {
jQuery('.top_header-date').hide();  
}
        });;    
}       
jQuery(document).on( 'click', '.top_header-date', function() {  
checkWorkingHours();
}); 
</script>
<div id="alertModal" class="modal fade modal_baur" tabindex="-1" role="dialog" style="z-index: 9999999;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header"> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            <span aria-hidden="true">
                <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g filter="url(#filter0_d)">
                    <rect x="2" y="1" width="38" height="38" rx="19" fill="white"/>
                    </g>
                    <path d="M26.7071 15.7071C27.0976 15.3166 27.0976 14.6834 26.7071 14.2929C26.3166 13.9024 25.6834 13.9024 25.2929 14.2929L26.7071 15.7071ZM15.2929 24.2929C14.9024 24.6834 14.9024 25.3166 15.2929 25.7071C15.6834 26.0976 16.3166 26.0976 16.7071 25.7071L15.2929 24.2929ZM16.7071 14.2929C16.3166 13.9024 15.6834 13.9024 15.2929 14.2929C14.9024 14.6834 14.9024 15.3166 15.2929 15.7071L16.7071 14.2929ZM25.2929 25.7071C25.6834 26.0976 26.3166 26.0976 26.7071 25.7071C27.0976 25.3166 27.0976 24.6834 26.7071 24.2929L25.2929 25.7071ZM25.2929 14.2929L15.2929 24.2929L16.7071 25.7071L26.7071 15.7071L25.2929 14.2929ZM15.2929 15.7071L25.2929 25.7071L26.7071 24.2929L16.7071 14.2929L15.2929 15.7071Z" fill="black"/>
                    <defs>
                    <filter id="filter0_d" x="0" y="0" width="42" height="42" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                    <feOffset dy="1"/>
                    <feGaussianBlur stdDeviation="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                    </filter>
                    </defs>
                </svg>
            </span>
        </button>
        <!-- <h4 class="modal-title"></h4> -->
      <!-- </div> -->
      <div class="modal-body"></div>
      <div class="modal_footer_baur">
        <button type="button" class="btn btn_default_baur" data-dismiss="modal">Продолжить оформление</button>
        <a href="/">Выбрать другие блюда</a>
      </div>
    </div>
  </div>
</div>
    <?php
}

// функция вызываемая ajax запросом
add_action('wp_ajax_setStockId', 'setStockId_callback'); 
add_action('wp_ajax_nopriv_setStockId', 'setStockId_callback');
function setStockId_callback() {
$StockId = intval( $_POST['StockId'] );
$always_check = intval( $_POST['always_check'] );   
$setStock = setStockId($StockId, $always_check);
$out = ($setStock) ? $setStock : array('changed' => 0); 
$out['alert'] = 0;  
    
if( $setStock['unset_items'] ){ // если удалялись товары из корзины показываем алерт
$out['alert'] = 1;  
$out['changed'] = 1;
// $out['alert_title'] = 'Предупреждение!';
$out['alert_body'] = '<div class="alert title_baur"><span>Некоторые товары недоступны для доставки по выбранному адресу и были удалены из корзины:<span class="baur_nogif"></span></span> <ul class="text-warning">'; 
foreach($setStock['unset_items'] as $item ){
$out['alert_body'] .=   '<li>'.$item['name'].'</li>';
}
$out['alert_body'] .=   '</ul></div>';  
}

echo json_encode($out);
    wp_die();
}



// функция установки id склада и удаления отсутствующих товаров из корзины
function setStockId( $StockId, $always_check ) {
    if ( !$StockId || ( $StockId == $_COOKIE[ 'StockId' ] && !$always_check ) ) {
        return false; // если не указан склад или он совпадает с тем что в куке и проверка необязательна ничего не делаем
    }   
$return = (!$StockId || $StockId == $_COOKIE[ 'StockId' ]) ? array('changed' => 0) : array('changed' => 1); 
setcookie( 'StockId', $StockId, time() + 60 * 60 * 24 * 30 * 12 , '/', '' ); // ставим куку
    
$unset_items = array(); // массив с удаленными товарами 
    
global $woocommerce; // подключаем woocommerce
$items = $woocommerce->cart->get_cart(); // получаем корзину
foreach ($items as $key =>$item){ // перебираем товары
$prod = array(); //массив с данными текущего довара 
$prod['id'] = $item['product_id'];  // id товара
$prod['key'] =  $key; // ключ товара в корзине
$prod['quantity'] = get_post_meta( $prod['id'], '_stock_at_'.$StockId, true );  // количество товара на нужном складе
        
$_product =  wc_get_product( $item['data']->get_id()); // получаем товар
$prod['name']=$_product->get_title(); // название товара
if(!$prod['quantity']){ // если на складе нет товара
$woocommerce->cart->remove_cart_item($key); // удаляем его из корзины
$unset_items[] = $prod; // добавляем удаленный товар в массив удаленных товаров
}

}

$return['unset_items'] = $unset_items;
return $return;
}
/*** END добавление скрипта для смены id склада ***/

/***  добавление склада в заказ ***/
// Сохраняем метаданные заказа со значением поля
add_action( 'woocommerce_checkout_update_order_meta', 'shipping_apartment_update_order_meta' );

function shipping_apartment_update_order_meta( $order_id ) {
    $StockId = intval( $_COOKIE['StockId'] ); // получаем id склада из куки 
    if ( $StockId ) { // если id получен сохраняем его
$order = wc_get_order( $order_id );
$order->update_meta_data( 'stock_id', $StockId );
$order->save();
    } 
}


// вывод поля в админке
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'action_function_name_2993' );
function action_function_name_2993( $order ){
$stock_id = $order->get_meta('stock_id');
$stock = get_term( $stock_id, 'location' );
$stock_name = ($stock) ? $stock->name : null;
if($stock_name) {
    echo '<p><strong>'.__('Склад').':</strong> ' . $stock_name . '</p>';
    }
}

/*** END добавление склада в заказ ***/




/*** часы работы ***/
// добавлпние времени работы в rest-api 
// ссылка  https://xn--80ahe4adlmgc0k.xn--p1ai/wp-json/wc/v3/general-info
add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v3', 'general-info', array(
        'methods' => 'GET',
        'callback' => 'get_openhours',
    ));
});
function get_openhours( $data ) {
$week_arr = array( 'Monday'=> 1,'Tuesday'=> 2,'Wednesday'=> 3,'Thursday'=> 4,'Friday'=> 5,'Saturday'=> 6,'Sunday'=> 7 );    
$opening_hours = get_field('opening_hours', 'option'); // массив с временем работы из настроек
    
$out = array();
$out['wp_timezone'] = wp_timezone_string();
$out['closest_delivery_time_minutes'] = get_field('closest_delivery_time_minutes', 'option');
    
foreach( $opening_hours['week'] as $k=>$v ){
$from_arr = explode(":", $v['from']);   
$from =     "{$from_arr[0]}:{$from_arr[1]}";
$to_arr = explode(":", $v['to']);   
$to =   "{$to_arr[0]}:{$to_arr[1]}";    
$out['week'][$week_arr[$k]] = array( 'from'=> $from,'to'=> $to);
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
    
$day_week =  $dt->format("l"); // день недели сегодня
$day_week_tomorrow = $dt2->modify( '+1 day' )->format('l'); // день недели завтра

$open = new DateTime("today {$opening_hours['week'][$day_week]['from']}", $tz); // дата и время открытия сегодня
$closed = new DateTime("today {$opening_hours['week'][$day_week]['to']}", $tz); // дата и время закрытия сегодня    
$status = ( ($dt > $open && $dt < $closed) ) ? 'open' : 'closed'; // статус открыто/закрыто
$opening_str = ($dt < $closed) ? $opening_hours['week'][$day_week]['from'] : $opening_hours['week'][$day_week_tomorrow]['from']; // время открытия, сегодняшнее если ещё не открыто или завтрашнее если уже закрыто
$opening_arr = explode(":", $opening_str);  
$opening =  "{$opening_arr[0]}:{$opening_arr[1]}"; // дата открытия без секунд
$out = array( 'status'=>$status, 'opening'=>$opening );
echo json_encode($out);
    wp_die();
}

// добавление  пункта в меню woocommerce в админке
if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
        'page_title'    => 'Пользовательские настройки woocommerce',
        'menu_title'    => 'Доп. настройки',
        'menu_slug' => 'woo-acf-settings',
        'parent_slug'   => 'woocommerce',
    ));
    
}
/*** END часы работы ***/


// Кнопка статистики в профиле
add_action( 'edit_user_profile', 'show_profile_fields' );
add_action( 'show_user_profile', 'show_profile_fields');
function show_profile_fields( $user ) { 
if( is_admin() ){
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
if(!$customer_id){wp_die();}
require get_template_directory() . '/inc/customer-statistics.php';
    wp_die();
}

add_action( 'admin_head', 'action_function_name_6132' ); 
// конец по статистике

//mail('qashqai911@gmail.com', 'rodn', 'test'); 


add_filter( 'wp_insert_post_data', 'meta_time_note', 10, 2 );
function meta_time_note( $data, $postarr ){
	$gatetime = get_post_meta( $postarr['ID'], 'billing_gatetimecheckout', true );

	$isset_time_none = 'Время доставки:';
	$pos = strripos($data['post_excerpt'], $isset_time_none);
	
	if ($pos === false && !empty($gatetime)) { 
		$new_note = $data['post_excerpt'] . '  | Время доставки: '.$gatetime;
		$data['post_excerpt'] = $new_note; 
	}
	
	return $data;
} 