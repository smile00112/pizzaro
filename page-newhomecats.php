<?php

/**

 * Template name: главная с категориями
 *
 * @package pizzaro
 */

global $post;
$page_meta_values = get_post_meta($post->ID, '_pizzaro_page_metabox', true);

$header_style = '';
if (isset($page_meta_values['site_header_style']) && !empty($page_meta_values['site_header_style'])) {
	$header_style = $page_meta_values['site_header_style'];
}

$footer_style = '';
if (isset($page_meta_values['site_footer_style']) && !empty($page_meta_values['site_footer_style'])) {
	$footer_style = $page_meta_values['site_footer_style'];
}

get_header($header_style); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main 777 15" role="main">

		<?php 
		//if(!isMobile())
		{
		
			while (have_posts()) : the_post();

				do_action('pizzaro_page_before');

				get_template_part('content', 'page');

				/**
				 * Functions hooked in to pizzaro_page_after action
				 *
				 * @hooked pizzaro_display_comments - 10
				 */
				do_action('pizzaro_page_after');

			endwhile; // End of the loop. 
		}
		// else{
		// 	//получаем баннеры для мобилы
		// 	$banners = getMobileBanners();

		// 	//выводим основную галлерею взамен баннера
		// 	echo showMobileBanners($banners['gallery']);

		// }


		$taxonomy     = 'product_cat';
		$orderby      = 'menu_order';
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$title        = '';
		$empty        = 1;

		$args = array(
			'taxonomy'     => $taxonomy,
			'orderby'      => $orderby,
			'show_count'   => $show_count,
			'pad_counts'   => $pad_counts,
			'hierarchical' => $hierarchical,
			'title_li'     => $title,
			'hide_empty'   => $empty,
			'exclude' 		 => array(18, 15)
		);
		$all_categories = get_categories($args);
		foreach ($all_categories as $cat) {
			if ($cat->slug == 'to-beer') continue;
			if ($cat->category_parent == 0) {
				$category_id = $cat->term_id;


				//  echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'.  .'</a>';
				$section_class = empty($section_class) ? 'section-products' : $section_class . ' section-products';

				if (!empty($animation)) {
					$section_class .= ' animate-in-view';
				}

				//if($category_id == 125 || $category_id == 124 || $category_id == 122){
		?>
				<div id="section-<?php echo $cat->slug; ?>" class="<?php echo esc_attr($section_class); ?>" <?php if (!empty($animation)) : ?>data-animation="<?php echo esc_attr($animation); ?>" <?php endif; ?>>


					<h2 class="section-title"><?php echo wp_kses_post($cat->name); ?></h2>
					<?php


					$atts = array_merge(array(
						'limit'        => '99',
						'columns'      => '4',
						'orderby'      => 'date',
						'order'        => 'DESC',
						//'orderby'      => 'menu_order',
						//'order'        => 'ASC',
						'category'     => $cat->slug,
						'cat_operator' => 'IN',
					));




					$default_atts 	= array('per_page' => intval(99), 'columns' => intval(4));
					//$atts 			= isset( $shortcode_atts ) ? $shortcode_atts : array();
					$atts 			= wp_parse_args($atts, $default_atts);

					//echo pizzaro_do_shortcode( 'recent_products' , $atts );
					//echo do_shortcode( '[products category="'.$cat->slug.'" orderby="menu_order" order="ASC"]' );
					echo do_shortcode('[custom_products category="' . $cat->slug . '" category_field="slug" orderby="menu_order" order="ASC"]');

	
					//беннеры в моб. версии под акциями
					if( ($cat->slug == 'akcii' || $cat->slug == 'kombo-nabory') && !empty($banners['gallery_1'])){
						//	echo 'gallery_1';
						echo showMobileBanners($banners['gallery_1']);
					}
					
					//беннеры в моб. версии под новинками
					if($cat->slug == 'novinki' && !empty($banners['gallery_2'])){
						//echo 'gallery_2';
						echo showMobileBanners($banners['gallery_2']);
					}	

					?>

				</div>
		<?php

				//}//check category
			}
		}

		?>






	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer($footer_style);
