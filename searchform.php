<?php
/**
 * Search Form Template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$unique_id = wp_unique_id('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>" class="screen-reader-text">
        <?php esc_html_e('Arama:', 'pratikwp'); ?>
    </label>
    <input type="search" 
           id="<?php echo esc_attr($unique_id); ?>" 
           class="search-field" 
           placeholder="<?php esc_attr_e('Arama...', 'pratikwp'); ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" 
           required />
    <button type="submit" class="search-submit">
        <span class="screen-reader-text"><?php esc_html_e('Ara', 'pratikwp'); ?></span>
        <span class="search-icon">🔍</span>
    </button>
</form>