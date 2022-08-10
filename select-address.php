<?php
/*
* Template Name: Select Address Template
*/
?>

<script src="https://api-maps.yandex.ru/2.1/?apikey=116a19cd-2760-485d-80a0-cc36a11caa2d&lang=ru_RU&coordorder=longlat" type="text/javascript"> </script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/select-address/select-address.js" type="text/javascript"> </script>
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/select-address/select-address.css" rel="stylesheet">
<div id="my_custom_checkout_field">
    <h3>Адрес доставки заказа</h3>
    <div class="select-address__content">
        <div class="select-address__info">
            <p><strong>Ваш адрес доставки:</strong><br> <span id="delivery-address">Не указан</span></p>
        </div>
        <div class="select-address__error">
            <p><strong>Ваш адрес не входит в зону доставки.</strong><br> К сожалению, мы не сможем доставить вам заказ.</p>
        </div>
        <input type="hidden" id="shipping_deliv_time" name="_shipping_deliv_time" value />
    </div>
    <button type="button" onclick="toggleSelectAddressModal()" class="button alt">Выбрать адрес</button>

    <div class="select-address-modal">
        <div class="select-address-modal__overlay" onclick="toggleSelectAddressModal(true)"></div>
        <div class="select-address-modal__wrapper">
            <button type="button" class="button select-address-modal__close" onclick="toggleSelectAddressModal(true)" )>✕
            </button>
            <div class="select-address-modal__map">
                <div id="map"></div>
            </div>
        </div>

    </div>
</div>


<?php
?>