<?php
/*
* Template Name: Точки доставки
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
    'post_type'   => 'deliverypoint',
    'suppress_filters' => true
));

foreach ($posts as $post) {
    setup_postdata($post);
    

	      
 $banners[] = array(
        'title'       => get_the_title(),
		'address'       => get_field('address'),
        'code'    => get_field('code_for_1c')
		
		
    );
}

wp_reset_postdata();

header('Content-Type: application/json');
echo json_encode($banners);

exit();
