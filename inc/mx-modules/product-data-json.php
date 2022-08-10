<?php

/////запись данных о товарах и категориях в json файл, оттуда приложение загружает данные, чтоб быстрее работало

function get_cat_list(){
	ini_set('display_errors', 'On'); //отключение ошибок на фронте
	ini_set('log_errors', 'On'); //запись ошибок в логи
	//remove_action('wp_footer', 'get_cat_list'); //чтоб не зациклилось
	$new_arr = array();
	
	$base_portion_size = get_field('base_portion_size', 'option');

	//$start = microtime(true);


	$all_product_data = get_all_products();
	$categories = build_menu_n();

	//echo '___Время1: '. round(microtime(true) - $start);	
	$all_products_to_categories = prepare_prod_list($all_product_data);
	//print_R($all_products);
	
	//echo '___Время2: '. round(microtime(true) - $start);	
	$parent_prods = [];
	$cats_map = []; 
	$ii = 0;
	foreach($categories as $k){
		if(strpos( mb_strtolower($k['name']), 'пиво' ) !== false ) continue;
		if(strpos( mb_strtolower($k['name']), 'сигареты' ) !== false ) continue;
		   if($k['name'] == 'Пиво') continue;
		   if($k['name'] == 'Пиво б/а') continue;

	   //$prod_list = json_decode(get_prod_list($k['id']), true);
	   $prod_list = $all_products_to_categories[$k['id']];
	   //if(!empty($prod_list))
	   {
		   $temp_cat = [
			   'id' => $k['id'],
			   'name' => $k['name'],
			   'parent' => $k['parent'],
			   'image' => $k['image']['src'],
			   'count' => $k['count'],
			   'columns' => (int)$k['columns'],	
			   'products' => [],
		   ];

		   if(!empty($k['parent'])){
			   foreach($prod_list as $pp){
				   $parent_prods[$k['parent']]['products'][]=$pp;
			   }

		   }else{
			   if(!empty($parent_prods[$k['id']]['products']))
				   $prod_list = $parent_prods[$k['id']]['products'];
		   }

		   $temp_cat['products'] = $prod_list;
		   array_push($new_arr, $temp_cat);
		   $cats_map[$temp_cat['id']] = $ii;
		   $ii++;
	   }
	   //break;
   }
	//echo '___Время3: '. round(microtime(true) - $start);	

	$new_json = json_encode($new_arr, JSON_UNESCAPED_UNICODE);
	prodcat_to_file($new_json);
	
}

function get_all_products(){
	$all_products = [];
	for($ic=1; $ic<55; $ic++){
		$url_domain = 'https://'.$_SERVER['SERVER_NAME']; //debug_to_file($url_domain); 
		$url_api = '/wp-json/wc/v2/products?status=publish&per_page=100&page='.$ic.'&orderby=menu_order&order=asc&in_stock=true&consumer_key=ck_8e9043f849e95e6d003c3cc2474fc22b2ed01eec&consumer_secret=cs_74c746f821c405606c0950997a33b194ffc06876';
		$prod = curl_init($url_domain.$url_api);
		curl_setopt($prod, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
		curl_setopt($prod, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($prod, CURLOPT_RETURNTRANSFER, 1);

		$result_prod = curl_exec ( $prod ); //debug_to_file($result);
		curl_close($prod);
		
		//add_action('wp_footer', 'get_cat_list'); //возвращаем удалённый hook
		
		$arr_prod = json_decode($result_prod, true);
		if(empty($arr_prod)) break; 
		$all_products = array_merge($arr_prod, $all_products);
		echo 'i'.$ic.'<br>';
	}
	return $all_products;
}

function prepare_prod_list($list = []){
	$new_prod_list = [];
	$base_count_type = '';
	foreach($list as &$p){

		$new_meta = $new_variations = $new_locations = $new_acf =  [];
		$base_count_type = $countable = $meausure_type_code = $portion_nat_size  = null;
		$meausure_unit = $sub_measure_unit = 'шт';
		$base_portion_size = 1;

		foreach($p['meta_data'] as $m){
			if($m['key'] == 'meausure_type_code')
				$base_count_type = $m['value'];					
			if($m['key'] == 'portion_size')
				$base_portion_size = $m['value'];
			if($m['key'] == 'meausure_unit')
				$meausure_unit = $m['value'];
			if($m['key'] == 'meausure_type_code')
				$meausure_type_code = $m['value'];
			if($m['key'] == 'countable')
				$countable = $m['value'];	
			if($m['key'] == 'portion_nat_size')
				$portion_nat_size = $m['value'];	
			if($m['key'] == 'sub_measure_unit')
				$sub_measure_unit = $m['value'];								
				
			if(in_array($m['key'], ['_use_discount_sklad','_disc_limit_stok_all','_disc_limit_stok_119','_disc_limit_stok_110','_disc_limit_stok_118','_disc_limit_stok_111','ingridients','cooking_time'])){
				$new_meta[]=$m;
			}
		}


		if(!empty($p['product_variations_data']))
		foreach($p['product_variations_data'] as $v){
			$new_variations[]=[
				'id' => $v['id'],
				'description' => $v['description'],
				'price' => $v['price'],
				'regular_price' => $v['regular_price'] ? $v['regular_price'] : $v['price'],
				'image' =>  $v['image']['src'],
				'attributes' => $v['attributes'],
				//'meta_data' => $v['meta_data'],
				'in_stock' => $v['in_stock'],
			];
		}
		if(!empty($p['locations']))
		foreach($p['locations'] as $l){
			$new_locations[]=[
				'id' => $l['id'],
				'quantity' => !empty($l['quantity']) ? (string)$l['quantity'] : "0",
			];
		}	
		if(!empty($p['acf'])){
			if(!empty($p['acf']['video_url'])){
				$p['acf']['video_url'] = [ $p['acf']['video_url']['url'] ];
			}
			$new_acf = $p['acf'];
		}	
	

		/* Веса */
		// $meausure_unit_nat = 'шт';
		// if($meausure_type_code == 'KGM'){
		// 	$meausure_unit_nat = 'кг';
		// }elseif($meausure_type_code == 'GRM'){
		// 	$meausure_unit_nat = 'кг';
		// }elseif($meausure_type_code == 'CMQ'){
		// 	$meausure_unit_nat = 'л';
		// }

		// $sub_measure_unit = $meausure_unit;
		// if(!$countable){
		// 	if($meausure_unit == 'гр') $meausure_unit = 'кг';
		// 	if($meausure_unit == 'л') $meausure_unit = 'мл';
		// }
		/* Принудительно устанавливаем единицы для невесовых товаров, т.к. при обратном сохранении они остаются от весовых */
		if($countable=="1" || is_null($countable)){
			$sub_measure_unit = 'шт';
			$meausure_unit = 'шт';
			$base_portion_size = 1;
			$portion_nat_size = 1;
		}
		$new_prod_list[$p['id']]=[
	
			'id' => $p['id'],
			'name' => $p['name'],
			'measure_unit' => $sub_measure_unit,
			'sub_measure_unit' => $meausure_unit,
			'countable' => ( ($countable=="1" || is_null($countable)) ? true : false ),	
			//'countable2' => $countable,	
			'base_portion_size' =>  $base_count_type != 'PCE' ? intval($base_portion_size) : null ,
			'portion_nat_size' =>  round( $portion_nat_size, 1) ,
			'permalink' => $p['permalink'],		
			'type' => $p['type'],
			'description' => $p['description'],
			'short_description' => $p['short_description'],
			'price' => $p['price'],
			'regular_price' => !empty($p['regular_price']) ? $p['regular_price'] : $p['price'],
			'manage_stock' => $p['manage_stock'],
			'stock_quantity' => $p['stock_quantity'],
			'in_stock' => $p['in_stock'],
			'backorders_allowed' => $p['backorders_allowed'],
			'backordered' => $p['backordered'],
			'average_rating' => $p['average_rating'],
			'rating_count' => $p['rating_count'],
			'upsell_ids' => $p['upsell_ids'],
			'cross_sell_ids' => $p['cross_sell_ids'],
			'parent_id' => $p['parent_id'],
			'categories' => array_column( $p['categories'], 'id' ),
			'images' => array_column( $p['images'], 'src' ),
			'attributes' => $p['attributes'],
			'default_attributes' => $p['default_attributes'],
			'variations' => $p['variations'],
			'related_ids' => $p['related_ids'],
			'meta_data' => $new_meta,
			'free_limits' => $p['free_limits'],
			'acf' => $new_acf,
			'locations' => $new_locations,
			'recommended_to_category' => $p['recommended_to_category'],
			'recommend_to_product' => $p['recommend_to_product'],
			'quantity_sale' => $p['quantity_sale'],
			//'attributesData' => $p['attributesData'],
			'weight' => $p['weight'],		
			'product_variations_data' => $new_variations,
		];
	}
	
	$new_prod_list_to_categories = [];
	foreach($new_prod_list as $p){
		if(!empty($p['categories'])){
			foreach($p['categories'] as $p_c){
				$new_prod_list_to_categories[$p_c][]=$p;
			}
		}
	}
	return $new_prod_list_to_categories;
}

function get_prod_list($cat_id){
	
	$url_domain = 'https://'.$_SERVER['SERVER_NAME']; //debug_to_file($url_domain); 
	$url_api = '/wp-json/wc/v2/products?status=publish&category='.$cat_id.'&per_page=200&orderby=menu_order&order=asc&in_stock=true&consumer_key=ck_8e9043f849e95e6d003c3cc2474fc22b2ed01eec&consumer_secret=cs_74c746f821c405606c0950997a33b194ffc06876';
	
	 
	$prod = curl_init($url_domain.$url_api);
	
	curl_setopt($prod, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
	curl_setopt($prod, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($prod, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
	
	$result_prod = curl_exec ( $prod ); //debug_to_file($result);
	
	//if(curl_error($ch)) { debug_to_file(curl_error($ch)); }
	curl_close($prod);
	
	//add_action('wp_footer', 'get_cat_list'); //возвращаем удалённый hook
	
	$arr_prod = json_decode($result_prod); //debug_to_file($arr);
	
	
	return $result_prod;
}


function prodcat_to_file($cont){
	//$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/themes/pizzaro/inc/mx-modules/files/prcat.json';
	$ctime = time();
	$path_new = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/prodcat.json';
	$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/2021/04/dataset_1.json';
	$path_tmp = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/prodcat_tmp.json';
	$file_tmp = fopen($path_tmp, 'w');
	fwrite($file_tmp, $cont);
	fclose($file_tmp);
	
	if(file_exists($path_tmp)) {
		//$cont_tmp = file_get_contents($path_tmp);
		$cont_tmp = $cont;
		//$json = json_decode($cont_tmp, true);
		
		$file = fopen($path, 'w');
		fwrite($file, $cont_tmp);
		fclose($file);
		
		//return $json;
	}
	rename($path_tmp, $path_new);
	/* 	if(file_exists($path_tmp)) {
			
			//$cont_tmp = file_get_contents($path_tmp);
			$cont_tmp = $cont;
			//$json = json_decode($cont_tmp, true); 
			
			$file = fopen($path_new, 'w');
			fwrite($file, $cont_tmp);
			fclose($file); 

			rename($path_tmp, $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/prodcat_2_.json');

			//return $json;
		} 
	*/
	
	//unlink($path_tmp); //удаление временного файла
	
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/productdata', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'get_product_json'
	));
});

function get_product_json(WP_REST_Request $request ){
	//$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/themes/pizzaro/inc/mx-modules/files/prcat.json';
	$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/app_sync/prodcat.json';
	//$path = $_SERVER['DOCUMENT_ROOT']. '/wp-content/uploads/2021/04/dataset_1.json';
	
	if ( !file_exists($path) ) {
		get_cat_list();
	}

	if ( file_exists($path) ) {
		$file = file_get_contents($path);
		$json = json_decode($file, true);
		return $json;
	}
	
	return 0;
}

/////исправление, если в json имеются некорректные символы
function repair_json($json){
	for ($i = 0; $i <= 31; ++$i) { 
		$json = str_replace(chr($i), "", $json); 
	}
	$json = str_replace(chr(127), "", $json);

	// This is the most common part
	// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
	// here we detect it and we remove it, basically it the first 3 characters 
	if (0 === strpos(bin2hex($json), 'efbbbf')) {
	   $json = substr($json, 3);
	}
	
	return $json;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'systeminfo/v1', '/updateproductdata', array( //регистрация маршрута
		'methods'             => 'GET',
		'callback'            => 'get_cat_list'
	));
});

function build_menu_n(){

	$taxonomy     = 'product_cat';
	$orderby      = 'menu_order';
	$show_count   = 0;      // 1 for yes, 0 for no
	$pad_counts   = 0;      // 1 for yes, 0 for no
	$hierarchical = true;      // 1 for yes, 0 for no  
	$title        = '';
	$empty        = false;

	$args = array(
		'taxonomy'     => $taxonomy,
		'orderby'      => 'menu_order',
		'show_count'   => $show_count,
		'pad_counts'   => $pad_counts,
		'hierarchical' => $hierarchical,
		'title_li'     => $title,
		'hide_empty'   => $empty,
		'exclude' 		 => array(1,15)
	);

	$all_categories = get_categories($args);
	$menu_structure = [];
	foreach ($all_categories as $index=>$cat) {
		
		//if ($cat->category_parent == 0) 
		$have_products =  custom_products_check_nal22( $cat->slug );
		if($have_products)

			$menu_structure[$cat->term_id]= [
				'id'  => $cat->term_id,
				'name' => $cat->name,
				//'show' => $have_products ? true : false,
				'slug' => get_category_link( $cat->term_id ),
				'parent' => $cat->category_parent,
				'columns' => get_term_meta( $cat->term_id, 'columns_count', true ) ? : 1,
			];
				
	}
	//$GLOBALS['category_menu'] = $menu_structure;

	return	$menu_structure;
}

function custom_products_check_nal22($category_slug) {
	$GLOBALS['products'] = new WP_Query([
	  'post_type'             => 'product',
	  'post_status'           => 'publish',
	  'tax_query'             => [
		[
		  'taxonomy' => 'product_cat',
		  'field'    => 'slug',
		  'terms'    => $category_slug,
		  'include_children' => true,
		]
	  ],
	  'meta_query' => [ [
		  'key' => '_stock_status',
		  'value' => 'instock',
	  ] ],	
	]);
  
	return $GLOBALS['products']->have_posts();
  } 
/* add_action('save_post_product', 'get_cat_list');
add_action('saved_product_cat', 'get_cat_list'); */