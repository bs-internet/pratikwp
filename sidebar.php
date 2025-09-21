<?php
/**
 * Sidebar Template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_active_sidebar('main-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar <?php echo esc_attr(pratikwp_sidebar_class()); ?>">
    
    <?php dynamic_sidebar('main-sidebar'); ?>
    
</aside><!-- #secondary -->