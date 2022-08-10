<?php
/*
* Template Name: Банеры
*/

$cache = get_cache('cache_banners');
if(empty($cache))
{
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
        'suppress_filters' => true,
        'orderby' => 'date', 
        'order' => 'DESC', 
    ));
    foreach ($posts as $post) {
        setup_postdata($post);
        
        $post_id_in = get_field('banner-post')[0]; //var_dump($post_id_in);
        //if(!$text) $text = false;
        $post_in = get_post( $post_id_in );
        $post_title_in = $post_in->post_title;
        $post_excerpt_in = get_the_excerpt( $post_id_in );;
        $post_content_in = $post_in->post_content; //echo $post_content_in;
        //$post_content_in = str_replace('<!-- wp:paragraph -->', '', $post_in->post_content);
        $post_thumb_in = get_the_post_thumbnail_url( $post_id_in, 'full' );
        $show_button = get_field('show_button', $post_id_in);

        $post_resource_type = ($show_button == true) ? get_field('action_type', $post_id_in) : null;
        $post_resource_id = ($show_button == true) ? get_field('resource', $post_id_in) : null;
        $post_resource_button_text = ($show_button == true) ? get_field('button_title', $post_id_in) : null;

        $post_arr = ['post_title' => $post_title_in, 'post_content' => $post_content_in, 'post_img' => $post_thumb_in, 'show_button' => $show_button, 'post_resource_type' => $post_resource_type,'post_resource_id' => $post_resource_id, 'post_resource_button_text' => $post_resource_button_text, ];
        
        if(get_field('type') == 'text'){
           
            $banners[] = array(
                'title'       => get_the_title(),
                'description' => $post_excerpt_in,
                'image'       => get_the_post_thumbnail_url(),
                'category'    => get_field('category'),
                'product'     => get_field('product'),
                'url'         => get_field('url'),
                'type'        => get_field('type'),
                'sort'        => $post->menu_order,
                'post'        => $post_arr,
                'gallery'        => !empty(get_field('galereja')) ? get_field('galereja') : 'gallery',
                'sklad'    => get_field('skladtaxon') ? get_field('skladtaxon') : []
                
            );
        }
        else{
            
            $banners[] = array(
                'title'       => get_the_title(),
                'description' => get_the_excerpt(),
                'image'       => get_the_post_thumbnail_url(),
                'category'    => get_field('category'),
                'product'     => get_field('product'),
                'url'         => get_field('url'),
                'type'        => get_field('type'),
                'sort'        => $post->menu_order,
                'gallery'        => !empty(get_field('galereja')) ? get_field('galereja') : 'gallery',

                'sklad'    => get_field('skladtaxon') ? get_field('skladtaxon') : [],


            );
        }
    }
    $cache = set_cache('cache_banners', $banners);
}

//return $cache;


wp_reset_postdata();

header('Content-Type: application/json');
echo json_encode($cache);

exit();
