<?php


///////////Подарки в корзине/////////////


 function get_gift_sells() {
	$associatetd_products = array();
	$associatetd_productsss = array();
	$products = new WP_Query( array(
	'post_type'   => 'product',
	'post_status' => 'publish',
	'posts_per_page' => 999,
	'fields'      => 'ids',
	'tax_query'   => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => 92,
		)
	),

	) );

	$gift_cross_sells = $products->posts;


		
	foreach ($gift_cross_sells as $product) {

		$products_in_cat = get_field( 'recommended_to_category' ,$product);
		$productscat = new WP_Query( array(
		'post_type'   => 'product',
		'post_status' => 'publish',
		'posts_per_page' => 999,
		'fields'      => 'ids',
		'tax_query'   => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $products_in_cat,
			)
		),

		) );

		$gift_cross_sellscat = $productscat->posts;
		//$recommend_to_product = 	array();
		//$associatetd_products[$product] = array();
		$recommend_to_product =  get_field( 'recommend_to_product' ,$product);
		if(is_array($recommend_to_product )){
		$recommend_to_product = $recommend_to_product;
		} elseif(!empty($recommend_to_product)){
		$recommend_to_product[] = 	$recommend_to_product;
		}

		//$associatetd_products[$product][] = $gift_cross_sellscat;
		// merge arrays to set unique product ids

		if(!empty($recommend_to_product)){
		$associatetd_products[$product] = array_map(function($a, $b){ 
					if($a === $b) return $a;
					return [$a, $b];
			  }, $recommend_to_product, $gift_cross_sellscat);
		} else {
		$associatetd_products[$product] = $gift_cross_sellscat;
		}

	}

	$cart_products = array();
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	$cart_products[]=$product_id;

		}
	//return $associatetd_products;
	foreach ($associatetd_products as $key => $productss) {
		foreach ($productss as  $prod) {
			foreach ($cart_products as $cartproductss) {
				if(is_array($prod) && in_array($cartproductss, $prod)){
					$associatetd_productsss[$key] =  get_field( 'free_limis' ,$key);;

				} elseif(in_array($cartproductss, $prod)){
					$associatetd_productsss[$key] =  get_field( 'free_limis' ,$key);;
				}
			}
		}
	}	

	return $associatetd_productsss;
}




add_action( 'woocommerce_calculate_totals', 'conditionally_add_free_product' );
function conditionally_add_free_product( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
	
	//if(!is_cart()) return;
 $associatetd_gift_productsss = get_gift_sells();
    // Settings
   
$cart_item_subtotal = 0;
    // Initializing
    $cart_subtotal = 0;
    $cart_items    = $cart->get_cart();


	
	
	foreach ($cart_items as $cart_item_key => $cart_item) {
		
		
	
		 
		   $current_product_price = $cart_item['data']->get_price(); 
	  
	  $product_qty = $cart_item['quantity'];
	 // $product_total = $product->get_total();
		 $product_total = ($current_product_price * $cart_item['quantity']);
		 if(array_key_exists( $cart_item['data']->get_id(),$associatetd_gift_productsss) != true ){
			 $cart_subtotal += $product_total ;
		// echo 'Minus<br>';
		}
		
		
	
	$free_product_id  = 0;
	  }
	
	
	
	   // Loop through cart items (first loop)
	  foreach ($associatetd_gift_productsss as $rec_item_key => $rec_item ){
		 //  $minimum_amount   = $rec_item[0]['order_value'];
    $free_product_id  = $rec_item_key;
	
   
	foreach ( $cart_items as $cart_item_key => $cart_item ){
		
		if ( $cart_item['data']->get_id() == $free_product_id ) {
			
			

$price = array_column($rec_item, 'order_value');

array_multisort($price, SORT_DESC, $rec_item);


	 $free_item_key = $cart_item_key; // Free product found (get its cart item key)
		
	  $current_product_price =$cart_item['data']->get_price();
 foreach ($rec_item as $key ){
	  $minimum_amount = $key['order_value'];
	  $quantityallowed = $key['quantity'];
	   	if( $cart_subtotal   >= $minimum_amount) {
		// wc_add_notice( __($cart_subtotal ), 'notice');
	  if (  $cart_item['quantity'] <= $quantityallowed  ) {
		  
             $cart_item_subtotal +=  $cart_item['data']->get_price()*$cart_item['quantity'];
			// $cart->add_fee( 'Скидка за дополнительные товары: ', -$cart_item_subtotal );
	 }
	 else {
		 
		 $cart_item_subtotal +=  $cart_item['data']->get_price()*$quantityallowed;
		   //$wc_cart->add_fee( 'Отстъпка', $cart_item_subtotal, true  );
		   
		
	 }
	 break 1;
		}
 }
 
 }
	    }
	

  
	
	
}
$carttotalval = $cart->get_cart_contents_total();
	 
	  $cart->subtotal = $carttotalval - $cart_item_subtotal;
	   $shipping_total = $cart->get_shipping_total();
$shipping_tax   = $cart->get_shipping_tax();
	$GLOBALS['customcarttotals'] = $cart->subtotal;

//$cart->add_fee( 'Скидка за дополнительные товары: ', $cart_item_subtotal );

//$cart->total = $cart->get_cart_contents_total() - $cart_item_subtotal;
	  //$cart->total  =   $cart->subtotal;
	  //WC()->cart->calculate_shipping();
	 // $cart->add_fee( 'Скидка за дополнительные товары: ',  );
	 
}


add_action( 'woocommerce_review_order_before_order_total', 'oskar_custom_cart_total' );
add_action( 'woocommerce_before_cart_totals', 'oskar_custom_cart_total' );
function oskar_custom_cart_total($cart) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;
   $shipping_total = WC()->cart->get_shipping_total();
$shipping_tax   = WC()->cart->get_shipping_tax();

    WC()->cart->total = $GLOBALS['customcarttotals'] + $shipping_total + $shipping_tax ;
    //var_dump( WC()->cart->total);
}




function custom_woocommerce_rest_pre_insert_shop_order_object(  $order, $request, $creating ){ 
 
 
 
 
 
 	$associatetd_products = array();
$associatetd_productsss = array();
	   $products = new WP_Query( array(
        'post_type'   => 'product',
        'post_status' => 'publish',
		'posts_per_page' => 999,
        'fields'      => 'ids',
        'tax_query'   => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => 92,
            )
        ),

    ) );
  
$gift_cross_sells = $products->posts;

		
			
foreach ($gift_cross_sells as $product) {
	
	$products_in_cat = get_field( 'recommended_to_category' ,$product);
		   $productscat = new WP_Query( array(
        'post_type'   => 'product',
        'post_status' => 'publish',
		'posts_per_page' => 999,
        'fields'      => 'ids',
        'tax_query'   => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $products_in_cat,
            )
        ),

    ) );
  
$gift_cross_sellscat = $productscat->posts;
	//$recommend_to_product = 	array();
	//$associatetd_products[$product] = array();
	$recommend_to_product =  get_field( 'recommend_to_product' ,$product);
	if(is_array($recommend_to_product )){
		$recommend_to_product = $recommend_to_product;
	} elseif(!empty($recommend_to_product)){
	$recommend_to_product[] = 	$recommend_to_product;
	}
	
	//$associatetd_products[$product][] = $gift_cross_sellscat;
	// merge arrays to set unique product ids
	
	if(!empty($recommend_to_product)){
	$associatetd_products[$product] = array_map(function($a, $b){ 
                    if($a === $b) return $a;
                    return [$a, $b];
              }, $recommend_to_product, $gift_cross_sellscat);
	} else {
		$associatetd_products[$product] = $gift_cross_sellscat;
	}
	
 
}
 
 


$cart_products = array();
   foreach ($order->get_items() as $product) {
		
		$cart_products[] = $product->get_product_id();
                
				
            }
 
 //return $associatetd_products;
foreach ($associatetd_products as $key => $productss) {
	foreach ($productss as  $prod) {
		
		
	foreach ($cart_products as $cartproductss) {
		
		
	if(is_array($prod) && in_array($cartproductss, $prod)){
		
		
		
		$associatetd_productsss[$key] =  get_field( 'free_limis' ,$key);;
		
		
	}
	
	
	}
	
	
	
	}
}


$cart_item_subtotal = 0;
    // Initializing
    $cart_subtotal = 0;
   // $cart_items    = $cart->get_cart();
$order_tottal_exclude_assoc = 0;
$free_product_id  = 0;
    // Loop through cart items (first loop)
	foreach ($order->get_items() as $product) {
		
		
	 $productobj = wc_get_product( $product->get_product_id() );
		 
		   $current_product_price = $productobj->get_price(); 
	  
	  $product_qty = $product->get_quantity();
	 // $product_total = $product->get_total();
		 $product_total = ($current_product_price * $product->get_quantity());
		 if(array_key_exists( $product->get_product_id(),$associatetd_productsss) != true ){
			 $cart_subtotal += $product_total ;
		// echo 'Minus<br>';
		}
		
		
	
	$free_product_id  = 0;
	  }
	
	
	$cart_item_subtotal = 0;
	 foreach ($order->get_items() as $product) {
		 
		 
		 
		 $productobj = wc_get_product( $product->get_product_id() );
		 
		   $current_product_price = $productobj->get_price(); 
	  
	  $product_qty = $product->get_quantity();
	 // $product_total = $product->get_total();
		 $product_total = ($current_product_price * $product->get_quantity());
		 
		 
	
	  foreach ($associatetd_productsss as $rec_item_key => $rec_item ){
   $free_product_id  = $rec_item_key;
   
   
$price = array_column($rec_item, 'order_value');

array_multisort($price, SORT_DESC, $rec_item);


   
	
		
		if ( $product->get_product_id() == $free_product_id ) {
			
			//$cart_subtotal = $cart_subtotal  - $product->get_total();



	
		
	
	  
 foreach ($rec_item as $key ){
	  $minimum_amount = $key['order_value'];
	  $quantityallowed = $key['quantity'];
	   	if( $cart_subtotal   >= $minimum_amount) {
		// wc_add_notice( __($cart_subtotal ), 'notice');
	  if ( $product_qty  <= $quantityallowed  ) {
		  //$product->set_subtotal(5);
		  $product->set_total(0);
 //$cart_item_subtotal += $current_product_price * $product_qty;
             //$cart_item_subtotal +=  $cart_item['data']->get_price()*$cart_item['quantity'];
			// $cart->add_fee( 'Скидка за дополнительные товары: ', -$cart_item_subtotal );
			 break 1;
	 }
	 else {
		 
		 $cart_item_subtotal = ($product_total - ($current_product_price * $quantityallowed));
		 
		// $product->set_subtotal( $cart_item_subtotal);
		  $product->set_total( $cart_item_subtotal);


		   //$wc_cart->add_fee( 'Отстъпка', $cart_item_subtotal, true  );
		   
		 break 1;
	 }
	// $order->calculate_totals();
	
		}
 }
 
 }
	    }
	

  
	
	
	 }


 
    return $order;
} 




//add the action 
add_filter('woocommerce_rest_pre_insert_shop_order_object', 'custom_woocommerce_rest_pre_insert_shop_order_object', 10, 3);








add_action('woocommerce_checkout_create_order', 'custom_woocommerce_before_checkout_create_order', 20, 2);






function custom_woocommerce_before_checkout_create_order( $order, $data ){ 
 
 
 
 
 
 	$associatetd_products = array();
$associatetd_productsss = array();
	   $products = new WP_Query( array(
        'post_type'   => 'product',
        'post_status' => 'publish',
		'posts_per_page' => 999,
        'fields'      => 'ids',
        'tax_query'   => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => 92,
            )
        ),

    ) );
  
$gift_cross_sells = $products->posts;

		
			
foreach ($gift_cross_sells as $product) {
	
	$products_in_cat = get_field( 'recommended_to_category' ,$product);
		   $productscat = new WP_Query( array(
        'post_type'   => 'product',
        'post_status' => 'publish',
		'posts_per_page' => 999,
        'fields'      => 'ids',
        'tax_query'   => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $products_in_cat,
            )
        ),

    ) );
  
$gift_cross_sellscat = $productscat->posts;
	$recommend_to_product = 	array();
	//$associatetd_products[$product] = array();
	$recommend_to_product =  get_field( 'recommend_to_product' ,$product);
	if(is_array($recommend_to_product )){
		$recommend_to_product = $recommend_to_product;
	} elseif(!empty($recommend_to_product)){
	$recommend_to_product[] = 	$recommend_to_product;
	}
	
	//$associatetd_products[$product][] = $gift_cross_sellscat;
	// merge arrays to set unique product ids
	
	if(!empty($recommend_to_product)){
	$associatetd_products[$product] = array_map(function($a, $b){ 
                    if($a === $b) return $a;
                    return [$a, $b];
              }, $recommend_to_product, $gift_cross_sellscat);
	} else {
		$associatetd_products[$product] = $gift_cross_sellscat;
	}
	
 
}
 
 


$cart_products = array();
   foreach ($order->get_items() as $product) {
		
		$cart_products[] = $product->get_product_id();
                
				
            }
 
 //return $associatetd_products;
foreach ($associatetd_products as $key => $productss) {
	foreach ($productss as  $prod) {
		
		
	foreach ($cart_products as $cartproductss) {
		
		
	if(is_array($prod) && in_array($cartproductss, $prod)){
		
		
		
		$associatetd_productsss[$key] =  get_field( 'free_limis' ,$key);;
		
		
	}
	
	
	}
	
	
	
	}
}

$new_total = 0;
$cart_item_subtotal = 0;
    // Initializing
    $cart_subtotal = 0;
   // $cart_items    = $cart->get_cart();
$order_tottal_exclude_assoc = 0;
$free_product_id  = 0;
    // Loop through cart items (first loop)
	foreach ($order->get_items() as $product) {
		
		
	 $productobj = wc_get_product( $product->get_product_id() );
		 
		   $current_product_price = $productobj->get_price(); 
	  
	  $product_qty = $product->get_quantity();
	 // $product_total = $product->get_total();
		 $product_total = ($current_product_price * $product->get_quantity());
		 if(array_key_exists( $product->get_product_id(),$associatetd_productsss) != true ){
			 $cart_subtotal += $product_total ;
		// echo 'Minus<br>';
		}
		
		
		

	  }
	
	
	$cart_item_subtotal = 0;
	 foreach ($order->get_items() as $product) {
		 
		 
		 
		 $productobj = wc_get_product( $product->get_product_id() );
		 
		   $current_product_price = $productobj->get_price(); 
	  
	  $product_qty = $product->get_quantity();
	 // $product_total = $product->get_total();
		 $product_total = ($current_product_price * $product->get_quantity());
		 
		 
	
	  foreach ($associatetd_productsss as $rec_item_key => $rec_item ){
   $free_product_id  = $rec_item_key;
   
   
$price = array_column($rec_item, 'order_value');

array_multisort($price, SORT_DESC, $rec_item);


   
	
		
		if ( $product->get_product_id() == $free_product_id ) {
			
			//$cart_subtotal = $cart_subtotal  - $product->get_total();



	
		
	
	  
 foreach ($rec_item as $key ){
	  $minimum_amount = $key['order_value'];
	  $quantityallowed = $key['quantity'];
	   	if( $cart_subtotal   >= $minimum_amount) {
		// wc_add_notice( __($cart_subtotal ), 'notice');
	  if ( $product_qty  <= $quantityallowed  ) {
		  //$product->set_subtotal(5);
		  $product->set_total(0);
 //$cart_item_subtotal += $current_product_price * $product_qty;
             //$cart_item_subtotal +=  $cart_item['data']->get_price()*$cart_item['quantity'];
			// $cart->add_fee( 'Скидка за дополнительные товары: ', -$cart_item_subtotal );
			 break 1;
	 }
	 else {
		 
		 $cart_item_subtotal = ($product_total - ($current_product_price * $quantityallowed));
		 $new_total += $cart_item_subtotal;
		// $product->set_subtotal( $cart_item_subtotal);
		  $product->set_total( $cart_item_subtotal);


		   //$wc_cart->add_fee( 'Отстъпка', $cart_item_subtotal, true  );
		   
		 break 1;
	 }
	// $order->calculate_totals();
	
		}
 }
 break 1;
 }
	    }
	

  
	
	
	 }
	 	 
	 
$shipping_total = $order->get_shipping_total();
$shipping_tax   = $order->get_shipping_tax();
	 $new_total += $cart_subtotal + $shipping_total + $shipping_tax;
	 
	 
 $order->set_total( $new_total );

 $order->calculate_totals();
    return $order;
} 


add_filter( 'woocommerce_rest_prepare_product_object', 'acf_fix', 10, 3 );

function acf_fix( $response, $object, $request ){

    if(!empty($response->data['acf']['recommend_to_product'])){
        $response->data['recommend_to_product'] = $response->data['acf']['recommend_to_product'];
    };
    if(!empty($response->data['acf']['recommended_to_category'])){
        $response->data['recommended_to_category'] = $response->data['acf']['recommended_to_category'];
    };
    // if(!empty($response->data['acf']['free_limis'])){
    //     $response->data['free_limits'] = $response->data['acf']['free_limis'];
    // };

return $response;

}
/*----GIFTS*/
