<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product;

$cross_sell_ids = $product->get_cross_sell_ids();

?>
<script>
var swiper4 = new Swiper("#modal_id_<?php echo $product->id ?>.cross_slider", {
  slidesPerView: 3,
  loop: true,
  spaceBetween: 10,
  navigation: {
    nextEl: ".cross_sell_slider-next",
    prevEl: ".cross_sell_slider-prev",
  },
  breakpoints: {
    320: {
        slidesPerView: 1,
        spaceBetween: 10
    },
    414: {
        slidesPerView: 2,
        spaceBetween: 10
    },
    640: {
      slidesPerView: 4,
      spaceBetween: 10
    }
  },
});
</script>
<?php if (count($cross_sell_ids) > 0) { ?>
<?php if (count($cross_sell_ids)) : ?>
<div class="custom-product--modal-bottom">
<div class="container_cross_sells">
  <div class="custom-product--modal-bottom-title">Мы рекомендуем</div>
  <div id="modal_id_<?php echo $product->id ?>" class="swiper cross_sell_slider cross_slider">

    <div class="swiper-wrapper custom-product--modal-cross-sells">
      <?php foreach ($cross_sell_ids as $cross_sell_id) : 
        $cross_sell = wc_get_product($cross_sell_id);
        $price_prefix = $cross_sell->is_type('variable') ? 'от' : '';
        $button_class = $cross_sell->is_type('variable') ? 'custom-product--open-modal' : 'ajax_add_to_cart add_to_cart_button custom_add_to_cart_btn';
      ?>
      <div class="swiper-slide custom-product--modal-cross-sell" data-product_id="<?php echo $cross_sell_id; ?>">
        <?php echo $cross_sell->get_image('woocommerce_thumbnail', ['class' => 'custom-product--modal-cross-sell-mage']); ?>

        <div class="custom-product--modal-cross-sell-title"><?php echo $cross_sell->get_title(); ?><?=apply_filters('the_count_type', $cross_sell);?></div>

        <?php if ($cross_sell->is_on_sale()) : ?>
          <div class="custom-product--modal-cross-sell-price-current <?php echo $button_class; ?>" data-quantity="1" data-product_id="<?php echo $cross_sell_id; ?>">
            <?php echo $price_prefix; ?> <?php echo $cross_sell->get_sale_price(); ?> <i class="fa fa-rub"></i>
          </div>
        <?php else : ?>
          <div class="custom-product--modal-cross-sell-price-current <?php echo $button_class; ?>" data-quantity="1" data-product_id="<?php echo $cross_sell_id; ?>">
            <?php echo $price_prefix; ?> <?php echo $cross_sell->get_price() ?: '0'; ?> <i class="fa fa-rub"></i>
          </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="swiper-button-next cross_sell_slider-next"></div>
  <div class="swiper-button-prev cross_sell_slider-prev"></div>
</div>
</div>
<?php endif; } ?>