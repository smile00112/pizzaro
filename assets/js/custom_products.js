jQuery(document).ready(function($){

  var $supplements = {
    modal: null,
    cart_button: null,
    cart_button_clone: null,
    startprice: 0,
    quantity: 1,
    price: null,
    counter: null,
    counter_clone: null,
  }

  function calcCustomProductCounterSumm($counter) {
    var price = parseFloat($counter.attr('data-price'));
    var count = parseInt($counter.find('input').val());
    var total = price * count;

    $counter.closest('.custom-product--modal').find('.custom-product--modal-add-to-cart span').text(total);
    if ($counter.closest('.custom-product--modal-clones').length > 0) {
      var product_id = $counter.closest('.custom-product--modal-clones').data('product_id');
      var $orig = $('.custom-product--modal.cpm-modal[data-id="' + product_id + '"] .product-counter');
      $orig.find('input').val(count);
      calcCustomProductCounterSumm($orig);
    }
  }

  function calcDopsProductCounterSumm($counter) {
    
    if(!$('.ajax_add_to_cart__add_dops').length) return;
    
    var count = parseInt($counter.find('input').val());
    var price = parseFloat($counter.attr('data-price'));
    var dops_price = parseFloat($counter.attr('data-dops_price'));
    var total = (price + dops_price) * count;

    $counter.closest('.custom-product--modal').find('.custom-product--modal-add-to-cart span').text(total);
  }

  function makeSupplementsClones() {
    var $counter_clone = $supplements.counter.clone();
    var $cart_button_clone = $supplements.cart_button.clone();
    
    // console.warn('counter', $supplements.counter);
    // console.warn('cart_button', $supplements.cart_button);
    if($counter_clone && $cart_button_clone){
      $cart_button_clone.addClass("add_to_cart_button");
      
      console.log('cart_button_clone',$cart_button_clone)

      $supplements.modal.find('.custom-product--modal-clones')
                      .html('')
                      .append($counter_clone)
                      .append($cart_button_clone);
    }
    $supplements.counter_clone = $counter_clone;
    $supplements.cart_button_clone = $cart_button_clone;
  }

  var $body = $('body');

  // Backdrop for custom modal
  var $backdrop = $('<div/>').addClass('custom-product--modal-backdrop');
  $('body').append($backdrop);

  $(document).on('click', '.add_to_cart_button', function(e){
	  
	setTimeout(() => {
	
		swal("Товар добавлен в корзину", {
		  buttons: false,
		  timer: 1000,
		});
		
	}, 800);
	  
    e.stopPropagation();
    e.stopImmediatePropagation();

    ///////////// кнопка купить с заменой на количество визуал
    if ($(this).hasClass('ajax_add_to_cart')){
        $(this).parents('.custom-product--bottom-right').find('.custom-product--catalog-product-counter').fadeIn(); 
        $(this).hide();
    }


	
  });
  

	
  //изменение колличества в каталого послек нажатия купить
  // $(document).on('click', '.custom-product--catalog-product-counter', function(e){
  //   e.stopPropagation();
  //   e.stopImmediatePropagation();
  // });


  ///////////// кнопка купить с заменой на количество визуал 
  $(document).on('change', '.ajax_add_to_cart_mod', function() {
    var add_to_cart_button = jQuery( this ).parents( ".custom-product" ).find( ".add_to_cart_button" );
    // Для работы добавления в корзину с помощью AJAX
    // add_to_cart_button.data( "quantity", jQuery( this ).val() );
    // Для работы добавления в корзину БЕЗ AJAX

  });  
  //Изменение количества товара посленажатия кнопки Купить
  $(document).on('change', '[data-catalog_simple_qty_changer]', function() {
      var $buy_btn = $(this).parents('.custom-product--bottom-right').find('.ajax_add_to_cart_catalog_simple_prod');
      $buy_btn.attr( "data-quantity", $(this).val() );
      $buy_btn.removeClass('added');
      $buy_btn.click();
  });

  ////////////// On cart button click
  $(document).on('click', '.custom-product--open-modal, .custom-product--open-preview, .custom-product--modal-cross-sell', function(e){
    e.preventDefault();
    e.stopPropagation();

    var $target = $(e.target);

    if ($target.hasClass('add_to_cart_button')) return;
    //если изменяем количество на корточке в каталоге
    if ($target.hasClass('ajax_add_to_cart_catalog_simple_prod') && $target.parents('.custom-product--bottom-right').length) return;
    
    // if ($target.parent().hasClass('custom-product--bottom-right')) return;    
    // console.log($target.parent().attr('class'));
    if ($target.hasClass('wc-forward')) {
      window.location.href = $target.attr('href');
      return;
    }
   if ($target.parents('.dont-show-modal').length) {
      return;
    }

    var product_id = $(this).data('product_id');
    var type = $(this).hasClass('custom-product--open-preview') ? 'preview' : 'modal';
    var type_class = 'cpm-' + type;

    // If have this product modal, just show
    if ( $('.custom-product--modal.' + type_class + '[data-id="' + product_id + '"]').length ) {
      $('.custom-product--modal.' + type_class + '[data-id="' + product_id + '"]').addClass('show');
      if ($('.custom-product--modal').length > 1) {
        $('.custom-product--modal.' + type_class + '[data-id="' + product_id + '"]').insertAfter('.custom-product--modal:last-child');
      }
      $backdrop.addClass('show');
      return;
    }

    // Create new modal
    var $loader = $('<div/>').addClass('custom-product--modal-loader');
    var $modal = $('<div/>').addClass('custom-product--modal ' + type_class).attr('data-id', product_id);
    $modal.append($loader);
    // Append created modal in backdrop container
    $backdrop.append($modal);
    // Show modal and backdrop
    $modal.addClass('show');
    $backdrop.addClass('show');
    // Fix body
    $body.addClass('is-non-scrollable');

    // Load modal view
    $.get('/wp-json/popup/v1/custom_product_' + type + '?product_id=' + product_id, function(res) {
      if (typeof res.product_view === 'undefined') {
        alert('Ошибка загрузки данных. Попробуйте перезагрузить страницу или выбрать товар немного позднее.');
        $modal.removeClass('show');
        $backdrop.removeClass('show');
        $body.removeClass('is-non-scrollable');
        setTimeout(() => { $modal.remove() }, 600);
        return;
      }

      // Change product view
      $modal.html(res.product_view);
      setTimeout(() => { 
        if ($modal.find('.custom-product--modal-variation.is-default').length > 0) {
          $modal.find('.custom-product--modal-variation.is-default').trigger('click');
        } else {
          $modal.find('.custom-product--modal-variation:first-child').trigger('click');
        }
        $modal.find('.custom-product--modal-inner').addClass('show'); 
      }, 300)
    });
  });

  $(document).on('click', '.custom-product--open-modal-supplements', function(e){
    e.preventDefault();
    e.stopPropagation();

    var product_id = $(this).data('product_id');

    $button = $(this).closest('.custom-product--modal').find('.custom-product--modal-add-to-cart');
    $supplements.cart_button = $button;
    $supplements.price = $button.find('span');
    $supplements.quantity = parseInt($button.data('quantity'));
    $supplements.counter = $(this).closest('.custom-product--modal').find('.product-counter');
    $supplements.startprice = $supplements.quantity * Math.ceil($supplements.counter.data('price'));

    // If have this product modal, just show
    if ( $('.custom-product--modal.supplements[data-id="' + product_id + '"]').length ) {
      var $modal = $('.custom-product--modal.supplements[data-id="' + product_id + '"]');
      $modal.addClass('show');
      if ($('.custom-product--modal').length > 1) {
        $modal.insertAfter('.custom-product--modal:last-child');
      }
      $supplements.modal = $modal;
      $backdrop.addClass('show');
      return;
    }

    // Create new modal
    var $loader = $('<div/>').addClass('custom-product--modal-loader');
    var $modal = $('<div/>').addClass('custom-product--modal supplements').attr('data-id', product_id);
    $modal.append($loader);
    // Append created modal in backdrop container
    $backdrop.append($modal);
    // Show modal and backdrop
    $modal.addClass('show');
    $backdrop.addClass('show');
    // Fix body
    $body.addClass('is-non-scrollable');

    // Load supplements modal view
    $.get('/wp-json/popup/v1/custom_product_supplements?product_id=' + product_id, function(res) {
      if (typeof res.supplements_view === 'undefined') {
        alert('Ошибка загрузки данных. Попробуйте перезагрузить страницу или выбрать товар немного позднее.');
        $modal.removeClass('show');
        $backdrop.removeClass('show');
        $body.removeClass('is-non-scrollable');
        setTimeout(() => { $modal.remove() }, 600);
        return;
      }

      // Change product view
      $modal.html(res.supplements_view);
      setTimeout(() => {
        $supplements.modal = $modal;
        makeSupplementsClones();

        $modal.find('.custom-product--modal-inner').addClass('show');
        $modal.find('.supp-checkbox').each(function( index ) {
          var el = $(this);
          var supp = $(el).closest('div.supp-div');
          var prod = $(supp).attr('data-prod');   
          var price = $(supp).attr('data-price');
          var quantity_box =   $(supp).find('.supp-quantity');  
          var quantity_input = $(quantity_box).find('input[name="quantity"]');
          $(quantity_input).attr('data-price', price);
        });  
          
        supplementsCalc();
      }, 300)
    });        
  });


  $(document).on('click', '.custom-product--modal .wc-forward', function(e){
    e.stopPropagation();
    window.location.href = $(this).attr('href');
  });

  /////////////// On backdrop click

  $(document).on('click', '.custom-product--modal-backdrop, .custom-product--modal-close, .custom-product--modal-close_v2', function(e){
    e.stopPropagation();
    var $target = $(e.target);

    if (!$target.hasClass('custom-product--modal-backdrop') && !$target.hasClass('custom-product--modal-close') && !$target.hasClass('.custom-product--modal-close_v2')) {
      return;
    }

    var is_close = $(this).hasClass('custom-product--modal-close') ? true : $target.hasClass('.custom-product--modal-close_v2');

    if (is_close) {
      var $modal = $(this).closest('.custom-product--modal');
      var is_supplements = $modal.hasClass('supplements');

      if (is_supplements) {
        $modal.removeClass('show');
        return;
      }
    }

    $('.custom-product--modal').removeClass('show');
    $('.custom-product--supplements-modal').removeClass('show');
    $('.custom-product--modal-backdrop').removeClass('show');
    $body.removeClass('is-non-scrollable');
  });

  ////////////// On variation click
  $(document).on('click', '.custom-product--modal-variation', function(){
    var $this = $(this);
    var id = parseInt($this.data('id'));
    var price = parseFloat($this.data('price'));
    var $modal = $this.closest('.custom-product--modal');
    var description_html = $this.find('.custom-product--modal-variation-description').html();
    var $counter = $modal.find('.product-counter');
    var $button = $modal.find('.custom-product--modal-add-to-cart');
    var $button_suplements = $modal.find('.custom-product--open-modal-supplements');

    $button.attr('data-product_id', id);
    $button.attr('data-price', price);
    $button.attr('data-variation_id', id);
    $button.attr('data-variation', $this.find('input[name="variation"]').val());

    // if(!!$button_suplements){
    //   $button_suplements.attr('data-product_id', id);
    //   $button_suplements.attr('data-quantity', $modal.find('.product-counter input[type="number"]'));
    //   $button_suplements.attr('data-startprice', price);
    // }

    $counter.attr('data-price', price);
    calcCustomProductCounterSumm($counter);
    /* Пересчет с учетом допов */
    supplementsDopsCalc()

    $modal.find('.custom-product--modal-variation').removeClass('active');
    $this.addClass('active');

    if (description_html != '') {
      $modal.find('.custom-product--modal-description').html(description_html);
    }
  });
  
    $(document).on('click', '.custom-product--other-variation', function(){
    var $this = $(this);
    var id = parseInt($this.data('id'));
    var price = parseFloat($this.data('price'));
    var $modal = $this.closest('.custom-product--modal');
    var description_html = $this.find('.custom-product--modal-variation-description').html();
    var $counter = $modal.find('.product-counter');
    var $button = $modal.find('.custom-product--modal-add-to-cart');

    $button.attr('data-product_id', id);
    $button.attr('data-variation_id', id);
    $button.attr('data-variation', $this.find('input[name="variation"]').val());

    $counter.attr('data-price', price);
    calcCustomProductCounterSumm($counter);
    /* Пересчет с учетом допов */
    supplementsDopsCalc();
    
    $modal.find('.custom-product--other-variation').removeClass('active');
    $this.addClass('active');

    if (description_html != '') {
      $modal.find('.custom-product--modal-description').html(description_html);
    }
  });

  
  /////////// PRODUCT COUNTER
	var cart_update_timeout = 0
  $(document).on('click', '.product-counter .changer', function(){
    var $this = $(this);
    var type = $(this).hasClass('plus') ? 'plus' : 'minus';
    var $counter = $this.closest('.product-counter');
    var $input = $counter.find('input.quantity');
    var $input_ves = $counter.find('input.fake-qty');
    var val = parseInt($input.val());
    var ves_base = $input_ves.data('portion');
    var ves_txt = $input_ves.data('txt');
    var $modal = $this.closest('.custom-product--modal');
    var $button = $modal.find('.custom-product--modal-add-to-cart');
    var product_id = $(this).parents('.custom-product--modal').data('id');
    if(typeof product_id=="undefined") product_id = $(this).parents('.custom-product').data('product_id');
	var remove_from_cart = false;
	
    if (type == 'plus') {
      val++;
    } else {
      val--
    }

    if (val < 1) {
		remove_from_cart = true;
		val = 0;
    }

    $input_ves.val( (ves_base*val)+ ' ' + ves_txt );
    $input.val(val);
    $button.attr('data-quantity', val);

    clearTimeout(cart_update_timeout);
    cart_update_timeout = setTimeout(function() {
      
	  /*удаляем если кол-во = 0*/
	  if(remove_from_cart === true){
		  $this.parents('.custom-product--bottom-right').find('.custom-product--add-to-cart').removeClass('added').show();
		  $this.parents('.custom-product--catalog-product-counter').hide();
		  $('a.remove_from_cart_button[data-product_id="'+product_id+'"]').click();
	  }else{
		  if($input.hasClass('catalog_simple_qty_changer')){
			$input.trigger("change");
    }

		  calcCustomProductCounterSumm($counter);
		  /* Пересчет с учетом допов */
		  supplementsDopsCalc(product_id);
	  }
    }, 800);
  }); 


  ////////// Ingridients controller
  $(document).on('click', '.custom-product--modal-ingridient .closer', function(e) {
    e.stopPropagation();
    e.preventDefault();

    var $closer = $(this);
    var $ingridients = $closer.closest('.custom-product--modal-ingridients');
    var $ingridient = $closer.closest('.custom-product--modal-ingridient');
    var $button = $closer.closest('.custom-product--modal').find('.custom-product--modal-add-to-cart');

    if ($closer.hasClass('revert')) {
      $ingridient.removeClass('excluded');
    } else {
      $ingridient.addClass('excluded');
    }

    var excluded = [];
    $ingridients.find('.custom-product--modal-ingridient.excluded').each(function(index, ingridient){
      excluded.push( $(ingridient).text().trim() );
    }).promise().done(function(){
      $button.attr('data-excluded', excluded.join(', '));
      $closer.toggleClass('revert');
    });
  });




  $(document).on('change', '.supp-select, .supp-radio, .supp-checkbox', function() {
    var el = $(this);
    var supplements = $(el).closest('div.supplements');
    var suppContener = $(el).closest('div.supp-label');

    var quantity =  $(supplements).attr('data-quantity');
    var type =  $(supplements).attr('data-type');
      
    switch (type) {
      case 'chekbox':
        var supp = $(el).closest('div.supp-div');
        var prod = $(supp).attr('data-prod');   
        var price = $(supp).attr('data-price');
        var quantity_box =   $(supp).find('.supp-quantity');  
        var quantity_input = $(quantity_box).find('input[name="quantity"]');
        if(quantity == 'once'){
        var q = ( $(el).prop('checked') ) ? 1 : 0;
          $(quantity_input).val(q).attr('data-price', price);
        }
      break;

      case 'radio':
        var input = $(supplements).find('.supp-radio:checked');
        var prod = $(input).val();  
        var price = $(input).attr('data-price');
        var quantity_box = $(supplements).find('.supp-quantity');
        var quantity_input = $(suppContener).find('input[name="quantity"]');
        var q = ( !!input ) ? 1 : 0;
        $(quantity_input).val(q).attr('data-price', price);
      break;
        
      case 'list':
        var option = $(el).find('option:checked');
        var prod = $(el).val();  
        var price = $(option).attr('data-price');
        var quantity_box = $(supplements).find('.supp-quantity');  
        var quantity_input = $(quantity_box).find('input[name="quantity"]');
        var q = ( !!prod ) ? 1 : 0;
        $(quantity_input).val(q).attr('data-price', price);      
      break;
        
      default:
      return false;
    }

    if(!!prod) {
       $(quantity_box).attr('data-prod', prod);
    }
     supplementsCalc();
    });  
      
    $(document).on('input change', 'input[name="quantity"]', function() {
      supplementsCalc();
    });
      
    $('#add_cart_form input[name="quantity"]').val(1);

      
    function supplementsCalc() {
      var startprice = $supplements.counter.data('price');
      var osn_q = $supplements.quantity;
      var endprice = startprice * osn_q;
      
      var arr = {}; 

      $( 'div.supplements input[name="quantity"]' ).each(function( index ) { 
        var quantity = Math.ceil(Number($(this).val()) );  
        var price =  Math.ceil(Number($(this).attr('data-price') ));
        var parent = $(this).closest('.supp-quantity');
        var prod = $(parent).attr('data-prod');
        if(!!prod && !!quantity && quantity > 0){  
          arr[index] = {'quantity': quantity, 'prod': prod};  
          endprice += quantity*osn_q*price;
        }
        //console.warn(quantity, price, $(this).prop('id'));
      });
        
      $supplements.counter_clone.attr('data-price', Math.ceil(endprice / osn_q));
      calcCustomProductCounterSumm($supplements.counter_clone);
        
      var json = JSON.stringify(arr);
      $supplements.cart_button_clone.attr('data-supplements', json);
    }
    
    $(document).on('click', '.ajax_add_to_cart__add_dops', function() {
      if($(this).hasClass('disabled')) return;

      $(this).hasClass('dop-added-to-cart') ? $(this).removeClass('dop-added-to-cart').text('Добавить') : $(this).addClass('dop-added-to-cart').text('Удалить');

      supplementsDopsCalc();
    });

   
    /* допы для пиццы - пересчитываем цену с учетом допов, переводим в json */
    function supplementsDopsCalc() {
      var $modal = $('.custom-product--modal.show');

      var $cart_button = $modal.find('.custom-product--modal-add-to-cart');
      var startprice = $modal.find('.product-counter').attr('data-price')*1;
      // console.log('startprice', startprice)
      var osn_q =  $('.custom-product--modal-inner.show .product-counter input').val()*1;
      var endprice = startprice * osn_q;
      var dopsprice = 0;
      var arr = {}; 

     
          $modal.find( '.dop-added-to-cart' ).each(function( index ) { 
            var quantity = osn_q;  
            var price =  Math.ceil(Number($(this).attr('data-product_dop_price') ));
            //var parent = $(this).closest('.supp-quantity');
            var product_id = $(this).data('product_id');
            var is_requred = $(this).hasClass('requred_dop');
            if(!!product_id && !!quantity && quantity > 0){  
              arr[index] = {'quantity': 1, 'prod': product_id, 'required': is_requred};  
              endprice += osn_q*price;
              dopsprice += price;
            }

            //console.log(product_id, quantity, price);
          });
            
          $cart_button.attr('data-price', Math.ceil(endprice));
          $modal.find('.product-counter').attr('data-dops_price', Math.ceil(dopsprice));

          calcDopsProductCounterSumm($modal.find('.product-counter'));
          //calcCustomProductCounterSumm($modal.find('.product-counter'));  
          var json = JSON.stringify(arr);
          $cart_button.attr('data-supplements', json);
       if($( '.custom-product--modal.show .dop-added-to-cart' ).length){}
    }

    $(window).keyup(function(e){
      var target = $('.checkbox-ios input:focus');
      if (e.keyCode == 9 && $(target).length){
        $(target).parent().addClass('focused');
      }
    });
     
    $('.checkbox-ios input').focusout(function(){
      $(this).parent().removeClass('focused');
    });

    // $(document).on('click', '.ajax_add_to_cart_mod', function() {
    //     $(this).remove();
    //     $(this).next().fadeIn();
    // });

    

});


jQuery(function($) { 
    
    // Make the code work after page load.
    $(document).ready(function(){      
      QtyChng();    
    });

    // Make the code work after executing AJAX.
    $(document).ajaxComplete(function () {
      QtyChng();
    });
     
    function QtyChng() {
      $(document).off("click", ".qib-button").on( "click", ".qib-button", function() {
      // Find quantity input field corresponding to increment button clicked.
      var qty = $( this ).siblings( ".quantity" ).find( ".qty" );
      // Read value and attributes min, max, step.
      var val = parseFloat(qty.val());
      var max = parseFloat(qty.attr( "max" ));
      var min = parseFloat(qty.attr( "min" ));    
      var step = parseFloat(qty.attr( "step" ));
      
      // Change input field value if result is in min and max range.
      // If the result is above max then change to max and alert user about exceeding max stock.
      // If the field is empty, fill with min for "-" (0 possible) and step for "+".
      if ( $( this ).is( ".plus" ) ) {
        if ( val === max ) return false;           
        if( isNaN(val) ) {
          qty.val( step );      
        } else if ( val + step > max ) {
          qty.val( max );
        } else {
          qty.val( val + step );
        }     
      } else {      
        if ( val === min ) return false;
        if( isNaN(val) ) {
          qty.val( min );
        } else if ( val - step < min ) {
          qty.val( min );
        } else {
          qty.val( val - step );
        }
      }
      
      /* устанавливаем то же колличество и для допов // скрипты в файле public_html/wp-content/plugins/qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php*/
      // var $product_quantity_contener = $(this).parents('.product-quantity');
      // if($product_quantity_contener.data('is_parent') == 'true'){
      //   console.log('is_parent=true')
      //     var $prod_key = $product_quantity_contener.data('product_key');
      //     $('[data-qty_parent="'+$prod_key+'"]').find('input[type="number"]').val(9);
      // }else{
      //   console.log('is_parent', $product_quantity_contener.data('is_parent'))
      // }

      qty.val( Math.round( qty.val() * 100 ) / 100 );
      qty.trigger("change");
        $( "body" ).removeClass( "sf-input-focused" );
      });
    }
      
    jQuery(document).on( "click", ".quantity input", function() {
      return false;
    });
    
    jQuery(document).on( "change input", ".quantity .qty", function() {          
      
      var add_to_cart_button = jQuery( this ).closest( ".product" ).find( ".add_to_cart_button" );
      // For AJAX add-to-cart actions        
      add_to_cart_button.attr( "data-quantity", jQuery( this ).val() );

      // For non-AJAX add-to-cart actions
      add_to_cart_button.attr( "href", "?add-to-cart=" + add_to_cart_button.attr( "data-product_id" ) + "&quantity=" + jQuery( this ).val() );        
    });
});