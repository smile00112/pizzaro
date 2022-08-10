<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `pizzaro_aboutpage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Testpage
 *
 * @package pizzaro
 */

remove_action( 'pizzaro_content_top', 'pizzaro_breadcrumb', 10 );

do_action( 'pizzaro_before_aboutpage' );

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			/**
			 * @hooked pizzaro_homepage_content - 10
			 */
		$order = wc_get_order(15032);
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

var_dump($gift_coupons);
		
		?>
		
		
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php 
get_footer();
