<?php
/*
* Template Name: Select Address Template
*/
?>


    <script>
        function updateCheckoutFromMap() {
            jQuery(document.body).trigger("wc_update_cart");
            jQuery(document.body).trigger("update_checkout");
        }
    </script>
 
  <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/select-address/select-address.js?t=<?=date("s");?>" type="text/javascript"> </script>
  <link href="<?php echo esc_url( get_template_directory_uri() ); ?>/select-address/select-address.css?t=<?=date("s");?>" rel="stylesheet">
    <div id="my_custom_checkout_field" class="select-address">
        <h3 class="select-address__title">Адрес доставки заказа</h3>
        <div class="select-address__content">
            <div class="select-address__info">
                <p><strong>Ваш адрес доставки:</strong><br> <span id="delivery-address">Не указан</span></p>
            </div>
            <div class="select-address__error">
                <p><strong>Ваш адрес не входит в зону доставки.</strong><br> К сожалению, мы не сможем доставить вам заказ.</p>
            </div>
        </div>
        <button type="button" onclick="show_modal('select-user-address_form');" class="button alt">Выбрать адрес</button>
        <!--
                 <div class="select-address-modal">
                    <div class="select-address-modal__overlay" onclick="toggleSelectAddressModal(true)"></div>
                    <div class="select-address-modal__wrapper">
                         <button type="button" class="select-address-modal__close" onclick="toggleSelectAddressModal(true)")>✕
                         </button>
                         <div class="select-address-modal__map">
                             <div id="map"></div>
                              <div class="select-address-modal__actions">
                                <button class="select-address-modal__accept button" type="button" onclick="submitAddress()">
                                    Подтвердить адрес
                                </button>
                              </div>
                                <div class="select-address-modal__error">
                                    <p><strong>Ваш адрес не входит в зону доставки.</strong><br>К сожалению, мы не сможем доставить вам заказ. Укажите другой адрес.</p>
                                     <button class="select-address-modal__edit button" type="button" onclick="clearAddress()">
                                           Изменить адрес
                                     </button>
                                </div>
                         </div>
                    </div>

                 </div>
        -->
     </div>
<div id="billing_homeaddress"> </div>
	 
<?php
?>
