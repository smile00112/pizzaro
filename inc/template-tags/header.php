<?php

if ( ! function_exists( 'pizzaro_skip_links' ) ) {
	/**
	 * Skip links
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'pizzaro' ); ?></a>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'pizzaro' ); ?></a>
		<?php
	}
}

if ( ! function_exists( 'pizzaro_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_site_branding() {
		?>
		<nav id="site-navigation2" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'pizzaro' ); ?>">
				<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span class="close-icon"><i class="po po-close-delete"></i></span><span class="menu-icon"><i class="po po-mobile-icon"></i></span><span class=" screen-reader-text"><?php echo esc_attr( apply_filters( 'pizzaro_menu_toggle_text', esc_html__( 'Menu', 'pizzaro' ) ) ); ?></span></button>



				<div class="handheld-navigation">
					<?php
					wp_nav_menu( apply_filters( 'pizzaro_handheld_menu_args', array(
						'menu'	=> 101,
						'container'			=> false,
						'fallback_cb'		=> 'pizzaro_nav_menu_fallback',
						'items_wrap'		=> '<span class="phm-close">' . apply_filters( 'pizzaro_handheld_menu_close_button_text', esc_html__( 'Close', 'pizzaro' ) ) . '</span><ul id="%1$s" class="%2$s">%3$s</ul>'
					) ) );
					?>
				</div>

			</nav><!-- #site-navigation -->
			<?php 
			$logo_header_mob = get_field('gr_logo_header_mobile', 'option'); //echo '<pre>'; print_r($logo_header_desk['logo_header_desktop']); echo '</pre>'; 
			if($logo_header_mob['logo_set_type'] == 'svg') $logo_header_mob = $logo_header_mob['logo_header_desktop_svg'];
			else if($logo_header_mob['logo_set_type'] == 'jpg') $logo_header_mob = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_mob['logo_header_desktop'].'"></div>';
			?>
		<div class="site-branding">
			<a class="baur_mobile-logo"  href="<?php echo esc_url( home_url( '/' )); ?>">
				<?php 
				$logo_header_mob = get_field('gr_logo_header_mobile', 'option'); //echo '<pre>'; print_r($logo_header_desk); echo '</pre>'; 
				if($logo_header_mob['logo_set_type'] == 'svg') $logo_header_mob = $logo_header_mob['logo_header_mobile_svg'];
				else if($logo_header_mob['logo_set_type'] == 'jpg') $logo_header_mob = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_mob['logo_header_mobile'].'"></div>';
				
				?>
			
				<?php echo $logo_header_mob; ?>
			</a>
			
			<?php 
			// pizzaro_site_title_or_logo();
			$logo_header_desk = get_field('gr_logo_header_desktop', 'option'); //echo '<pre>'; print_r($logo_header_desk); echo '</pre>'; 
			if($logo_header_desk['logo_set_type'] == 'svg') $logo_header_desk = $logo_header_desk['logo_header_desktop_svg'];
			else if($logo_header_desk['logo_set_type'] == 'jpg') $logo_header_desk = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_desk['logo_header_desktop'].'"></div>';
			//else if($logo_header_desk['logo_set_type'] == 'svgfile') $logo_header_desk = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_desk['logo_header_desktop_file_svg'].'"></div>';
			?>
			<a class="baur_desctop-logo"  href="<?php echo esc_url( home_url( '/' )); ?>">
				<?php echo $logo_header_desk; ?>
				<?php //print_r($logo_header_desk); ?>
			</a><!--<img src="https://demo.xn--90agcwb4c1dc.xn--p1ai/wp-content/uploads/2021/04/test_svg.svghttps://demo.xn--90agcwb4c1dc.xn--p1ai/wp-content/uploads/2021/04/test_svg.svg">-->
		</div>
		<?php
	}
}

if ( ! function_exists( 'pizzaro_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_site_title_or_logo() {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		} elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			jetpack_the_site_logo();
		} elseif ( apply_filters( 'pizzaro_site_logo_svg', true ) ) {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">';
			pizzaro_get_template( 'global/logo-svg.php' );
			echo '</a>';
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">';
			?>
			<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
			<?php if ( '' != get_bloginfo( 'description' ) ) : ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			<?php endif;
			echo '</a>';
		}
	}
}

if ( ! function_exists( 'pizzaro_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_primary_navigation() {
		if ( apply_filters( 'pizzaro_show_primary_navigation', true ) ) {
			?>
			<?php
		}
	}
}

if ( ! function_exists( 'pizzaro_secondary_navigation' ) ) {
	/**
	 * Display Secondary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_secondary_navigation() {
		if ( apply_filters( 'pizzaro_show_secondary_navigation', true ) && has_nav_menu( 'food_menu' ) ) {
			?>
			
			<?php 
			show_navigation_menu('main');
			
			$logo_header_desk_slick = get_field('gr_logo_desktop_header_slick', 'option'); //echo '<pre>'; print_r($logo_header_desk['logo_header_desktop']); echo '</pre>'; 
			if($logo_header_desk_slick['logo_set_type'] == 'svg') $logo_header_desk_slick = $logo_header_desk_slick['logo_header_desktop_svg'];
			else if($logo_header_desk_slick['logo_set_type'] == 'jpg') $logo_header_desk_slick = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_desk_slick['logo_header_desktop'].'"></div>';
			//else if($logo_header_desk['logo_set_type'] == 'svgfile') $logo_header_desk = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_header_desk['logo_header_desktop_file_svg'].'"></div>';
		/*	?>
			<nav class="secondary-navigation baur_navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'pizzaro' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' )); ?>">
					<?php echo $logo_header_desk_slick; ?>
				</a>
				<?php
					wp_nav_menu( apply_filters( 'pizzaro_food_menu_args', array(
						'theme_location'	=> 'food_menu',
						'container'			=> 'div',
						'fallback_cb'		=> 'pizzaro_nav_menu_fallback',
					) ) );
				?>
			</nav><!-- #secondary-navigation -->
			<?php
		*/
		}
	}
}

if ( ! function_exists( 'pizzaro_header_phone' ) ) {
	/**
	 * Displays phone number in the header
	 */
	function pizzaro_header_phone() {
		$header_phone_args = apply_filters( 'pizzaro_header_phone_args', array(
			'text'			=> esc_html__( 'Call and Order in', 'pizzaro' ),
			'phone_numbers'	=> array(
				array(
					'city'		=> esc_html__( 'London', 'pizzaro' ),
					'number'	=> '54 548 779 654'
				),
				array(
					'city'		=> esc_html__( 'Paris', 'pizzaro' ),
					'number'	=> '33 398 621 710'
				),
				array(
					'city'		=> esc_html__( 'New York', 'pizzaro' ),
					'number'	=> '718 54 674 021'
				)
			)
		) );

		if ( apply_filters( 'pizzaro_show_header_phone_numbers', true ) && ! empty( $header_phone_args['phone_numbers'] ) ) : ?>
		<div class="header-phone-numbers">
			<!--<div class="header-phone-numbers-wrap">
				<span class="intro-text"><?php echo esc_html( $header_phone_args['text'] ); ?></span>
				<select class="select-city-phone-numbers" name="city-phone-numbers" id="city-phone-numbers">
					<?php foreach ( $header_phone_args['phone_numbers'] as $key => $phone_number ) { ?>
						<option value="<?php echo esc_attr( $phone_number['number'] ); ?>"><?php echo esc_html( $phone_number['city'] ); ?></option>
					<?php } ?>
				</select>
			</div>-->
			
	
				<!--
				<div class="header-phone-numbers-wrap for-tel" style="display: inline-flex!important">
					<span class="intro-text">Телефон</span>
					<span class="phone-number"><?php echo get_field('contact_phone_visual', 'option'); ?></span>
				</div>
			
				<div class="header-phone-numbers-wrap" style="display: inline-flex!important">
					<span class="intro-text">Время работы</span>
					<span id="" class="phone-number time-work">с 10:00 до 22:00</span>
				</div>
					-->
				<div class="header-phone-numbers-wrap for-tel" style="display: inline-flex!important">
					<span id="" >с 10:00 до 22:00</span>
					<span class="phone-number"><?php echo get_field('contact_phone_visual', 'option'); ?></span>
				</div>

				<? if(!isMobile()){ show_header_shippings(); } ?>

				<div class="header-phone-numbers-wrap timeWorkBaur" style="display: none">
					<span class="intro-text">Ваш адрес</span>
					<span class="phone-number"><span class="add_address">Введите адрес доставки</span>
						<span class="editing_an_address">
							<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 1L10.7071 0.292893C10.3166 -0.0976311 9.68342 -0.0976311 9.29289 0.292893L10 1ZM2 9L1.29289 8.29289C1.10536 8.48043 1 8.73478 1 9H2ZM2 12H1C1 12.5523 1.44772 13 2 13V12ZM5 12V13C5.26522 13 5.51957 12.8946 5.70711 12.7071L5 12ZM13 4L13.7071 4.70711C14.0976 4.31658 14.0976 3.68342 13.7071 3.29289L13 4ZM16 16C16.5523 16 17 15.5523 17 15C17 14.4477 16.5523 14 16 14V16ZM1 14C0.447715 14 0 14.4477 0 15C0 15.5523 0.447715 16 1 16V14ZM9.29289 0.292893L1.29289 8.29289L2.70711 9.70711L10.7071 1.70711L9.29289 0.292893ZM1 9V12H3V9H1ZM2 13H5V11H2V13ZM5.70711 12.7071L13.7071 4.70711L12.2929 3.29289L4.29289 11.2929L5.70711 12.7071ZM13.7071 3.29289L10.7071 0.292893L9.29289 1.70711L12.2929 4.70711L13.7071 3.29289ZM16 14H1V16H16V14Z" fill="#EC681C"/>
							</svg>
						</span>
					</span>
				</div>
		
			<div id="messicons" class="messicons">
				<div class="userhead">
					<svg width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 14V16H13V14C13 12.3431 11.6569 11 10 11H4C2.34315 11 1 12.3431 1 14Z" stroke="#343941" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M7 7C8.65685 7 10 5.65685 10 4C10 2.34315 8.65685 1 7 1C5.34315 1 4 2.34315 4 4C4 5.65685 5.34315 7 7 7Z" stroke="#343941" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>

					<?php 
					global $current_user; wp_get_current_user(); 
					
					$display_name = $current_user->display_name;
					$first_name = $current_user->first_name;
					$show_name = $display_name;
					if(!empty($first_name)) $show_name = $first_name;
					
					if(is_user_logged_in()) { 
						echo '<a href="/my-account-2">' . $show_name . "</a>";
					?>
					
					<?php } else { ?>
					<div id="login-button">Войти</div>
					<?php //echo do_shortcode('[xoo_el_action type="login" display="link" text="Войти" change_to="myaccount" redirect_to="same"]'); ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php endif;
	}
}

// вот тут нет кода

if ( ! function_exists( 'pizzaro_header_navigation_link' ) ) {
	function pizzaro_header_navigation_link() {
		$link_args = apply_filters( 'pizzaro_header_navigation_link_args', array(
			'my-account' =>	array(
				'title'	=> is_user_logged_in() ? esc_html__( 'My Account', 'pizzaro' ) : esc_html__( 'Login / Register', 'pizzaro' ),
				'link'	=> is_woocommerce_activated() ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : wp_login_url(),
				'icon'	=> '',
				'class'	=> 'my-account',
			)
		) );
		if ( apply_filters( 'pizzaro_show_header_navigation_link', true ) && ! empty( $link_args ) ) : ?>
		<div class="header-nav-links">
			<?php if( is_array( $link_args ) && ! empty( $link_args ) ) : ?>
				<ul>
					<?php foreach ( $link_args as $key => $link_arg ) : ?>
						<li>
							<?php
								$class = isset( $link_arg['class'] ) ? $link_arg['class'] : '';
								$link = isset( $link_arg['link'] ) ? $link_arg['link'] : '#';
								$icon = isset( $link_arg['icon'] ) ? $link_arg['icon'] : '';
								$title = isset( $link_arg['title'] ) ? $link_arg['title'] : '';
							?>
							<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $link ); ?>">
								<i class="<?php echo esc_attr( $icon ); ?>"></i>
								<?php echo esc_html( $title ) ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php endif;
	}
}

if ( ! function_exists( 'pizzaro_header_info_wrapper' ) ) {
	/**
	 * The info wrapper
	 */
	function pizzaro_header_info_wrapper() {
		echo '<div class="header-info-wrapper">';
	}
}

if ( ! function_exists( 'pizzaro_header_info_wrapper_close' ) ) {
	/**
	 * The info wrapper close
	 */
	function pizzaro_header_info_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'pizzaro_header_wrapper' ) ) {
	/**
	 * The header wrapper open
	 */
	function pizzaro_header_wrapper() {
		echo '<div class="header-wrap">';
	}
}

if ( ! function_exists( 'pizzaro_header_wrapper_close' ) ) {
	/**
	 * The header wrapper close
	 */
	function pizzaro_header_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'pizzaro_secondary_navigation_wrapper' ) ) {
	/**
	 * The secondary navigation wrapper
	 */
	function pizzaro_secondary_navigation_wrapper() {
		echo '<div class="pizzaro-secondary-navigation">';
	}
}

if ( ! function_exists( 'pizzaro_secondary_navigation_wrapper_close' ) ) {
	/**
	 * The secondary navigation wrapper close
	 */
	function pizzaro_secondary_navigation_wrapper_close() {
		echo '</div>';
	}
}
