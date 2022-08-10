<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package pizzaro
 */

?><!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<meta charset="<?php bloginfo('charset');?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta name="yandex-verification" content="f886116a51c47f57" />
<!-- Продвижение сайта: интернет-агентство creative.bz  -->
<meta name="cmsmagazine" content="dfd5c6bcefa9b2c1f3a96e57c71933cc" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo('pingback_url');?>">
<?php if (isset($_GET['mobileapp']) && !empty($_GET['mobileapp'])) {} else {?>
		<?php wp_head();?>
		<?php }?>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse') == false):?>
<!-- !!! Метрику под этот блок !!!-->

	<!--чат битрикс-->
	<script>
			(function(w,d,u){
					var s=d.createElement('script');
	s.async=true;
	s.src=u+'?'+(Date.now()/60000|0);
					var h=d.getElementsByTagName('script')[0];
	h.parentNode.insertBefore(s,h);
			})(window,document,'https://cdn-ru.bitrix24.ru/b16299006/crm/site_button/loader_1_shnz36.js');
	</script>

<? endif; ?> 
<!--
<script src="https://api-maps.yandex.ru/2.1/?apikey=116a19cd-2760-485d-80a0-cc36a11caa2d&lang=ru_RU&coordorder=longlat" type="text/javascript"> </script>
-->

</head>

<body <?php body_class();?>>


<?php
$contact_phone        = get_field('contact_phone', 'option');
$contact_phone_visual = get_field('contact_phone_visual', 'option');
?>

<?php if (isset($_GET['mobileapp']) && !empty($_GET['mobileapp'])) {} else {
	?>
	<div id="page" class="hfeed site tessst">
				<div class="top_header-date">
					<svg class="bottom_left" width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 0V44C0 52.8366 7.16344 60 16 60H60L0 0Z" fill="#FF6600"/>
					</svg>
					<svg class="top_right" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M70 70V16C70 7.16344 62.8366 0 54 0H0L70 70Z" fill="#FF0000"/>
					</svg>
					<div class="icon_sleep" style="position:relative">
						<video id="so_icon_sleep" class="so_video_bg jquery-background-video is-playing is-visible" loop autoplay playsinline muted data-bgvideo="" poster="/wp-content/themes/pizzaro/assets/images/zastavka.png" data-bgvideo-fade-in="500" data-bgvideo-pause-after="120" data-bgvideo-show-pause-play="true" data-bgvideo-pause-play-x-pos="right" data-bgvideo-pause-play-y-pos="top" style="min-width: auto; min-height:auto; width: 100%; height: auto; position: absolute; left: 17%; top: 50%; transform: translate(-50%, -50%); transition-duration: 500ms; z-index: 999;">
							<source src="/wp-content/themes/pizzaro/assets/images/sleep.webm" type="video/webm">
						</video>
					</div>
					<div>
						<span class="top_header-date_top">Мы работаем с 09:00, но уже сейчас готовы принять заказ.</span>
						<span class="top_header-date_bottum">Доставим в удобное для вас время.</span>
					</div>
				</div>
				<div class="baur_modal-fix_onclick-null">
					<div class="baur_modal-fix_container">
						<div class="icon_not-address"></div>
						<p>Вам необходимо выбрать адрес, что-бы добавлять товары в корзину<p>
						<div class="in_address">Выбрать адрес</div>
						<div class="resume">Продолжить</div>
					</div>
				</div>
				<div class="top_header">
					<div class="top_header-close">
						<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L1.70711 0.292893ZM8.29289 9.70711C8.68342 10.0976 9.31658 10.0976 9.70711 9.70711C10.0976 9.31658 10.0976 8.68342 9.70711 8.29289L8.29289 9.70711ZM9.70711 1.70711C10.0976 1.31658 10.0976 0.683417 9.70711 0.292893C9.31658 -0.0976311 8.68342 -0.0976311 8.29289 0.292893L9.70711 1.70711ZM0.292893 8.29289C-0.0976311 8.68342 -0.0976311 9.31658 0.292893 9.70711C0.683417 10.0976 1.31658 10.0976 1.70711 9.70711L0.292893 8.29289ZM0.292893 1.70711L8.29289 9.70711L9.70711 8.29289L1.70711 0.292893L0.292893 1.70711ZM8.29289 0.292893L0.292893 8.29289L1.70711 9.70711L9.70711 1.70711L8.29289 0.292893Z" fill="white"/>
						</svg>
					</div>
					<div class="top_header-logo">
						<?php
						$logo_header_mob_app = get_field('gr_logo_mob_app', 'option');
						if ($logo_header_mob_app['logo_set_type'] == 'svg') {
							$logo_header_mob_app = $logo_header_mob_app['logo_header_desktop_svg'];
						} else if ($logo_header_mob_app['logo_set_type'] == 'jpg') {
							$logo_header_mob_app = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_mob_app['logo_header_desktop'].'"></div>';
						}
						require get_template_directory() . '/inc/mx-modules/mobdet/mobdet.php';
						?>
						<div class="top_header-logo_img">
						<?php echo $logo_header_mob_app;?>
						</div>
						<span class="top_header-logo_title"><?php echo get_field('gr_mob_app_text', 'option'); ?></span>
					</div>
					<a href="<?php echo $link_market;  ?>" class="top_header-button"> <!--$link_market var from mobdet.php-->
						<span><?php echo get_field('gr_mob_app_text_button', 'option'); ?></span>
					</a>
				</div>
	<?php
	$logo_burger_mob = get_field('gr_logo_burger_mobile', 'option');
	//echo '<pre>';
	//print_r($logo_header_desk['logo_header_desktop']);
	//echo '</pre>';
	if ($logo_burger_mob['logo_set_type'] == 'svg') {
		$logo_burger_mob = $logo_burger_mob['logo_header_desktop_svg'];
	} else if ($logo_burger_mob['logo_set_type'] == 'jpg') {
		$logo_burger_mob = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_burger_mob['logo_header_desktop'].'"></div>';
	}

	?>
	<div class="baur_menu-container">
					<div class="baur_menu-container_top">
						<div class="baur_menu-logo">
	<?php echo $logo_burger_mob;?>
	</div>
						<div class="baur_menu-close">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L1.70711 0.292893ZM12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L12.2929 13.7071ZM13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L13.7071 1.70711ZM0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L0.292893 12.2929ZM0.292893 1.70711L12.2929 13.7071L13.7071 12.2929L1.70711 0.292893L0.292893 1.70711ZM12.2929 0.292893L0.292893 12.2929L1.70711 13.7071L13.7071 1.70711L12.2929 0.292893Z" fill="#EA5C2C"/>
							</svg>
						</div>
					</div>
					<div class="baur_delivery">
						<span class="baur_delivery-title">Ваш адрес</span>
						<span class="baur_delivery-city">Введите ваш адрес доставки</span>
						<span class="baur_delivery-city">
							<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 1L10.7071 0.292893C10.3166 -0.0976311 9.68342 -0.0976311 9.29289 0.292893L10 1ZM2 9L1.29289 8.29289C1.10536 8.48043 1 8.73478 1 9H2ZM2 12H1C1 12.5523 1.44772 13 2 13V12ZM5 12V13C5.26522 13 5.51957 12.8946 5.70711 12.7071L5 12ZM13 4L13.7071 4.70711C14.0976 4.31658 14.0976 3.68342 13.7071 3.29289L13 4ZM16 16C16.5523 16 17 15.5523 17 15C17 14.4477 16.5523 14 16 14V16ZM1 14C0.447715 14 0 14.4477 0 15C0 15.5523 0.447715 16 1 16V14ZM9.29289 0.292893L1.29289 8.29289L2.70711 9.70711L10.7071 1.70711L9.29289 0.292893ZM1 9V12H3V9H1ZM2 13H5V11H2V13ZM5.70711 12.7071L13.7071 4.70711L12.2929 3.29289L4.29289 11.2929L5.70711 12.7071ZM13.7071 3.29289L10.7071 0.292893L9.29289 1.70711L12.2929 4.70711L13.7071 3.29289ZM16 14H1V16H16V14Z" fill="#6D5627"/>
							</svg>
						</span>
					</div>
	<?php wp_nav_menu([
			'menu'       => 'href_mobile2',
			'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class' => 'baur_items',
		]);
	?>
	<div class="baur_auth userhead">
						<svg width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M3 4C3 1.79086 4.79086 0 7 0C9.20914 0 11 1.79086 11 4C11 6.20914 9.20914 8 7 8C4.79086 8 3 6.20914 3 4ZM0 14C0 11.7909 1.79086 10 4 10H10C12.2091 10 14 11.7909 14 14V16C14 16.5523 13.5523 17 13 17H1C0.447715 17 0 16.5523 0 16V14Z" fill="#1C1C1C"/>
						</svg>
	<?php global $current_user;
	wp_get_current_user();?>
						<?php if (is_user_logged_in()) {
		echo '<a href="/my-account-2">'.$current_user->display_name."</a>";
		?>
								<?php } else {?>
								<?php //echo do_shortcode('[xoo_el_action type="login" display="link" text="Войти" change_to="myaccount" redirect_to="same"]'); ?>
		<div id="login-button">Войти</div>
		<?php }?>
					</div>
					<div class="baur_menu-container_bottum">
						<div class="baur_phone">
							<div class="baur_phone-num">
								<span>C 09:00 до 18:00</span>
								<a href="tel: <?php echo $contact_phone;?>"><?php echo $contact_phone_visual;
	?></a>
							</div>
							<a class="baur_phone-btn" href="tel: <?php echo $contact_phone;?>">
								<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M3.62 7.79C5.06 10.62 7.38 12.93 10.21 14.38L12.41 12.18C12.68 11.91 13.08 11.82 13.43 11.94C14.55 12.31 15.76 12.51 17 12.51C17.55 12.51 18 12.96 18 13.51V17C18 17.55 17.55 18 17 18C7.61 18 0 10.39 0 1C0 0.45 0.45 0 1 0H4.5C5.05 0 5.5 0.45 5.5 1C5.5 2.25 5.7 3.45 6.07 4.57C6.18 4.92 6.1 5.31 5.82 5.59L3.62 7.79Z" fill="black"/>
								</svg>
								<span>Позвонить</span>
							</a>
						</div>
						<div class="baur_social">
							<a href="#" target="_blank">
								<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18.8883 15.5532C18.5661 15.39 16.9967 14.6242 16.7034 14.5149C16.4101 14.4105 16.1971 14.3558 15.9834 14.6781C15.7745 14.9923 15.1589 15.7115 14.9709 15.9212C14.7828 16.1309 14.598 16.147 14.2806 16.0048C13.9584 15.8416 12.929 15.5065 11.7068 14.4105C10.7521 13.5611 10.1165 12.5149 9.92767 12.1926C9.73963 11.8744 9.90677 11.6944 10.0659 11.5353C10.2121 11.389 10.3881 11.1673 10.5512 10.9744C10.7063 10.7815 10.7561 10.6522 10.8695 10.4432C10.9739 10.2174 10.9201 10.0415 10.8405 9.88235C10.761 9.72324 10.1205 8.14583 9.85293 7.51744C9.5974 6.89387 9.32981 6.97342 9.13293 6.97342C8.94892 6.95655 8.73517 6.95655 8.52222 6.95655C8.30927 6.95655 7.96133 7.0361 7.66802 7.34146C7.37472 7.66369 6.54624 8.43351 6.54624 9.99083C6.54624 11.5522 7.69293 13.0629 7.85204 13.2887C8.01517 13.4976 10.1077 16.7119 13.3179 18.0932C14.0837 18.4155 14.6784 18.6083 15.1428 18.7674C15.9086 19.0101 16.6078 18.9764 17.1598 18.8968C17.7705 18.7964 19.0514 18.1222 19.3198 17.3692C19.5922 16.6115 19.5922 15.9839 19.5127 15.8416C19.4331 15.6954 19.2242 15.6158 18.902 15.4736L18.8883 15.5532ZM13.0664 23.4466H13.0495C11.1491 23.4466 9.27035 22.9315 7.62945 21.9689L7.24454 21.739L3.22669 22.7853L4.30669 18.8759L4.04713 18.4741C2.98599 16.7869 2.42303 14.8343 2.42311 12.8411C2.42311 7.00717 7.19874 2.24842 13.0745 2.24842C15.9207 2.24842 18.591 3.35735 20.5999 5.36628C21.5909 6.34551 22.377 7.51228 22.9123 8.79854C23.4476 10.0848 23.7214 11.4648 23.7178 12.858C23.7097 18.6879 18.9381 23.4466 13.0704 23.4466H13.0664ZM22.1275 3.83869C19.683 1.4778 16.4687 0.143066 13.0495 0.143066C5.9974 0.143066 0.255078 5.86048 0.25106 12.8869C0.25106 15.1305 0.836864 17.3194 1.95865 19.2568L0.142578 25.8574L6.93115 24.0871C8.81176 25.101 10.9138 25.6343 13.0503 25.6396H13.0544C20.1105 25.6396 25.8528 19.9222 25.8569 12.8909C25.8569 9.4886 24.5302 6.28637 22.1114 3.87967L22.1275 3.83869Z" fill="url(#paint0_linear)"/>
									<defs>
									<linearGradient id="paint0_linear" x1="12.9992" y1="25.8556" x2="12.9992" y2="0.141523" gradientUnits="userSpaceOnUse">
									<stop stop-color="#20B038"/>
									<stop offset="1" stop-color="#60D66A"/>
									</linearGradient>
									</defs>
								</svg>
							</a>
							<a href="#" class="insta" target="_blank">
								<svg width="27" height="22" viewBox="0 0 27 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M24.8412 0.571371C23.9504 0.587736 22.5828 1.05531 16.0063 3.75558C11.3901 5.68271 6.78669 7.64034 2.19638 9.62835C1.07419 10.0702 0.489718 10.5004 0.435947 10.9212C0.33308 11.7301 1.51138 11.9803 2.9936 12.4572C4.20229 12.8453 5.82946 13.2988 6.67577 13.3175C7.4426 13.3339 8.29827 13.0206 9.24277 12.3824C15.6953 8.08067 19.0222 5.90877 19.2326 5.86201C19.3799 5.82928 19.5833 5.7872 19.7235 5.90877C19.8615 6.03034 19.8474 6.25945 19.8311 6.32258C19.7142 6.81587 13.6567 12.2561 13.3084 12.6138L13.14 12.7822C11.8542 14.0516 10.5567 14.8816 12.7964 16.3381C14.821 17.6543 15.9993 18.4936 18.08 19.8449C19.4126 20.7053 20.4576 21.7269 21.8323 21.603C22.4659 21.5446 23.1181 20.9578 23.4525 19.2043C24.2357 15.0663 25.7787 6.09347 26.134 2.39492C26.1555 2.08814 26.1422 1.77991 26.0943 1.47613C26.0654 1.23088 25.9452 1.00549 25.7576 0.844904C25.4747 0.615791 25.0352 0.569033 24.8412 0.571371Z" fill="#23A0DC"/>
								</svg>
							</a>
							<a href="#" target="_blank">
								<svg width="26" height="28" viewBox="0 0 26 28" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12.3229 0.00394238C10.1487 0.030189 5.47427 0.387481 2.85977 2.78608C0.914982 4.71309 0.235957 7.56212 0.156371 11.0851C0.0903308 14.5954 0.0107442 21.1875 6.36496 22.9824V25.7163C6.36496 25.7163 6.32517 26.8102 7.04822 27.0346C7.93891 27.3165 8.44606 26.4749 9.29273 25.5707L10.8718 23.7851C15.2194 24.1466 18.5493 23.3135 18.9329 23.1899C19.8151 22.9079 24.7842 22.2729 25.5961 15.6799C26.4292 8.87106 25.1905 4.58101 22.9545 2.64046H22.941C22.2662 2.019 19.5543 0.042889 13.4956 0.0208757C13.4956 0.0208757 13.046 -0.00960426 12.3229 0.00309571V0.00394238ZM12.3974 1.91825C13.0147 1.91402 13.3897 1.94026 13.3897 1.94026C18.518 1.95381 20.9657 3.49728 21.5431 4.01713C23.4261 5.63088 24.3964 9.49844 23.686 15.1829C23.0112 20.6947 18.9811 21.0436 18.2352 21.2815C17.9177 21.3831 14.9849 22.1061 11.29 21.8682C11.29 21.8682 8.53835 25.1888 7.67814 26.044C7.54183 26.1938 7.38265 26.2378 7.28105 26.2158C7.13543 26.1803 7.0914 25.9999 7.09987 25.7527L7.12611 21.2154C1.73709 19.7253 2.05459 14.1026 2.11217 11.1655C2.17821 8.22844 2.72938 5.82561 4.37022 4.19832C6.57917 2.20104 10.5483 1.9318 12.3958 1.91825H12.3974ZM12.8038 4.85448C12.7595 4.85404 12.7155 4.86237 12.6744 4.87901C12.6333 4.89565 12.5958 4.92026 12.5643 4.95143C12.5327 4.9826 12.5076 5.0197 12.4905 5.06061C12.4733 5.10151 12.4644 5.14541 12.4643 5.18976C12.4643 5.37942 12.6184 5.52928 12.8038 5.52928C13.6433 5.51331 14.4776 5.6637 15.2587 5.97177C16.0398 6.27983 16.7521 6.73948 17.3547 7.32421C18.5849 8.51885 19.1843 10.1241 19.2072 12.223C19.2072 12.4084 19.357 12.5625 19.5467 12.5625V12.549C19.6361 12.5492 19.7219 12.5141 19.7855 12.4513C19.8491 12.3885 19.8853 12.3031 19.8862 12.2137C19.9273 11.2258 19.7671 10.2398 19.4153 9.3157C19.0635 8.39161 18.5275 7.54871 17.8398 6.83822C16.4995 5.52843 14.8011 4.85364 12.8038 4.85364V4.85448ZM8.34108 5.63088C8.10143 5.59586 7.85701 5.64397 7.64851 5.76719H7.63073C7.14666 6.05094 6.71058 6.40957 6.33872 6.82975C6.02968 7.18705 5.86204 7.54857 5.81802 7.89655C5.79177 8.10398 5.80955 8.31142 5.87136 8.50954L5.89337 8.52308C6.24135 9.54585 6.69601 10.5297 7.25142 11.4559C7.96687 12.7572 8.84734 13.9607 9.871 15.0365L9.90148 15.0805L9.94974 15.1161L9.98022 15.1516L10.0158 15.1821C11.0955 16.2088 12.3018 17.0933 13.6056 17.8144C15.0958 18.6255 16 19.009 16.5427 19.1682V19.1767C16.7019 19.2249 16.8467 19.2469 16.9923 19.2469C17.4548 19.213 17.8927 19.0253 18.236 18.7135C18.6543 18.3416 19.009 17.9037 19.2859 17.4173V17.4088C19.5458 16.9195 19.4578 16.4563 19.0827 16.1431C18.3316 15.4865 17.5193 14.9033 16.657 14.4015C16.0796 14.0882 15.4929 14.2779 15.2549 14.5954L14.7478 15.2346C14.4879 15.5521 14.0154 15.5081 14.0154 15.5081L14.0019 15.5165C10.4789 14.6165 9.53911 11.0495 9.53911 11.0495C9.53911 11.0495 9.49508 10.5644 9.82105 10.3172L10.456 9.80578C10.76 9.55855 10.9717 8.97266 10.6457 8.39439C10.1474 7.53092 9.56544 6.71846 8.90834 5.9687C8.76469 5.79194 8.56316 5.67168 8.33939 5.62918L8.34108 5.63088ZM13.3897 6.63671C12.9402 6.63671 12.9402 7.31574 13.394 7.31574C13.953 7.32481 14.5048 7.44395 15.0177 7.66637C15.5307 7.88879 15.9948 8.21011 16.3835 8.61198C16.7381 9.00315 17.0106 9.46151 17.1849 9.95991C17.3591 10.4583 17.4315 10.9866 17.3978 11.5135C17.3994 11.6027 17.4358 11.6877 17.4993 11.7504C17.5627 11.813 17.6482 11.8484 17.7374 11.8488L17.7509 11.8666C17.8407 11.8659 17.9267 11.8299 17.9902 11.7664C18.0538 11.7029 18.0898 11.6169 18.0904 11.527C18.1209 10.1817 17.7026 9.05309 16.8822 8.14886C16.0576 7.24462 14.907 6.73747 13.438 6.63671H13.3897ZM13.946 8.46212C13.4829 8.44858 13.4651 9.14115 13.924 9.15469C15.0399 9.21227 15.5818 9.77615 15.6529 10.9361C15.6544 11.0241 15.6904 11.1081 15.753 11.1699C15.8157 11.2318 15.9001 11.2667 15.9882 11.2671H16.0017C16.047 11.2652 16.0914 11.2542 16.1324 11.2349C16.1734 11.2156 16.2101 11.1884 16.2405 11.1547C16.2708 11.1211 16.2942 11.0817 16.3091 11.0389C16.3241 10.9962 16.3304 10.9508 16.3277 10.9056C16.2481 9.39345 15.4234 8.54171 13.9595 8.46297H13.946V8.46212Z" fill="#9069AE"/>
								</svg>
							</a>
						</div>
						<div class="baur_bottum-delivery">Доставка по Челябинску 30 мин.</div>
					</div>
				</div>
	<?php
	do_action('pizzaro_before_header');?>

	<?php $header_bg_version = pizzaro_get_header_bg_version();?>

			<header id="masthead" class="site-header header-v1 <?php echo esc_attr($header_bg_version);?>" role="banner" style="<?php pizzaro_header_styles();?>">

				<div class="site-header-wrap baurPrimaryHeader">
					<div class="col-full baur_logoPrimary">

	<?php
	/**
	 * Functions hooked into pizzaro_header_v1 action
	 *
	 * @hooked pizzaro_skip_links                       - 0
	 * @hooked pizzaro_site_branding                    - 20
	 * @hooked pizzaro_primary_navigation               - 30
	 * @hooked pizzaro_header_phone                     - 40
	 * @hooked pizzaro_header_cart                      - 50
	 * @hooked pizzaro_secondary_navigation             - 60
	 */
	do_action('pizzaro_header_v1');?>
	</div>
					<div class="baur_menu-btn">
						<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 10C0.447715 10 0 10.4477 0 11C0 11.5523 0.447715 12 1 12V10ZM13 12C13.5523 12 14 11.5523 14 11C14 10.4477 13.5523 10 13 10V12ZM1 0C0.447715 0 0 0.447715 0 1C0 1.55228 0.447715 2 1 2V0ZM13 2C13.5523 2 14 1.55228 14 1C14 0.447715 13.5523 0 13 0V2ZM1 5C0.447715 5 0 5.44772 0 6C0 6.55228 0.447715 7 1 7V5ZM13 7C13.5523 7 14 6.55228 14 6C14 5.44772 13.5523 5 13 5V7ZM1 12H13V10H1V12ZM1 2H13V0H1V2ZM1 7H13V5H1V7Z" fill="white"/>
						</svg>
					</div>
				<!--
					<div class="m_baur_search-form" >
						<span class="m_baur_search-form-text">
							<span class="m_baur_search-title">Ваш адрес</span>
							<span class="m_add_address">Введите адрес доставки</span>
						</span>
						<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 1L10.7071 0.292893C10.3166 -0.0976311 9.68342 -0.0976311 9.29289 0.292893L10 1ZM2 9L1.29289 8.29289C1.10536 8.48043 1 8.73478 1 9H2ZM2 12H1C1 12.5523 1.44772 13 2 13V12ZM5 12V13C5.26522 13 5.51957 12.8946 5.70711 12.7071L5 12ZM13 4L13.7071 4.70711C14.0976 4.31658 14.0976 3.68342 13.7071 3.29289L13 4ZM16 16C16.5523 16 17 15.5523 17 15C17 14.4477 16.5523 14 16 14V16ZM1 14C0.447715 14 0 14.4477 0 15C0 15.5523 0.447715 16 1 16V14ZM9.29289 0.292893L1.29289 8.29289L2.70711 9.70711L10.7071 1.70711L9.29289 0.292893ZM1 9V12H3V9H1ZM2 13H5V11H2V13ZM5.70711 12.7071L13.7071 4.70711L12.2929 3.29289L4.29289 11.2929L5.70711 12.7071ZM13.7071 3.29289L10.7071 0.292893L9.29289 1.70711L12.2929 4.70711L13.7071 3.29289ZM16 14H1V16H16V14Z" fill="#686868"/>
						</svg>
					</div>
				-->
				<? if(isMobile()){ show_header_shippings(); }?>

					<div class="baur_header_bottom_menu">
					<div class="animation start-home"></div>
	<?php
	// wp_nav_menu( [] );
	$args = array(
		'menu'       => 'primaryBaur', // ID, slug или название меню
		'menu_class' => 'menu_baur-items',
		'container'  => 'nav',
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	);

	wp_nav_menu($args);
	?>
	
					</div>
				</div>
			</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to pizzaro_before_content
	 *
	 * @hooked pizzaro_header_widget_region - 10
	 */
	do_action('pizzaro_before_content');?>

			<div id="content" class="site-content" tabindex="-1" <?php pizzaro_site_content_style();?>>
				<div class="col-full">

	<?php
	/**
	 * Functions hooked in to pizzaro_content_top
	 *
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action('pizzaro_content_top');
}?>