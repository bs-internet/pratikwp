<?php
/**
 * Admin Interface Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Admin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_pages']);
        add_action('admin_init', [$this, 'init_admin']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_notices', [$this, 'admin_notices']);
        add_action('admin_bar_menu', [$this, 'add_admin_bar_menu'], 999);
        
        // Meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        
        // AJAX handlers
        add_action('wp_ajax_pratikwp_dismiss_notice', [$this, 'dismiss_notice']);
        
        // Theme activation/deactivation
        add_action('after_switch_theme', [$this, 'theme_activation']);
        add_action('switch_theme', [$this, 'theme_deactivation']);
    }

    /**
     * Add admin pages
     */
    public function add_admin_pages() {
        // Main dashboard page
        add_menu_page(
            __('PratikWp Dashboard', 'pratikwp'),
            __('PratikWp', 'pratikwp'),
            'manage_options',
            'pratikwp-dashboard',
            [$this, 'dashboard_page'],
            'dashicons-admin-appearance',
            3
        );
        
        // System info page
        add_submenu_page(
            'pratikwp-dashboard',
            __('Sistem Bilgileri', 'pratikwp'),
            __('Sistem Bilgileri', 'pratikwp'),
            'manage_options',
            'pratikwp-system-info',
            [$this, 'system_info_page']
        );
        
        // Demo import page
        add_submenu_page(
            'pratikwp-dashboard',
            __('Demo İçe Aktarma', 'pratikwp'),
            __('Demo İçe Aktarma', 'pratikwp'),
            'manage_options',
            'pratikwp-demo-import',
            [$this, 'demo_import_page']
        );
    }

    /**
     * Initialize admin
     */
    public function init_admin() {
        // Register settings
        register_setting('pratikwp_admin_options', 'pratikwp_dashboard_settings');
        
        // Setup admin notices
        $this->setup_admin_notices();
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on PratikWp admin pages
        if (strpos($hook, 'pratikwp') === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_style(
            'pratikwp-admin-style',
            PRATIKWP_ASSETS . '/css/admin.css',
            ['wp-color-picker'],
            PRATIKWP_VERSION
        );
        
        wp_enqueue_script(
            'pratikwp-admin-script',
            PRATIKWP_ASSETS . '/js/admin.js',
            ['jquery', 'wp-color-picker', 'media-upload'],
            PRATIKWP_VERSION,
            true
        );
        
        wp_localize_script('pratikwp-admin-script', 'pratikwpAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pratikwp_admin_nonce'),
            'strings' => [
                'confirm_demo_import' => __('Demo içeriği mevcut içeriğin üzerine yazacak. Devam etmek istediğinizden emin misiniz?', 'pratikwp'),
                'importing' => __('İçe aktarılıyor...', 'pratikwp'),
                'import_success' => __('Demo içeriği başarıyla içe aktarıldı!', 'pratikwp'),
                'import_error' => __('İçe aktarma sırasında bir hata oluştu.', 'pratikwp'),
                'copying_text' => __('Kopyalanıyor...', 'pratikwp'),
                'copied_text' => __('Kopyalandı!', 'pratikwp'),
            ]
        ]);
    }

    /**
     * Dashboard page
     */
    public function dashboard_page() {
        ?>
<div class="wrap pratikwp-admin-page">
    <div class="pratikwp-admin-header">
        <div class="pratikwp-admin-header-content">
            <h1><?php esc_html_e('PratikWp Dashboard', 'pratikwp'); ?></h1>
            <p class="description"><?php esc_html_e('Tema yönetimi ve hızlı erişim paneli', 'pratikwp'); ?></p>
        </div>
        <div class="pratikwp-admin-header-actions">
            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                <?php esc_html_e('Temayı Özelleştir', 'pratikwp'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=pratikwp-settings'); ?>" class="button">
                <?php esc_html_e('Tema Ayarları', 'pratikwp'); ?>
            </a>
        </div>
    </div>

    <div class="pratikwp-dashboard-grid">
        <!-- Theme Info -->
        <div class="pratikwp-dashboard-card">
            <h3><?php esc_html_e('Tema Bilgileri', 'pratikwp'); ?></h3>
            <div class="theme-info">
                <div class="info-row">
                    <span class="info-label"><?php esc_html_e('Sürüm:', 'pratikwp'); ?></span>
                    <span class="info-value"><?php echo PRATIKWP_VERSION; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?php esc_html_e('WordPress Sürümü:', 'pratikwp'); ?></span>
                    <span class="info-value"><?php echo get_bloginfo('version'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?php esc_html_e('PHP Sürümü:', 'pratikwp'); ?></span>
                    <span class="info-value"><?php echo PHP_VERSION; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?php esc_html_e('Elementor:', 'pratikwp'); ?></span>
                    <span class="info-value">
                        <?php echo class_exists('\Elementor\Plugin') ? '✅ ' . __('Aktif', 'pratikwp') : '❌ ' . __('Pasif', 'pratikwp'); ?>
                    </span>
                </div>
            </div>

            <div class="theme-actions mt-3">
                <a href="<?php echo admin_url('admin.php?page=pratikwp-system-info'); ?>" class="button">
                    <?php esc_html_e('Detaylı Sistem Bilgileri', 'pratikwp'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
    }

    /**
     * System info page
     */
    public function system_info_page() {
        ?>
<div class="wrap pratikwp-admin-page">
    <h1><?php esc_html_e('Sistem Bilgileri', 'pratikwp'); ?></h1>

    <div class="pratikwp-system-info">
        <p><?php esc_html_e('Sistem bilgileri sayfası kaldırılmıştır.', 'pratikwp'); ?></p>
    </div>
</div>
<?php
    }

    /**
     * Demo import page
     */
    public function demo_import_page() {
        ?>
<div class="wrap pratikwp-admin-page">
    <h1><?php esc_html_e('Demo İçe Aktarma', 'pratikwp'); ?></h1>

    <div class="pratikwp-demo-import">
        <div class="demo-import-warning">
            <h3><?php esc_html_e('⚠️ Önemli Uyarı', 'pratikwp'); ?></h3>
            <p><?php esc_html_e('Demo içeriği içe aktarmak mevcut içeriğinizin üzerine yazacaktır. Bu işlem geri alınamaz. Lütfen devam etmeden önce sitenizin yedeğini alın.', 'pratikwp'); ?>
            </p>
        </div>

        <div class="demo-previews">
            <div class="demo-preview">
                <div class="demo-screenshot">
                    <img src="<?php echo PRATIKWP_ASSETS . '/images/demo-preview.jpg'; ?>" alt="Demo Preview" />
                </div>
                <div class="demo-info">
                    <h3><?php esc_html_e('Varsayılan Demo', 'pratikwp'); ?></h3>
                    <p><?php esc_html_e('İş ve kurumsal siteler için hazırlanmış demo içerik.', 'pratikwp'); ?></p>
                    <div class="demo-includes">
                        <ul>
                            <li><?php esc_html_e('✅ Ana sayfa tasarımı', 'pratikwp'); ?></li>
                            <li><?php esc_html_e('✅ Blog sayfaları', 'pratikwp'); ?></li>
                            <li><?php esc_html_e('✅ İletişim sayfası', 'pratikwp'); ?></li>
                            <li><?php esc_html_e('✅ Hakkımızda sayfası', 'pratikwp'); ?></li>
                            <li><?php esc_html_e('✅ Menü yapısı', 'pratikwp'); ?></li>
                            <li><?php esc_html_e('✅ Widget ayarları', 'pratikwp'); ?></li>
                        </ul>
                    </div>
                    <button type="button" class="button button-primary demo-import-btn" data-demo="default">
                        <?php esc_html_e('Demo İçe Aktar', 'pratikwp'); ?>
                    </button>
                </div>
            </div>
        </div>

        <div id="import-progress" class="import-progress" style="display: none;">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text"><?php esc_html_e('İçe aktarılıyor...', 'pratikwp'); ?></div>
        </div>
    </div>
</div>
<?php
    }



    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'pratikwp_page_settings',
            __('PratikWp Sayfa Ayarları', 'pratikwp'),
            [$this, 'page_settings_meta_box'],
            ['page', 'post'],
            'side',
            'default'
        );
    }

    /**
     * Page settings meta box
     */
    public function page_settings_meta_box($post) {
        wp_nonce_field('pratikwp_page_settings', 'pratikwp_page_settings_nonce');
        
        $sidebar = get_post_meta($post->ID, '_pratikwp_sidebar', true);
        $disable_title = get_post_meta($post->ID, '_pratikwp_disable_title', true);
        $meta_description = get_post_meta($post->ID, '_pratikwp_meta_description', true);
        ?>
<table class="form-table">
    <tr>
        <th><label for="pratikwp_sidebar"><?php esc_html_e('Sidebar', 'pratikwp'); ?></label></th>
        <td>
            <select name="pratikwp_sidebar" id="pratikwp_sidebar">
                <option value=""><?php esc_html_e('Varsayılan', 'pratikwp'); ?></option>
                <option value="show" <?php selected($sidebar, 'show'); ?>><?php esc_html_e('Göster', 'pratikwp'); ?>
                </option>
                <option value="none" <?php selected($sidebar, 'none'); ?>><?php esc_html_e('Gizle', 'pratikwp'); ?>
                </option>
            </select>
        </td>
    </tr>
    <tr>
        <th><label for="pratikwp_disable_title"><?php esc_html_e('Sayfa Başlığı', 'pratikwp'); ?></label></th>
        <td>
            <label>
                <input type="checkbox" name="pratikwp_disable_title" id="pratikwp_disable_title" value="1"
                    <?php checked($disable_title, '1'); ?> />
                <?php esc_html_e('Sayfa başlığını gizle', 'pratikwp'); ?>
            </label>
        </td>
    </tr>
    <tr>
        <th><label for="pratikwp_meta_description"><?php esc_html_e('Meta Açıklama', 'pratikwp'); ?></label></th>
        <td>
            <textarea name="pratikwp_meta_description" id="pratikwp_meta_description" rows="3"
                class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
            <p class="description"><?php esc_html_e('SEO için sayfa açıklaması (160 karakter önerilir)', 'pratikwp'); ?>
            </p>
        </td>
    </tr>
</table>
<?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['pratikwp_page_settings_nonce']) || !wp_verify_nonce($_POST['pratikwp_page_settings_nonce'], 'pratikwp_page_settings')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save sidebar setting
        if (isset($_POST['pratikwp_sidebar'])) {
            update_post_meta($post_id, '_pratikwp_sidebar', sanitize_text_field($_POST['pratikwp_sidebar']));
        }
        
        // Save disable title setting
        $disable_title = isset($_POST['pratikwp_disable_title']) ? '1' : '0';
        update_post_meta($post_id, '_pratikwp_disable_title', $disable_title);
        
        // Save meta description
        if (isset($_POST['pratikwp_meta_description'])) {
            update_post_meta($post_id, '_pratikwp_meta_description', sanitize_textarea_field($_POST['pratikwp_meta_description']));
        }
    }

    /**
     * Add admin bar menu
     */
    public function add_admin_bar_menu($wp_admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $wp_admin_bar->add_menu([
            'id' => 'pratikwp-menu',
            'title' => __('PratikWp', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-dashboard'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-dashboard',
            'title' => __('Dashboard', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-dashboard'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-customizer',
            'title' => __('Özelleştir', 'pratikwp'),
            'href' => admin_url('customize.php'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-settings',
            'title' => __('Tema Ayarları', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-settings'),
        ]);
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        // Elementor recommendation
        if (!class_exists('\Elementor\Plugin') && current_user_can('install_plugins')) {
            ?>
<div class="notice notice-warning is-dismissible pratikwp-elementor-notice">
    <p>
        <strong><?php esc_html_e('Önerilen Eklenti', 'pratikwp'); ?></strong>
        <?php esc_html_e('PratikWp teması Elementor ile en iyi performansı gösterir.', 'pratikwp'); ?>
        <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>"
            class="button button-primary">
            <?php esc_html_e('Elementor\'u Yükle', 'pratikwp'); ?>
        </a>
    </p>
</div>
<?php
        }
    }



    /**
     * Setup admin notices
     */
    private function setup_admin_notices() {
        $dismissed_notices = get_option('pratikwp_dismissed_notices', []);
    }



    /**
     * Dismiss notice AJAX handler
     */
    public function dismiss_notice() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Yetkiniz yok.', 'pratikwp'));
        }
        
        $notice = sanitize_text_field($_POST['notice']);
        $dismissed_notices = get_option('pratikwp_dismissed_notices', []);
        
        if (!in_array($notice, $dismissed_notices)) {
            $dismissed_notices[] = $notice;
            update_option('pratikwp_dismissed_notices', $dismissed_notices);
        }
        
        wp_send_json_success();
    }





    /**
     * Theme activation
     */
    public function theme_activation() {
        update_option('pratikwp_show_welcome', false);
        delete_option('pratikwp_dismissed_notices');
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        delete_transient('pratikwp_welcome_redirect');
    }

    /**
     * Theme deactivation
     */
    public function theme_deactivation() {
        delete_transient('pratikwp_welcome_redirect');
    }
}