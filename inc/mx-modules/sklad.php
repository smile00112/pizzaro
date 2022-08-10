
/*** показываем товары с нужного склада ***/
//add_action( 'woocommerce_product_query', 'set_stock_product_query' );
function set_stock_product_query( $q ){
//$low_stock = get_post_meta();	
$StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; // Id склада из куки
if( $StockId ){ // если Id получен добавляем в запрос товаров условие
    $meta_query = $q->get( 'meta_query' ); 
    $meta_query[] = array(
		//'key' => '_stock_at_'.$StockId, // метаполе с количеством товара
        'key' => '_stock_status-'.$StockId, // метаполе с количеством товара 
        'value' => 0,
        'compare' => '>'
        ); 
    $q->set( 'meta_query', $meta_query );
}
}



//add_filter( 'woocommerce_shortcode_products_query', 'filter_function_name_4282', 10, 3 );
function filter_function_name_4282( $query_args, $attributes, $type ){
$StockId = ( $_COOKIE['StockId'] ) ? intval( $_COOKIE['StockId'] ) : null; 

if( $StockId ){ 
    $meta_query = $query_args['meta_query']; 
    $meta_query[] = array(
		//'key' => '_stock_at_'.$StockId,
        'key' => '_stock_status-'.$StockId, 
        'value' => 0, //если ниже порога доступности, то не выводить товар на данном складе
        'compare' => '>'
        ); 
    $query_args['meta_query'] = $meta_query;
}
    return $query_args;
}

// установка статуса in_stock в зависимости от наличия на нужном складе
//add_filter( 'woocommerce_product_is_in_stock', 'filter_function_name_5277', 10, 2 );
function filter_function_name_5277( $in_stock, $product ) { // in_stock - статус 0/1
    if ($in_stock && is_product() ) { // если страница товара
		 //debug_to_file('low stock check prod_id:'.$product);
        $StockId = ( $_COOKIE[ 'StockId' ] ) ? intval( $_COOKIE[ 'StockId' ] ) : null; // id склада
        if ( $StockId ) { // если получен id склада
            $quantity = get_post_meta( $product->get_id(), '_stock_at_' . $StockId, true ); // проверяем количество на этом складе
			$low_stock = get_post_meta( $product->get_id(), '_low_stock_amount', true ); // проверяем количество относительно порога доступности продажи
            //if ( !$quantity ) { // если нет на складе
            //    $in_stock = 0; // указываем что нет на складе
            //}
			//if ( !$quantity ) { // если нет на складе
			if($quantity <= $low_stock) { //debug_to_file('low stock check');
				$in_stock = 0;
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
    
// checkWorkingHours();
// let timerId = setInterval(() => checkWorkingHours(), 1000*60*1);
// function checkWorkingHours(){
//         var data = {
//             action: 'checkWorkingHours',
//         };
// jQuery.post( '<?php
//  echo admin_url('admin-ajax.php') 
 ?>', data, function(response) {
// var obj = JSON.parse( response );
// if ( obj.status == 'closed' ){
// jQuery('.top_header-date').show(500);
// jQuery('.top_header-date .top_header-date_top').html('Мы работаем с '+obj.opening+', но уже сейчас готовы принять заказ.'); 
// } else {
// jQuery('.top_header-date').hide();  
// }
//         });;    
// }       
// jQuery(document).on( 'click', '.top_header-date', function() {  
// checkWorkingHours();
// }); 
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

// функция вызываемая ajax запросом, включение/отключение action позволяет контроллировать товары добавленные в корзину
// относятся ли эти товары к адресу/склада, который указал пользователь, и вслучае чего, показывает уведомление и очищает корзину
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
foreach($setStock['unset_items'] as $item ){ //print_r($item);
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

/////////////////////////////////////////////////////////
///////удержание общего количества товара, чтоб можно было списать для конкретного склада
add_filter( 'woocommerce_order_item_quantity', 'hold_order_item_quantity', 10, 3 );
function hold_order_item_quantity( $quantity, $order, $item ) {
	debug_to_file('quantity: '.$quantity);
	debug_to_file('order: '.$order);
    $quantity = 0;

    return $quantity;
}


//add_action( 'woocommerce_after_shop_loop_item', 'display_loop_item_stock_notice', 90 );
function display_loop_item_stock_notice(){
	global $product;
	debug_to_file('prod_ID: '.$product->get_ID());
	if ( $product->is_in_stock() ) { 
        debug_to_file('in stock');
    } 
    // Out of stock
    else {
        debug_to_file('out stock');
    }
}


////////получение всех складов
function get_all_stock(){
	$stock_terms = get_terms( [ 'taxonomy' => 'location', 'hide_empty' => true, ] );
	$stock_arr_id = [];
	foreach($stock_terms as $s){
		array_push($stock_arr_id, $s->term_id);
	}
	
	return $stock_arr_id ;
}

////получение общего кол-ва по всем складам
//add_action( 'woocommerce_single_product_summary', 'mh_output_stock_status', 21 );
function mh_output_stock_status ( ) {
    global $product;

    debug_to_file('prod_ID: '.$product->get_ID());
	if ( $product->is_in_stock() ) { 
        //debug_to_file('in stock');
    } 
    // Out of stock
    else {
        //debug_to_file('out stock');
    }
	$low_stock = get_post_meta($product->get_ID(), '_low_stock_amount', true);
	?>
		<script type="text/javascript" >
			localStorage.setItem('low-stock-<?php echo $product->get_ID(); ?>', '<?php echo $low_stock; ?>');
		</script>
	<?php
	
	$stock_arr_id = get_all_stock();
	foreach($stock_arr_id as $stock){
		$stock_qty = get_post_meta($product->get_ID(), '_stock_at_'.$stock, true);
		
		$GLOBALS['stock_qty'][$stock] = $stock_qty;
		?>
		<script type="text/javascript" >
			localStorage.setItem('stock-qty-<?php echo $stock; ?>', '<?php echo $stock_qty; ?>');
			
		</script>
		
		<?php
	}
	//print_r($GLOBALS['stock_qty']);
	
}


//проверка наличия на определённом складе для фронта в карточке товара
//add_action('wp_footer', 'get_stock_front', 99); 
function get_stock_front() {
	global $product;
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) { 
		var product_id = $('.single-product #main .product').attr('id');
		if(product_id){
		product_id = product_id.replace('product-','');
		console.log(product_id);
		
		var storage_id  = localStorage.getItem( 'storageId' );
		var storage_qty = localStorage.getItem( 'stock-qty-' + storage_id );
		var low_stock = localStorage.getItem( 'low-stock-' + product_id );
		console.log('storage_id: ', storage_id);
		console.log('stock-qty: ',storage_qty);
		console.log('low-stock: ',low_stock);
		
		jQuery(document).ready(function($) {//добавление блока для вывода кол-ва по складам    
			$('.single-product #main').append('<div id="stocks_qty"></div>');
			var i;
			for(i = 0; i < 4 ; i++ ){		
				$('.single-product #main #stocks_qty').append('<div class="stocks_item" data-st-id="'+i+'" data-st-qty="'+1+'"></div>');
			}
			$('.single-product #main .product .stock').addClass('show-stock-block');
			if(storage_qty > low_stock) $('.single-product #main .product .stock').text(storage_qty + ' в наличии');
			else{
				$('.single-product #main .product .stock').text('Нет в наличии');
				$('.single-product #main .product .stock').removeClass('in-stock');
				$('.single-product #main .product .stock').addClass('out-of-stock'); 
				$('.single-product #main .product .single_add_to_cart_button').attr('disabled', 'true');
			}
		});
		}
	});
	</script>
		
	<?php //}
}



/*add_action( 'pre_get_posts', 'filter_low_stock_loop' );
function filter_low_stock_loop($query){
	if(is_shop()){
		$query->set('meta_key', '_low_stock_amount');
		$query->set('meta_value', '3');
	}
	
}*/

//изменение статуса наличия при обновлении заказа(в заказе должно указываться, с какого склада вычет)
function save_low_stock_status( $product_id ) { //debug_to_file('proccess lowstock meta: '.$product_id);
    $product = wc_get_product( $product_id );
	$StockId = ( $_COOKIE[ 'StockId' ] ) ? intval( $_COOKIE[ 'StockId' ] ) : null; // id склада
	$quantity = get_post_meta( $product_id, '_stock_at_' . $StockId, true ); // проверяем количество на этом складе
	$low_stock = get_post_meta( $product_id, '_low_stock_amount', true ); // проверяем количество относительно порога доступности продажи
	//debug_to_file('quantity: '.$quantity);
	//debug_to_file('low_stock: '.$low_stock);
	if($quantity <= $low_stock){ //_stock_status
		update_post_meta($product_id, '_stock_status-'.$StockId, '0');
		debug_to_file('change to low stock');
	}
	else if($quantity > $low_stock){
		update_post_meta($product_id, '_stock_status-'.$StockId, '1');
	}
	
	
}