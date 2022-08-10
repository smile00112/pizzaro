<?php

//   /wp-json/mass_discounts/v1/check  для крона
add_action('rest_api_init', function () {
	register_rest_route('mass_discounts/v1', '/check', array( //регистрация маршрута
		'methods' => 'GET',
		'callback' => 'mass_discounts',
	));
});
function mass_discounts() {

	$discounts = get_all_discounts();
	//print_r($discounts);

	foreach($discounts as $item){
		
		$name = $item->post_title;
		$discount_id = $item->ID;
		$procent = get_post_meta($discount_id, 'proc', true);
		$time_from = get_post_meta($discount_id, 'time_from', true);
		$time_to = get_post_meta($discount_id, 'time_to', true);
		$categories = get_post_meta($discount_id, 'category', true);
		print_R($categories);
		$time_now = strtotime(date ( 'Y-m-d H:i:s') );
	}
	if(empty($time_from)){
		if(strtotime($time_to) > $time_now){
			add_discount( $procent , $categories);
		}else{
			remove_discount($categories);
		}
	}else{
		if(strtotime($time_from) > $time_now){

		}else{
			if(strtotime($time_to) > $time_now){
				add_discount( $procent, $categories );
			}else{
				remove_discount($categories);
			}
		}
	}
	// if(strtotime($time_from))
	// 	add_discount(41959);
}

function get_all_discounts(){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'asc',
		'post_type'        => 'mass_discount',
		'post_status'      => 'publish',
		'meta_key'         => 'status',
		'meta_value'  => 	'1',
	);
	
	$d = get_posts( $args );
	
	return $d;
}
function add_discount($discount, $categories = [], $post_id=0){
	global $wpdb;

	$rel = [];
	if(!empty($categories))
		$rel = array(
			// Использование нескольких таксономий требует параметр relation
			'relation' => 'AND', // значение AND для выборки товаров принадлежащим одновременно ко всем указанным терминам
			// массив для категории имеющей слаг slug-category-1
			array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $categories
			),

		);

	// Выполнение запроса по категориям и атрибутам
	$args = array(
		// Использование аргумента tax_query для установки параметров терминов таксономии
		'tax_query' => $rel,
		// Параметры отображения выведенных товаров
		'posts_per_page' => 99999, // количество выводимых товаров
		'post_type' => 'product', // тип товара
		'orderby' => 'title', // сортировка
	);
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$product = wc_get_product(get_post());
		$price = $product->get_price();
		$sale_price = round( $price - ($price * $discount / 100) );//ROUND(pm2.meta_value - (pm2.meta_value * $discount / 100) )

		//echo $price . '/' . $sale_price. '___';
		// $product->id
		update_post_meta($product->id, '_sale_price', $sale_price);
		the_title();
		//echo '---';
	endwhile;



	//update_post_meta($productId, '_sale_price', '');
	//update wp_postmeta set meta_value = meta_value * 1.135 where meta_key='_sale_price'	
	//update wp_postmeta set meta_value = meta_value * 1.135 where meta_key='_price'

	return false;
}

function remove_discount($categories = [], $post_id=0){
	echo 'remove_discount';
	global $wpdb;
	$rel = [];
	// Выполнение запроса по категориям и атрибутам
	$args = array(
		// Использование аргумента tax_query для установки параметров терминов таксономии
		'tax_query' => $rel,
		// Параметры отображения выведенных товаров
		'posts_per_page' => 99999, // количество выводимых товаров
		'post_type' => 'product', // тип товара
		'orderby' => 'title', // сортировка
	);
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$product = wc_get_product(get_post());
		$price = $product->get_price();
		$sale_price = round( $price - ($price * $discount / 100) );//ROUND(pm2.meta_value - (pm2.meta_value * $discount / 100) )

		//echo $price . '/' . $sale_price. '___';
		// $product->id
		delete_post_meta($product->id, '_sale_price', '');
		the_title();
	endwhile;

}

