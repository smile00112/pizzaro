<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package pizzaro
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">

					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Страница не найдена.', 'pizzaro' ); ?></h1>
					</header><!-- .page-header -->

					<p>Ничего не нашли? Посмотрите товары на <a href="/" class="link-custom-simple">Главной странице</a></p>

					<?php /*
					echo '<section aria-label="Search">';

					if ( is_woocommerce_activated() ) {
						the_widget( 'WC_Widget_Product_Search' );
					} else {
						get_search_form();
					}

					echo '</section>';

					if ( is_woocommerce_activated() ) {

						echo '<div class="fourohfour-columns-2">';

							echo '<section class="col-1" aria-label="Promoted Products">';

								pizzaro_promoted_products();

							echo '</section>';

							echo '<nav class="col-2" aria-label="Product Categories">';

							echo '<h2>' . esc_html__( 'Product Categories', 'pizzaro' ) . '</h2>';

							the_widget( 'WC_Widget_Product_Categories', array(
																			'count'		=> 1,
							) );
							echo '</nav>';

							echo '</div>';

							echo '<section aria-label="Popular Products" >';

							echo '<h2>' . esc_html__( 'Popular Products', 'pizzaro' ) . '</h2>';

							echo pizzaro_do_shortcode( 'best_selling_products', array(
								'per_page'  => 4,
								'columns'   => 4,
							) );

							echo '</section>';
					}
					*/ ?> 

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();
