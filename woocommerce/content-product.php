<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$product = wc_get_product(get_post());
//print_r($product);
//print_R($product);
$price_prefix = $product->is_type('variable') ? 'от' : '';
$button_class = $product->is_type('variable') ? 'custom-product--open-modal' : 'ajax_add_to_cart add_to_cart_button ajax_add_to_cart_catalog_simple_prod';
$button_title = $product->is_type('variable') ? 'Выбрать' : 'В корзину';
$preview_class = $product->is_type('variable') ? 'custom-product--open-modal' : 'custom-product--open-preview';

//для товаров с ингридиентами отдельное модальное окно
if( $product->get_type() == 'supplements' ) {
  $button_class = 'custom-product--open-modal custom-product--open-preview';
  $preview_class = 'custom-product--open-preview';
}
//Если у товара есть обязательные допы, вызываем модальное окно
$reqired_dops = get_post_meta($product->id, 'required_dops', true);
if( $reqired_dops ){
  $button_class = 'custom-product--open-modal custom-product--open-preview';
  $preview_class = 'custom-product--open-preview';
}

$the_count_type = apply_filters('the_count_type', $product);
if($the_count_type) $the_count_type.= '&nbsp;/&nbsp;';
?>

<div class="custom-product <?php echo $preview_class; ?>" data-product_id="<?php echo $product->id; ?>">
  <div class="custom-product--image">
    <a href="<?php echo $product->get_permalink(); ?>" onclick="return false;"><?php echo $product->get_image('woocommerce_thumbnail'); ?></a>
    <? product_single_custom_labels();?>
  </div>
  <div class="custom-product--info">
    <div class="custom-product--title">
      <a href="<?php echo $product->get_permalink(); ?>"><?php the_title(); ?></a>
    </div>
    <div class="custom-product--description"><?php the_excerpt(); ?><?=$the_count_type;?><?=$product->get_stock_quantity();?>шт.</div>
    <div class="custom-product--bottom">
      <div class="custom-product--bottom-left">
        <div class="custom-product--price">
          <?php if ($product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price()) : ?>
            <div class="custom-product--price-old"><?php echo $price_prefix; ?> <?php echo $product->get_regular_price(); ?> <i class="fa fa-rub"></i></div>
            <div class="custom-product--price-current"><?php echo $price_prefix; ?> <?php echo $product->get_sale_price(); ?> <i class="fa fa-rub"></i></div>
          <?php else : ?>
            <div class="custom-product--price-current"><?php echo $price_prefix; ?> <?php echo $product->get_price() ?: '0'; ?> <i class="fa fa-rub"></i></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="custom-product--bottom-right <? if( $product->is_type('simple') && empty($reqired_dops) ){?>dont-show-modal<?}?>">
        <button class="custom-product--add-to-cart <?php echo $button_class; ?>" data-product_id="<?php echo $product->id; ?>" data-quantity="1"><?php echo $button_title; ?></button>

        <div class="custom-product--catalog-product-counter" style="display:none;">
          <div class="product-counter" data-price="<?php echo $price; ?>" data-dops_price="0" style="max-width: unset;  margin-bottom: 0;" >
              <div class="minus changer"><i class="fa fa-minus"></i></div>
              <input type="number" class="quantity qty catalog_simple_qty_changer" data-catalog_simple_qty_changer value="1" min="1"> 
              <div class="plus changer"><i class="fa fa-plus"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>