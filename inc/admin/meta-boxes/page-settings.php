<?php
/**
 * Page Settings Meta Box
 * 
 * @package PratikWp
 * @version 1.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Page Settings Meta Box handler
 */
class PratikWp_Page_Settings {

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
        $post_types = apply_filters('pratikwp_page_settings_post_types', ['page']);
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'pratikwp_page_settings',
                __('PratikWp Sayfa Ayarları', 'pratikwp'),
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
        wp_nonce_field('pratikwp_page_settings_nonce', 'pratikwp_page_settings_nonce');

        // Get current values
        $page_layout = get_post_meta($post->ID, '_pratikwp_page_layout', true);
        $sidebar_position = get_post_meta($post->ID, '_pratikwp_sidebar_position', true);
        $header_transparent = get_post_meta($post->ID, '_pratikwp_header_transparent', true);
        $hide_page_title = get_post_meta($post->ID, '_pratikwp_hide_page_title', true);
        $custom_body_class = get_post_meta($post->ID, '_pratikwp_custom_body_class', true);
        ?>
        <div class="pratikwp-meta-box">
            <div class="meta-box-section">
                <label for="pratikwp_page_layout" class="meta-label">
                    <strong><?php esc_html_e('Sayfa Düzeni', 'pratikwp'); ?></strong>
                </label>
                <select name="pratikwp_page_layout" id="pratikwp_page_layout" class="widefat">
                    <option value=""><?php esc_html_e('Varsayılan (Tema Ayarından)', 'pratikwp'); ?></option>
                    <option value="full-width" <?php selected($page_layout, 'full-width'); ?>><?php esc_html_e('Tam Genişlik', 'pratikwp'); ?></option>
                    <option value="contained" <?php selected($page_layout, 'contained'); ?>><?php esc_html_e('Container İçinde', 'pratikwp'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Bu sayfanın ana düzenini belirler', 'pratikwp'); ?></p>
            </div>

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
                <p class="description"><?php esc_html_e('Sidebar\'ın görüntüleneceği konum', 'pratikwp'); ?></p>
            </div>

            <div class="meta-box-section">
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_header_transparent" value="1" <?php checked($header_transparent, '1'); ?> />
                    <?php esc_html_e('Transparent Header', 'pratikwp'); ?>
                </label>
                <p class="description"><?php esc_html_e('Header\'ı sayfa içeriğinin üzerine yerleştirir', 'pratikwp'); ?></p>
            </div>

            <div class="meta-box-section">
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_page_title" value="1" <?php checked($hide_page_title, '1'); ?> />
                    <strong><?php esc_html_e('Sayfa Başlığını Gizle', 'pratikwp'); ?></strong>
                </label>
                <p class="description"><?php esc_html_e('Bu sayfada sayfa başlığını göstermez', 'pratikwp'); ?></p>
            </div>

            <div class="meta-box-section">
                <label for="pratikwp_custom_body_class" class="meta-label">
                    <strong><?php esc_html_e('Özel Body Class', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_custom_body_class" id="pratikwp_custom_body_class" 
                       value="<?php echo esc_attr($custom_body_class); ?>" class="widefat" 
                       placeholder="<?php esc_attr_e('custom-page special-layout', 'pratikwp'); ?>" />
                <p class="description"><?php esc_html_e('Bu sayfaya özel CSS sınıfları ekler', 'pratikwp'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['pratikwp_page_settings_nonce']) || 
            !wp_verify_nonce($_POST['pratikwp_page_settings_nonce'], 'pratikwp_page_settings_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $meta_fields = [
            '_pratikwp_page_layout' => 'sanitize_text_field',
            '_pratikwp_sidebar_position' => 'sanitize_text_field',
            '_pratikwp_header_transparent' => 'sanitize_text_field',
            '_pratikwp_hide_page_title' => 'sanitize_text_field',
            '_pratikwp_custom_body_class' => 'sanitize_text_field',
        ];

        foreach ($meta_fields as $meta_key => $sanitize_callback) {
            $post_key = str_replace('_pratikwp_', 'pratikwp_', $meta_key);
            
            if (isset($_POST[$post_key])) {
                $value = call_user_func($sanitize_callback, $_POST[$post_key]);
                update_post_meta($post_id, $meta_key, $value);
            } else {
                if (in_array($meta_key, ['_pratikwp_header_transparent', '_pratikwp_hide_page_title'])) {
                    delete_post_meta($post_id, $meta_key);
                }
            }
        }
    }
}