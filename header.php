<?php
/**
 * The template for displaying the header.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package pizzaro
 */

$header_version = pizzaro_get_header_version();

get_header( $header_version );

require_once( get_template_directory() . '/select-address-start/select-address-start.php' );
?>
