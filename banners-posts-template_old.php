<?php
/*
* Template Name: Банеры1
*/

$banners = array();

/*$posts = get_posts(array(
    'numberposts' => -1,
    'category'    => 0,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'include'     => array(),
    'exclude'     => array(),
    'meta_key'    => '',
    'meta_value'  =>'',
    'post_type'   => 'mapppops',
    'suppress_filters' => true
));

foreach ($posts as $post) {
    setup_postdata($post);*/
    

	        // Do something...
 /*$banners[] = array(
        'title'       => get_the_title(),
        'message' => get_the_excerpt(),
        'picture_url'       => get_the_post_thumbnail_url(),
		'uuid'       => $post->ID,
		//'picture_url'       => get_sub_field('sub_field'),
		'button_link'       => get_field('button_link'),
        'button_title'    => get_field('button_title'),
		'repeat_mode'    => get_field('repeat_mode')
		
    );*/
	///wp-content/uploads/2021/02/600-40-001-1.png
	$server_url = "https://$_SERVER[HTTP_HOST]";
	$banners[] = array(
        'title'       => 'ВНИМАНИЕ!!!!',
        'message' => 'Обновите приложение! Это устаревшая версия. Заказ не будет обработан',
        'picture_url'       => $server_url . '/wp-content/uploads/2021/02/banner-attantion.jpg',
		'uuid'       => 11111, 
		'button_link'       => '',
        'button_title'    => 'Ознакомился',
		'repeat_mode'    => 'Повторять'
		
    );
//}

//wp_reset_postdata();

header('Content-Type: application/json');
//echo json_encode($banners);

exit();
