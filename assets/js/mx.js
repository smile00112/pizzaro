$(document).ready(function () {
	//console.log('mx');

	////////////попап
	
	//если попап одноразовый, после закрытия создать куки, что был показан
    $('.pop-button-mx.pop-button-mx-close').on('click', function(e){ ////console.log(e, ' click close popup');
        $(this).closest('.popup-mx').removeClass('show');
		
		if(!$(this).closest('.popup-mx').hasClass('mode-repeatable')) 
			set_popup_cookie($(this).closest('.popup-mx').attr('data-id'));	
    });
	
	let sklad_cur = localStorage.getItem('storageId'); //console.log(sklad_cur);
	
	$('.popup-mx').each(function(){
		let sklad_ar = $(this).attr('data-sklad'); //console.log(sklad_ar);
		let pop_id = $(this).attr('data-id');
		if (sklad_ar.indexOf(sklad_cur) >= 0){ //console.log('popup to sklad'); //если попап относится к складу, который выбрал пользователь(актуальность попапа)
		
			//если одноразовый и нет куки, что был показан, то показываем
			if(Cookies.get('popup-mx-id-'+pop_id) === undefined && !$(this).hasClass('mode-repeatable')){ //если один раз показать и нет куки
				$(this).removeClass('show');
				$(this).addClass('show');
			}
			if($(this).hasClass('mode-repeatable')){ //если повторямый, то показываем
				$(this).removeClass('show');
				$(this).addClass('show');
			}
			
			$('.add_to_cart_button, .checkout-button').addClass('disabled');
		}
	});
	
	////////////попап




	////блок товара в моб версии. уменьшить отступ если нет описания
	$('.products .product-outer').each(function () {
		if ($(this).find('.woocommerce-product-details__short-description').length == 0) {
			$(this).addClass('no-descr');
		}
	});

	////бонусная система прогресс-бар, заполнение цветом
	var bon_perc_progress = $('.bon-progress').attr('data-perc');
	//console.log(bon_perc_progress);
	if (bon_perc_progress == 100) $('#bonus-syst-block .bon-bar').addClass('full-lev');
	bon_perc_progress = bon_perc_progress + '%';

	$('#bonus-syst-block .bon-bar').css({
		'width': bon_perc_progress
	});

	////бонусная система, модальное окно с инф.
	$('#bonus-syst-block .info-bottom .bon-but-modal').on('click', function () {
		$('#bon-info-modal').toggle();
		$('#bonus-syst-block .info-bottom .bon-but-modal').toggleClass('active');
	});

	$('#bon-info-modal .close').on('click', function () {
		$('#bon-info-modal').toggle();
		$('#bonus-syst-block .info-bottom .bon-but-modal').toggleClass('active');
	});

	////появление корзины в моб. версии при первом добавлении товара
	$(".pizzaro-handheld-footer-bar").on("DOMSubtreeModified", function () {
		//console.log(parseInt($('.footer-cart_count').html()));
		let count_cart = parseInt($('.footer-cart_count').html());
		if (!$('.pizzaro-handheld-footer-bar').hasClass('active_cart') && count_cart > 0) {
			//console.log('first add prod');
			$('.pizzaro-handheld-footer-bar').addClass('active_cart');
		}
	});

	/////если тип товара Групповой(кастомный), делаем обёртку для стилей
	if ($('form.single-type-supplements').length > 0) {
		$('form.single-type-supplements').wrap('<div class="product-form-wrapper"></div>'); //;
		$('.product-form-wrapper').insertAfter(".single-product-wrapper");
	}


	//////оформление заказа, отображение/структура
	//изменение структуры вывода полей в оформлении заказа
	$('#my_custom_checkout_field').after($('.woocommerce-additional-fields__field-wrapper')); //комментарий
	$('#my_custom_checkout_field').after($('#billing_gatetimecheckout_field')); //время доставки
	$('#my_custom_checkout_field').after($('#billing_company_field')); //этаж
	$('#my_custom_checkout_field').after($('#billing_address_2_field')); //квартира
	$('<div id="wrap-clone-deliv"><div class="mx-loader"></div></div>').insertBefore('.adr-block');

	//если обновился блок доставки, скрыть loader
	$("#wrap-clone-deliv").bind("DOMSubtreeModified", function () { ////console.log('change clone deliv');
		if ($('.woocommerce-shipping-totals').length > 0) {
			$('.mx-loader').hide();
		}
	});
	//обновления строки адреса
	$("#delivery-address").bind("DOMSubtreeModified", function () { ////console.log('change addr str: ', $("#delivery-address").html(), ' - ',$("#billing_address_1").val());
		if ($("#delivery-address").html() == 'undefined') $("#delivery-address").html('');
		if ($("#billing_address_1").val() == 'undefined') $("#billing_address_1").val('');
		button_addr_change_name();
	});
	if ($("#billing_address_1").val() == 'undefined') $("#billing_address_1").val('');

	//блок адреса, если доставка(при загрузке)
	if ($('#shipping_method_0_local_pickup4').attr('checked') == 'checked') {
		//console.log('iss samovivoz');
		$('#my_custom_checkout_field #delivery-address').text('Самовывоз');
		$('#billing_address_1').val('Самовывоз');
		$('.adr-block').hide();
	} else {
		//$('#my_custom_checkout_field #delivery-address').text('');
		//$('#billing_address_1').val('');
		var address_cookie = getCookie('address_sel');
		//console.log(address_cookie);
		if (address_cookie != '') $('#billing_address_1').val(address_cookie);
		$('#my_custom_checkout_field #delivery-address').text(address_cookie);
	}

	button_addr_change_name();

	//блок адреса, если доставка(при выборе)
	$(document.body).on('change', 'input[name="shipping_method[0]"]', function () {
		$('.mx-loader').show(); ////console.log('on change');
		var value = $(this).val();
		if (value == 'local_pickup:4') {
			$('#my_custom_checkout_field #delivery-address').text('Самовывоз'); //если самовывоз, то заглушка для обязательного поля адрес
			$('#billing_address_1').val('Самовывоз');
			$('.adr-block').hide();
		} else {
			//console.log($('.baur_delivery-city').html());
			//$('#my_custom_checkout_field #delivery-address').text($('.baur_delivery-city').html()); 
			//$('#billing_address_1').val($('.baur_delivery-city').html());
			$('.adr-block').show();
			var address_cookie = getCookie('address_sel');
			//console.log(address_cookie);
			if (address_cookie != '') $('#billing_address_1').val(address_cookie);
			$('#my_custom_checkout_field #delivery-address').text(address_cookie);
		}
		button_addr_change_name();
	});

	//при обновлении правого блока, обновляем блок доставки слева
	$(document.body).on('change', '#order_review', function () { ////console.log('change right: ',$('#order_review .woocommerce-shipping-totals').html());
		button_addr_change_name();
		if ($('#order_review .woocommerce-shipping-totals').html() != '') {
			$('#wrap-clone-deliv .woocommerce-shipping-totals').remove();
			$('#order_review .woocommerce-shipping-totals').clone(true).appendTo('#wrap-clone-deliv');
			$('#order_review .woocommerce-shipping-totals').empty();
		}
	});



	//////////////////



	if ($('.popup-mx .pop-button').attr('href') == '#') {
		$('.popup-mx .pop-button').click(function (event) {
			////console.log('click');
			$('.popmake-close').click();
			if (Cookies.get("pum-28295") == undefined) {
				//Cookies.set("pum-28295", "true");
			}
		});
	}




	///////////авторизация через номер тел. попап
	$('.phone-number.time-work').on('click', function () {
		//console.log('click timework');
		$('#auth-sms-wrap').show();
		$('#fade-auth').show();
		$('[name="auth-phone"]').focus();
	});

	$('#fade-auth, .close-auth, .pass-auth').on('click', function () {
		//console.log('click close');
		$('#auth-sms-wrap').hide();
		$('#fade-auth').hide();
	});

	if ($('#login-button').length > 0) {
		$('.userhead').on('click', function () {
			//console.log('click login');
			$('#auth-sms-wrap').show();
			$('#fade-auth').show();
			$('[name="auth-phone"]').focus();
		});
		$('.userhead').addClass('not-login');
		$('#auth-sms-wrap').show();
		$('#fade-auth').show();
		$('[name="auth-phone"]').focus();
	} else {

	}

	var cur_url_domain = 'https://' + window.location.hostname;


	$('#auth-phone').mask("(999) 999-99-99"); //<div id="login-button"></div>
	$('#auth-sms').on('submit', function (e) {
		e.preventDefault();
		$('#auth-sms .send-sms').click();
	})
	//запрос пользователя на получение смс кода для авторизации
	$('#auth-sms .send-sms').on('click', function () {
		//console.log('click send');
		//$('#satus-send-push').removeClass('show');
		//var check_valid = valid_message_push();
		$('#auth-sms-wrap .loader-auth').show();
		//console.log($('#auth-sms-wrap #auth-phone').val().length);
		let auth_phone = $('#auth-sms-wrap #auth-phone').val();
		if ($('#auth-sms-wrap #auth-phone').val().length == 15) {
			
			var data = $('#auth-sms').serialize();
			$cur_url_domain = 'https://' + window.location.hostname;
			//console.log(data);
			$.ajax({
				type: 'get',
				url: $cur_url_domain + '/wp-json/authsms/v1/sendsms',
				data: data,
				cache: false,
				success: function (response) {
					//console.log('send_sms: ', response);

					//$('#push-count-reciver').text(response);//количество получателей
					////console.log('success');
					//success_send_push();
				}
			});
			/**/
			form_input_sms_code();
			$($('#auth-sms-wrap .error-input-code').text(''));
			$('#auth-sms-wrap .loader-auth').hide();
		} else {
			$('#auth-sms-wrap .loader-auth').hide();
			$($('#auth-sms-wrap .error-input-code').text(''));
			$('#auth-sms-wrap .error-input-code').text('Проверьте введённый номер телефона');
		}
	});

	//отправка кода из смс для авторизации
	$('#order_comments_field').on('click', function () {
		//console.log('ccchange');
		//console.log('cookie: ' + getCookie('zone_deliv'));

		$cur_url_domain = 'https://' + window.location.hostname;
		$.ajax({
			type: 'get',
			url: $cur_url_domain + '/wp-json/delivery/v1/changecost',
			//data : data,
			cache: false,
			success: function (response) {
				//console.log('success');
			}
		});


		updateCheckoutFromMap();
	});

	$('#user-date-birth').mask("99.99.9999");

	if ($('.single-product-wrapper .label-hit').length == 0) $('.single-product-wrapper .label-preorder').addClass('no-label-near-hit');
	if ($('.single-product-wrapper .label-spicy').length == 0) $('.single-product-wrapper .label-vegan').addClass('no-label-near-spicy');
	$('.product-inner .label-preorder').each(function () {
		if ($(this).siblings('.label-hit').length == 0) $(this).addClass('no-label-near-hit');
	});
	$('.product-inner .label-vegan').each(function () {
		if ($(this).siblings('.label-spicy').length == 0) $(this).addClass('no-label-near-spicy');
	});

});


function set_popup_cookie(popup_id_mx) {
	var date = new Date();
	//console.log(date);
	$pop_cookie = 'popup-mx-id-' + popup_id_mx;
	Cookies.set($pop_cookie, popup_id_mx);
	document.cookie = $pop_cookie + "=" + popup_id_mx + "; max-age=3600;path=/;" //max-age кол-во секунд

}

$(document).on('keyup', '#sms-p-1', function(e){ 
	//console.warn($(this).val().length); 
	////console.warn(e.keyCode);
	if($(this).val().length == 4){
		$('.send-sms-code').click();
	}
	if(e.keyCode == 13){
		$('.send-sms-code').click();
	}
});

$(document).on('click', '.bonus-history-list-header ', function(e){ 
	var $parent = $(this).parent();
	if($parent.hasClass('history_close')){
		$parent.removeClass('history_close');
		$parent.addClass('history_open');
	}else{
		$parent.removeClass('history_open');
		$parent.addClass('history_close');
	}
	if(e.keyCode == 13){
		$('.send-sms-code').click();
	}
});

//новое содержимое формы: ввод код из смс
function form_input_sms_code() {
	var user_phone_input = $('#auth-phone').val();
	var sms_code_form = '<form id="input-sms-code" action="">' +
		'<div class="text-head">Введите код из СМС сообщения</div>' +
		'<input type="number" id="sms-p-1" class="sms-p" value=""><!--<input type="number" id="sms-p-2" class="sms-p" value=""><input type="number" id="sms-p-3" class="sms-p" value=""><input type="number" id="sms-p-4" class="sms-p" value="">-->' +
		'<input type="hidden" id="auth-sms-code" name="auth-sms-code" value=""><input type="hidden" name="phone" value="' + user_phone_input + '">' +
		'<button type="button" class="send-sms-code">Отправить код</button>' +
		'</form>' +
		'<div class="sms-info-block"><div class="sms-text-info hh">Не приходит сообщение?</div><div class="pass-auth">Войти как гость</div>' +
		'<div class="change-number">Изменить номер</div></div>';
	$('#auth-sms-wrap .wrap').html(sms_code_form);

	$('.pass-auth').on('click', function () {
		$('#auth-sms-wrap').hide();
		$('#fade-auth').hide();
	});

	processing_input_code(); //обработка полей ввода

	$('#auth-sms-wrap .change-number').on('click', function () {
		document.location.reload();
	}); //для изменения номер перезагрузка страницы по клику



	$(document).on('click', '#input-sms-code .send-sms-code', function () {
		console.log('click auth by sms');
		//$('#satus-send-push').removeClass('show');
		//var check_valid = valid_message_push();

		//$cur_url_domain = 'https://' + window.location.hostname;
		//let loader_auth = $cur_url_domain + '/wp-content/themes/pizzaro/assets/images/ajax-loader.gif';
		//$('#auth-sms .send-sms').append('<img src="'+loader_auth+'">');

		$('#auth-sms-wrap .loader-auth').show();

		let all_parts_code = $('#sms-p-1').val();// + $('#sms-p-2').val() + $('#sms-p-3').val() + $('#sms-p-4').val();
		$('#auth-sms-code').val(all_parts_code);
		//console.log($('#auth-sms-code').val().length);

		//let check_valid = check_code_valid(); //console.log();
		//alert($('#auth-sms-code').val().length);
		
		if ($('#auth-sms-code').val().length == 4) {
			var data = $('#input-sms-code').serialize();

			//console.log(data);
			$.ajax({
				type: 'get',
				url: '/wp-json/authsms/v1/authbysms',
				data: data,
				cache: false,
				success: function (response) {
					//console.log('get auth: ', response);
					$('#auth-sms-wrap .error-input-code').text('');
					if (response == 'loginsuccess') {
						document.location.reload();
					} else if (response == 'unvalidsmscode') {
						$('#auth-sms-wrap .loader-auth').hide();
						$('#auth-sms-wrap .error-input-code').text('Неверный код');
					} else if (response == 'usernotfound') {
						$('#auth-sms-wrap .loader-auth').hide();
						$('#auth-sms-wrap .error-input-code').text('Пользователь не найден');
					}
					//form_input_sms_code();
					//$('#push-count-reciver').text(response);//количество получателей
					////console.log('success');
					//success_send_push();
				},
				error: function (jqXHR, exception) {
					console.error('sms code check error', jqXHR, exception);
				}
			});
		} else {
			$('#auth-sms-wrap .loader-auth').hide();
			$($('#auth-sms-wrap .error-input-code').text(''));
			$('#auth-sms-wrap .error-input-code').text('Проверьте введённый код');
		}
	});
}

function processing_input_code() {
	//$('.sms-p').mask("9"); //маска номера телефона

	$('.sms-p').on('focus click', function () { //установка курсора вначало строки
	//	if ($(this).val().length == 0)
	//		$(this)[0].setSelectionRange(0, 0);
	});

	/////стилизация полей смс-кода
	// $('.sms-p').on('keydown', function () { //текущее поле, если не пустое, помечаем красным, как текущее
	// 	if ($(this).val().length > 0) {
	// 		//$(this).addClass('cur');

	// 	}

	// 	var cur_inp_id = $(this).attr('id');

	// 	$('.sms-p').each(function (i, n) { //проверяем каждое поле
	// 		if ($(n).attr('id') != cur_inp_id) { //если не текущее
	// 			if ($(n).val().length > 0) { //если не пустое, делаем зелёное
	// 				//$(n).addClass('ready');
	// 				//$(n).removeClass('cur');
	// 			}
	// 		}
	// 		if ($(n).val().length > 0 && i == 3) { //если последнее, делаем зелёное
	// 			//$(n).addClass('ready');
	// 			//$(n).removeClass('cur');
	// 		}
	// 	});


	// });



	$('.sms-p').on('keyup', function () { //если удалено значение, меняем стиль поля(серый)
		// if ($(this).val() == '_') {
		// 	//console.log('val length 0');
		// 	$(this).removeClass('ready');
		// 	$(this).removeClass('cur');
		// }
		// var key = event.keyCode || event.charCode;
		// if (key != 8 && key != 46) {
		// 	$(this).addClass('ready');
		// 	$(this).next('.sms-p').focus();
		// 	$(this).next('.sms-p').addClass('cur');
		// }

	});
	////////
}


function check_code_valid() {
	$('.sms-p').each(function () {
		//console.log('each val "', $(this).val().charCodeAt(0), '"');
		if ($(this).val().length == 0 || $(this).val() == '_' || $(this).val() == '' || $(this).val() == ' ' || $(this).val().charCodeAt(0) == NaN) return 'Ошибка ввода кода';
	});
	return 'ok';
}



function updateCheckoutFromMap() { ////console.log('update checkout');
	jQuery(document.body).trigger("wc_update_cart");
	jQuery(document.body).trigger("update_checkout");
}

function getCookie(name) {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	let cookie_val = parts.pop().split(';').shift();
	if (parts.length === 2) { ////console.log(name,' ',cookie_val);
		return cookie_val;
	}
}


//название кнопки карты Изменить адрес/Выбор адрес
function button_addr_change_name() {
	if (jQuery('#billing_address_1').val() == '') { ////console.log('empty addr');
		jQuery('.baur_new_map_on_check').html('Выбор адреса');
	} else { ////console.log('not empty addr');
		jQuery('.baur_new_map_on_check').html('Изменить адрес');
	}
}

//получение куки по названию
function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	))
	return matches ? decodeURIComponent(matches[1]) : undefined
}