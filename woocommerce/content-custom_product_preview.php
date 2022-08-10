<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product;

$price = $product->is_on_sale() ? $product->get_sale_price() : $product->get_price();
$ingridients = get_field('ingridients', $product->id);
$gallery_images_ids = $product->get_gallery_image_ids();
$custom_fields = get_fields($product->id); // все допполя
$supplements_required = (isset($custom_fields['supplements_required'] ) && $custom_fields['supplements_required'] && !empty($custom_fields[ 'supplements' ])) ? 1 : 0;

?>
<script>
    var swiper = new Swiper("#modal_id_<?php echo $product->id ?>.slider_preview_img", {
        loop: false,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper("#modal_id_<?php echo $product->id ?>.slider_big_img", {
        loop: true,
        spaceBetween: 10,
        thumbs: {
            swiper: swiper,
        },
        navigation: {
            nextEl: "#modal_id_<?php echo $product->id ?> .swiper-button-next",
            prevEl: "#modal_id_<?php echo $product->id ?> .swiper-button-prev",
        },
    });
</script>
<div class="custom-product--modal-inner">

  <div class="custom-product--modal-close" style="background:url('https://pizza.xn--90agcwb4c1dc.xn--p1ai/wp-content/themes/pizzaro/assets/images/close-auth.png')"></div>

  <div class="custom-product--modal-top" style="background-color: #fafafa;">
    <div class="custom-product--modal-top-left" 333 style="width:465px">
    <?php if (empty($gallery_images_ids)) {
                echo $product->get_image('woocommerce_single', ['class' => 'custom-product--modal-image']);
                product_single_custom_labels();
            } else { ?>
                <? //php echo $product->get_image('woocommerce_thumbnail', ['class' => 'custom-product--modal-image']); 
                ?>
                <div class="custom-product--modal-gallery">
                    <?/*php foreach ($gallery_images_ids as $gallery_image_id) : 
                    $gallery_image_arr = wp_get_attachment_image_src($gallery_image_id, 'thumbnail');
                    $gallery_image_url = !empty($gallery_image_arr) ? $gallery_image_arr[0] : '';
                    ?>
                    <img class="custom-product--modal-gallery-image" src="<?php echo $gallery_image_url; ?>" alt="">
                    <?php endforeach; */ ?>
                    <div id="modal_id_<?php echo $product->id ?>" class="swiper slider_big_img">
                    <? product_single_custom_labels();?>
                        <div class="swiper-wrapper">
                            <?php foreach ($gallery_images_ids as $gallery_image_id) :
                                $gallery_image_arr = wp_get_attachment_image_src($gallery_image_id, 'large');
                                $gallery_image_url = !empty($gallery_image_arr) ? $gallery_image_arr[0] : '';
                            ?>
                                <div class="swiper-slide">
                                    <img class="custom-product--modal-gallery-image" src="<?php echo $gallery_image_url; ?>" alt="">
                                </div>
                            <?php endforeach; ?>

                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <div thumbsSlider="" id="modal_id_<?php echo $product->id ?>" class="swiper slider_preview_img">
                        <div class="swiper-wrapper">

                            <?php foreach ($gallery_images_ids as $gallery_image_id) :
                                $gallery_image_arr = wp_get_attachment_image_src($gallery_image_id, 'thumbnail');
                                $gallery_image_url = !empty($gallery_image_arr) ? $gallery_image_arr[0] : '';
                            ?>
                                <div class="swiper-slide">
                                    <img class="custom-product--modal-gallery-image" src="<?php echo $gallery_image_url; ?>" alt="">
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            <?php } ?>
    </div>

    <div class="custom-product--modal-top-right">
      <div class="custom-product--modal-title">
        <?php echo $product->get_title(); ?>
      </div>
      <? 
          $the_count_type = apply_filters('the_count_type', $product);
          // $qua = $product->get_stock_quantity();
          // if($qua) $qua.='pc.';
          $qua = pre_quantity_unit($product);
          if($the_count_type && $qua) $the_count_type.="&nbsp;/&nbsp;";

      ?>
      <div class="custom-product--modal-description">
        <?php echo $product->get_description(); ?> <?=$the_count_type;?><?=$qua;?>
      </div>

     <div class="custom-product--modal-ingridients">
        <!-- Состав:  -->
        <?php foreach ($ingridients as $ingridient) : ?>
          <?php 
            $can_exclude_class = ($ingridient['can_exclude']) ? 'can-exclude' : 'cant-exclude';
            $closer = ($ingridient['can_exclude']) ? ' <span class="closer"><i class="fa fa-times-circle"></i><i class="fa fa-undo"></i></span>' : '';
          ?>
          <span class="custom-product--modal-ingridient <?php echo $can_exclude_class; ?>"><?php echo $ingridient['ingridient_title'] . $closer; ?></span>
        <?php endforeach; ?>
      </div>
      <input type="hidden" name="excluded_ingridients" value="">

      <!-- БКЖУ -->
      <div class="custom-product--kgbu">
          <? if($product->get_weight()){?>
          <div class="custom-product--kgbu-elem">
              <div class="kgbu-title">Вес</div>
              <div class="kgbu-value"><? echo $product->get_weight();?> г</div>
          </div>
          <? }?>
          <? if($custom_fields['protein_product_nutr']){?>
          <div class="custom-product--kgbu-elem">
          <div class="kgbu-title">Белки</div>
          <div class="kgbu-value"><? echo $custom_fields['protein_product_nutr']?> г</div>
          </div>
          <? }?>
          <? if($custom_fields['fat_product_nutr']){?>
          <div class="custom-product--kgbu-elem">
          <div class="kgbu-title">Жиры</div>
          <div class="kgbu-value"><? echo $custom_fields['fat_product_nutr']?> г</div>
          </div>
          <? }?>
          <? if($custom_fields['uglevod_product_nutr']){?>
          <div class="custom-product--kgbu-elem">
          <div class="kgbu-title">Углеводы</div>
          <div class="kgbu-value"><? echo $custom_fields['uglevod_product_nutr']?> г</div>
          </div>
          <? }?>
          <? if($custom_fields['kkal_product_nutr']){?>
          <div class="custom-product--kgbu-elem">
          <div class="kgbu-title">Калорийность</div>
          <div class="kgbu-value"><? echo $custom_fields['kkal_product_nutr']?> Ккал</div>
          </div>
          <? }?>        
      </div>
      <!-----БКЖУ -->


      <div class="custom-product--modal-product-counter">
        <div class="product-counter" data-price="<?php echo $price; ?>">
          <div class="minus changer"><i class="fa fa-minus"></i></div>
		  <?=apply_filters('fake_qty', $product);?>
          <input type="number" disabled class="quantity" value="1" min="1">
          <div class="plus changer"><i class="fa fa-plus"></i></div>
        </div>
      </div>

      <button class="custom-product--modal-add-to-cart ajax_add_to_cart <?if(!$supplements_required):?>add_to_cart_button<?endif;?>" data-product_id="<?php echo $product->id; ?>" data-variation_id="0" data-quantity="1" <? echo ($supplements_required==1 ? 'onclick="$(this).next().click();return false;"' : '') ;?>>Добавить в корзину за <span class="summ"><?php echo $price; ?></span> <i class="fa fa-rub"></i></button>

      <?php if ($product->get_type() == 'supplements') : ?>
        <button class="custom-product--open-modal-supplements" data-startprice="<?php echo $price; ?>" data-product_id="<?php echo $product->id; ?>"><i class="fa fa-plus"></i> Выбрать ингредиенты</button>
      <?php endif; ?>
    </div>
  </div>

  <!-- <div class="custom-product--modal-bottom"> -->
    <?php wc_get_template_part('content', 'custom_product_modal__upsells'); ?>
    <?php wc_get_template_part('content', 'custom_product_modal__cross_sells'); ?>
  <!-- </div> -->
</div>