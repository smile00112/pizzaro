<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

	<?php else : ?>

		<h3><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
$in_html = '';
$shipping_address_1 = '';
$total = '';

if(isset($_GET['code'])){

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

    $qq = "SELECT *
	FROM `wp_mstore_checkout`WHERE code = '".$_GET['code']."'
	LIMIT 50";
	$stmt = $db->query($qq);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $stmt->fetchAll(); 

	foreach ($rows as $key => $value) {
	     $data_sql = json_decode(urldecode(base64_decode($value['order'])), true);
	}

	$shipping_address_1 = $data_sql['shipping']['address_1'];
	$total = $data_sql['shipping_lines'][0]['total'];
	
	if(!empty($total)){
		$in_html = '
		<input type="hidden" id="dost_cost_input" name="dost_cost_input" value="'.$total. '">
		';
	}

}


if(isset($_COOKIE['shipping_address_1'])){
	$shipping_address_1 = $_COOKIE['shipping_address_1'];
	$total = $_COOKIE['total'];
}

				// echo '<pre>';
				// print_r($_COOKIE) ;
				// //print_r($key) ;
				// echo '</pre>';
?>		
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {

			
			if($key=='billing_flat'){
				
				
				echo '
<p class="form-row form-row-wide address-field thwcfd-field-wrapper thwcfd-field-text validate-required" id="billing_address_1_field_" data-priority="50">
<label for="suggest" class="">Адрес&nbsp;<abbr class="required" title="обязательно">*</abbr></label>


<input type="text" class="input-text " name="suggest" id="suggest" placeholder="Название улицы и номер дома" value="'.$shipping_address_1.'" autocomplete="off" required  onfocus="this.removeAttribute(\'readonly\');" readonly>

	
				
<div id="all-map" class="col-xl-12" style="margin-bottom:50px">
    
    <label for="suggest" class="">Укажите адрес достаки, поставив точку на карте, или введя его в поле выше&nbsp;<abbr class="required" title="обязательно">*</abbr></label>

    	<div class="form-group" id="rez">
    		<input type="hidden" id="dost_cost_input" name="dost_cost_input" value="'.$total. '">
    	</div>
    <div class="map adres" id="map" data-id="ww" style="height: 300px;width: 100%;">
    </div>
</div>
				';
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
			}else{
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); //поля оформления, например _billing_gatetimecheckout
			}
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
