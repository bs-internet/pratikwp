<?php
/**
 * Header Template
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    
    <?php
    // Elementor'un header lokasyonu aktifse temanın header'ını gösterme.
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
        /**
         * pratikwp_header hook.
         *
         * @hooked pratikwp_header_content - 10
         */
        do_action('pratikwp_header');
    }
    ?>

    <div id="content" class="site-content">
        <div class="container">