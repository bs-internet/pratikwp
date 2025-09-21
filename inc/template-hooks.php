<?php
/**
 * Theme Hooks
 * Defines all hooks for the theme and connects them to the corresponding template functions.
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// =============================================================================
// Header Hooks
// =============================================================================
add_action('pratikwp_header', 'pratikwp_header_content');

// =============================================================================
// Footer Hooks
// =============================================================================
add_action('pratikwp_footer', 'pratikwp_footer_content');

// =============================================================================
// Post Hooks
// =============================================================================
add_action('pratikwp_post_header', 'pratikwp_post_header_content');
add_action('pratikwp_post_content', 'pratikwp_post_content_body');
add_action('pratikwp_post_footer', 'pratikwp_post_footer_content');

// =============================================================================
// Page Hooks
// =============================================================================
add_action('pratikwp_page_header', 'pratikwp_page_header_content');
add_action('pratikwp_page_content', 'pratikwp_page_content_body');