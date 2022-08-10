<?php
add_action('wp_enqueue_scripts', 'swiper_js');
function swiper_js()
{
	//  wp_enqueue_script('jquery');
	// wp_enqueue_style('style-swiper', 'https://unpkg.com/swiper@7/swiper-bundle.min.css');
	// wp_enqueue_script('script-swiper', 'https://unpkg.com/swiper@7/swiper-bundle.min.js', array(), '1.0.0', true);
	wp_enqueue_style('swiper', get_template_directory_uri() . '/assets/libs/swiper/swiper-bundle.min.css');
	wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/libs/swiper/swiper-bundle.min.js', array(), null, true);	
	
}

add_shortcode('swiper_slider', 'swiper_slider');

function swiper_slider($atts = null, $content = null, $tag = null)
{

	$custom_post_type = get_posts(array(
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'post_type'   => 'banners',
		'numberposts' => 15,
	));

	foreach( $custom_post_type as $post ){
		$img_src = $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		//print_r($img_src);
		$url = '';
		$type = get_post_meta($post->ID, 'type', true);
		switch($type){
			case 'url':
				$url = get_post_meta($post->ID, 'url', true);
			break;
			case 'category':
				$cat_id = get_post_meta($post->ID, 'category', true);
				$url = get_category_link($cat_id);
			break;
			case 'product':
				$product_id = get_post_meta($post->ID, 'product', true);
				$url = get_permalink($product_id);
			break;
			case 'text':
				$post_id = get_post_meta($post->ID, 'banner-post', true);
				$url = get_permalink($post_id);
			break;

		}

		//$content.= '<div class="swiper-slide" style="heigth: 600px;">'. ($url ? '<a href="'.$url.'">' : '') . get_the_post_thumbnail($post->ID, 'full') . ($url ? '</a>' : ''). '</div>';
		$content.= '<div class="swiper-slide"><div class="bg-slide '.($url ? 'cur-pointer' : '').'" '.($url ? 'onclick="location.href=\''.$url.'\'"' : '').' style="background:url('.$img_src[0].')"></div></div>';
	}

	if ($content) {
		$content = '<div class="swiper mainSlider"><div class="swiper-wrapper">' . $content . '</div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div><div class="swiper-pagination"></div></div>';
	}


	add_action('wp_footer', 'home_slider_init');

	return $content;
}

function home_slider_init()
{
?>
	<script defer>
		document.addEventListener('DOMContentLoaded', function() {
			var isMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) 
			var $pagination = {
					el: ".swiper-pagination",
					type: "bullets",
			};
			
			if(!isMobile) $pagination = false;
			var $navigation = {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				}; 
			if(isMobile) $navigation = false;

			var swiper = new Swiper(".mainSlider", {
				loop: true,

				// lazy: {
				// 	enabled: true,
				// 	loadPrevNext: false,
				// 	loadOnTransitionStart: true
				// },
				// effect: 'fade',
				// fadeEffect: {
				// 	crossFade: true
				// },

				//spaceBetween: 30,
				centeredSlides: true,
				lazyLoading: false,
				autoHeight: (isMobile ? true : false),
				// autoplay: {
				// 	delay: 10000,
				// 	disableOnInteraction: false,
				// },
				// pagination: {
				// el: ".swiper-pagination",
				// clickable: true,
				// },
				pagination: $pagination,
				navigation: $navigation,
			});
		});
	</script>
	<style>

		.swiper-slide .bg-slide{
			background-position: center!important;
			background-size: cover!important;
			background-repeat: no-repeat!important;
			cursor: pointer;
		}
		.cur-pointer{
			cursor: pointer;
		}
		.mainSlider .swiper-pagination-bullet{
			width: 50px;
			background-color: #30BA6B;
			border-radius: 0;
			height: 5px;
		}
		.mainSlider .swiper-pagination.swiper-pagination-bullets{
			display: flex;
    		justify-content: center;
		}
		.mainSlider .swiper-pagination-bullet{
			flex-grow: 0.2;
		}

		@media (min-width: 425px){
			.swiper-slide .bg-slide{
				height: 650px;
				
			}
		}

		@media (max-width: 425px){
			.swiper-slide .bg-slide{
				height: 250px;
			}
			.mainSlider .swiper-button-next, .mainSlider .swiper-button-prev{
				display: none;
			}
			.mainSlider{
				margin-top: 10px;
			}
			#catalog-app {
				padding: 15px 0px 10px 0;
			}
		}
	</style>		
<?php
}
