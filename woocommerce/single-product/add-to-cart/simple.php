<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $product;
if ( !$product->is_purchasable() ) {
	return;
}


$supplements_html = '<div id="suplem_modal">';
$supplements_required = 0;
if($product->get_type() == 'supplements'){
$custom_fields = get_fields(); // все допполя
$supplements_required = (isset($custom_fields['supplements_required']) && $custom_fields['supplements_required'] ) ? 1 : 0;

$supplements = $custom_fields[ 'supplements' ]; // повторитель supplements
$products_ids = array_column( $supplements, 'products' ); // id товаров не сгруппированые
$prod_ids = array(); // сгруппированые id товаров
foreach ( $products_ids as $pr_ids ) {
	$prod_ids = array_merge( $prod_ids, $pr_ids );
}
$prod_ids = array_unique( $prod_ids ); // id товаров без дублей
$products = wc_get_products( array( 'include' => $prod_ids, 'limit' => count( $prod_ids ) ) ); // список товаров
$prod = array(); // массив с значениями из товаров
foreach ( $products as $p ) {
	$prod[ $p->get_id() ] = array( 'name' => $p->get_name(), 'price' => $p->get_price(), 'price_html' => $p->get_price_html(), 'product' => $p );
}

$i = 0;
foreach ( $supplements as $supp ) {
	$i++;
	$max_value = ( isset($supp['quantity_max']) && $supp['quantity_max'] ) ? $supp['quantity_max'] : 9999;
	
	$supplements_html .= '<div class="supplements supplements-' . $supp[ 'quantity' ] . ' supplements-' . $supp[ 'type' ] . '" data-quantity="' . $supp[ 'quantity' ] . '" data-max_value="' . $max_value . '"  data-type="' . $supp[ 'type' ] . '" >';
	
	$supplements_html .= "<h4 class=\"supp-h\">{$supp['title']}</h4>";
	if( isset($supp['quantity_max']) && $supp['quantity_max'] ) {
	$supplements_html .= "<div class=\"supp-info-max text-success\">Не более {$supp['quantity_max']} шт., осталось <strong class=\"supp-ost-max\">{$supp['quantity_max']}</strong> шт.</div>";	
	}
	
	switch ( $supp[ 'type' ] ) {
		case 'chekbox':
			$inputs = '';
			foreach ( $supp[ 'products' ] as $vp ) {
				$inputs .= '<div class="supp-div" data-prod="' . $vp . '" data-price="' . $prod[ $vp ][ 'price' ] . '" >
	<span class="supp-name">' . $prod[ $vp ][ 'name' ] . '</span> 
	<label class="checkbox-ios supp-label">
	<input class="checkbox-other supp-checkbox" type="checkbox" name="cb_' . $i . '" id="cb_' . $i . '_' . $vp . '" value="' . $vp . '">
	<span class="checkbox-ios-switch"></span>
	</label>
	<div class="supp-quantity woocommerce-cart-form__cart-item" data-prod="' . $vp . '">' . woocommerce_quantity_input( array( 'min_value' => 0, 'max_value' => $max_value, 'input_value' => 0, ), $prod[ $vp ][ 'product' ], false ) . '</div>
	<div class="supp-price">' . $prod[ $vp ][ 'price_html' ] . '</div>
	</div>
	';
			}
			$supplements_html .=  $inputs;
			break;

		case 'radio':
			$inputs = '';
			foreach ( $supp[ 'products' ] as $vp ) {
				$inputs .= '<label>
	<input type="radio" class="supp-radio" name="cb_' . $i . '" id="cb_' . $i . '_' . $vp . '" data-id="' . $i . '_' . $vp . '" data-price="' . $prod[ $vp ][ 'price' ] . '" value="' . $vp . '">
	' . $prod[ $vp ][ 'name' ] . ' ' . $prod[ $vp ][ 'price_html' ] . '
	</label>
	';
			}
			$supplements_html .= $inputs . '<div class="supp-quantity woocommerce-cart-form__cart-item">' . woocommerce_quantity_input( array( 'min_value' => 0, 'max_value' => $max_value, 'input_value' => 0, 'product_name' => $supp['title'] ), null, false ) . '</div>';

			break;

		case 'list':
			$option = '';
			foreach ( $supp[ 'products' ] as $vp ) {
				$option .= '<option value="' . $vp . '"  data-price="' . $prod[ $vp ][ 'price' ] . '" >' . $prod[ $vp ][ 'name' ] . ' ' . $prod[ $vp ][ 'price_html' ] . '</option>';
			}
			$supplements_html .= '<select class="supp-select"><option value="">---</option>' . $option . '</select>
			<div class="supp-quantity woocommerce-cart-form__cart-item">' . woocommerce_quantity_input( array( 'min_value' => 0, 'input_value' => 0, 'max_value' => 999, 'product_name' => $supp['title'] ), null, false ) . '</div>';
			break;
	}
$supplements_html .=  '</div>';

} $supplements_html .=  '</div>';
} // END if($product->get_type() == 'supplements')

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ): ?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart simple-t <?php if($product->get_type() == 'supplements') echo 'single-type-supplements';  ?>" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
	<div id="add_cart_form" data-startprice="<?php echo $product->get_price(); ?>">
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );
		woocommerce_quantity_input(
			array(
				'min_value' => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST[ 'quantity' ] ) ? wc_stock_amount( wp_unslash( $_POST[ 'quantity' ] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
			)
		);

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>
		<input type="hidden" id="supplements_cart" name="supplements">
		<div id="total_end_price" class="wc-block-components-totals-item__label text-center"><span class="woocommerce-Price-amount amount"><bdi></div>
		<button id="add_cart_btn" type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
			<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		</button>
<?php 
$increase_delivery_time = get_increase_delivery_time( $product->get_id() );
if ( $increase_delivery_time ) { // + к времени доставки 
	echo '<p class="IncreaseDeliveryTime-p text-center">'.format_increase_delivery_time($increase_delivery_time).'</p>';
} 
			?>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</div>
	<?php if($supplements_required): ?>
	<div id="add_cart_error" class="woocommerce-error">Выберите дополнительные товары</div>
	<?php endif; ?>
</form>

<?php

echo $supplements_html;

	wp_reset_postdata();
	?>
<script>
jQuery(document).on('change', '.supp-select, .supp-radio, .supp-checkbox', function() {
var el = jQuery(this);	
var supplements = jQuery(el).closest('div.supplements');
var quantity =  jQuery(supplements).attr('data-quantity');
var type =  jQuery(supplements).attr('data-type');
	
switch (type) {
  case 'chekbox':
var supp = jQuery(el).closest('div.supp-div');
var prod = jQuery(supp).attr('data-prod');	 
var price = jQuery(supp).attr('data-price');
var quantity_box = 	jQuery(supp).find('.supp-quantity');	
var quantity_input = jQuery(quantity_box).find('input[name="quantity"]');
if(quantity == 'once'){
var q = ( jQuery(el).prop('checked') ) ? 1 : 0;
jQuery(quantity_input).val(q).attr('data-price', price);
}	
	
    break;
  case 'radio':
var	input = jQuery(supplements).find('.supp-radio:checked');
var prod = jQuery(input).val();	
var price = jQuery(input).attr('data-price');
var quantity_box = jQuery(supplements).find('.supp-quantity');
var quantity_input = jQuery(quantity_box).find('input[name="quantity"]');
var q = ( !!input ) ? 1 : 0;
jQuery(quantity_input).val(q).attr('data-price', price);
    break;
		
  case 'list':
var	option = jQuery(el).find('option:checked');
var prod = jQuery(el).val();	
var price = jQuery(option).attr('data-price');
var quantity_box = jQuery(supplements).find('.supp-quantity');	
var quantity_input = jQuery(quantity_box).find('input[name="quantity"]');
var q = ( !!prod ) ? 1 : 0;
jQuery(quantity_input).val(q).attr('data-price', price);			
    break;
		
  default:
  return false;
}	
 if(!!prod) {
jQuery(quantity_box).attr('data-prod', prod);
 }
 supplementsCalc(); 
});	
	
jQuery(document).on('input change', 'input[name="quantity"]', function() {
	suppMaxsCalc(this);
supplementsCalc();
});	
	
jQuery(document).ready(function(){ 
jQuery('#add_cart_form input[name="quantity"]').val(1);
jQuery( '.supp-checkbox' ).each(function( index ) { 
var el = jQuery(this);
var supp = jQuery(el).closest('div.supp-div');
var prod = jQuery(supp).attr('data-prod');	 
var price = jQuery(supp).attr('data-price');
var quantity_box = 	jQuery(supp).find('.supp-quantity');	
var quantity_input = jQuery(quantity_box).find('input[name="quantity"]');
jQuery(quantity_input).attr('data-price', price);
});	
	
 supplementsCalc(); 
});
	
function supplementsCalc(){
var startprice = Math.ceil( Number( jQuery('#add_cart_form').attr('data-startprice') ) );
var osn_q = Math.ceil( Number( jQuery('#add_cart_form input[name="quantity"]').val() ) );
var endprice = startprice * osn_q; 
	
var arr = {}; 
jQuery( 'div.supplements input[name="quantity"]' ).each(function( index ) { 
var quantity = Math.ceil(Number(jQuery(this).val()) );	
var price =  Math.ceil(Number(jQuery(this).attr('data-price') ));
var parent = jQuery(this).closest('.supp-quantity');
var prod = jQuery(parent).attr('data-prod');
if(!!prod && !!quantity && quantity > 0){	
arr[index] = {'quantity': quantity, 'prod': prod};	
endprice = 	endprice + quantity*osn_q*price;
	}
});
	
jQuery('div.summary.entry-summary > p.price > span > bdi, #total_end_price bdi').html(endprice.toFixed(2) + '&nbsp;<span class="woocommerce-Price-currencySymbol">&#8381;</span>');

	
var json = JSON.stringify(arr);
jQuery('#supplements_cart').val(json);
	
<?php if($supplements_required): ?>
if(json == '{}' ){
jQuery('#add_cart_form').hide();
jQuery('#add_cart_error').show();
} else {
jQuery('#add_cart_form').show();
jQuery('#add_cart_error').hide();
}
<?php endif; ?>		
	
}

function suppMaxsCalc(el){
var parent = el.closest('.supplements');	
var max = jQuery(parent).attr('data-max_value');
if(!!max){
var maxval = Number.parseInt(max);
var sum = 0;
jQuery(parent).find( 'input[name="quantity"]' ).each(function( index ) { 
var quantity = Math.ceil(Number(jQuery(this).val()) );	
sum = sum + quantity;		
});	
if( sum >= max)	{
jQuery(parent).find( 'button.plus' ).addClass('disabled');	
jQuery(parent).find('.supp-info-max').addClass('text-danger');		
} else {
jQuery(parent).find( 'button.plus' ).removeClass('disabled');	
jQuery(parent).find('.supp-info-max').removeClass('text-danger');		
}
jQuery(parent).find('.supp-ost-max').text(max - sum);	
}
		 
}
	
jQuery(window).keyup(function(e){
	var target = jQuery('.checkbox-ios input:focus');
	if (e.keyCode == 9 && jQuery(target).length){
		$(target).parent().addClass('focused');
	}
});
 
jQuery('.checkbox-ios input').focusout(function(){
	jQuery(this).parent().removeClass('focused');
});
</script>
<style>
	@media (min-width: 1200px){
#total_end_price {
    display: none;
}
}
	.yith_wapo_group_total{
	display: none !important;	
	}
	span.supp-name {
    flex-basis: 50%;
}
.supplements.supplements-multiple .checkbox-ios, .supplements.supplements-once .woocommerce-cart-form__cart-item{
	display: none;
	}
	
.supplements .woocommerce-cart-form__cart-item{
	 flex: none;
    width: max-content;
	margin: 0 0.5em;
	}
.supp-div {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    justify-content: space-between;
    margin-bottom: 0.5em;
 /*   border-bottom: 1px #9e9e9e dotted;*/
}	
	
	.checkbox-ios {
	display: inline-block;    
	height: 28px;    
	line-height: 28px;  
	margin-right: 10px;      
	position: relative;
	vertical-align: middle;
	font-size: 14px;
	user-select: none;	
}
.checkbox-ios .checkbox-ios-switch {
	position: relative;	
	display: inline-block;
	box-sizing: border-box;			
	width: 56px;	
	height: 28px;
	border: 1px solid rgba(0, 0, 0, .1);
	border-radius: 25%/50%;	
	vertical-align: top;
	background: #eee;
	transition: .2s;
}
.checkbox-ios .checkbox-ios-switch:before {
	content: '';
	position: absolute;
	top: 1px;
	left: 1px;	
	display: inline-block;
	width: 24px;	
	height: 24px;
	border-radius: 50%;
	background: white;
	box-shadow: 0 3px 5px rgba(0, 0, 0, .3);
	transition: .15s;
}
.checkbox-ios input[type=checkbox] {
	display: block;	
	width: 0;
	height: 0;	
	position: absolute;
	z-index: -1;
	opacity: 0;
}
.checkbox-ios input[type=checkbox]:not(:disabled):active + .checkbox-ios-switch:before {
	box-shadow: inset 0 0 2px rgba(0, 0, 0, .3);
}
.checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch {
	background: limegreen;
}
.checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch:before {
	transform:translateX(28px);
}
 
/* Hover */
.checkbox-ios input[type="checkbox"]:not(:disabled) + .checkbox-ios-switch {
	cursor: pointer;
	border-color: rgba(0, 0, 0, .3);
}
 
/* Disabled */
.checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch {
	filter: grayscale(70%);
	border-color: rgba(0, 0, 0, .1);
}
.checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch:before {
	background: #eee;
}
 
/* Focus */
.checkbox-ios.focused .checkbox-ios-switch:before {
	box-shadow: inset 0px 0px 4px #ff5623;
}
</style>
	<?php do_action( 'woocommerce_after_add_to_cart_form' );  ?>

	<?php endif; ?>