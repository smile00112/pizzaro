jQuery(document).ready(function($){
	console.log('mx-admin');
	var push_log_type = 'simple';
	let url = new URL(location.href);
	console.log(url);
	if(url.search == '?page=sys-custom-settings-events'){
		push_log_type = 'event';
		var $html = '<form id="push-l" method="get" action=""><h3>История отправки</h3><div class="logs"><table class="wp-list-table widefat fixed striped table-view-list posts table-view-list-event"><thead><tr><th>Заголовок</th><th>Текст</th><th>Тип</th><th>Дата</th><th>Отправлено</th><th>Просмотрено</th><th>%</th><th>Переслать</th></tr></thead>	<tbody id="the-log-list"></tbody><tfoot>	</tfoot></table></div><div class="more_btn_contener"><button type="button" id="more_logs" class="" data-offset="0">Загрузить еще</button> </div></form>';
		$('.acf-postbox').after($html)
	}

		/* Логи */
		$(document).on('click', '#more_logs', function(event){
			load_logs($(this).data('offset'));
		});

		$(document).on('click', '[data-push_resent]', function(event){
			resent_push($(this).data('push_id'));
		});		

		function load_logs($offset) {
			var $_table = $('#the-log-list');
			var $_more_btn = $('#more_logs');
			$.ajax({
				type: 'post',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'get_push_logs',
					offset: $offset,
					type: push_log_type
				},
				cache: false,
				dataType: 'json',
				beforeSend: function( xhr ) {
					$_more_btn.text('Загрузка...');
				},
				success: function(response) {
					console.warn(response);
					$_more_btn.text('Загрузить еще')
					$_table.append(response.table);
					$_more_btn.data('offset', response.offset);
					if(response.offset >= response.data.length){
						$_more_btn.hide();
					}
				},
				error: function(e){
					$_more_btn.text('Загрузить еще');
					$_more_btn.hide();
				}

			});

		}

		function resent_push($push_id) {
			$.ajax({
				type: 'post',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'resent_push', 
					push_id: $push_id,
				},
				cache: false,
				dataType: 'html',
				success: function(response) {
					alert('Отправлено')
				}
			});

		}
		$('#more_logs').click();


	
	//обновление json товары-категории
	$('#adminmenu #collapse-menu').before('<button id="update-file-prodcat">Обновить товары</button>');  
	
	$('#update-file-prodcat').on('click', function(){ console.log('click #update-file-prodcat');
		$el = $(this);
		$.ajax({
			url: '/wp-json/systeminfo/v1/updateproductdata',
			type: "GET",
            beforeSend: function (xhr) {
              $el.text('Обновляю');
            },
            complete: function() {
				alert('Данные обновлены'); 
				$el.text('Обновить товары');
            },
			success: function(data){
				console.log('update file prodcat success');
			},
			error: function(data){
				console.log('update file prodcat error');
			}
		});
	});
	

	//обновление json кеша
	$('#adminmenu #collapse-menu').before('<button id="remove_cache_files">Обновить данные</button>');  
	
	$('#remove_cache_files').on('click', function(){ console.log('click #update-file-prodcat');
		$el = $(this);
		$.ajax({
			url: '/wp-admin/admin-ajax.php',
			type: "GET",
			data: {action : "remove_cache_files"},
            beforeSend: function (xhr) {
              $el.text('Очищаю');
            },
            complete: function() {
				alert('Данные удалены'); 
				$el.text('Обновить данные');
            },
			success: function(data){
				console.log('remove_cache_files success');
			},
			error: function(data){
				console.log('remove_cache_files error');
			}
		});
	});
	
	////блок оценка заказа в админке
	var order_rate = $('#woocommerce-order-data #order_data #rate_order').val();
	if(order_rate == 5) var class_order_rate = 'rate-green';
	if(order_rate == 4) var class_order_rate = 'rate-blue';
	if(order_rate == 3) var class_order_rate = 'rate-orange';
	if(order_rate == 2) var class_order_rate = 'rate-red';
	if(order_rate == 1) var class_order_rate = 'rate-red1';
	$('#woocommerce-order-data #order_data').append('<div id="order-rate">Оценка заказа: <span id="order-rate-num" class="'+class_order_rate+'">'+order_rate+'</span></div>');
	
	////блок Удовлетворённость пользователя в профиле 
	var user_order_rate = $('#profile-page #user-rate-order').val();
	var user_order_rate_round = Math.round($('#profile-page #user-rate-order').val());
	if(user_order_rate_round == 5) var class_user_order_rate = 'rate-green';
	if(user_order_rate_round == 4) var class_user_order_rate = 'rate-blue';
	if(user_order_rate_round == 3) var class_user_order_rate = 'rate-orange';
	if(user_order_rate_round == 2) var class_user_order_rate = 'rate-red';
	if(user_order_rate_round == 1) var class_user_order_rate = 'rate-red1';
	$('.user-edit-php #profile-page .page-title-action').after('<div id="user-order-rate">Удовлетворённость клиента: <span id="order-rate-num" class="'+class_user_order_rate+'">'+user_order_rate+'</span></div>');
	
	
	
	if($('#wp-admin-bar-my-account .display-name').html() == 'demo'){
		$('#adminmenu').addClass('demo-ac');
	}
	
	$("#acf-group_607483867aeef").appendTo("#mx_kkal_product_data");
	$("#acf-group_6139ec07a77ff").appendTo("#mx_time_product_data");
	
	$('.pushsend').datepicker();
	$('#user-extra-birth').datepicker();
	
	
	$.fn.hasAttr = function(name) {  //функция проверки наличия атрибута
		return this.attr(name) !== undefined;
	};
	
	//пуш-фильтры участие в акциях, при выборе вариантов select
	$('.form-table #user-on-promo').on('change', function(){ //console.log('change promo');
		if($(this).hasAttr('checked')) {
			$(this).removeAttr('checked');
			$(this).val('false');
		}
		else{
			$(this).attr('checked', 'checked');
			$(this).val('true');
		}
	});
	
	//////////PUSH
	//пуш-фильтры, ввод ср. чек ОТ
	$('#filter-aver-order-min').on('keydown', function(){
		//console.log('keydown ',$(this).val().length);
		$('#filter-aver-order-max').attr('placeholder', 'Обязательно');
		if($(this).val().length <= 1) $('#filter-aver-order-max').attr('placeholder', '');
	});
	
	//если мин. значение 0, а максимальное больше 0, то поставить мин 1, чтоб сработал фильтр
	$('#filter-aver-order-max').on('keydown', function(){ console.log('keydown ',$(this).val()); console.log('in min ',$('#filter-aver-order-min').val());
		if($(this).val().length > 0 && (parseInt($('#filter-aver-order-min').val()) == 0 || $('#filter-aver-order-min').val().length == 0) ) $('#filter-aver-order-min').val(1);
	});
	$('#filter-sum-order-max').on('keydown', function(){ console.log('keydown ',$(this).val()); console.log('in min ',$('#filter-sum-order-min').val());
		if($(this).val().length > 0 && (parseInt($('#filter-sum-order-min').val()) == 0 || $('#filter-sum-order-min').val().length == 0) ) $('#filter-sum-order-min').val(1);
	});
	
	//если мин. значение 0, а максимальное больше 0, то поставить мин 1, чтоб сработал фильтр
	
});