<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
	<?php do_action('woocommerce_before_cart_table'); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-thumbnail">&nbsp;</th>
				<th class="product-name"><?php esc_html_e('Блюдо', 'woocommerce'); ?></th>
				<th class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
				<th class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
				<th class="product-subtotal"><?php esc_html_e('Блюд на сумму', 'woocommerce'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
					$parent_class = (isset($cart_item['supplements_ids']) && $cart_item['supplements_ids']) ? ' cart-parent-item ' : ''; // класс основного товара
					$sub_class = (isset($cart_item['parent_key']) && $cart_item['parent_key']) ? ' cart-sub-item ' : ''; // класс доптовара
					$fullProduct = wc_get_product($cart_item['product_id']);
					$upsellIds = $fullProduct->get_upsell_ids();


					if (in_array($upsellIds, $product_id)) continue;
			?>

			
					<div class='cart-pre' style='display:none'>
						<?
						echo "<pre>";
						print_r($upsellIds);
						print_r($cart_item['supplements_ids']);
						echo 'cart_item_data=';
						print_r($cart_item['cart_item_data']);
						echo 'parent_key=';
						print_r($cart_item['parent_key']);

						echo "</pre>";
						?>
					</div>
			

					<tr  id="cart_itemid<?=$cart_item['product_id'];?>"  class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key));
																echo $parent_class;
																echo $sub_class; ?>">

						<td class="product-remove">
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									esc_html__('Remove this item', 'woocommerce'),
									esc_attr($product_id),
									esc_attr($_product->get_sku())
								),
								$cart_item_key
							);
							?>
						</td>

						<td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

							if (!$product_permalink) {
								echo $thumbnail; // PHPCS: XSS ok.
							} else {
								printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
							}
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e('Блюдо', 'woocommerce'); ?>">
							<?php
							if (!$product_permalink) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
							} else {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
							}

							do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

							// Meta data.
							echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

							// Backorder notification.
							if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
							}
							?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
							<?php
							echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
							?>
						</td>
						
						<? if( empty($cart_item['parent_key']) )// если обычный товар
							{
						?>
							<td class="product-quantity" data-is_parent="<? echo(!empty($cart_item['supplements_ids']) ? 'true' : 'false'); ?>" data-product_key="<? echo $cart_item_key; ?>" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
								<?php
								if ($_product->is_sold_individually()) {
									$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
								} else { ?>
									<?php $product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									); ?>
								<?php }
									echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
								?>
							</td>
							<td class="product-subtotal" data-title="<?php esc_attr_e('Блюд на сумму', 'woocommerce'); ?>">
								<?php
								if(empty($cart_item['supplements_ids'])){
									echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
								}else{
									/* если товар с допами, суммируем цену основного товара и допов */
									$prod_data = json_decode( apply_filters('woocommerce_cart_item_price', $cart_item['data'], $cart_item, $cart_item_key), true);
									$price = !empty($prod_data['sale_price']) ? $prod_data['sale_price'] : $prod_data['regular_price'];
									$main_product_price =  $price * $cart_item['quantity'];
									$dops_price = 0;
									foreach (WC()->cart->get_cart() as $cart_item_key2 => $cart_item2) {
										if(empty($cart_item2['parent_key'])){
											continue;
											//echo WC()->cart->get_product_price_num($_product);
											

											// echo  $cart_item['quantity'];
											// print_R($cart_item);
										}
										$prod_data2 = json_decode( apply_filters('woocommerce_cart_item_price', $cart_item2['data'], $cart_item2, $cart_item_key2), true);
										$price2 = !empty($prod_data2['sale_price']) ? $prod_data2['sale_price'] : $prod_data2['regular_price'];
										$dop_product   = apply_filters('woocommerce_cart_item_product', $cart_item2['data'], $cart_item2, $cart_item_key2);
										$dops_price+= $price2 * $cart_item2['quantity'];

									}
									//echo $main_product_price.'__'.$dops_price;
									echo apply_filters('woocommerce_cart_item_subtotal', ($main_product_price+$dops_price).'&nbsp;<span class="woocommerce-Price-currencySymbol">₽</span>', $cart_item, $cart_item_key); // PHPCS: XSS ok.
								}
								?>
							</td>

						<? 
							}
							else
							{ //шаблон для допов 
						?>
								<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
									<?php
									if ($_product->is_sold_individually()) {
										$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
									} else { ?>
										<?php $product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										); ?>
									<?php }  ?>
									<!---->
									<div class="qib-container-dop">
										<span class="cart-dops-qty"><? echo $cart_item['quantity'].'&nbspшт.'; ?></span>
									</div>
									<div class="dop-product-qty"  data-qty_parent="<? echo $cart_item['parent_key']; ?>" style="display: none;">
										<? echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.?>
									</div>
								</td>
								<td class="product-subtotal" data-title="<?php esc_attr_e('Блюд на сумму', 'woocommerce'); ?>">
									<?php
									//echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
									?>
								</td>
						<? } ?>


					</tr>
			<?php
				}
			}
			?>
			<?php
			$products = wc_get_products(array(
				'category' => array('category' =>  'dopy',  'return' => 'ids',),
			));
			//var_dump($products);
			$associatetd_products = array();
			foreach ($products as $product) {


				$associatetd_products[$product->get_id()] = get_field('recommend_to_product', $product->get_id());


				// if(is_array($product_ids)) {
				//var_dump($product_ids); 
				// foreach ($product_ids as $key => $product_id) {


				// $product_cart_id = WC()->cart->generate_cart_id( $product_id );
				// $in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

				// if ( $in_cart ) {
				//echo "<!--testcontent-->";
				// $notice = 'Product ID ' . $product_id . ' is in the Cart!';
				// wc_print_notice( $notice, 'notice' );

				//}
				// }
				// }
			}
			?>

			<?php do_action('woocommerce_cart_contents'); ?>
			
			<? include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/pizzaro/inc/woocommerce/pizzaro-cart-gifts.php'; ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if (wc_coupons_enabled()) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Промокод', 'woocommerce'); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
							<?php do_action('woocommerce_cart_coupon'); ?>
						</div>
					<?php } ?>
					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

					<?php do_action('woocommerce_cart_actions'); ?>


					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
				</td>
			</tr>

			<?php do_action('woocommerce_after_cart_contents'); ?>

		</tbody>
	</table>
	<?php do_action('woocommerce_before_cart_collaterals'); ?>
	<div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>


		<?php do_action('woocommerce_cart_actions'); ?>
		<button type="submit" class="button displayonmobile" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
	</div>
	<?php do_action('woocommerce_after_cart_table'); ?>
</form>





<?php do_action('woocommerce_after_cart'); ?>