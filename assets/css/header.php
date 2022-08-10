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
		<div class="site-branding">
			<a class="baur_mobile-logo"  href="https://rollkroll.люблюеду.рф/">
				<svg width="110" height="46" viewBox="0 0 110 46" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M14.9559 15.2802C15.8143 15.3127 16.6479 15.4407 17.4445 15.6539C15.5426 18.8593 13.7477 21.7761 11.784 24.4858C9.84497 27.161 7.60546 29.6351 5.0716 31.9852C4.78137 31.5201 4.52613 31.0346 4.30794 30.5268C8.52761 27.8171 12.6217 20.2974 14.9559 15.2802ZM18.0661 15.8388C18.7845 16.0764 19.472 16.3852 20.1163 16.7569C15.9028 23.8846 13.3442 28.7454 7.16497 34.441C6.62156 33.9657 6.12755 33.4396 5.68911 32.8708C8.24973 30.476 10.304 27.5347 12.2656 24.825C14.2417 22.1011 16.1518 19.0624 18.0661 15.8388ZM21.0158 17.3338C21.6745 17.7989 22.2776 18.3352 22.8169 18.9303C20.1821 21.8879 18.0949 24.6524 16.2012 27.3093C14.3199 29.954 12.3768 32.9257 10.3122 36.3808C9.55886 36.0741 8.8446 35.6882 8.18386 35.2332C13.76 30.6568 17.3457 23.4783 21.0158 17.3338ZM23.3417 19.5539C23.7863 20.1227 24.1754 20.7382 24.5006 21.3882C22.8601 24.0938 21.0631 27.2036 19.1303 29.759C17.1995 32.3143 14.8427 34.9123 12.3294 36.9841C11.856 36.8907 11.3949 36.7668 10.9462 36.6165C15.1 30.0576 18.2802 25.2333 23.3417 19.5539ZM24.9967 22.5379C25.3548 23.5271 25.5751 24.5813 25.6306 25.6802C23.8666 27.3926 21.9544 29.2918 20.4477 30.9818C18.9121 32.7022 17.2242 34.8574 15.664 37.1446C15.2935 37.1812 14.9147 37.1994 14.5339 37.1994C14.1058 37.1994 13.6838 37.1771 13.2681 37.1304C18.5293 32.7307 21.5489 28.2091 24.9967 22.5379ZM25.5998 27.2077C25.1696 32.0502 21.5489 35.9909 16.8229 36.9659C19.4864 33.6996 22.2549 29.7915 25.5998 27.2077ZM3.89833 29.4136C3.69455 28.7474 3.55252 28.0568 3.48048 27.3458C5.43182 24.0105 7.92245 20.6244 9.92113 18.2235C10.4687 17.5653 11.644 16.2897 12.3315 15.4874C12.9408 15.3675 13.5645 15.2944 14.2046 15.2761C11.7016 20.535 8.97016 26.1372 3.89833 29.4136ZM3.45166 25.4507C3.76453 21.0916 6.66067 17.4353 10.6395 15.9647C10.1661 16.5497 9.70294 17.1043 9.23569 17.6669C7.33787 19.946 5.33713 22.3489 3.45166 25.4507Z" fill="#EA5C2C"/>
					<path fill-rule="evenodd" clip-rule="evenodd" d="M43.7828 10.6739C43.7828 6.65735 47.0337 3.38739 51.0544 3.38739C55.0825 3.38739 58.3558 6.65735 58.3558 10.6739C58.3558 14.7202 55.0825 17.9603 51.0544 17.9603C47.0337 17.9603 43.7828 14.7202 43.7828 10.6739ZM55.5309 10.6739C55.5309 7.94144 53.5729 6.04516 51.0544 6.04516C48.5658 6.04516 46.6003 7.94144 46.6003 10.6739C46.6003 13.4287 48.5658 15.31 51.0544 15.31C53.5729 15.31 55.5309 13.4287 55.5309 10.6739ZM49.5229 36.801H46.3395L43.3952 32.1895C42.7824 31.2222 42.4387 30.8998 41.4597 30.8998H40.152V36.801H37.3273V22.4491H40.152V28.1704H41.3775C42.3565 28.1704 42.7002 27.8255 43.313 26.8807L46.1377 22.4491H49.3211L45.9509 27.743C45.1364 28.8677 44.6805 29.2577 44.2994 29.4601C44.7478 29.6701 45.2186 30.0375 46.0331 31.3272L49.5229 36.801ZM52.5494 36.801V22.4491H57.2498C61.2552 22.4491 63.6241 24.8261 63.6241 27.9679C63.6241 31.1472 61.2552 33.5017 57.2498 33.5017H55.3666V36.801H52.5494ZM55.3666 25.1185V30.8398H57.4739C59.2898 30.8398 60.9488 29.9925 60.9488 27.9679C60.9488 26.0408 59.2898 25.1185 57.4739 25.1185H55.3666ZM65.9929 29.6251C65.9929 25.5909 69.2436 22.3066 73.264 22.3066C77.2918 22.3066 80.5649 25.5909 80.5649 29.6251C80.5649 33.6892 77.2918 36.9435 73.264 36.9435C69.2436 36.9435 65.9929 33.6892 65.9929 29.6251ZM77.7402 29.6251C77.7402 26.8807 75.7823 24.9761 73.264 24.9761C70.7755 24.9761 68.8102 26.8807 68.8102 29.6251C68.8102 32.392 70.7755 34.2816 73.264 34.2816C75.7823 34.2816 77.7402 32.392 77.7402 29.6251ZM91.7815 36.801L88.2469 27.638L84.6899 36.801H81.5662L87.4473 22.4491H89.0988L94.9052 36.801H91.7815ZM106.877 36.801L103.342 27.638L99.7849 36.801H96.6613L102.542 22.4491H104.201L110 36.801H106.877ZM66.1947 8.69159L69.7295 17.8325H72.8533L67.0467 3.51522H65.3951L59.5063 17.8325H62.6375L66.1947 8.69159ZM81.2905 8.69159L84.8253 17.8325H87.9491L82.1499 3.51522H80.4909L74.6095 17.8325H77.7333L81.2905 8.69159ZM30.4243 17.8325V3.51522H35.11C39.1092 3.51522 41.4819 5.89396 41.4819 9.02072C41.4819 12.1924 39.1092 14.5412 35.11 14.5412H33.2372V17.8325H30.4243ZM33.2372 6.17822V11.8857H35.3413C37.1543 11.8857 38.8033 11.0479 38.8033 9.02072C38.8033 7.09828 37.1543 6.17822 35.3413 6.17822H33.2372Z" fill="#040404"/>
					<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6045 13.7886L11.9121 11.5169C12.0205 10.7166 12.0802 9.85242 12.0802 8.94829C12.0802 6.66237 11.6994 4.68189 11.1547 3.34396C10.8803 2.67016 10.602 2.25211 10.3911 2.03299C10.3781 2.0195 10.3659 2.00725 10.3545 1.99614C10.343 2.00725 10.3308 2.0195 10.3178 2.03299C10.1069 2.25211 9.82857 2.67016 9.55424 3.34396C9.00951 4.68189 8.62871 6.66237 8.62871 8.94829C8.62871 10.5407 8.8138 11.9996 9.11826 13.2006L9.47559 14.61L8.21478 15.3344C4.44758 17.4987 1.91749 21.5579 1.91749 26.2057C1.91749 33.1245 7.52629 38.7333 14.4451 38.7333C15.1189 38.7333 15.7789 38.6803 16.4217 38.5786L18.8956 38.187L21.0292 36.8659C24.6006 34.6547 26.9727 30.7064 26.9727 26.2057C26.9727 21.5579 24.4426 17.4987 20.6754 15.3344L19.4146 14.61L19.772 13.2006C20.0764 11.9996 20.2615 10.5407 20.2615 8.94829C20.2615 6.66237 19.8807 4.68189 19.336 3.34396C19.0616 2.67016 18.7833 2.25211 18.5724 2.03299C18.5594 2.0195 18.5472 2.00725 18.5358 1.99614C18.5243 2.00725 18.5121 2.0195 18.4991 2.03299C18.2882 2.25211 18.0099 2.67016 17.7355 3.34396C17.1908 4.68189 16.81 6.66237 16.81 8.94829C16.81 9.85242 16.8697 10.7166 16.9781 11.5169L17.2857 13.7887L14.9953 13.6899C14.8131 13.6821 14.6297 13.6781 14.4451 13.6781C14.3633 13.6781 14.2816 13.6789 14.1999 13.6804C14.098 13.6824 13.9962 13.6856 13.8948 13.6899L11.6045 13.7886ZM15.43 4.26793C16.071 1.70741 17.2222 0 18.5358 0C20.5479 0 22.179 4.00629 22.179 8.94829C22.179 10.6822 21.9782 12.3008 21.6306 13.6718C25.9687 16.1641 28.8902 20.8437 28.8902 26.2057C28.8902 30.6638 26.8707 34.6501 23.6967 37.2999C23.174 37.7362 22.62 38.1363 22.0386 38.4962C22.6228 38.8198 23.1112 39.2952 23.4505 39.8691C23.7797 40.426 23.9686 41.0756 23.9686 41.7694C23.9686 43.8344 22.2946 45.5085 20.2295 45.5085C18.3913 45.5085 16.863 44.182 16.5493 42.4341C16.5106 42.2184 16.4904 41.9962 16.4904 41.7694C16.4904 41.3134 16.572 40.8765 16.7215 40.4725C16.0564 40.5778 15.3763 40.6377 14.6843 40.6489C14.6047 40.6502 14.525 40.6508 14.4451 40.6508C6.46729 40.6508 0 34.1835 0 26.2057C0 20.8437 2.92151 16.1641 7.25957 13.6718C6.91201 12.3008 6.71122 10.6822 6.71122 8.94829C6.71122 4.00629 8.34235 0 10.3545 0C11.668 0 12.8192 1.70741 13.4602 4.26793C13.8011 5.62961 13.9977 7.23256 13.9977 8.94829C13.9977 9.25252 13.9915 9.55321 13.9794 9.84963C13.9523 10.5154 13.8955 11.1596 13.8123 11.7742C13.9289 11.7692 14.0459 11.7655 14.1632 11.7633C14.257 11.7615 14.3509 11.7606 14.4451 11.7606C14.6571 11.7606 14.8681 11.7652 15.0779 11.7742C14.9947 11.1596 14.9379 10.5154 14.9108 9.84963C14.8987 9.55321 14.8925 9.25252 14.8925 8.94829C14.8925 7.23256 15.0891 5.62961 15.43 4.26793ZM19.2561 39.147L18.5199 41.1376C18.448 41.3319 18.4079 41.5438 18.4079 41.7694C18.4079 42.7754 19.2235 43.591 20.2295 43.591C21.2356 43.591 22.0512 42.7754 22.0512 41.7694C22.0512 41.0856 21.6752 40.4869 21.1095 40.1736L19.2561 39.147Z" fill="black"/>
				</svg>
			</a>
			<?php 
			pizzaro_site_title_or_logo(); 
			?>
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
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link baur1" rel="home">';
			pizzaro_get_template( 'global/logo-svg.php' );
			echo '</a>';
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link baur2" rel="home">';
			?>
			<h1 class="site-title">
				<?php 
				bloginfo( 'name' ); 
				?>
			</h1>
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
			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'pizzaro' ); ?>">
				<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span class="close-icon"><i class="po po-close-delete"></i></span><span class="menu-icon"><i class="po po-menu-icon"></i></span><span class=" screen-reader-text"><?php echo esc_attr( apply_filters( 'pizzaro_menu_toggle_text', esc_html__( 'Menu', 'pizzaro' ) ) ); ?></span></button>

				<div class="primary-navigation">
					<?php
					wp_nav_menu( apply_filters( 'pizzaro_main_menu_args', array(
						'theme_location'	=> 'main_menu',
						'container'			=> false,
						'fallback_cb'		=> 'pizzaro_nav_menu_fallback',
					) ) );
					?>
				</div>

				<div class="handheld-navigation">
				
					<?php
					wp_nav_menu( apply_filters( 'pizzaro_handheld_menu_args', array(
						'theme_location'	=> 'handheld',
						'container'			=> false,
						'fallback_cb'		=> 'pizzaro_nav_menu_fallback',
						'items_wrap'		=> '<span class="phm-close">' . apply_filters( 'pizzaro_handheld_menu_close_button_text', esc_html__( 'Close', 'pizzaro' ) ) . '</span><form role="search" method="get" class="woocommerce-product-search" action="https://xn--80aaahhb8btheqsn2p.xn--p1ai/"><input type="search" id="woocommerce-product-search-field-1" class="search-field" placeholder="Поиск по меню…" value="" name="s"><button type="submit" value="Поиск"></button><input type="hidden" name="post_type" value="product"></form><ul id="%1$s" class="%2$s">%3$s</ul>'
					) ) );
					?>
				</div>

			</nav><!-- #site-navigation -->
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
			<nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'pizzaro' ); ?>">
				<?php
					wp_nav_menu( apply_filters( 'pizzaro_food_menu_args', array(
						'theme_location'	=> 'food_menu',
						'container'			=> false,
						'fallback_cb'		=> 'pizzaro_nav_menu_fallback',
					) ) );
				?>
			</nav><!-- #secondary-navigation -->
			<?php
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
			<div class="header-phone-numbers-wrap">
				<span class="intro-text"><?php echo esc_html( $header_phone_args['text'] ); ?></span>
				<select class="select-city-phone-numbers" name="city-phone-numbers" id="city-phone-numbers">
					<?php foreach ( $header_phone_args['phone_numbers'] as $key => $phone_number ) { ?>
						<option value="<?php echo esc_attr( $phone_number['number'] ); ?>"><?php echo esc_html( $phone_number['city'] ); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="header-phone-numbers-wrap"><span class="intro-text">с 10:00 до 22:00</span>
			<span id="city-phone-number-label" class="phone-number"></span></div>
			<div id="messicons" class="messicons">
			<a href="https://vk.com/rollkroll" class="vk" target="_blank"></a>
			<a href="https://www.instagram.com/rollkroll.ru/" class="insta" target="_blank"></a>
			
			
			<div class="userhead">
			<?php global $current_user; wp_get_current_user(); ?>

			<?php if(is_user_logged_in()) { 
			  echo '<a href="/my-account-2">' . $current_user->display_name . "</a>";
			
			?>
			
			<?php } else { ?>
		<?php echo do_shortcode('[xoo_el_action type="login" display="link" text="Войти" change_to="myaccount" redirect_to="same"]'); ?>
			<?php } ?>
			</div>
			</div>
			
		</div>
		
		<?php endif;
	}
}

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


if ( ! function_exists( 'pizzaro_header_info_flex_wrapper' ) ) {
	/**
	 * The info wrapper
	 */
	function pizzaro_header_info_flex_wrapper() {
		echo '<div class="header-info-flex-wrapper">';
	}
}

if ( ! function_exists( 'pizzaro_header_info_flex_wrapper_close' ) ) {
	/**
	 * The info wrapper close
	 */
	function pizzaro_header_info_flex_wrapper_close() {
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
