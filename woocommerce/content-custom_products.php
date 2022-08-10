<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
global $products;

if ( $products->have_posts() ) {
?>
  <div class="custom-products">
    <?php
        while ( $products->have_posts() ) : $products->the_post();
            wc_get_template_part( 'content', 'custom_product' );
        endwhile;
    ?>
  </div>
<?php
} else {
    echo __( 'No products found' );
}

wp_reset_postdata();

?>