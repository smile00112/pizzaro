<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package pizzaro
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to pizzaro_page add_action
	 *
	 * @hooked pizzaro_page_header          - 10
	 * @hooked pizzaro_page_content         - 20
	 * @hooked pizzaro_init_structured_data - 30
	 */
	do_action( 'pizzaro_page' );
	?>
	<?php if(is_page(2037)) { ?>
	<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, attach: "*"});
</script>
<?php } ?>

</div><!-- #post-## -->
