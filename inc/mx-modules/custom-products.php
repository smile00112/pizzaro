<?php

/// стили для кастомных товаров
function custom_products_scripts() {
  wp_enqueue_style('custom_products', get_template_directory_uri() . '/assets/css/custom_products.css?rand=' . time());
  wp_enqueue_script('custom_products', get_template_directory_uri() . '/assets/js/custom_products.js?rand=' . time(), array('jquery'));
}
add_action('wp_enqueue_scripts', 'custom_products_scripts');
////////////////////////


/// кастомный запрос для вывода товаров из категории (Shortcode [custom_products])
add_shortcode('custom_products', 'custom_products_shortcode');
function custom_products_shortcode($atts) {
  $StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; // Id склада из куки
  $meta_query = [];
  if( $StockId ){ // если Id получен добавляем в запрос товаров условие
      $meta_query[] = array(
          'key' => '_stock_status-'.$StockId, // метаполе с количеством товара	
          'value' => 0,
          'compare' => '>'
      ); 
      // $meta_query[] = array(
      //     'key' => '_disc_limit_stok_'.$StockId, // метаполе с количеством товара	
      //     'value' => 0,
      //     'compare' => '>'
      // ); 
 
  }


  $GLOBALS['products'] = new WP_Query([
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'posts_per_page'        => $atts['limit'] ?: 50,
    'orderby'               => $atts['orderby'] ?: 'menu_order',
    'order'                 => $atts['order'] ?: 'ASC',
    'tax_query'             => [
      [
        'taxonomy' => 'product_cat',
        'field'    => $atts['category_field'] ?: 'slug',
        'terms'    => $atts['category'],
        'include_children' => false,
      ]
    ],
    'meta_query' => $meta_query
  ]);

  wc_get_template_part( 'content', 'custom_products' );
  wp_reset_postdata();
} 
////////////////////////

/// загрузка данных в модальную форму custom_product
add_action('rest_api_init', function () {
  register_rest_route('popup/v1', '/custom_product_modal', array(
    'methods' => 'GET',
    'callback' => 'get_custom_product_modal',
  ));
});

function get_custom_product_modal(WP_REST_Request $request) {
  if (isset($request['product_id'])) {
    $product = wc_get_product($request['product_id']);

    if (!empty($product)) {
      $GLOBALS['product'] = $product;
      ob_start();
      wc_get_template_part('content', 'custom_product_modal');
      $product_view = ob_get_contents();
      ob_end_clean();

      return ['product_view' => $product_view];
    } else {
      return ['err' => 'Product not find.'];
    }
  } else {
    return ['err' => 'Wrong params.'];
  }
}
///////////////////////

/// загрузка данных в модальную форму custom_product
add_action('rest_api_init', function () {
  register_rest_route('popup/v1', '/custom_product_preview', array(
    'methods' => 'GET',
    'callback' => 'get_custom_product_preview',
  ));
});

function get_custom_product_preview(WP_REST_Request $request) {
  if (isset($request['product_id'])) {
    $product = wc_get_product($request['product_id']);

    if (!empty($product)) {
      $GLOBALS['product'] = $product;
      ob_start();
      wc_get_template_part('content', 'custom_product_preview');
      $product_view = ob_get_contents();
      ob_end_clean();

      return ['product_view' => $product_view];
    } else {
      return ['err' => 'Product not found.'];
    }
  } else {
    return ['err' => 'Wrong params.'];
  }
}
///////////////////////

/// загрузка данных конструктора supplements в модальную форму custom_product
add_action('rest_api_init', function () {
  register_rest_route('popup/v1', '/custom_product_supplements', array(
    'methods' => 'GET',
    'callback' => 'get_custom_product_supplements',
  ));
});

function get_custom_product_supplements(WP_REST_Request $request) {
  if (isset($request['product_id'])) {
    $product = wc_get_product($request['product_id']);

    if (!empty($product)) {
      $GLOBALS['product'] = $product;
      ob_start();
      wc_get_template_part('content', 'custom_product_supplements');
      $supplements_view = ob_get_contents();
      ob_end_clean();

      return ['supplements_view' => $supplements_view];
    } else {
      return ['err' => 'Product not found.'];
    }
  } else {
    return ['err' => 'Wrong params.'];
  }
}
///////////////////////

// добавление доптоваров в корзину
add_action( 'woocommerce_add_to_cart', 'action_function_name_9057', 10, 6 );
function action_function_name_9057( $cart_item_key_p, $product_id_p, $quantity_p, $variation_id_p, $variation_p, $cart_item_data_p ) {
	if (isset($_POST['supplements']) && $_POST['supplements']) { // если есть доппродукты
		$parent_key = $cart_item_key_p; // ключ основного продукта
		$supplements = $_POST['supplements']; // доппродукты
		unset($_POST['supplements']); // удаляем доппродукты из пост запроса что-бы не зациклить 
		$supplements_array = json_decode(stripslashes($supplements), true); //массив с доппродуктами
		if (json_last_error()) {
			return false; // если в json ошибка ничего не делаем
		}
		global $woocommerce;
		$supplements_ids = array(); // ключи доптоваров в корзине
		foreach ($supplements_array as $k => $v) {
			$sup_prod = intval($v['prod']); // id доптовара
			$sup_quantity = intval($v['quantity']); // кол-во доптовара
			if ($sup_prod && $sup_quantity) { // если есть id и кол-во
				$sup_key = $woocommerce->cart->add_to_cart($product_id = $sup_prod, $quantity = $sup_quantity * $quantity_p, $variation_id = 0, $variation = array(), $cart_item_data = array('parent_key' => $parent_key)); // добавление в корзину
				$supplements_ids[] = $sup_key; // доавляем ключ доптовара в массив
			}
		}

		if ($supplements_ids) { // если есть доптовары добавляем их ключи основному
			$cart = WC()->cart->cart_contents;
			foreach ($cart as $cart_item_id => $cart_item) {
				if ($parent_key == $cart_item_id) {//родительскому товару присваиваем список допов
					$cart_item['supplements_ids'] = $supplements_ids;
					WC()->cart->cart_contents[$cart_item_id] = $cart_item;
				}
				
				if ( in_array( $cart_item_id, $supplements_ids ) ){//Допу присваиваем родителя
					$cart_item['parent_key'] = $parent_key;
				}
			}
			WC()->cart->set_session();
		}
	}
  //Проверим корзину на дубли товаров, суммируем одинаковые и почистим их
	//получим содержание корзины
	$cart = WC()->cart->get_cart();
	foreach ($cart as $cart_item_key => $cart_item) {
        if(!empty($cart_item['parent_key'])) continue; // Пропускаем допы
		//если нашли такой же товар, то удаляем добавляемый и устанавливаем количество
		if(($product_id_p == $cart_item['product_id'] && $variation_id_p == $cart_item['variation_id']) && ($cart_item_key != $cart_item_key_p)){
			//(!$cart_item['parent_key'])
      $item_p = WC()->cart->get_cart_item($cart_item_key_p);
      if( identical_values_cp($item_p['supplements_ids'], $cart_item['supplements_ids']) ){
        WC()->cart->set_quantity($cart_item_key, $quantity_p);
              //чистим допы у удаляемого товара
              $item_p = WC()->cart->get_cart_item($cart_item_key_p);
              if(!empty($item_p['supplements_ids'])){
                  foreach ($item_p['supplements_ids'] as $suplement_key){
                      WC()->cart->remove_cart_item($suplement_key);
                  }
              }
        //Удаляем дубль
        WC()->cart->remove_cart_item($cart_item_key_p);
        WC()->cart->set_session();
      }      
      
			break;
		}
		//$woocommerce->cart->set_quantity('8d317bdcf4aafcfc22149d77babee96d', '100');
	}
}

// удаление доптоваров при удалении основного
add_action( 'woocommerce_remove_cart_item', 'action_function_name_6120', 10, 2 );

function action_function_name_6120( $cart_item_key, $that ) {
	 // если у удаляемого товара есть параметр с ключами доптоваров
	if ( isset( $that->cart_contents[ $cart_item_key ][ 'supplements_ids' ] ) && is_array( $that->cart_contents[ $cart_item_key ][ 'supplements_ids' ] ) ) {
		global $woocommerce;
		// перебираем ключи доптоваров
		foreach ( $that->cart_contents[ $cart_item_key ][ 'supplements_ids' ] as $sup_key ) { 
			$woocommerce->cart->remove_cart_item( $sup_key ); // удаляем доптовар из корзины
		}
	}
}


////////Кнопка Купить для кастомных типов товаров(Групповой)
add_action( 'woocommerce_single_product_summary', 'supplements_custom_addtocart', 60 );
function supplements_custom_addtocart () {
	global $product;
	if ( 'supplements' == $product->get_type() ) { 
		$template_path = plugin_dir_path( __FILE__ ) . 'woocommerce/'; //debug_to_file('suppl type templ: '.$template_path);
		wc_get_template( 'single-product/add-to-cart/simple.php',
			'',
			'',
			trailingslashit( $template_path ) );
	}
}

function identical_values_cp( $arrayA , $arrayB ) {

  sort( $arrayA );
  sort( $arrayB );

  return $arrayA == $arrayB;
}