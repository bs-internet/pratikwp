<?php
/**
 * Sidebar template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Don't show sidebar if not active
if (!is_active_sidebar('main-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar col-md-4">
    <?php dynamic_sidebar('main-sidebar'); ?>
</aside><!-- #secondary -->