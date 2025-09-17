<?php
/**
 * Search form template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="input-group">
        <input type="search" class="form-control search-field"
            placeholder="<?php esc_attr_e('Arama...', 'pratikwp'); ?>" value="<?php echo get_search_query(); ?>"
            name="s" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>" />
        <button class="btn btn-primary search-submit" type="submit"
            aria-label="<?php esc_attr_e('Ara', 'pratikwp'); ?>">
            <?php esc_html_e('Ara', 'pratikwp'); ?>
        </button>
    </div>
</form>