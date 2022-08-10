<?php
if (!function_exists('isMobile')) {
	function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}
if (!function_exists('get_all_stock_data')) {
	////////получение всех складов
	function get_all_stock_data(){
		$stock_terms = get_terms( [ 'taxonomy' => 'location', 'hide_empty' => true, ] );
		$stock_arr_data = [];
		foreach($stock_terms as $s){
			$stock_arr_data[]=[
				'id' => $s->term_id,
				'name' => $s->name,
			];
		}
		
		return $stock_arr_data ;
	}
}
if (!function_exists('save_stock')) {
	/* Сохранение склада */
	add_action('rest_api_init', function () {
		register_rest_route('system/v1', '/save_stock', array( //регистрация маршрута
			'methods'	=> 'POST',
			'callback'	=> 'save_stock'
		));
	});
	function save_stock(WP_REST_Request $request) {

		$params = $request->get_params();
		print_r($params);
		if($params['action']!='edit_address') return false;

		if(!empty($params['stock'])){
			session_start();
			$_SESSION['user_address'] = 111;
			$StockId = intval($params['stock']);
			setcookie( 'StockId', $StockId, time() + 60 * 60 * 24 * 30 * 12 , '/', '' ); // ставим куку
		}
	return false;


	}
}



function show_header_shippings(){
	$user_address = get_default_address();
	?>
<div class="m_baur_search-form-m">
	<div class="m_baur_search-form-shipping-select">
		<div class="m_baur_search-form--btn m_baur_search-form-deliv-btn active" data-sf_btn="delivery">Доставка</div>
		<div class="m_baur_search-form--btn m_baur_search-form-pickup-btn" data-sf_btn="pickup" >Самовывоз</div>
	</div>
	<div class="m_baur_search-form-shipping-data m_baur_search-form-shipping-data-delivery" data-sf_contener="delivery">
		<span class="m_baur_search-form-text m_baur_search-form-text-delivery">
			<span class="m_baur_search-title">Ваш адрес</span>
			<span class="m_add_address"><?if(empty($user_address['short_address'])){?>Введите адрес доставки<?}else{echo $user_address['short_address'];}?></span>
		</span>
		<span class="show_map">
			<svg width="20" height="20" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10 1L10.7071 0.292893C10.3166 -0.0976311 9.68342 -0.0976311 9.29289 0.292893L10 1ZM2 9L1.29289 8.29289C1.10536 8.48043 1 8.73478 1 9H2ZM2 12H1C1 12.5523 1.44772 13 2 13V12ZM5 12V13C5.26522 13 5.51957 12.8946 5.70711 12.7071L5 12ZM13 4L13.7071 4.70711C14.0976 4.31658 14.0976 3.68342 13.7071 3.29289L13 4ZM16 16C16.5523 16 17 15.5523 17 15C17 14.4477 16.5523 14 16 14V16ZM1 14C0.447715 14 0 14.4477 0 15C0 15.5523 0.447715 16 1 16V14ZM9.29289 0.292893L1.29289 8.29289L2.70711 9.70711L10.7071 1.70711L9.29289 0.292893ZM1 9V12H3V9H1ZM2 13H5V11H2V13ZM5.70711 12.7071L13.7071 4.70711L12.2929 3.29289L4.29289 11.2929L5.70711 12.7071ZM13.7071 3.29289L10.7071 0.292893L9.29289 1.70711L12.2929 4.70711L13.7071 3.29289ZM16 14H1V16H16V14Z" fill="<? if(isMobile()) echo '#686868'; else echo '#EC681C';?>"/>
			</svg>
		</span>
	</div>
	<div class="m_baur_search-form-shipping-data m_baur_search-form-shipping-data-pickup" data-sf_contener="pickup" style="display:none">
		<span class="m_baur_search-form-text">
			<span class="m_add_address m_baur_search-form-text-pickup" data-pickup_point>Введите точку самовывоза</span>
		</span>
		<span class="show_pickups">
			<svg width="20" height="20" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10 1L10.7071 0.292893C10.3166 -0.0976311 9.68342 -0.0976311 9.29289 0.292893L10 1ZM2 9L1.29289 8.29289C1.10536 8.48043 1 8.73478 1 9H2ZM2 12H1C1 12.5523 1.44772 13 2 13V12ZM5 12V13C5.26522 13 5.51957 12.8946 5.70711 12.7071L5 12ZM13 4L13.7071 4.70711C14.0976 4.31658 14.0976 3.68342 13.7071 3.29289L13 4ZM16 16C16.5523 16 17 15.5523 17 15C17 14.4477 16.5523 14 16 14V16ZM1 14C0.447715 14 0 14.4477 0 15C0 15.5523 0.447715 16 1 16V14ZM9.29289 0.292893L1.29289 8.29289L2.70711 9.70711L10.7071 1.70711L9.29289 0.292893ZM1 9V12H3V9H1ZM2 13H5V11H2V13ZM5.70711 12.7071L13.7071 4.70711L12.2929 3.29289L4.29289 11.2929L5.70711 12.7071ZM13.7071 3.29289L10.7071 0.292893L9.29289 1.70711L12.2929 4.70711L13.7071 3.29289ZM16 14H1V16H16V14Z" fill="<? if(isMobile()) echo '#686868'; else echo '#EC681C';?>"/>
			</svg>
		</span>
	</div>			
</div>

<div class="modal-form" id="set-pickup-point" style="display: none;">
  <div class="modal-form__overlay" ></div>

	<div class="modal-form__wrapper">
		<div class="modal-form__close" >✕</div>

		<div class="modal-form__content">
			<span class="modal-form__triangle modal-form__triangle--top"></span>
			<div class=" modal-form__triangle--center">
			
				<div class="baur_modal-fix_container pickup-points-modal">
					<p>Выберите ближайшую точку<br>самовывоза.</p><p>
					<?
						$stocks = get_all_stock_data();
						foreach($stocks as $stock){

					?><div class="in_address" data-st="<?=$stock['id'];?>"><?=$stock['name'];?></div><? 
						}
					?>
					

					<!--<div class="resume">Продолжить</div>-->
				</div>			
			
			</div>
			<span class="modal-form__triangle modal-form__triangle--bottom"></span>
			<div class="modal-form__footer">
				 <!-- <button class="button select-address-start__button select-address-start__button--white" >Пропустить</button>
			 
				<button class="button select-address-start__button select-address-start__button--orange" >Подтвердить</button>-->
			</div>
		</div>
	</div>
</div>

<div class="select-address user-addresses select-address-start-mod modal-address-" id="select-pickup-point" style="display: none;" >
        <div class="select-address-start-fan__wrapper modal-address__wrapper-" >
			<div class="baur_modal-fix_container pickup-points-modal">
				<p>Выберите ближайшую точку<br>самовывоза.</p><p>
				<?
					$stocks = get_all_stock_data();
					foreach($stocks as $stock){

				?><div class="in_address" data-st="<?=$stock['id'];?>"><?=$stock['name'];?></div><? 
					}
				?>
				

				<!--<div class="resume">Продолжить</div>-->
			</div>	
		</div>

</div>
	
	<?
}

add_action('wp_footer', 'shippings_scripts', 99); 
function shippings_scripts() {
	?>
	<style>
		.stuck .m_baur_search-form-m{
			display: none!important;
		}
		@media ( min-width: 768px ){/* Десктоп */
			.m_baur_search-form-m {
				display: inline-flex!important;
				grid-area: search;
				border-radius: 8px;
				justify-content: space-between;
				align-items: center;
				cursor: pointer;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				align-content: space-around;
				align-items: center;
				color: #EF762C;
				width: 24vw;
				max-width: 350px;
			}
			.m_baur_search-form-shipping-select {
				display: flex;
				flex-direction: row;
				justify-content: center;
				align-items: center;
				width: 100%;
				flex: 0 0 50%;
				background-color: #fff;
			}
			.m_baur_search-form-shipping-select .m_baur_search-form--btn {
				flex: 0 0 44%;
				height: 80%;
				color: #000;
				background-color: #fff;
				text-align: center;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.m_baur_search-form--btn.active {
				color: #EF762C;
				border-bottom: 2px solid red;
				background-color: antiquewhite;
			}
			.m_baur_search-form-shipping-data {
				min-height: 20px;
				display: flex;
				align-items: center;
				width: 100%;
				justify-content: space-evenly;
				flex-wrap: nowrap;
				align-content: center;
				padding: 5px 0;
				
			}
			.m_baur_search-title{
				display: none;
				color: #000;
				float: left;
				text-align: left;
				letter-spacing: normal;
				font-size: unset;
				line-height: inherit;
				font-size: 12px;
				margin-bottom: -5 px;
			}
			
		}
			
		@media ( max-width: 768px ){	
			.m_baur_search-form-m{
				grid-area: search;
				background: #1C1C1C;
				border-radius: 8px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 11px 20px;
				margin: 20px;
				cursor: pointer;
			
				
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				align-content: space-around;
				align-items: center;
			}
			.m_baur_search-form-shipping-select{
				display: flex;
				flex-direction: row;
				justify-content: center;
				align-items: center;
				width: 100%;
				flex: 0 0 50%;
					background-color: #000;
			}
			.m_baur_search-form-shipping-select .m_baur_search-form--btn{
			flex: 0 0 49%;
			height: 80%;
			background-color: #1C1C1C;
			text-align: center;
			display: flex;
			align-items: center;
			justify-content: center;
			
			}
			.m_baur_search-form--btn.active{
				background-color: #000;
				color: #fff;
			}
			.m_baur_search-form-shipping-data{
				min-height: 50px;
				display: flex;
				align-items: center;
				width: 100%;
				justify-content: space-evenly;
				flex-wrap: nowrap;
				align-content: center;
				padding: 10px 1px;
			}
			.m_baur_search-form-shipping-data .m_add_address{
				font-size: 14px;
			}
			.show_map, .show_pickups{
				padding: 10px;
			}

		}
		.m_baur_search-form-m .show_pickups svg{
			vertical-align: middle;
		}

		.modal-form {
			display: none;
			position: fixed;
			z-index: 10000;
			height: 100%;
			width: 100%;
			left: 0;
			top: 0;
		}
		.modal-form__overlay{
			position: fixed;
			z-index: 1;
			left: 0;
			top: 0;
			height: 100%;
			width: 100%;
			background: rgba(0,0,0,0.2);
		}
		.modal-form__wrapper {
			z-index: 2;
			min-height: 300px;
			width: 100%;
			max-width: 400px;
			background: white;
			overflow: hidden;
			border-radius: 16px;
			position: fixed;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
		}

		.modal-form__close {
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			z-index: 3;
			position: absolute;
			right: 20px;
			top: 20px;
			color: black;
			background: #FFFFFF;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
			border-radius: 50%;
			height: 38px;
			width: 38px;
		}
		.modal-form__triangle--center{
				height: 100%;
			width: 100%;
			text-align: center;
		}
		.modal-form .baur_modal-fix_container .in_address{
			width: 100%;
		}
	</style>

	<script>
		$(function(){
			$('.m_baur_search-form--btn').click(function(){
				$('.m_baur_search-form--btn').removeClass('active');
				$(this).addClass('active');
				$('.m_baur_search-form-shipping-data');
				var type = $(this).data('sf_btn');
				if(type == 'delivery'){
					$('.m_baur_search-form-shipping-data-delivery').show();
					$('.m_baur_search-form-shipping-data-pickup').hide();
					localStorage.shipping_method = 'delivery';
				}else{
					$('.m_baur_search-form-shipping-data-delivery').hide();
					$('.m_baur_search-form-shipping-data-pickup').show();
					localStorage.shipping_method = 'pickup';
				}
				
			});
			
			$(document).on('click', '.baur_modal-fix_container.pickup-points-modal .in_address', function(e){
				var adress = $(this).text();
				if($(this).data('st'))
				$.ajax({
					type : 'POST',
					url : '/wp-json/system/v1/save_stock',
					async: true,
					data : 'stock='+$(this).data('st')+'&action=edit_address',
					dataType: 'html',
					success: function (data) {
						localStorage.shipping_method = 'pickup';
						localStorage.pickupAddress =`${adress}`;
						localStorage.adsressStreet = `${adress}`;
						//document.querySelector('.baur_modal-fix_onclick-null.pickup-points-modal').classList.remove('active')
						$('[data-pickup_point]').text( localStorage.pickupAddress );
						CloseForm();
						
						document.location.reload();
					},
				});
			});
			 
			$('.show_pickups, m_baur_search-form-text-pickup').click(function(){
				show_modal_form('set-pickup-point');
			});
			
			$('.show_map, .m_baur_search-form-text-delivery').click(function(){
				if (window.innerWidth < 468) 
					$('.m_baur_search-form').click()
				else
					$('.add_address').click()
			});	
			//$('.m_baur_search-form').click() 		//карта
			//show_modal_form('set-pickup-point') //точки самовывоза
			
			var init = function(){
				$('[data-pickup_point]').text( localStorage.pickupAddress );
				
				if(!!localStorage.shipping_method)
					$('[data-sf_btn="'+localStorage.shipping_method+'"]').click();
				
				/* При оформлении заказа выбираем нужный пункт*/
				if (document.location.pathname === '/checkout-2/') {
					if(!!localStorage.shipping_method){
						if(localStorage.shipping_method == 'delivery'){
							if($('#shipping_method [value="flat_rate:1"]').length)
							setTimeout(function(){ $('#shipping_method [value="flat_rate:1"]').click(); }, 400 );
						}else if(localStorage.shipping_method == 'pickup'){
							if($('#shipping_method [value="local_pickup:4"]').length)
							setTimeout(function(){ $('#shipping_method [value="local_pickup:4"]').click(); }, 400 );
						}
					}
				}else{
					//alert(document.location.pathname)
				}
			}
			
			init();
		})


		function show_modal_form(modal_id){

			var $form = $('#'+modal_id);
			
			$form.show();
			//$form.find('.modal-form__wrapper').prop('style', 'transform: translateY(100%);');
			

			$('#'+modal_id).find('.modal-form__close').on('click', function(){
				CloseForm();
			})
			$('#'+modal_id).find('.modal-form__overlay').on('click', function(){
				CloseForm();
			})
		} //sayHi style = "transform: translateY(0)!important;"

		function CloseForm(){

			//$form.find('.modal-form__wrapper').prop('style', 'transform: translateY(-110%);');
			$('.modal-form').hide();
		}
	</script>
	
	<?
}