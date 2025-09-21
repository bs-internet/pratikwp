<?php
/**
 * Admin Interface Class
 * 
 * @package PratikWp
 * @version 1.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Admin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_pages']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // Elementor bildirimi
        $this->init_elementor_notice();
    }

    /**
     * Elementor bildirimini başlat
     */
    private function init_elementor_notice() {
        // Bu dosyanın varlığını ve çağrıldığını varsayıyoruz.
        // require_once PRATIKWP_INC . '/notices/ElementorNotice.php';
        if (class_exists('PratikWp_ElementorNotice')) {
            new PratikWp_ElementorNotice();
        }
    }

    /**
     * Add admin pages
     */
    public function add_admin_pages() {
        // Ana dashboard sayfası
        add_menu_page(
            __('PratikWp Kontrol Paneli', 'pratikwp'),
            __('PratikWp', 'pratikwp'),
            'manage_options',
            'pratikwp-dashboard',
            [$this, 'dashboard_page'],
            'dashicons-admin-appearance',
            3
        );
        
        // Firma İletişim Bilgileri (Yeni Sınıftan Çağrılıyor)
        // Bu dosyanın functions.php içinde require edileceğini varsayıyoruz.
        // require_once PRATIKWP_INC . '/admin/CompanyInfo.php';
        if (class_exists('PratikWp_CompanyInfo')) {
            $company_info_page = new PratikWp_CompanyInfo();
            add_submenu_page(
                'pratikwp-dashboard',
                __('Firma İletişim Bilgileri', 'pratikwp'),
                __('Firma Bilgileri', 'pratikwp'),
                'manage_options',
                'pratikwp-company',
                [$company_info_page, 'render_page']
            );
        }
        
        // Sosyal Medya Hesapları (Yeni Sınıftan Çağrılacak - Sonraki Adım)
        // require_once PRATIKWP_INC . '/admin/SocialMedia.php';
        if (class_exists('PratikWp_SocialMedia')) {
            $social_media_page = new PratikWp_SocialMedia();
            add_submenu_page(
                'pratikwp-dashboard',
                __('Sosyal Medya Hesapları', 'pratikwp'),
                __('Sosyal Medya', 'pratikwp'),
                'manage_options',
                'pratikwp-social',
                [$social_media_page, 'render_page']
            );
        }
        
        // WhatsApp (Mevcut Sınıftan Çağrılıyor)
        if (class_exists('PratikWp_WhatsApp')) {
            add_submenu_page(
                'pratikwp-dashboard',
                __('WhatsApp', 'pratikwp'),
                __('WhatsApp', 'pratikwp'),
                'manage_options',
                'pratikwp-whatsapp',
                ['PratikWp_WhatsApp', 'whatsapp_settings_page']
            );
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'pratikwp') === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        
        wp_enqueue_style(
            'pratikwp-admin',
            PRATIKWP_ASSETS . '/css/admin.css',
            ['wp-color-picker'],
            PRATIKWP_VERSION
        );
        
        wp_enqueue_script(
            'pratikwp-admin',
            PRATIKWP_ASSETS . '/js/admin.js',
            ['jquery', 'wp-color-picker'],
            PRATIKWP_VERSION,
            true
        );
        
        wp_localize_script('pratikwp-admin', 'pratikwpAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pratikwp_admin_nonce'),
        ]);
    }

    /**
    * Dashboard page
    */
    public function dashboard_page() {
        $theme_version = PRATIKWP_VERSION;
        $wp_version = get_bloginfo('version');
        $php_version = PHP_VERSION;
        $is_elementor_active = defined('ELEMENTOR_VERSION');
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <div class="pratikwp-dashboard-header">
                <h1><?php esc_html_e('PratikWp Kontrol Paneli', 'pratikwp'); ?></h1>
                <p class="pratikwp-version-tag"><?php printf(__('Tema Sürümü: %s', 'pratikwp'), esc_html($theme_version)); ?></p>
            </div>

            <div class="pratikwp-card padding">
                <h2 class="card-title"><?php esc_html_e('Hızlı İşlemler', 'pratikwp'); ?></h2>
                <div class="pratikwp-grid pratikwp-grid-4">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pratikwp-company')); ?>" class="pratikwp-quick-action">
                        <span class="dashicons dashicons-building"></span>
                        <h4><?php esc_html_e('Firma Bilgileri', 'pratikwp'); ?></h4>
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pratikwp-social')); ?>" class="pratikwp-quick-action">
                        <span class="dashicons dashicons-share"></span>
                        <h4><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h4>
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pratikwp-whatsapp')); ?>" class="pratikwp-quick-action">
                        <span class="dashicons dashicons-whatsapp"></span>
                        <h4><?php esc_html_e('WhatsApp', 'pratikwp'); ?></h4>
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pratikwp-slider')); ?>" class="pratikwp-quick-action">
                        <span class="dashicons dashicons-images-alt2"></span>
                        <h4><?php esc_html_e('Slider Yönetimi', 'pratikwp'); ?></h4>
                    </a>                    
                </div>
            </div>
            
            <div class="pratikwp-grid pratikwp-grid-2">
                <div class="pratikwp-card padding">
                    <h3 class="card-title"><?php esc_html_e('Sistem Bilgileri', 'pratikwp'); ?></h3>
                    <ul class="pratikwp-info-list">
                        <li>
                            <strong><?php esc_html_e('WordPress Sürümü:', 'pratikwp'); ?></strong>
                            <span><?php echo esc_html($wp_version); ?></span>
                        </li>
                        <li>
                            <strong><?php esc_html_e('PHP Sürümü:', 'pratikwp'); ?></strong>
                            <span>
                                <?php echo esc_html($php_version); ?>
                                <?php if (version_compare($php_version, '8.0', '<')): ?>
                                    <span class="pratikwp-status-badge is-warning"><?php esc_html_e('Güncelleme Önerilir', 'pratikwp'); ?></span>
                                <?php else: ?>
                                    <span class="pratikwp-status-badge is-success">✓</span>
                                <?php endif; ?>
                            </span>
                        </li>
                        <li>
                            <strong><?php esc_html_e('Elementor Durumu:', 'pratikwp'); ?></strong>
                            <span>
                                <?php if ($is_elementor_active) : ?>
                                    <span class="pratikwp-status-badge is-success"><?php esc_html_e('Aktif', 'pratikwp'); ?></span>
                                <?php else : ?>
                                    <span class="pratikwp-status-badge is-danger"><?php esc_html_e('Pasif', 'pratikwp'); ?></span>
                                <?php endif; ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}