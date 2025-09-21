<?php
/**
 * Post Settings Meta Box
 * 
 * @package PratikWp
 * @version 1.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Post Settings Meta Box handler
 */
class PratikWp_Post_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        $post_types = apply_filters('pratikwp_post_settings_post_types', ['post']);
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'pratikwp_post_settings',
                __('PratikWp Yazı Ayarları', 'pratikwp'),
                [$this, 'render_meta_box'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render meta box
     */
    public function render_meta_box($post) {
        // Add nonce field
        wp_nonce_field('pratikwp_post_settings_nonce', 'pratikwp_post_settings_nonce');

        // Get current values
        $sidebar_position = get_post_meta($post->ID, '_pratikwp_sidebar_position', true);
        $hide_featured_image = get_post_meta($post->ID, '_pratikwp_hide_featured_image', true);
        ?>
        <div class="pratikwp-meta-box">
            <div class="meta-box-section">
                <label for="pratikwp_sidebar_position" class="meta-label">
                    <strong><?php esc_html_e('Sidebar Konumu', 'pratikwp'); ?></strong>
                </label>
                <select name="pratikwp_sidebar_position" id="pratikwp_sidebar_position" class="widefat">
                    <option value=""><?php esc_html_e('Varsayılan (Tema Ayarından)', 'pratikwp'); ?></option>
                    <option value="left" <?php selected($sidebar_position, 'left'); ?>><?php esc_html_e('Sol Taraf', 'pratikwp'); ?></option>
                    <option value="right" <?php selected($sidebar_position, 'right'); ?>><?php esc_html_e('Sağ Taraf', 'pratikwp'); ?></option>
                    <option value="none" <?php selected($sidebar_position, 'none'); ?>><?php esc_html_e('Sidebar Yok', 'pratikwp'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Yazı görüntülenirken sidebar pozisyonu', 'pratikwp'); ?></p>
            </div>

            <div class="meta-box-section">
                <label class="meta-label">
                    <strong><?php esc_html_e('Görüntüleme Seçenekleri', 'pratikwp'); ?></strong>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_featured_image" value="1" <?php checked($hide_featured_image, '1'); ?> />
                    <?php esc_html_e('Öne Çıkan Görseli Gizle', 'pratikwp'); ?>
                </label>
                 <p class="description"><?php esc_html_e('Yazı sayfasında öne çıkan görseli gizler.', 'pratikwp'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['pratikwp_post_settings_nonce']) || 
            !wp_verify_nonce($_POST['pratikwp_post_settings_nonce'], 'pratikwp_post_settings_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Save meta fields
        $meta_fields = [
            '_pratikwp_sidebar_position' => 'sanitize_text_field',
            '_pratikwp_hide_featured_image' => 'sanitize_text_field',
        ];

        foreach ($meta_fields as $meta_key => $sanitize_callback) {
            $post_key = str_replace('_pratikwp_', 'pratikwp_', $meta_key);
            
            if (isset($_POST[$post_key])) {
                $value = call_user_func($sanitize_callback, $_POST[$post_key]);
                update_post_meta($post_id, $meta_key, $value);
            } else {
                if (in_array($meta_key, ['_pratikwp_hide_featured_image'])) {
                    delete_post_meta($post_id, $meta_key);
                }
            }
        }
    }
}