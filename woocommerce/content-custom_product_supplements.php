<?php
defined( 'ABSPATH' ) || exit;

global $product;

$supplements_html = '';
$supplements_required = 0;

$custom_fields = get_fields($product->id); // все допполя

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
  $supplements_html .= '<div class="supp_content">';
  $supplements_html .= "<h4 class=\"supp-h\">{$supp['title']}</h4>";
  if( ($supp[ 'type' ] == 'chekbox' || $supp[ 'quantity' ] == 'multiple' ) && $supp['quantity_max'] > 0 )
    $supplements_html .= "<h5 class=\"supp-h_max\">Не более {$supp['quantity_max']} шт., осталось <span data-max_ostatok=\"{$supp['quantity_max']}\">{$supp['quantity_max']}</span> шт.</h5>";
  
  $supplements_html .= '<div class="supplements supplements-' . $supp[ 'quantity' ] . ' supplements-' . $supp[ 'type' ] . '" data-quantity="' . $supp[ 'quantity' ] . '" data-quantity _max="' . $supp[ 'quantity_max' ] . '"  data-type="' . $supp[ 'type' ] . '" >';
    //switch ( $supp[ 'type' ] ) {
    switch ( $supp[ 'quantity' ] ) {      
      case 'multiple':
        $inputs = '';
        foreach ( $supp[ 'products' ] as $vp ) {
          $inputs .= '
          <div class="supp-div" data-prod="' . $vp . '" data-price="' . $prod[ $vp ][ 'price' ] . '" >

            <label class="checkbox-ios supp-label" style="" for="cb_' . $i . '_' . $vp . '">

            <div class="supp-quantity woocommerce-cart-form__cart-item" data-prod="' . $vp . '">' . woocommerce_quantity_input( array( 'min_value' => 0, 'max_value' => $supp['quantity_max'] /*$prod[ $vp ][ 'product' ]->get_stock_quantity()*/, 'input_value' => 0, ), $prod[ $vp ][ 'product' ], false ) . '</div>

            <input class="checkbox-other supp-checkbox" type="'.($supp[ 'type' ]=='chekbox' ? 'checkbox' : 'radio').'" name="cb_' . $i . '" id="cb_' . $i . '_' . $vp . '" value="' . $vp . '">
            <span class="checkbox-ios-switch"></span>

            <span class="supp-name">' . $prod[ $vp ][ 'name' ] . '</span> 
            <div class="supp-price">+' . $prod[ $vp ][ 'price_html' ] . '</div>
            </label>
          </div>
    ';
      }
      $supplements_html .=  $inputs;
      break;

    case 'once':
      $inputs = '';
      foreach ( $supp[ 'products' ] as $vp ) {
        
        $inputs .= '
        <div class="supp-div" data-prod="' . $vp . '" data-price="' . $prod[ $vp ][ 'price' ] . '" >
        <label class="checkbox-ios supp-label" for="cb_' . $i . '_' . $vp . '">
          <div class="supp-quantity woocommerce-cart-form__cart-item" style="display:none">' . woocommerce_quantity_input( array( 'min_value' => 0, 'max_value' => 999, 'input_value' => 0, 'product_name' => $supp['title'] ), null, false ) . '</div>
          <input type="'.($supp[ 'type' ]=='chekbox' ? 'checkbox' : 'radio').'" class="supp-radio checkbox-other supp-checkbox" name="cb_' . $i . '" id="cb_' . $i . '_' . $vp . '" data-id="' . $i . '_' . $vp . '" data-price="' . $prod[ $vp ][ 'price' ] . '" value="' . $vp . '" style="display:none;">
          <span class="checkbox-ios-switch"></span>
          <span class="supp-name">' . $prod[ $vp ][ 'name' ] . apply_filters( 'the_count_type', $vp ) .'</span> 
          <div class="supp-price">+' . $prod[ $vp ][ 'price_html' ] . '</div>
        </label> 
        </div>
  ';
      }
      $supplements_html .= $inputs ;

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
$supplements_html .=  '</div>';

}

wp_reset_postdata();
?>

<div class="custom-product--modal-inner">
  <div class="custom_product--modal-header">
    <h3>Выберите опции</h3>
    <div class="custom-product--modal-close custom-product--modal-close-v2">&times;</div>
  </div>

  <div class="custom-product--modal-content" style="overflow: hidden;">
    <div class="inner-block">
      <?php echo $supplements_html; ?>
    </div>  
  </div>
  <!--<div class="custom-product--modal-clones" data-product_id="<?php echo $product->id; ?>"></div>-->
  
  <div class="custom-product--modal-clones" data-product_id="<?php echo $product->id; ?>">
    <div class="product-counter" data-price="<?php echo $product->get_price(); ?>">
      <div class="minus changer"><i class="fa fa-minus"></i></div>
      <input type="number" value="1" min="1">
      <div class="plus changer"><i class="fa fa-plus"></i></div>
    </div>
    <button class="custom-product--modal-add-to-cart ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo $product->id; ?>" data-variation_id="0" data-quantity="1" data-supplements="{}">Добавить в корзину за <span class="summ"><?php echo ($product->is_on_sale() ? $product->get_sale_price() : $product->get_price()); ?></span> <i class="fa fa-rub"></i></button>
  </div>

</div>
<script>
  //$('.supplements .input[type="text"]') [type="radio"]
  /* Переключатели radio */
  $(document).on('change', '.supplements-once input[type="number"]', function() {  //переключатель в режиме radioButton и один продукт
    var $radio = $(this).parents('.supp-div').find('input[type="radio"]');
    var $this = $(this);
      if($radio.length){
          if(!$radio.prop('checked')) $radio.click();
          $(this).parents('.supplements-once').find('input[type="number"]').each(function( index, value ) {
              if(($(value).val()*1 > 0) && ($(this).prop('id') !== $this.prop('id'))){
                 $(value).val('0');
              }
          });
      }
  });

  $(document).on('change', '.supplements-once input[type="radio"]', function() {
      $(this).parents('.supp-div').find('input[type="number"]').val(1).change();
  });

  /* плюс\минус radio */
  $(document).on('change', '.supplements-multiple input[type="number"]', function() {
      var $radio = $(this).parents('.supp-div').find('input[type="radio"]');
      var $this = $(this);
        if($radio.length){
            $(this).parents('.checkbox-ios').removeClass('checkbox-ios'); // Велосипед, т.к. задваивается клик 

            if(!$radio.is(':checked')) $radio.click();
            $(this).parents('.supplements-multiple').find('input[type="number"]').each(function( index, value ) {
                if(($(value).val()*1 > 0) && ($(this).prop('id') !== $this.prop('id'))){
                  $(value).val('0');
                }
            });
        }
  });  

  $(document).on('change', '.supplements-once input[type="checkbox"]', function() {
      //Остатки для чекбоксов
      console.log('Остатки для чекбоксов');
      ostatok_element = $(this).parents('.supp_content').find('[data-max_ostatok]'),
      ostatok_max = ostatok_element.data('max_ostatok')*1,
      ostatok_current = ostatok_element.text()*1,
      total_ostatok = 0;
      if(!!ostatok_element){
        $(this).parents('.supplements-once').find('input[type="number"]').each(function( index, value ) {
                total_ostatok+=$(value).val()*1;
        });

        if(total_ostatok > ostatok_max){
          //$(this).val($(this).val()*1-1);
          $(this).prop('checked', false);
          return false;
        }
        ostatok_element.text(ostatok_max-total_ostatok)
      }
    });

   $(document).on('change', '.supplements input[type="number"]', function() {
      var count = $(this).val()*1,
      $this = $(this),
      norm = true,
      ostatok_element=$(this).parents('.supp_content').find('[data-max_ostatok]'),
      ostatok_max = ostatok_element.data('max_ostatok')*1,
      ostatok_current = ostatok_element.text()*1,
      total_ostatok = 0;
      //Остатки
      if(!!ostatok_element){
        $(this).parents('.supplements-multiple').find('input[type="number"]').each(function( index, value ) {
                total_ostatok+=$(value).val()*1;
        });

        if(total_ostatok > ostatok_max){
          $(this).val($(this).val()*1-1);
          return false;
        }
        ostatok_element.text(ostatok_max-total_ostatok)
      }

      // if( $(this).parents('.supplements').hasClass('supplements-once') ){

      //   $(this).parents('.supplements-once').find('input[type="number"]').each(function( index, value ) {
      //       if(($(value).val()*1 > 0) && ($(this).prop('id') !== $this.prop('id'))){
      //         $(value).val('0');
      //         alert();
      //         $(value).parents('.supp-div').find('[type="checkbox"]').click();
      //       }
      //   });
      // }
      if(norm){
        console.log('!!!3333')
        if(count){
          $(this).parents('.supp-div').find('[type="checkbox"]').prop('checked', 'checked');
        }else
        { 
          $(this).parents('.supp-div').find('[type="checkbox"]').click(); //выключаем чекбокс
        }
      }  
  });	


  /* выключение radio button */
  $(document).on('mousedown', '.checkbox-ios', function(e) {
      if($(this).find('input[type="radio"]').prop('checked')){
        setTimeout(() => {    
          $(this).find('input[type="radio"]').prop('checked', false);
          $(this).parents('.supplements-once').find('.qib-container input[type="number"]').val(0).change();
        }, 200);
      }
  });
</script>
<style>
   .supplements-multiple input[type="radio"]{
   display: none;
  }
</style>