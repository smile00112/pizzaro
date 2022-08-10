<?php
/*
* Template Name: Банеры2
*/

$banners = array();

$posts = get_posts(array(
    'numberposts' => -1,
    'category'    => 0,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'include'     => array(),
    'exclude'     => array(),
    'meta_key'    => '',
    'meta_value'  =>'',
    'post_type'   => 'banners',
    'suppress_filters' => true
));

foreach ($posts as $post) {
    setup_postdata($post);
	
	$text = get_field('banner-text');
    
    $banners[] = array(
        'title'       => get_the_title(),
        'description' => get_the_excerpt(),
        'image'       => get_the_post_thumbnail_url(),
        'category'    => get_field('category')
    );
}

wp_reset_postdata();

header('Content-Type: application/json');
//echo json_encode($banners);

exit();
