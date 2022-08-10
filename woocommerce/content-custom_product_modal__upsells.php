<?php
if (!defined('ABSPATH')) {
  exit;
}

global $product;

$cross_sell_ids = $product->get_upsell_ids();
$reqired_dops = get_post_meta($product->id, 'required_dops', true);	
//print_r($reqired_dops);
?>
<script>
  var swiper3 = new Swiper("#modal_id_<?php echo $product->id ?>.upsell_slider", {
    slidesPerView: 3,
    loop: false,
    spaceBetween: 10,
    navigation: {
      nextEl: ".upsell_slider-next",
      prevEl: ".upsell_slider-prev",
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
    <div class="custom-product--modal-bottom custom-product--modal-upsells">
      <div class="container_upsells">
        <div class="custom-product--modal-bottom-title">Дополнительно</div>
        <div id="modal_id_<?php echo $product->id ?>" class="swiper cross_sell_slider upsell_slider">

          <div class="swiper-wrapper custom-product--modal-cross-sells ">
            <?php foreach ($cross_sell_ids as $cross_sell_id) :

              $cross_sell = wc_get_product($cross_sell_id);

              $price_prefix = $cross_sell->is_type('variable') ? 'от' : '';
              $button_class = $cross_sell->is_type('variable') ? 'custom-product--open-modal' : 'ajax_add_to_cart add_to_cart_button';
            ?>
              <div class="swiper-slide custom-product--modal-upsells-sl" data-product_id="<?php echo $cross_sell_id; ?>">
                <?php echo $cross_sell->get_image('woocommerce_thumbnail', ['class' => 'custom-product--modal-cross-sell-mage']); ?>

                <div class="custom-product--modal-cross-sell-title dops-for-product-price">
                  <?php echo $cross_sell->get_title(); ?><?=apply_filters('the_count_type', $cross_sell);?> 
                  <br><span><?php echo ($cross_sell->is_on_sale() ? $cross_sell->get_sale_price() : $cross_sell->get_price()); ?> <i class="fa fa-rub"></i></span>
                </div>
                
                <?php /*if ($cross_sell->is_on_sale()) : ?>
                  <div class="custom-product--modal-cross-sell-price-current" data-quantity="1" data-product_id="<?php echo $cross_sell_id; ?>">
                    <?php echo $price_prefix; ?> <?php echo $cross_sell->get_sale_price(); ?> <i class="fa fa-rub"></i>
                  </div>
                <?php else : ?>
                  <div class="custom-product--modal-cross-sell-price-current" data-quantity="1" data-product_id="<?php echo $cross_sell_id; ?>">
                    <?php echo $price_prefix; ?> <?php echo $cross_sell->get_price() ?: '0'; ?> <i class="fa fa-rub"></i>
                  </div>
                <?php endif; */?>

                <div class="container_upsells__wrapper">
                  <div data-product_id="<?php echo $cross_sell_id; ?>" data-product_dop_price="<?php echo($cross_sell->is_on_sale() ? $cross_sell->get_sale_price() : $cross_sell->get_price());?>" class="ajax_add_to_cart__add_dops button--upsell--add-cart-dops">
                    Добавить
                  </div>
                  <div class="checkmark-icon">
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

        </div>
        <div class="swiper-button-next upsell_slider-next"></div>
        <div class="swiper-button-prev upsell_slider-prev"></div>
      </div>
    </div>

  <?
  /*Включаем обязательные допы*/
  if($reqired_dops){?>
    <script>
      var arr = [<?=implode(',', $reqired_dops);?>];
      setTimeout(() => { 
          arr.forEach(function(item, i, arr) {
             console.log('arr_elem', item);
             $('.ajax_add_to_cart__add_dops[data-product_id="'+item+'"]').click().addClass('disabled').text('Добавлено');
          });
         
          console.log('arr', arr);
      }, 600);
    </script>
  <? } ?>
<?php endif;
} ?>