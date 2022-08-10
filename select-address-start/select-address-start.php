<?php
/*
* Template Name: Select Address Start Template
*/

 //   wp_enqueue_style('select_adr_css', get_template_directory_uri().'/select-address-start/select-address-start.css');
 //   wp_enqueue_script('select_adr_js', get_template_directory_uri().'/select-address-start/js/v3/select-address-start.js'); 

/*
Комментарии к адресу: 
- не давать положить в корзину, нужно чтобы пользователь выбрал точку самовывоза или выбрал адрес. Должно всплвать поп-ап окно как на родной доставке
- если выбирает доставку, то сначала выдается список сохранёных адресов. И ниже кнопка Указать другой адрес. По кнопке переход на карту
*/

wp_enqueue_style('select-address-startCss', get_template_directory_uri() . '/select-address-start/select-address-start.css', array(), '1.0.' . strval(rand(123, 999) ) );
wp_enqueue_script('select-address-startJs', get_template_directory_uri() . '/select-address-start/js/v3/select-address-start.js', array(), '1.0.' . strval(rand(123, 999) ) );

/*
?>
<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/select-address-start/select-address-start.css?ver=<?php echo rand(123, 999) ?>" rel="stylesheet">
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/select-address-start/js/v3/select-address-start.js?ver=<?php echo rand(123, 999) ?>" type="text/javascript"> </script>
<? 
*/
?>
    <?php 
        $user_address = get_default_address();
       // print_r($user_address);
    ?>
   <div class="select-address-start-mod modal-address-" id="select-address_form" style="display: none;" >
        <div class="select-address-start-fan__wrapper modal-address__wrapper-" >
            <!--<div class="select-address-start__close modal-address__close" >✕</div>-->
            <div class="select-address-start__map">
                <div id="map-select-first" class="map-select-first--active">
                      <!--<img id="map-preloader" src="/wp-content/themes/pizzaro/assets/images/sleep.gif">-->
 
                    <video style="width: 100%;" id="map-preloader" class="map-select-first--active" loop autoplay playsinline muted data-bgvideo="" poster="/wp-content/themes/pizzaro/assets/images/zastavka.png" data-bgvideo-fade-in="500" data-bgvideo-pause-after="120" data-bgvideo-show-pause-play="true" data-bgvideo-pause-play-x-pos="right" data-bgvideo-pause-play-y-pos="top" style="min-width: auto; min-height:auto; width: 100%; height: auto; position: absolute; left: 17%; top: 50%; transform: translate(-50%, -50%); transition-duration: 500ms; z-index: 999;">
							<source src="/wp-content/themes/pizzaro/assets/images/sleep.webm" type="video/webm">
						</video>

                </div>
            </div>
            <div class="select-address-start__content">
                <span class="select-address-start__triangle select-address-start__triangle--top"></span>
                <span class="select-address-start__triangle select-address-start__triangle--bottom"></span>
                <p class="select-address-start__title">Введите адрес доставки</p>
                <label class="select-address-start-input" for>
                    <span class="select-address-start-input__label" >Адрес</span>
                    <span class="select-address-in_zone"></span>
                    <input class="select-address-start-input__input"  id="address-map" name="address" placeholder="Введите адрес" autocomplete="none" onfocus="this.setAttribute('autocomplete', 'none');" value="<?=$user_address['short_address'];?>"/>
                    <span class="select-address-location">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 11C19 15.4183 15.4183 19 11 19M19 11C19 6.58172 15.4183 3 11 3M19 11H22M11 19C6.58172 19 3 15.4183 3 11M11 19V22M3 11C3 6.58172 6.58172 3 11 3M3 11H0M11 3V0M15 11V11C15 13.2091 13.2091 15 11 15V15C8.79086 15 7 13.2091 7 11V11C7 8.79086 8.79086 7 11 7V7C13.2091 7 15 8.79086 15 11Z" stroke="#FF6501" stroke-width="2"/>
                        </svg>
                    </span>
                </label>
                <label class="select-address-start-input" for="apartment">
                    <span class="select-address-start-input__label" >Квартира</span>
                    <input class="select-address-start-input__input" type="number" id="apartment" name="apartment" placeholder="Введите № квартиры" value="<?=$user_address['apartment'];?>"/>
                </label>
                <div class="select-address-start__footer">
                    <button class="button select-address-start__button select-address-start__button--white" data-address_cansel>Пропустить</button>
                    <!-- <button class="button select-address-start__button select-address-start__button--white" onclick="modal('set-first-address').close()">Пропустить</button> -->
                    <button class="button select-address-start__button select-address-start__button--orange" data-address_confirm>Подтвердить</button>
                </div>
            </div>
        </div>

   </div>



   <div class="select-address user-addresses select-address-start-mod modal-address-" id="select-delivery-type" style="display: none;" >
        <div class="fan__wrapper_type-info modal-address__wrapper-" >
            <p>Выберите способ доставки</p>
            <ul class="modal-user-addresses">
                <li><button type="button" class="add-address-btn" onclick="close_modal();show_modal('select-user-address_form');">Доставка курьером</button></li>
                <li><button type="button" class="add-address-btn" onclick="close_modal();show_modal('select-pickup-point');">Самовывоз</button></li>
            </ul>
        </div>
    </div>

   <div class="select-address user-addresses select-address-start-mod modal-address-" id="select-user-address_form" style="display: none;" >
        <div class="fan__wrapper_type-info modal-address__wrapper-" >
            <p>Ваши сохранённые адреса</p><br>
            <ul class="modal-user-addresses">
            <?	
                /* Получаем адреса */
                $addresses = get_address_data();
                foreach( $addresses as $index=>$address){
                    if(!$address['short_address']) continue;

                    echo '<li class="u-address"><button type="button" data-address_select="'.$index.'">'.$address['short_address'].'</button></li>';
                };

                
            ?>
                <li><button type="button" class="add-address-btn" onclick="close_modal();localStorage.address_mode='new';show_address_map();">Добавить адрес</button></li>
            </ul>
        </div>
    </div>
<!--
<button type="button" onclick="show_address_map()" data-fancy_modal2="select-address_form">КАРТА</button>
<button type="button" onclick="show_modal('select-user-address_form');" data-fancy_modal2="select-address_form">Адреса</button>
-->
