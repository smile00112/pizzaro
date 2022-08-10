<?php
  
//автоматическое меню из категорий магазина
function show_navigation_menu($menu_type = 'main'){
	$menu_html = '';
	$menu_structure = (!empty($GLOBALS['category_menu'])) ? $GLOBALS['category_menu'] : build_menu();

	switch($menu_type){
		case 'main':
			foreach($menu_structure as $m){
				if($m['show']){	
					$childrens = '';
					if($m['childrens']){
						foreach($m['childrens'] as $m_ch){
							if($m_ch['show']){
								$childrens.= '<li id="menu-item-'.$m_ch['id'].'" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-'.$m_ch['id'].'"><a href="'.$m_ch['slug'].'">'.$m_ch['name'].'</a></li>';	
							}
						}
						if($childrens){
							$childrens = '<ul class="sub-menu" wfd-invisible="true">'.$childrens.'</ul>';
						}
					}

					$menu_html.='<li id="menu-item-'.$m['id'].'" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-'.$m['id'].'"><a href="'.$m['slug'].'">'.$m['name'].'</a>'.$childrens.'</li>';
			
				}

			}
			$menu_html = '<nav class="secondary-navigation baur_navigation" role="navigation" aria-label="Secondary Navigation"><div class="menu-food-menu-container"><ul id="menu-food-menu" class="menu">'.$menu_html.'</ul></div></nav>';
		break;

		case 'main_mobile':
			foreach($menu_structure as $m){
				if($m['show']){
					if(!$m['name']) continue;
					// if($m['childrens']){
					// 	$childrens = '';
					// 	foreach($m['childrens'] as $m_ch){
					// 		if($m_ch['show']){
					// 			$childrens.= '<li id="menu-item-'.$m_ch['id'].'" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-'.$m_ch['id'].'"><a href="'.$m_ch['slug'].'">'.$m_ch['name'].'</a></li>';	
					// 		}
					// 	}
					// 	if($childrens){
					// 		$childrens = '<ul class="sub-menu" wfd-invisible="true">'.$childrens.'</ul>';
					// 	}
					// }

					$menu_html.='<li id="menu-item-'.$m['id'].'" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-'.$m['id'].'"><a href="'.$m['slug'].'">'.$m['name'].'</a></li>';
			
				}

			}
			$menu_html = '<nav class="menu-primarybaur-container"><ul id="menu-primarybaur" class="menu_baur-items">'.$menu_html.'</ul></nav>';
		break;		
	}

	echo $menu_html;
}


function build_menu(){

	$taxonomy     = 'product_cat';
	$orderby      = 'menu_order';
	$show_count   = 0;      // 1 for yes, 0 for no
	$pad_counts   = 0;      // 1 for yes, 0 for no
	$hierarchical = 1;      // 1 for yes, 0 for no  
	$title        = '';
	$empty        = 1;

	$args = array(
		'taxonomy'     => $taxonomy,
		'orderby'      => 'menu_order',
		'show_count'   => $show_count,
		'pad_counts'   => $pad_counts,
		'hierarchical' => $hierarchical,
		'title_li'     => $title,
		'hide_empty'   => $empty,
		'exclude' 		 => array(1, 15)
	);

	$all_categories = get_categories($args);
	$menu_structure = [];
	foreach ($all_categories as $index=>$cat) {
		
		//if ($cat->category_parent == 0) 
		$have_products =  custom_products_check_nal( $cat->slug );
		//if($have_products)
		{
			if ($cat->category_parent == 0) {
				$menu_structure[$cat->term_id]= [
					'id'  => $cat->term_id,
					'name' => $cat->name,
					'show' => $have_products ? true : false,
					'slug' => get_category_link( $cat->term_id ),
					'parent' => $cat->category_parent,
					'childrens' => []
				];
			}else{
				$menu_structure[$cat->category_parent]['childrens'][]= [
					'id'  => $cat->term_id,
					'name' => $cat->name,
					'show' => $have_products ? true : false,
					'slug' => get_category_link( $cat->term_id ),
					'parent' => $cat->category_parent,
				];
				//Бывает, что товар прикреплён только к дочерней категории 
				if($have_products) $menu_structure[$cat->category_parent]['show'] = true;
			}

		}
				
	}
	$GLOBALS['category_menu'] = $menu_structure;

	return	$menu_structure;
}

function custom_products_check_nal($category_slug) {
  $GLOBALS['products'] = new WP_Query([
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'tax_query'             => [
      [
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $category_slug,
        'include_children' => false,
      ]
    ],
	'meta_query' => [ [
		'key' => '_stock_status',
		'value' => 'instock',
	] ],	
  ]);

  return $GLOBALS['products']->have_posts();
} 
/**************************************************/