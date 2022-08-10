<?php
exit;
/**
 * Pizzaro engine room
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
//require_once( get_template_directory() . '/select-address/min-amount_and_free-ship.php' );

// Подключение карты

//require_once( get_template_directory() . '/select-address/functions.php' );

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
add_action('woocommerce_thankyou', 'new_order');

function new_order($order_id) {
    
    //$exchange = new Exchange();
    //$exchange->sendOrder($order_id);
        
    $order = wc_get_order($order_id);
	if(($order->get_status() != 'pending')){
   
    SMS::send($order->get_billing_phone(), 'Заказ принят. Ваш Ролл Кролл.');
	}

}

add_filter('woocommerce_checkout_fields', 'prefill_checkout');

function prefill_checkout($fields) {
    
    $data = WC()->session->get('custom_data');
    
    $fields['billing']['billing_address_1']['default'] = $data['address_1'];
    $fields['billing']['billing_city']['default'] = $data['city'];
    
    $fields['shippiing']['shippiing_address_1']['default'] = $data['address_1'];
    $fields['shippiing']['shippiing_city']['default'] = $data['city'];

    unset($fields['billing']['billing_country']);  //удаляем! тут хранится значение страны оплаты
    unset($fields['shipping']['shipping_country']); ////удаляем! тут хранится значение страны доставки    
    
    return $fields;
}


add_action('init', function(){
    
});






add_action( 'woocommerce_rest_product_cat_query', 'filter_function_name_7971', 10, 2 ); 

function filter_function_name_7971( $prepared_args ){
$prepared_args['menu_order'] = 1;

	return $prepared_args;
}

//wp_deregister_script('jquery'); 
//wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"), false, '3.3.1', true); 
//wp_enqueue_script('jquery');

add_action( 'wp_enqueue_scripts', 'baur_theme_scripts' );
add_action( 'wp_enqueue_scripts', 'baur2_theme_scripts' );
function baur2_theme_scripts() {
    wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	//wp_enqueue_script( 'datetimepickerJs', get_template_directory_uri() . '/assets/js/other/jquery.datetimepicker.full.min.js', array( 'jquery' ) );
}
function baur_theme_scripts() {
    wp_enqueue_style( 'baurCss', get_template_directory_uri() . '/assets/css/baur.css', array(), '1.0.'.strval(rand(123, 999)) );
    wp_enqueue_script( 'baurJs', get_template_directory_uri() . '/assets/js/baur.js', array( 'jquery' ), '1.1.0', true );
}


///////////////////mx media connect
add_action( 'wp_enqueue_scripts', 'mx_theme_scripts' );
function mx_theme_scripts(){
	wp_enqueue_style( 'mxCss', get_template_directory_uri() . '/assets/css/mx.css', array(), '1.0.'.strval(rand(123, 999)) );
	//wp_enqueue_script( 'momentJs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js' );
	//wp_enqueue_script( 'datetimepickerJs', get_template_directory_uri() . '/assets/js/other/jquery.datetimepicker.full.min.js' );
	//wp_enqueue_script( 'datetimepickerJs', get_template_directory_uri() . '/assets/js/other/datetimepicker-custom.js', array( 'jquery' ), true );
	//wp_enqueue_script( 'timepickerJs', get_template_directory_uri() . '/assets/js/other/jquery-ui-timepicker-addon.js' );
	wp_enqueue_script( 'mxJs', get_template_directory_uri() . '/assets/js/mx.js', array( 'jquery' ), '1.0.'.strval(rand(123, 999)), true );
	//
	//
	
	//w
	//wp_enqueue_script( 'mxJsadmin', get_template_directory_uri() . '/assets/js/admin/mx-admin.js', array( 'jquery' ), '1.0.'.strval(rand(123, 999)), true );
}

add_action('admin_enqueue_scripts', 'mx_admin_media');
function mx_admin_media(){
	//wp_enqueue_script('admin-script', get_template_directory_uri() . '/assets/js/admin/mx-admin.js', array( 'jquery' ), '1.0.'.strval(rand(123, 999)), true );
	//wp_enqueue_style( 'admin-css', get_template_directory_uri() . '/assets/css/admin/mx-admin.css', array(), '1.0.'.strval(rand(123, 999)) );
	//wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/style-admin.css');
}


/*add_action('wp_enqueue_scripts', 'custom_datepicker');
function custom_datepicker() {
    //wp_enqueue_script('jquery-ui-datepicker');
    //wp_enqueue_script('jquery-ui-core');          
    wp_enqueue_script('jquery-ui-timepicker-addon',get_stylesheet_directory_uri().'/js/jquery-ui-timepicker-addon.js',array());
    wp_enqueue_style('jquery-ui-timepicker-addon',get_stylesheet_directory_uri().'/css/jquery-ui-timepicker-addon.css',array());
    wp_enqueue_style('jquery-ui',get_stylesheet_directory_uri().'/css/jquery-ui.css',array());  
}*/


////////////////////////////



function my_awesome_func( WP_REST_Request $request ){
$user = get_user_by( 'login',  $request['slug'] );
$have_gift = get_user_meta( $user->ID, 'have_gift', true );
	if ( $have_gift != true || $have_gift != 'true' ) return 'false';
		
 


	

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

//add_action( 'woocommerce_checkout_process', 'user_fields_woocommerce_checkout_process' );

function user_fields_woocommerce_checkout_process(){

  if( is_user_logged_in() )
  add_action('woocommerce_checkout_update_user_meta', 'my_custom_checkout_field_update_user_meta' );

  else 
  add_action( 'woocommerce_created_customer',  'my_custom_checkout_field_update_user_meta' );
}

add_action( 'woocommerce_order_status_processing', 'mysite_hold');
add_action( 'woocommerce_order_status_completed', 'mysite_hold');
add_action( 'woocommerce_order_status_on-hold', 'mysite_hold');
function mysite_hold( $order_id) {
 


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
        update_user_meta( $user_id,  'have_gift', 'true');
		 } else {
			//update_user_meta( $user_id,  'have_gift', 'false' ); 
		 }
     }
	 
	 
  
}




function wpschool_api_options_page( ) {
    ?>
    
        <h2>Условие расчёта стоимости доставки</h2>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">


    
    <?php
      //echo DB_NAME.DB_USER.DB_PASSWORD.DB_HOST;

#&&&&&&&&&&&&&&&&&&&&&&&& подключение к БД &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
$user = DB_USER;                      ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
$password = DB_PASSWORD;     ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
$dbname = DB_NAME;                    ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
$host = DB_HOST;
                                    ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
$db = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
try {                               ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
  $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->exec("set names utf8");      ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
}                                   ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
catch(PDOException $e) {            ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    echo $e->getMessage();          ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
}                                   ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//$conn = new PDO('sqlite:/home/lynn/music.sql3');
#&&&&&&&&&&&&&&&&&&&&&&&& подключение к БД &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

$q = "SELECT * FROM `dost_cost` WHERE `id` = 1";
$stmt = $db->query($q);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll(); 

foreach ($rows as $key => $value) {
     $data = unserialize($value['data']);
}

if(is_array($data)){

// echo '<pre>';
// print_r($data);
// echo '</pre>';

$coord = '';
$square = '';
    if(!empty($data['coord'])){
      $coord = $data['coord'];
      //echo 'Координаты '.$data['coord'];
    }
    if(!empty($data['square'])){
      $square = $data['square'];
      //echo 'Координаты '.$data['coord'];
    }
foreach ($data as $key => $value) {

    


    if (strpos($key, 'rast_') !== false) {
        if(!empty($value[0])){
            $ex = explode('_', $key);
            $p = $ex[1];
            if(empty($d)){$d = '';}
            if(empty($min_data)){$min_data = '';}
            if(empty($echo)){$echo = '';}
            if(empty($echo_rest)){$echo_rest = '';}
            if(empty($parent)){$parent = 0;}


            foreach ($data['min_sum_'.$ex[1]] as $key_data => $value_data) {
                $d.='<h6 class="card-subtitle mb-2 text-muted">
                Мин. сумма заказа  '.$value_data.'  руб. стоимость на доставку  '.$data['cost_'.$ex[1]][$key_data].'  руб.
                </h6>';
                $min_data.='
                            <div class="col-xs-12 col-md-12 mb-6 row  min_par" id="in'.$p.$key_data.'">
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group col-md-12">
                                      <label>Мин. сумма заказа</label>
                                        <input autocomplete="off" class="input form-control nal" name="min_sum_'.$p.'[]" type="text" placeholder="Мин. сумма заказа" value="'.$value_data.'"/>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4 ">
                                    <div class="form-group col-md-12">
                                      <label>Cтоимость доставки</label>
                                        <input autocomplete="off" class="input form-control nal" name="cost_'.$p.'[]" type="text" placeholder="стоимость доставки" value="'.$data['cost_'.$ex[1]][$key_data].'"/>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4 "> 
                                    <div class="form-group col-md-12"> 
                                        <button class="btn remove-in2 btn-danger" type="button" data-co="f1_count_in" data-id="'.$p.$key_data.'">-</button> 
                                    </div> 
                                </div>
                            </div>
                '; 
                $parent = $parent+1;               
            }
            $echo .= '
                <div class="card col-xs-12 col-md-12 mb-1">
                  <div class="card-body">
                    <h5 class="card-title">Расстояние до  '.$value[0].'  км.</h5>
                    '.$d.'
                  </div>
                </div>

            ';
            
            $echo_rest .='

                        <div class="col-xs-12 col-md-12 row main_par" id="f'.$p.'" data-id="'.$p.'" style="order: '.$p.';">
                                <div class="col-xs-12 col-md-12 row">
                                    <div class="form-group col-md-3">
                                      <label>Если расстояние до ...</label>
                                        <input autocomplete="off" class="input form-control nal" name="rast_'.$p.'[]" type="text" placeholder="Расстояние до ..." data-items="" value="'.$value[0].'"/>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button class="btn remove-in1 btn-danger" type="button" data-co="f1_count_in" data-id="'.$p.'">Удалить условие</button>
                                    </div>
                                </div>                            
                                
                                <!-- -->
                                
                                <div class="col-xs-12 col-md-12 mb-6" id="add_in_in'.$p.'"></div>
                                '.$min_data.'
                                <div class="col-xs-12 col-md-12 " style="order: 999;">
                                    <div class="form-group col-md-4">

                                        <button class="btn add-more-more btn-primary" type="button" 
                                        data-co="f'.$p.'_count_in"
                                        data-id="'.$p.$parent.'"
                                        data-parent="'.$p.'"
                                         >+</button>
                                    </div>
                                </div> 
                                <input type="hidden" name="count_in" id="f'.$p.'_count_in" value="'.$p.$parent.'"> 
                                <!-- // -->                                                  
                            <hr>   
                        </div>
                        
            '; 
            $max_v[] = $ex[1];          
            $count = $ex[1];
        }
        
    }
    unset($p,$d,$parent);    
    unset($p,$d,$min_data);
}



}

if(is_array($max_v)){
  $max = max($max_v);
}

// echo '<pre>';
// print_r($max_v);
// echo '</pre>';

if(empty($max)){
    $count = 1;
}else{
    $count = $max+1;
}
?>

<div class="row col-xs-12 col-md-12">
<form role="form" autocomplete="off" class="col-xs-12 col-md-12" id="res_form">            

                        <div class="col-xs-12 col-md-12 form-inline" id="res">
                            
                            <?php
                            echo '<div class="row mb-5">'.$echo.'</div>';
                            ?>
                        </div>
                        <input type="hidden" name="count" id="count" value="<?php echo $count;?>">
                        
                        <hr>
                        <div id="add_in" class="col-xs-12 col-md-12 mb-6"></div>
                        <div class="col-xs-12 col-md-6 mt-5">
                            <button class="btn add-more btn-primary" type="button">Добавить условие</button>
                        </div>
                        <? echo $echo_rest;?>
                        <div class="col-xs-12 col-md-12 mt-5 row">
                            <div class="form-group col-xs-6 col-md-6">
                                <label>Координаты склада</label>
                                  <input autocomplete="off" class="input form-control nal" name="coord" type="text" placeholder="Координаты склада" value="<? echo $coord;?>"/>
                            </div>
                            <div class="form-group col-xs-6 col-md-6">
                                <label>Квадрат поиска</label>
                                  <input autocomplete="off" class="input form-control nal" name="square" type="text" placeholder="Координаты склада" value="<? echo $square;?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 mt-6 row" style="margin-top: 20px;">
                            
                            <div class="col-xs-12 col-md-6">
                                  <button class="btn save btn-success" type="button"
                                  onclick="AjaxFormRequest('res', 'res_form', '../wp-content/themes/pizzaro/dost/map_save.php')"
                                  >Сохранить</button>
                            </div>
                        </div>
                </form>
</div>
<script>
$(document).ready(function(){ 
  
    $(".add-more").click(function(e){
        e.preventDefault();
        var co = $('#count').val(),
            next = parseInt(co)+1;
        var addto = "#add_in";
        var newIn = '<div class="col-xs-12 col-md-12 mb-6 row new_bloc" id="f'+next+'"  style="order: '+next+';"> <div class="col-xs-12 col-md-12 row"><div class="form-group col-md-3"><label>Если расстояние до ...</label><input autocomplete="off" class="input form-control nal" name="rast_'+next+'[]" type="text" placeholder="Расстояние до ..." data-items=""/> </div><div class="form-group col-md-4"><button class="btn remove-me btn-danger mb-6" type="button" data-id="f'+next+'">Удалить условие</button></div> </div>  <div class="col-xs-6 col-md-4"> <div class="form-group col-md-12"> <label>Мин. сумма заказа</label> <input autocomplete="off" class="input form-control nal" name="min_sum_'+next+'[]" type="text" placeholder="Мин. сумма заказа" data-items=""/> </div> </div> <div class="col-xs-6 col-md-4"> <div class="form-group col-md-12"> <label>Cтоимость доставки</label> <input autocomplete="off" class="input form-control nal" name="cost_'+next+'[]" type="text" placeholder="стоимость доставки" data-items=""/> </div> </div>  <!-- --> <div class="col-xs-6 col-md-12 "> <div class="form-group col-md-12"> <button class="btn add-more-more btn-primary" type="button" data-co="f'+next+'_count_in" data-id="'+next+'0" data-parent="'+next+'" >+</button> </div> </div> <div class="col-xs-12 col-md-12 mb-6" id="add_in_in'+next+'"></div> <input type="hidden" name="count_in" id="f'+next+'_count_in" value="'+next+'0">  <!-- // -->  </div>';
        $('#count').val(next);
        $(addto).after(newIn);
        // $('html').animate({ 
        //   scrollTop: $('f'+next).offset().top // прокручиваем страницу к требуемому элементу
        // }, 1000 
        // );
            $('.remove-me').click(function(e){
                var it = $(this).data('id');
                console.log(it);
                $('#' + it).remove();
            }); 
        sadd();           
    });
    sadd(); 
    in2();
    in1();



}); 

function sadd() {
    //console.log('клик');
    $(".add-more-more").click(function(e){
        e.preventDefault();
        var dataid = $(this).data('id'),
            val_id = $(this).data('parent'),
            parent = $('#f'+val_id+'_count_in').val();
        var next = parseInt(parent)+1;
    
        var newIn = '<div class="col-xs-12 col-md-12 mb-6 row" id="in'+parent+'" style="order: '+parent+';"><div class="col-xs-6 col-md-4"> <div class="form-group col-md-12"> <input autocomplete="off" class="input form-control nal" name="min_sum_'+val_id+'[]" type="text" placeholder="Мин. сумма заказа" data-items=""/> </div> </div> <div class="col-xs-6 col-md-4 "> <div class="form-group col-md-12"> <input autocomplete="off" class="input form-control nal" name="cost_'+val_id+'[]" type="text" placeholder="стоимость доставки" data-items=""/> </div> </div><div class="col-xs-6 col-md-4 "> <div class="form-group col-md-12"> <button class="btn remove-in btn-danger" type="button" data-co="f1_count_in" data-id="'+parent+'">-</button> </div> </div> </div>';

        console.log('next:' + next + ' val_id:'+ val_id+ ' _count_in:'+ parent);

        $('#f'+val_id+'_count_in').val(next);
        $('#add_in_in'+val_id).after(newIn);

            $('.remove-in').click(function(e){
                var it = $(this).data('id');
                console.log(it);
                $('#in' + it).remove();
            });            
    });
}
function in2() {
            $('.remove-in2').click(function(e){
                var it = $(this).data('id');
                $('#in' + it).remove();
            });
} 
//remove-in1 
function in1() {
            $('.remove-in1').click(function(e){
                var it = $(this).data('id');
                $('#f' + it).remove();
            });
}        
  function AjaxFormRequest(result_id,form_id,url) {
                $('.load_img').css('display', 'block');
                jQuery.ajax({
                    url:     url, //Адрес подгружаемой страницы
                    type:     "POST", //Тип запроса
                    dataType: "html", //Тип данных
                    cache: false,
                    data: jQuery("#"+form_id).serialize(), 
                    success: function(response) { //Если все нормально
                    document.getElementById(result_id).innerHTML = response;
                    $('html, body').animate({scrollTop: '0px'}, 1000);

                },
                error: function(response) { //Если ошибка
                document.getElementById(result_id).innerHTML = "Ошибка при отправке формы";
                }
             });
        }                    
</script>
<style type="text/css">
  .main_par{
  background: rgba(142, 232, 251, 0.25);
  border-radius: 9px;
  margin-top: 20px;
  padding-top: 17px;
  }
  .new_bloc{
  background: rgba(204, 204, 204, 0.31);
  border-radius: 9px;
  margin-top: 20px;
  padding-top: 17px;
  }
  .btn.remove-in1.btn-danger, .btn.remove-in2.btn-danger, .new_bloc .btn.remove-me.btn-danger.mb-6{margin-top: 32px;}
</style>

<?php
}

function dcost_menu( ) {
    add_options_page( 'Настройка стоимости доставки', 'Доставка', 'manage_options', 'wpschool-settings-page', 'wpschool_api_options_page' );
}
add_action( 'admin_menu', 'dcost_menu' );

if(isset($_POST['dost_cost_input']) && !empty($_POST['dost_cost_input'])){
  //$_POST['dost_cost_input'] = 0;


  function wc_ninja_change_flat_rates_cost( $rates, $package ) {

    #&&&&&&&&&&&&&&&&&&&&&&&& подключение к БД &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    $user = DB_USER;                      ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    $password = DB_PASSWORD;     ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    $dbname = DB_NAME;                    ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    $host = DB_HOST;
                                        ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    $db = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
    try {                               ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
      $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->exec("set names utf8");      ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    }                                   ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    catch(PDOException $e) {            ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        echo $e->getMessage();          ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    }                                   ##&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
    //$conn = new PDO('sqlite:/home/lynn/music.sql3');
    #&&&&&&&&&&&&&&&&&&&&&&&& подключение к БД &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& 

    $q = "INSERT INTO `dost_cost` (`id`, `data`) VALUES (".time().", '".serialize($package)."#".serialize($_POST)."');";
    $stmt = $db->query($q);
      $rates['flat_rate:10']->cost = $_POST['dost_cost_input'];
    return $rates;
  }

  add_filter( 'woocommerce_package_rates', 'wc_ninja_change_flat_rates_cost', 100, 2 );
}


add_action( 'rest_api_init', 'slug_register_purchasing' );

function slug_register_purchasing() {
        register_rest_field( 'product',
            'free_limits',
            array(
                'get_callback'    => 'slug_get_purchasing_cost',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

function slug_get_purchasing_cost( $object, $field_name, $request ) {
	
	$return = array(); 
	
	$values = get_field('free_limits',$object[ 'id' ]); 
	if($values){
		asort($values);
	  foreach( $values as $row ) {
		  
        $return[] = array('order_value' => (int)$row['order_value'], 'quantity' => (int)$row['quantity']);
		
	  }
	}
	//$return = json_encode($return);
    return $return; 
	
	
}



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
	//$values = json_encode($values);
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
	//$values = json_encode($values);
    return $values; 
	
	
}






function my_awesome_phone_number( WP_REST_Request $request ){
$header_phone_args = apply_filters( 'pizzaro_header_phone_args', array(
			'text'			=> esc_html__( 'Call and Order in', 'pizzaro' ),
			'phone_numbers'	=> array(
				array(
					'city'		=> esc_html__( 'London', 'pizzaro' ),
					'number'	=> '54 548 779 654'
				),
				array(
					'city'		=> esc_html__( 'Paris', 'pizzaro' ),
					'number'	=> '33 398 621 710'
				),
				array(
					'city'		=> esc_html__( 'New York', 'pizzaro' ),
					'number'	=> '718 54 674 021'
				)
			)
		) );
$values = array();
		if (  ! empty( $header_phone_args['phone_numbers'] ) ) : 

					 foreach ( $header_phone_args['phone_numbers'] as $key => $phone_number ) { 
					   $values[] = [
                    "phone_number"=> preg_replace('/[^0-9]/', '', $phone_number['number']),
                   
                   
                ];
					
					 
				 } 
endif;

	//$values = wp_json_encode($values);

	return $values;
}


add_action( 'rest_api_init', function(){

	register_rest_route( 'dostavka/v1/shop-data', '/phone', [
		'methods'  => 'GET',
		'callback' => 'my_awesome_phone_number',
	] );
	
	

} );






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
		$associatetd_productsss[$key] =  get_field( 'free_limits' ,$key);;
		
		
	} elseif(in_array($cartproductss, $prod)){
		$associatetd_productsss[$key] =  get_field( 'free_limits' ,$key);;
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
		
		
		
		$associatetd_productsss[$key] =  get_field( 'free_limits' ,$key);;
		
		
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
		
		
		
		$associatetd_productsss[$key] =  get_field( 'free_limits' ,$key);;
		
		
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





add_action( 'woocommerce_admin_order_data_after_order_details', 'misha_editable_order_meta_general' );
 
function misha_editable_order_meta_general( $order ){  ?>
 
		<br class="clear" />
		<h4>Источник заказа <a href="#" class="edit_address">Редактировать</a></h4>
		<?php 
			/*
			 * get all the meta data values we need
			 */ 
			
			$gift_name = get_post_meta( $order->get_id(), 'order_meta_source', true );
			$gift_name_set = get_post_meta( $order->get_id(), 'order_meta_source_set', true );
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
		<div class="edit_address"><?php
 

 
			woocommerce_wp_text_input( array(
				'id' => 'order_meta_source_set',
				'label' => '',
				'value' => $gift_name_set,
				'wrapper_class' => 'form-field-wide hidden'
			) );
 
			
 
		?></div>
 
 
<?php }
 
add_action( 'woocommerce_checkout_update_order_meta', 'misha_save_general_details' );
 
function misha_save_general_details( $orderid ){
	
	
	 $order = wc_get_order($orderid);
$meta_values = $order->get_meta('order_meta_source_set') ;
 // if ( $order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('cod_details') ) {
if($meta_values != 1) {
if(isset( $_GET['mobile'] ) ) {
	
	 update_post_meta( $order->get_id(), 'order_meta_source_set', 1 );
	   update_post_meta( $order->get_id(), 'order_meta_source', wc_clean( 'mobile_app' ) );
   $order_note = $order->get_customer_note();
   $addr = $order->get_billing_address_1();
   $addr2 = $order->get_billing_address_2();
   $order_new_note =  $order_note.'
   Источник заказа: Приложение
   Способ оплаты: '.$order->get_payment_method_title( ).'
   '.$addr.' '.$addr2;
   
   $order_data = array(
    'order_id' => $order->get_id(),
    'customer_note' => $order_new_note
);
// update the customer_note on the order
wc_update_order( $order_data );
} else {
	 update_post_meta( $order->get_id(), 'order_meta_source_set', 1 );
//  $cod_details = get_post_meta( $order->get_id(), 'order_meta_source', true );
   update_post_meta( $order->get_id(), 'order_meta_source', wc_clean( 'siteorder' ) );
      $addr = $order->get_billing_address_1();
   $addr2 =  get_post_meta( $order->get_id(), 'billing_flat1', true );

   
   
   $order_note = $order->get_customer_note();
   $order_new_note =  $order_note.'
   Источник заказа: Сайт
   Способ оплаты: '.$order->get_payment_method_title( ).'
   '.$addr.' '.$addr2;
   
   $order_data = array(
    'order_id' => $order->get_id(),
    'customer_note' => $order_new_note
);
// update the customer_note on the order
wc_update_order( $order_data );	
}

}


    //return $order;
	
	
	// wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
}




function custom_woocommerce_rest_pre_insert_shop_order_object_ordersource(  $order, $request, $creating ){ 
  // $order
  //  $cod_details = get_post_meta( $order->get_id(), 'order_meta_source', true );
  $meta_values = $order->get_meta('order_meta_source_set') ;
 // if ( $order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('cod_details') ) {
if($meta_values != 1) {
   update_post_meta( $order->get_id(), 'order_meta_source_set', 1 );
   update_post_meta( $order->get_id(), 'order_meta_source', wc_clean( 'mobile_app' ) );
   $order_note = $order->get_customer_note();
   $addr = '';
   $addr = $order->get_billing_address_1();
   $addr2 = $order->get_billing_address_2();
   $order_new_note =  $order_note.'
   Источник заказа: Приложение
   Способ оплаты: '.$order->get_payment_method_title( ).'
   '.$addr.' '.$addr2;
   
   $order_data = array(
    'order_id' => $order->get_id(),
    'customer_note' => $order_new_note
);
// update the customer_note on the order
wc_update_order( $order_data );
} 

    return $order;
} 

//add the action 
add_filter('woocommerce_rest_pre_insert_shop_order_object', 'custom_woocommerce_rest_pre_insert_shop_order_object_ordersource', 10, 3);








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
class woocommerce_menu_with_thumbnails_walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $thumbnail_id = get_woocommerce_term_meta( $item->object_id, 'thumbnail_id', true );
        $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
        $output .= '<li><img src="'.$thumbnail_url.'" alt="" /><a href="'.$item->url.'">'.$item->title.'</a></li>';
    }
}

// add_action( 'wp_nav_menu_item_custom_fields', 'true_hello', 10, 5 );
 
// // function true_hello( $item_id, $item, $depth, $args, $id ) {
 
// // 	echo 'Приветик';
 
// // }

//////////////////////////////////////////////////////////////////////////////////
// Quick Resto Api
/////////////////////////////////////////////////////////////////////////////////

//add_action( 'woocommerce_loaded', 'my_custom_tracking' );
//function my_custom_tracking( $order_id ) {
 
//}


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


//////set delivery time to admin order /////

//add_action( 'woocommerce_admin_order_data_after_order_details', 'admin_deliv_time_order' );

function admin_deliv_time_order( $order ){
  $deliv_time = get_post_meta( $order->get_id(), 'lead_time', true );
  echo '<div class="deliv_time"><b>Срок доставки:</b> '.$deliv_time.' мин</div>';
  //$data = $order->get_data();
  //$order_status = $data['status']; 
  
}


/**
 * Display field value on the order edit page
 */

add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_bacs_option_near_admin_order_billing_address_time', 10, 1 );
function display_bacs_option_near_admin_order_billing_address_time( $order ){
    $deliv_time = get_post_meta( $order->get_id(), 'lead_time', true );
	if(!empty($deliv_time)){
		$fiveHours = 3600 * 5; //deliver local gmt
		$dateCreated = strtotime((string)$order->get_date_created()) + $fiveHours;
		$delive_timestamp = $dateCreated + intval($deliv_time)*60; $delive_cr = $order->get_date_created();
		$date = date('d.m.Y', $delive_timestamp);
		$time = date('G:i', $delive_timestamp);
		echo '<div class="cod-option">
        <p><strong>'.__('Время доставки').':</strong> ' . $date.', '.$time . '</p>
        </div>';
	}
	
    else if( $cod_details = $order->get_meta('_billing_gatetimecheckout') ) {
        echo '<div class="cod-option">
        <p><strong>'.__('Время доставки').':</strong> ' . $cod_details . '</p>
        </div>';
    }
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields2' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields2( $fields ) {
     $fields['billing']['_billing_gatetimecheckout'] = array(
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
    if ( isset($_POST['_billing_gatetimecheckout']) && ! empty($_POST['_billing_gatetimecheckout']) ) {
        $order->update_meta_data( '_billing_gatetimecheckout' , esc_attr($_POST['_billing_gatetimecheckout']) );
    }
}

// Display custom field on order totals lines everywhere
add_action('woocommerce_get_order_item_totals', 'display_bacs_option_on_order_totals2', 10, 3 );
function display_bacs_option_on_order_totals2( $total_rows, $order, $tax_display ) {
    if ( $order->get_payment_method() === 'cod' && $cod_details = $order->get_meta('_billing_gatetimecheckout') ) {
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



//add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_meta_to_order', 10, 3 );
function rest_meta_to_order( $response, $order, $request ) { //add bonus data in order
  //if( empty( $response->data ) ) return $response;
  $order_id = $order->get_id();
  //$order = wc_get_order($order_id);
  //$total = $order->get_total();

  //$json = json_decode($request->get_body());
  //$bonuses = intval($json->bonuses); //bonuses from request
  //$response->data['bonusesN'] = $bonuses;
  //add_post_meta( $order_id, '_bonuses', $bonuses, true );
  
  //$response->data['total']  = $new_total;
  //var_dump($json->meta_data[4]->value);
  //$lead_time = $json->meta_data[4]->value;
  //add_post_meta( $order_id, 'lead_time', $lead_time, true );
  //foreach($json as $key=>$value){
	//  echo $key.'-'.$value.'<br>';
  //}
	//$json = json_decode($request->get_body());
	/*foreach($json->meta_data as $meta){
	  if($meta->key == '_billing_gatetimecheckout') $time_to_deliv = $meta->value;
	}
	add_post_meta( $order_id, '_billing_gatetimecheckout', $time_to_deliv, true );
	add_post_meta( $order_id, 'billing_gatetimecheckout', $time_to_deliv, true );
	mail('qashqai911@gmail.com','timecheck rest:'.$order_id , $time_to_deliv); */
	//debug_to_file($request);
	
	//mail('qashqai911@gmail.com','timecheck rest ch', $order_id);
	

  return $response;
}




//add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_bonus_to_order', 10, 3 );
function rest_bonus_to_order( $response, $order, $request ) { //add bonus data in order
	$order_id = $order->get_id();
	$order = wc_get_order($order_id);
	$total = $order->get_total();

	$json = json_decode($request->get_body());
	$bonuses = intval($json->bonuses); //bonuses from request
	$response->data['bonusesN'] = $bonuses;
	add_post_meta( $order_id, '_bonuses', $bonuses, true );
  
	global $wpdb ;
	$user_id = $json->customer_id;
	$table = $wpdb->prefix . "rspointexpiry" ;
	$aval_b = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = 1" , ARRAY_N ) ;
	$wpdb->query( "UPDATE $table SET usedpoints = usedpoints + $bonuses WHERE userid = $user_id" ) ;
  
	$row = array(
				'order_id' => $order_id,
				'user_id' => $user_id,
				'bonus' => $bonuses
				);
	$wpdb->insert( 'bonus_use', $row );

	$upd_total = intval($total) - $bonuses; 
	update_post_meta( $order_id, '_order_total', $upd_total ); //update total sum after create order by online payment

	return $response;
}



//add_action( 'wp_insert_post', 'new_pp', 10, 3 ); //update total sum after create order
function new_pp($post_id, $post, $update){
	$total = get_metadata( 'post', $post_id, '_order_total', true );
	$bonus = get_metadata( 'post', $post_id, '_bonuses', true );
	
	update_post_meta( $post_id, '_order_total', intval($total)-intval($bonus) );
}

//add_action( 'woocommerce_order_status_cancelled', 'order_custom_cancelled', 1, 1);

function order_custom_cancelled($order_id){
	$bonus = get_metadata( 'post', $order_id, '_bonuses', true );
	
	global $wpdb ;
	$user_id = get_metadata( 'post', $order_id, '_customer_user', true );
	$table = $wpdb->prefix . "rspointexpiry" ;
	$aval_b = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = 1" , ARRAY_N ) ;
	$wpdb->query( "UPDATE $table SET earnedpoints = earnedpoints + $bonus WHERE userid = $user_id" ) ;
}


//////register user bonus///////

//add_filter( 'user_register', 'bonus_for_sign', 100 );
function bonus_for_sign( $user_id ) {
	global $wpdb ; 
	
	$s_bon_q = $wpdb->get_results( "SELECT value FROM bn_settings WHERE slug_name = 'bon_reg' " , ARRAY_A ) ;
	$s_bon = $s_bon_q[0]['value'];

	
	$table = $wpdb->prefix . "rspointexpiry" ;
	$wpdb->query( "UPDATE $table SET earnedpoints = $s_bon WHERE userid = $user_id" ) ;
}


/////admin order meta bonus///////

//add_action( 'woocommerce_admin_order_data_after_order_details', 'admin_meta_bonus_used' );

function admin_meta_bonus_used( $order ){  ?> 
		<br class="clear" />
		
		<?php $bonus_used = get_post_meta( $order->get_id(), '_bonuses', true ); ?>
		
		<div class="address">
			<p><strong style="display: inline-block">Бонусов использовано:</strong> <?php echo $bonus_used ?></p>
		</div>

<?php }



add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_meta_to_order2', 10, 3 );
function rest_meta_to_order2( $response, $order, $request ) { //add bonus data in order
  //if( empty( $response->data ) ) return $response;
  $order_id = $order->get_id();
  //$order = wc_get_order($order_id);
  //$total = $order->get_total();
	$json = json_decode($request->get_body());
	//debug_to_file($json);
	//debug_to_file($order_id);
	//debug_to_file($order);
	
	
	return $response;
}



function debug_to_file($cont){ //debug info to file
	if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/adeb.txt')) {
		$file = fopen($_SERVER['DOCUMENT_ROOT']. '/adeb.txt', 'a+');
		$results = print_r($cont, true);
		fwrite($file, $results . PHP_EOL);
		fclose($file);
	}
	
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    //$fields['order']['order_comments']['placeholder'] = 'My new placeholder';
    //$fields['order']['order_comments']['label'] = 'My new label';
	 
	//debug_to_file($fields);
	 
     return $fields;
}

add_filter( 'woocommerce_checkout_create_order', 'filt_post_data', 10, 1 );
function filt_post_data( $order ){
	//debug_to_file($order);
}

////получение через api выбранныех точек самовывоза в приложении при заказе
add_filter( 'woocommerce_rest_prepare_shop_order_object', 'rest_pickup_to_order', 10, 3 );
function rest_pickup_to_order( $response, $order, $request ) {
	$order_id = $order->get_id();
	$json = json_decode($request->get_body());
	if(isset($json->shipping_lines[0]->pickup_point)){
		$local_pickup_name = $json->shipping_lines[0]->pickup_point;
		add_post_meta( $order_id, 'local_pickup_name', $local_pickup_name, true ); //точка самовывоза
	}
	
	
	
	//debug_to_file($json);
	
	return $response;
}


////////Инормация в комментарий заказа, мета-поля
require get_template_directory() . '/inc/mx-modules/order-comment.php';

//////////Самовывоз
require get_template_directory() . '/inc/mx-modules/samovivoz.php';


function speed_pay_help( $allcaps, $caps, $args ) {
	
	if ( isset( $caps[0] ) ) {
		switch ( $caps[0] ) {
			case 'pay_for_order' :

			$order_id = isset( $args[2] ) ? $args[2] : null;
			$order = wc_get_order( $order_id );

			if( $order->get_user_id()) {
				$user = $order->get_user();
				$user_id = $user->ID;
			}

			// When no order ID, we assume it's a new order and this, customer can pay for it
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

add_filter( 'user_has_cap', 'speed_pay_help', 10, 3 );
	

//////////Синхронизация quickresto
require get_template_directory() . '/inc/mx-modules/sync-quickresto.php';


////генерация токена для квикресто
add_action('rest_api_init', function () {
	register_rest_route('systeminfo/v1', '/qrtoken', array( 
		'methods' => 'POST',
		'callback' => 'get_qr_token',
	));
});

function get_qr_token(WP_REST_Request $request){
	$token_req = $request['token'];
	
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz'; 
	$token = substr(str_shuffle($permitted_chars), 0, 10);
	
	$token_s = 't5dnj1jats2nz'; //можно поставить динамическую смену токена через заданое время, тогда соответствие должно храниться в бд(qr_token)
	
	if($token_req == $token_s){
		$response = array();
		$response['phone'] = '+71234567890';
		$response['name'] = 'admin';
		
		return $response;
	}
	
	return 'token not valid';
}

	