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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('İçeriğe geç', 'pratikwp'); ?></a>

        <?php
    // Elementor Header Location
    if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('header')) {
        // Elementor manages header
    } else {
        // Fallback header
        pratikwp_default_header();
    }
    ?>

        <div id="content" class="site-content">
            <?php
        // Page title area (only if not using Elementor)
        if (!is_front_page() && !function_exists('elementor_theme_do_location')) {
            pratikwp_page_title();
        }
        ?>

            <div class="container">
                <?php
            // Breadcrumbs (can be disabled via customizer)
            if (get_theme_mod('show_breadcrumbs', true) && !is_front_page()) {
                pratikwp_breadcrumbs();
            }
            ?>