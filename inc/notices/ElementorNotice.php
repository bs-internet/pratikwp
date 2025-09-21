<?php
/**
 * Elementor Notice Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_ElementorNotice {
    
    public function __construct() {
        add_action('admin_notices', [$this, 'elementor_notice']);
        add_action('wp_ajax_pratikwp_dismiss_elementor_notice', [$this, 'dismiss_notice']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_notice_script']);
        add_action('admin_init', [$this, 'check_dismissal']);
    }

    /**
     * Elementor bildirimini göster
     */
    public function elementor_notice() {
        // Elementor varsa bildirim gösterme
        if (class_exists('\Elementor\Plugin')) {
            return;
        }

        // Admin sayfalarında değilse gösterme
        if (!is_admin()) {
            return;
        }

        // Bildirim kapatılmışsa gösterme (7 gün boyunca)
        $dismissed = get_transient('pratikwp_elementor_notice_dismissed');
        if ($dismissed) {
            return;
        }

        // Sadece manage_options yetkisi olan kullanıcılara göster
        if (!current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="notice notice-warning is-dismissible pratikwp-elementor-notice">
            <div style="display: flex; align-items: center; padding: 10px 0;">
                <div style="margin-right: 15px;">
                    <span class="dashicons dashicons-admin-appearance" style="font-size: 32px; color: #E91E63;"></span>
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px 0; font-size: 16px;">
                        <?php esc_html_e('PratikWp Teması Elementor Gerektiriyor', 'pratikwp'); ?>
                    </h3>
                    <p style="margin: 0 0 12px 0; font-size: 14px;">
                        <?php esc_html_e('Bu tema Elementor sayfa oluşturucu eklentisi ile en iyi şekilde çalışır. Elementor kurulmamış veya etkinleştirilmemiş.', 'pratikwp'); ?>
                    </p>
                    <p style="margin: 0;">
                        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=elementor&tab=search&type=term')); ?>" class="button button-primary">
                            <?php esc_html_e('Elementor\'u Kur', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('plugins.php')); ?>" class="button button-secondary" style="margin-left: 10px;">
                            <?php esc_html_e('Eklentileri Yönet', 'pratikwp'); ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Bildirimi kapat (Ajax)
     */
    public function dismiss_notice() {
        // Nonce kontrolü
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pratikwp_admin_nonce')) {
            wp_send_json_error([
                'message' => __('Güvenlik hatası.', 'pratikwp')
            ]);
            return;
        }
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error([
                'message' => __('Yetkiniz yok.', 'pratikwp')
            ]);
            return;
        }

        // 7 gün boyunca bildirimi gösterme
        set_transient('pratikwp_elementor_notice_dismissed', true, 7 * DAY_IN_SECONDS);
        
        wp_send_json_success([
            'message' => __('Bildirim kapatıldı.', 'pratikwp')
        ]);
    }

    /**
     * URL parametresi ile bildirimi kapatma
     */
    public function check_dismissal() {
        if (isset($_GET['pratikwp_dismiss_elementor_notice']) && $_GET['pratikwp_dismiss_elementor_notice'] == '1') {
            if (current_user_can('manage_options')) {
                set_transient('pratikwp_elementor_notice_dismissed', true, 7 * DAY_IN_SECONDS);
                
                // Yönlendirme yap ve parametreyi kaldır
                wp_redirect(remove_query_arg('pratikwp_dismiss_elementor_notice'));
                exit;
            }
        }
    }

    /**
     * Bildirim için JS dosyası
     */
    public function enqueue_notice_script($hook) {
        // Elementor varsa script yükleme
        if (class_exists('\Elementor\Plugin')) {
            return;
        }

        // Bildirim kapatılmışsa script yükleme
        $dismissed = get_transient('pratikwp_elementor_notice_dismissed');
        if ($dismissed) {
            return;
        }

        // Sadece yönetim sayfalarında
        if (!is_admin()) {
            return;
        }

        wp_enqueue_script(
            'pratikwp-elementor-notice',
            PRATIKWP_ASSETS . '/js/elementor-notice.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );

        wp_localize_script('pratikwp-elementor-notice', 'pratikwpElementorNotice', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pratikwp_admin_nonce'),
            'dismiss_url' => add_query_arg('pratikwp_dismiss_elementor_notice', '1')
        ]);
    }
}