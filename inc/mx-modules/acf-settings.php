<?php
/////////////////////////добавление раздела в админку Системный надстройки от ACF
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Системные надстройки',
		'menu_title'	=> 'Сист. надстройки',
		'menu_slug' 	=> 'sys-custom-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> true
	));

	// acf_add_options_page(array(
	// 	'page_title' 	=> 'Рассылка',
	// 	'menu_title'	=> 'Рассылка',
	// 	'menu_slug' 	=> 'test_push',
	// 	'capability'	=> 'edit_posts',
	// 	'redirect'		=> true
	// ));	

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Общие надстройки для приложения',
		'menu_title'	=> 'Приложение',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-app',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Параметры бонусов',
		'menu_title'	=> 'Бонусы',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-bon',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Контактные данные',
		'menu_title'	=> 'Контакты',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-contacts',
	));
	
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Условия для расчёта доставки',
		'menu_title'	=> 'Доставка',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-deliv',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Настройки СМС сообщений',
		'menu_title'	=> 'СМС',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-sms',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Оформление',
		'menu_title'	=> 'Оформление',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-front',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Промокоды',
		'menu_title'	=> 'Промокоды',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-coupons',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Личный промокод пользователя',
		'menu_title'	=> 'Личный промокод',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-user_personal_promocode',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Товары',
		'menu_title'	=> 'Товары',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-products',
	));	

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Предпочтения',
		'menu_title'	=> 'Предпочтения',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-preferences',
	));	

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Автоматические события',
		'menu_title'	=> 'Автоматические события',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-events',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Рассылка Тест',
		'menu_title'	=> 'Рассылка Тест',
		'parent_slug'	=> 'test_push',
		'menu_slug' 	=> 'single_push',
	));		

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Меню кабинета пользователя',
		'menu_title'	=> 'Меню кабинета пользователя',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-user-menu',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Информационные страницы в приложении',
		'menu_title'	=> 'Информационные страницы в приложении',
		'parent_slug'	=> 'sys-custom-settings',
		'menu_slug' 	=> 'sys-custom-settings-info-pages',
	));

// 	// Добавим подменю в меню админ-панели "Инструменты" (tools):
// add_action('admin_menu', 'register_my_custom_submenu_page22');
 
// function register_my_custom_submenu_page22() {
// 	add_submenu_page(
// 		'tools.php', 
// 		'!!!!!!!!!',
// 		'!!!!!!!!!',
// 		'edit_posts',
// 		'my-custom-submenu-page11',
// 		'my_custom_submenu_page_callback'
// 	);
// }

// function my_custom_submenu_page_callback() {
// 	// контент страницы
// 	echo '<div class="wrap">';
// 		echo '<h2>'. get_admin_page_title() .'</h2>';
// 	echo '</div>';

// }
}
