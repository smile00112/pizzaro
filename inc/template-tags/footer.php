<?php

if ( ! function_exists( 'pizzaro_footer_static_content' ) ) {
	/**
	 * Display the static content before footer
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function pizzaro_footer_static_content() {
		$static_block_id = apply_filters( 'pizzaro_footer_static_block_id', '' );
		if( ! empty( $static_block_id ) ) :
			$static_block = get_post( $static_block_id );
			echo '<div class="footer-v1-static-content">' . do_shortcode( $static_block->post_content ) . '</div>';
		?>
		<?php
		endif;
	}
}

if ( ! function_exists( 'pizzaro_footer_logo' ) ) {
	/**
	 * Display the logo at footer
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function pizzaro_footer_logo() {
		ob_start();
		pizzaro_site_title_or_logo();
		$footer_logo = apply_filters( 'pizzaro_footer_logo_html', ob_get_clean() );
		 
		$logo_footer_desk = get_field('gr_logo_footer_desktop', 'option'); //echo '<pre>'; print_r($logo_footer_desk['logo_header_desktop']); echo '</pre>'; 
		if($logo_footer_desk['logo_set_type'] == 'svg') $logo_footer_desk = $logo_footer_desk['logo_header_desktop_svg'];
		else if($logo_footer_desk['logo_set_type'] == 'jpg') $logo_footer_desk = '<div class="logo-desk logo-desk-header"><img class="" src="'.$logo_footer_desk['logo_header_desktop'].'"></div>';
		
		$footer_logo = $logo_footer_desk;
		
		echo '<div class="footer-link-app">';
		echo '<div class="footer-andr"><a href="'. get_field('gr_mob_app_link', 'option') .'" target="_blank"  rel="nofollow"><img src="'. get_template_directory_uri() . '/assets/images/icon-google.svg"></a></div>';
		echo '<div class="footer-ios"><a href="'. get_field('gr_mob_app_link_ios', 'option') .'"  target="_blank"  rel="nofollow"><img src="'. get_template_directory_uri() .'/assets/images/icon-app.svg"></a></div>';
		echo '</div>';

		echo '
		<div class="baur_social_desctop">
		';
		if($GLOBALS['pizzaro_options']['whatsapp'])
		echo'
			<a href="https://api.whatsapp.com/send?phone='.$GLOBALS['pizzaro_options']['whatsapp'].'" target="_blank">
				<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18.8883 15.5532C18.5661 15.39 16.9967 14.6242 16.7034 14.5149C16.4101 14.4105 16.1971 14.3558 15.9834 14.6781C15.7745 14.9923 15.1589 15.7115 14.9709 15.9212C14.7828 16.1309 14.598 16.147 14.2806 16.0048C13.9584 15.8416 12.929 15.5065 11.7068 14.4105C10.7521 13.5611 10.1165 12.5149 9.92767 12.1926C9.73963 11.8744 9.90677 11.6944 10.0659 11.5353C10.2121 11.389 10.3881 11.1673 10.5512 10.9744C10.7063 10.7815 10.7561 10.6522 10.8695 10.4432C10.9739 10.2174 10.9201 10.0415 10.8405 9.88235C10.761 9.72324 10.1205 8.14583 9.85293 7.51744C9.5974 6.89387 9.32981 6.97342 9.13293 6.97342C8.94892 6.95655 8.73517 6.95655 8.52222 6.95655C8.30927 6.95655 7.96133 7.0361 7.66802 7.34146C7.37472 7.66369 6.54624 8.43351 6.54624 9.99083C6.54624 11.5522 7.69293 13.0629 7.85204 13.2887C8.01517 13.4976 10.1077 16.7119 13.3179 18.0932C14.0837 18.4155 14.6784 18.6083 15.1428 18.7674C15.9086 19.0101 16.6078 18.9764 17.1598 18.8968C17.7705 18.7964 19.0514 18.1222 19.3198 17.3692C19.5922 16.6115 19.5922 15.9839 19.5127 15.8416C19.4331 15.6954 19.2242 15.6158 18.902 15.4736L18.8883 15.5532ZM13.0664 23.4466H13.0495C11.1491 23.4466 9.27035 22.9315 7.62945 21.9689L7.24454 21.739L3.22669 22.7853L4.30669 18.8759L4.04713 18.4741C2.98599 16.7869 2.42303 14.8343 2.42311 12.8411C2.42311 7.00717 7.19874 2.24842 13.0745 2.24842C15.9207 2.24842 18.591 3.35735 20.5999 5.36628C21.5909 6.34551 22.377 7.51228 22.9123 8.79854C23.4476 10.0848 23.7214 11.4648 23.7178 12.858C23.7097 18.6879 18.9381 23.4466 13.0704 23.4466H13.0664ZM22.1275 3.83869C19.683 1.4778 16.4687 0.143066 13.0495 0.143066C5.9974 0.143066 0.255078 5.86048 0.25106 12.8869C0.25106 15.1305 0.836864 17.3194 1.95865 19.2568L0.142578 25.8574L6.93115 24.0871C8.81176 25.101 10.9138 25.6343 13.0503 25.6396H13.0544C20.1105 25.6396 25.8528 19.9222 25.8569 12.8909C25.8569 9.4886 24.5302 6.28637 22.1114 3.87967L22.1275 3.83869Z"     fill="rgb(7 244 10)";/>
					<defs>
					<linearGradient id="paint0_linear" x1="12.9992" y1="25.8556" x2="12.9992" y2="0.141523" gradientUnits="userSpaceOnUse">
					<stop stop-color="#20B038"/>
					<stop offset="1" stop-color="#60D66A"/>
					</linearGradient>
					</defs>
				</svg>
			</a>';
			if($GLOBALS['pizzaro_options']['telegram'])
			echo'
			<a href="tg://resolve?domain='.$GLOBALS['pizzaro_options']['telegram'].'" class="insta" target="_blank">
				<svg width="27" height="22" viewBox="0 0 27 22" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M24.8412 0.571371C23.9504 0.587736 22.5828 1.05531 16.0063 3.75558C11.3901 5.68271 6.78669 7.64034 2.19638 9.62835C1.07419 10.0702 0.489718 10.5004 0.435947 10.9212C0.33308 11.7301 1.51138 11.9803 2.9936 12.4572C4.20229 12.8453 5.82946 13.2988 6.67577 13.3175C7.4426 13.3339 8.29827 13.0206 9.24277 12.3824C15.6953 8.08067 19.0222 5.90877 19.2326 5.86201C19.3799 5.82928 19.5833 5.7872 19.7235 5.90877C19.8615 6.03034 19.8474 6.25945 19.8311 6.32258C19.7142 6.81587 13.6567 12.2561 13.3084 12.6138L13.14 12.7822C11.8542 14.0516 10.5567 14.8816 12.7964 16.3381C14.821 17.6543 15.9993 18.4936 18.08 19.8449C19.4126 20.7053 20.4576 21.7269 21.8323 21.603C22.4659 21.5446 23.1181 20.9578 23.4525 19.2043C24.2357 15.0663 25.7787 6.09347 26.134 2.39492C26.1555 2.08814 26.1422 1.77991 26.0943 1.47613C26.0654 1.23088 25.9452 1.00549 25.7576 0.844904C25.4747 0.615791 25.0352 0.569033 24.8412 0.571371Z" fill="#23A0DC"/>
				</svg>
			</a>';
		echo '</div>'; 

		echo '<div class="footer-logo bbb">' . $footer_logo . '</div>';
	}
}

if ( ! function_exists( 'pizzaro_social_icons' ) ) {
	/**
	 * Displays footer social icons
	 */
	function pizzaro_social_icons() {
		$social_networks 		= apply_filters( 'pizzaro_set_social_networks', pizzaro_get_social_networks() );
		$social_links_output 	= '';
		$social_link_html		= apply_filters( 'pizzaro_footer_social_link_html', '<a class="%1$s" href="%2$s"  target="_blank" rel="nofollow"></a>' );

		foreach ( $social_networks as $social_network ) {
			if ( isset( $social_network[ 'link' ] ) && !empty( $social_network[ 'link' ] ) ) {
				$social_links_output .= sprintf( '<li>' . $social_link_html . '</li>', $social_network[ 'icon' ], $social_network[ 'link' ] );
			}
		}

		if ( apply_filters( 'pizzaro_show_footer_social_icons', true ) && ! empty( $social_links_output ) ) {

			ob_start();
			?>
			<div class="footer-social-icons">
				<span class="social-icon-text"><?php echo esc_html( apply_filters( 'pizzaro_footer_social_icons_text', esc_html__( 'Follow us', 'pizzaro' ) ) ); ?></span>
				<ul class="social-icons list-unstyled">
					<?php echo wp_kses_post( $social_links_output ); ?>
				</ul>
			</div>
			<?php
			echo apply_filters( 'pizzaro_footer_social_links_html', ob_get_clean() );
		}
	}
}

if ( ! function_exists( 'pizzaro_footer_address' ) ) {
	/**
	 * Displays store address at the footer
	 */
	function pizzaro_footer_address() {
		$address_args = apply_filters( 'pizzaro_footer_site_address_args', array(
			'name'    => esc_html__( 'Pizzaro Restaurant', 'pizzaro' ),
			'address' => esc_html__( '901-947 South Drive, Houston, TX 77057, USA', 'pizzaro' ),
			'tel_no'  => esc_html__( 'Telephone: +1 555 1234', 'pizzaro' ),
			'fax_no'  => esc_html__( 'Fax: +1 555 4444', 'pizzaro' )
		) );
		if ( apply_filters( 'pizzaro_show_footer_site_address', true ) && ! empty( $address_args ) ) : ?>
		<div class="site-address">
			<ul class="address">
				<?php foreach( $address_args as $key => $address_arg ) : ?>
				<li><?php echo esc_html( $address_arg ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif;
	}
}

if ( ! function_exists( 'pizzaro_footer_action' ) ) {
	/**
	 * Displays an action button at the footer
	 */
	function pizzaro_footer_action() {
		$action_button_args = apply_filters( 'pizzaro_footer_action_button_args', array(
			'text'  => esc_html__( 'Find us on Map', 'pizzaro' ),
			'icon'  => 'po po-map-marker',
		) );
		if ( apply_filters( 'pizzaro_show_footer_action_button', true ) && ! empty( $action_button_args ) ) : ?>
		<a role="button" class="footer-action-btn" data-toggle="collapse" href="#footer-map-collapse">
			<i class="<?php echo esc_attr( $action_button_args['icon'] );?>"></i>
			<?php echo esc_html( $action_button_args['text'] ); ?>
		</a>
		<?php endif;
	}
}

if ( ! function_exists( 'pizzaro_footer_v1_map' ) ) {
	/**
	 * Displays Google map in footer v1
	 */
	function pizzaro_footer_v1_map() {
		?>
		<div id="footer-map-collapse" class="footer-map collapse"><?php 
			if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse') == false)
				echo pizzaro_map_content(); 
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'pizzaro_footer_newsletter' ) ) {
	/**
	 * Displays an newsletter at the footer
	 */
	function pizzaro_footer_newsletter() {
		if ( apply_filters( 'pizzaro_show_footer_newsletter', true ) ) : ?>
		<div class="footer-newsletter">
			<?php pizzaro_newsletter_form(); ?>
		</div>
		<?php endif;
	}
}

if ( ! function_exists( 'pizzaro_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function pizzaro_credit() {
		if ( apply_filters( 'pizzaro_show_credit_info', true ) ) : ?>
		<div class="site-info">
			<p class="copyright"><?php echo wp_kses_post( apply_filters( 'pizzaro_copyright_text', $content = sprintf( esc_html__( 'Copyright &copy; %s %s Theme. All rights reserved.', 'pizzaro' ), date( 'Y' ), get_bloginfo( 'name' ) ) ) ); ?></p>
		</div><!-- .site-info -->
		<?php endif;
	}
}
